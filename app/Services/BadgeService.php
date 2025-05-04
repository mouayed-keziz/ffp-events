<?php

namespace App\Services;

use App\Models\EventAnnouncement;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

// Intervention Image v3 Imports
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Interfaces\ImageInterface;
use Intervention\Image\Typography\FontFactory;

// Endroid QR Code Imports (using Builder with constructor)
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class BadgeService
{
    /**
     * Returns validation rules for badge template images.
     * For use with Filament's SpatieMediaLibraryFileUpload component.
     * 
     * @return array
     */
    public static function validateBadgeTemplateRule(): array
    {
        return [
            'image',
            'max:10240', // 10MB limit
            function (string $attribute, $value, \Closure $fail) {
                // Skip validation if there's no file
                if (empty($value)) {
                    return;
                }

                // When used within SpatieMediaLibraryFileUpload, $value can be:
                // 1. A temporary upload file (new upload)
                // 2. A string path (existing file)
                // 3. Null (no file)

                try {
                    // Get file path depending on value type
                    $filePath = null;

                    if ($value instanceof UploadedFile) {
                        $filePath = $value->getRealPath();
                    } else if (is_string($value) && file_exists($value)) {
                        $filePath = $value;
                    }

                    // Skip validation if we couldn't get a file path
                    if (!$filePath) {
                        return;
                    }

                    // Process the image
                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($filePath);

                    $width = $image->width();
                    $height = $image->height();

                    if ($width <= 0 || $height <= 0) {
                        $fail(__('panel/event_announcement.badge_templates.invalid_dimensions'));
                        return;
                    }

                    $aspectRatio = $height / $width;

                    // A4 Portrait aspect ratio is sqrt(2) ~= 1.414
                    // Allow a tolerance range (e.g., 1.39 to 1.42)
                    if ($aspectRatio < 1.39 || $aspectRatio > 1.42) {
                        $fail(__('panel/event_announcement.badge_templates.aspect_ratio_error', ['ratio' => '1:1.414']));
                        return;
                    }

                    // Minimum height check
                    if ($height < 1080) {
                        $fail(__('panel/event_announcement.badge_templates.min_height_error', ['height' => 1080]));
                        return;
                    }
                } catch (\Throwable $e) {
                    // Silently fail validation exceptions - they'll be caught by other rules
                }
            },
        ];
    }

    /**
     * Get the file path for a specific badge type of an event from Spatie Media Library.
     *
     * @param int $eventId
     * @param string $badgeType ('visitor', 'exhibitor')
     * @return string|null Path to the media file or null if not found.
     */
    public static function getTemplatePath(int $eventId, string $badgeType): ?string
    {
        $event = EventAnnouncement::find($eventId);
        if (!$event) {
            return null;
        }

        $collectionName = match (strtolower($badgeType)) {
            'visitor' => 'visitor_badge_template',
            'exhibitor' => 'exhibitor_badge_template',
            default => null,
        };

        if (!$collectionName) {
            return null;
        }

        $media = $event->getFirstMedia($collectionName);

        if (!$media) {
            return null;
        }

        // Check if the file actually exists on the disk
        if (!$media->getPath() || !file_exists($media->getPath())) {
            return null;
        }

        return $media->getPath();
    }

    /**
     * Generates a badge preview image by overlaying text and a QR code onto a template.
     * Applies text and QR to the middle vertical 1/3 of the top-left quarter.
     * Text occupies top 2/3 of this slice, QR occupies bottom 1/3 (and is made smaller).
     *
     * @param string $templatePath Path to the valid A4 portrait template image.
     * @param array $data Associative array containing 'name', 'job', 'company', 'qr_data'.
     * @return ImageInterface|null The generated Intervention Image object or null on error.
     */
    public static function generateBadgePreview(string $templatePath, array $data): ?ImageInterface
    {
        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($templatePath);

            // --- Define Regions ---
            $totalWidth = $image->width();
            $totalHeight = $image->height();

            // 1. Top-left Quarter Dimensions
            $quarterWidth = intval($totalWidth / 2);
            $quarterHeight = intval($totalHeight / 2);

            // 2. Middle Vertical 1/3 Slice of the Top-Left Quarter
            $sliceHeight = intval(($quarterHeight / 3));
            $sliceY = $sliceHeight; // Start X of the middle slice (after the first slice)
            $sliceX = 0;          // Start Y at the top
            $sliceWidth = $quarterWidth / 2; // Height is the quarter's height

            // 3. Divide Slice Horizontally: 2/3 for Text, 1/3 for QR
            $textAreaWidth = intval(($sliceWidth * 4) / 5);
            $qrAreaWidth = $sliceWidth - $textAreaWidth; // Remaining height

            // Define absolute coordinates for the areas within the main image
            $textAreaX = $sliceX;
            $textAreaY = $sliceY;
            $qrAreaX = $sliceX + $textAreaWidth;
            $qrAreaY = $sliceY; // QR area starts below text area



            // --- Add Text (Name, Job, Company) ---
            $textPadding = intval($sliceWidth * 0.05); // 5% padding inside text area
            $availableTextWidth = $sliceWidth - (2 * $textPadding);

            // Make font size smaller relative to the text area height
            $fontSize = max(6, intval($sliceHeight / 10)); // Smaller base font size (e.g., 1/10th height), min 10px
            $lineHeight = intval($fontSize * 1.5); // Adjust line spacing accordingly

            // Center X position for text within the slice
            $textCenterX = $textAreaX + intval($sliceWidth / 2);
            //TODO
            $textCenterX = $textAreaX + intval($sliceWidth / 8);

            // Set path to a valid TTF font file
            $fontPath = public_path('fonts/Roboto-VariableFont_wdth,wght.ttf');

            // Helper function to draw centered text
            $drawText = function (string $text, int $yPos, int $currentFontSize, string $color = '#000000') use ($image, $textCenterX, $fontPath, $availableTextWidth) {
                $image->text($text, $textCenterX, $yPos, function (FontFactory $font) use ($currentFontSize, $color, $fontPath, $availableTextWidth) {
                    if ($fontPath && file_exists($fontPath)) {
                        $font->file($fontPath);
                    }
                    $font->size($currentFontSize);
                    $font->color($color);
                    $font->align('start');
                    $font->valign('middle'); // Vertically align text relative to yPos
                });
            };

            // Calculate Y positions for text lines, centered vertically within the text area
            $nameFontSize = $fontSize; // Use base font size for name
            $otherFontSize = intval($fontSize * 0.9); // Slightly smaller for job/company
            $totalTextBlockHeight = $nameFontSize + $otherFontSize + $otherFontSize + ($lineHeight - $fontSize) + ($lineHeight * 0.9 - $otherFontSize) * 2; // Approximate height
            $textBlockStartY = $textAreaY + max($textPadding, intval(($sliceHeight - $totalTextBlockHeight) / 2)); // Center the whole block

            // Name
            $nameY = $textBlockStartY + intval($nameFontSize / 2);
            $drawText("Nom : " . $data['name'] ??  'KEZIZ MOUAYED', $nameY, $nameFontSize, '#000000'); // Darker text

            // Job Title
            $jobY = $nameY + intval($lineHeight * 0.8); // Position below name
            $drawText("Position : " . $data['job'] ??  'Software Engineer', $jobY, $otherFontSize, '#000000');

            // Company
            $companyY = $jobY + intval($lineHeight * 0.8); // Position below job
            $drawText("Entreprise : " . $data['company'] ??  'Example Corp', $companyY, $otherFontSize, '#000000');


            // --- Generate and Add QR Code (Smaller) ---
            // Calculate max size based on making it smaller within the QR area
            $qrMaxDim = min($sliceWidth / 2, $sliceHeight / 2); // Smallest dimension of the QR area
            $qrTargetSize = intval($qrMaxDim * 1.3); // Target 75% of the smaller dimension

            if ($qrTargetSize > 10) { // Ensure QR size is usable
                $builder = new Builder(
                    writer: new PngWriter(),
                    writerOptions: [],
                    validateResult: false,
                    data: $data['qr_data'] ?? Str::uuid()->toString(),
                    encoding: new Encoding('UTF-8'),
                    errorCorrectionLevel: ErrorCorrectionLevel::Low,
                    size: $qrTargetSize, // Use the smaller calculated target size
                    margin: 0, // Let Intervention handle positioning/padding
                    roundBlockSizeMode: RoundBlockSizeMode::Margin
                );

                $qrCodeResult = $builder->build();
                $qrCodeString = $qrCodeResult->getString();
                $qrImage = $manager->read($qrCodeString); // Read generated QR code

                // Center the QR code within the QR area
                $qrPlaceX = $qrAreaX + intval(($sliceWidth - $qrImage->width() * 0.9));
                $qrPlaceY = $qrAreaY + intval(($sliceHeight - $qrImage->height()) / 2);

                // Place QR code
                $image->place($qrImage, 'top-left', $qrPlaceX, $qrPlaceY);
            }

            return $image; // Return the modified Intervention image object

        } catch (\Throwable $e) { // Catch broader errors
            return null; // Return null on any error
        }
    }
}
