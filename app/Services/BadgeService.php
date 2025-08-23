<?php

namespace App\Services;

use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Color\Color;
use App\Models\EventAnnouncement;
use Illuminate\Http\UploadedFile;

// Intervention Image v3 Imports
use Endroid\QrCode\Builder\Builder;
use Illuminate\Support\Facades\Log;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\View;

// Endroid QR Code Imports (using Builder with constructor)
use Intervention\Image\ImageManager;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\ErrorCorrectionLevel;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Typography\FontFactory;
use Intervention\Image\Interfaces\ImageInterface;

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
     * This method uses the CSS-based approach for badge generation.
     *
     * @param string $templatePath Path to the valid A4 portrait template image.
     * @param array $data Associative array containing 'name', 'job', 'company', 'qr_data'.
     * @return ImageInterface|null The generated Intervention Image object or null on error.
     */
    public static function generateBadgePreview(string $templatePath, array $data): ?ImageInterface
    {
        return self::generateBadgePreviewWithCSS($templatePath, $data);
    }

    /**
     * Generates a badge preview image by overlaying text and a QR code onto a template.
     * Places content in the left top quarter of the image, with larger text and slightly smaller QR code.
     * Text includes name (bold), job title and company (smaller, gray) with proper wrapping.
     *
     * @param string $templatePath Path to the valid A4 portrait template image.
     * @param array $data Associative array containing 'name', 'job', 'company', 'qr_data'.
     * @return ImageInterface|null The generated Intervention Image object or null on error.
     */
    public static function generateBadgePreviewWithCSS(string $templatePath, array $data): ?ImageInterface
    {
        try {
            // Generate QR code (slightly smaller than before)
            $builder = new Builder(
                writer: new PngWriter(),
                writerOptions: [],
                validateResult: false,
                data: $data['qr_data'] ?? Str::uuid()->toString(),
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::Low,
                size: 230, // Increased size for better visibility
                margin: 0,
                roundBlockSizeMode: RoundBlockSizeMode::Margin,
                backgroundColor: new Color(255, 255, 255, 0) // Transparent background
            );

            $qrCodeResult = $builder->build();
            $qrCodeString = $qrCodeResult->getString();
            $qrCodeBase64 = base64_encode($qrCodeString);

            // Load the background template image
            $manager = new ImageManager(new Driver());
            $templateImage = $manager->read($templatePath);

            // Get template dimensions
            $templateWidth = $templateImage->width();
            $templateHeight = $templateImage->height();

            // Create a blank canvas with the same dimensions as the template
            $canvas = $manager->create($templateWidth, $templateHeight);

            // Draw the badge content directly on the canvas
            // This is a simplified version that places text and QR code in the top-left quarter

            // Define the badge content area (top-left quarter)
            $contentX = 0;
            $contentY = -20;
            $contentWidth = intval($templateWidth / 2);
            $contentHeight = intval($templateHeight / 2);

            // Calculate text positions
            $nameY = $contentY + intval($contentHeight * 0.28);
            $jobY = $nameY + 45; // Increased spacing for larger text
            $companyY = $jobY + 35; // Increased spacing between job and company
            $textCenterX = $contentX + intval($contentWidth / 2);

            // Set path to the Roboto variable font
            $fontPath = public_path('fonts/Roboto-VariableFont_wdth,wght.ttf');

            // Add name (bold) - larger text
            $canvas->text($data['name'] ?? 'Unknown', $textCenterX, $nameY, function (FontFactory $font) use ($fontPath, $contentWidth) {
                if ($fontPath && file_exists($fontPath)) {
                    $font->file($fontPath);
                }
                //$font->size(22); // Smaller text (was 32)
                $font->color('#000000');
                $font->align('center');
                $font->wrap($contentWidth * 0.97); // Wrap text to fit within the content width
                $font->valign('middle');
                // Simulate bold by increasing font size
                $font->size(32); // Smaller text (was 38)
            });

            // Add job title if available - larger text
            if (isset($data['job'])) {
                $canvas->text($data['job'] ?? 'N/A', $textCenterX, $jobY, function (FontFactory $font) use ($fontPath) {
                    if ($fontPath && file_exists($fontPath)) {
                        $font->file($fontPath);
                    }
                    $font->size(23); // Smaller text (was 24)
                    $font->color('#666666');
                    $font->align('center');
                    $font->valign('middle');
                });
            }

            // Add company if available - larger text
            if (isset($data['company'])) {
                $canvas->text($data['company'] ?? 'N/A', $textCenterX, $companyY, function (FontFactory $font) use ($fontPath) {
                    if ($fontPath && file_exists($fontPath)) {
                        $font->file($fontPath);
                    }
                    $font->size(23); // Smaller text (was 24)
                    $font->color('#666666');
                    $font->align('center');
                    $font->valign('middle');
                });
            }

            // Add QR code
            $qrImage = $manager->read($qrCodeString);
            $qrX = $contentX + intval(($contentWidth - $qrImage->width()) / 2);
            $qrY = $companyY + 48;
            $canvas->place($qrImage, 'top-left', $qrX, $qrY);

            // Overlay the canvas onto the template
            $templateImage->place($canvas, 'top-left', 0, 0);


            return $templateImage;
        } catch (\Throwable $e) {
            // Log the error
            \Illuminate\Support\Facades\Log::error('Failed to generate badge with CSS: ' . $e->getMessage(), [
                'exception' => $e,
                'data' => $data
            ]);
            return null;
        }
    }

    /**
     * Create a blank white A4 portrait PNG file and return its filesystem path.
     * Dimensions chosen are 2480x3508 (300 DPI) with ~1.414 aspect ratio.
     *
     * @return string Path to generated blank PNG file.
     */
    public static function createBlankA4Template(): string
    {
        $width = 1240;  // A4 @ 150dpi width (kept consistent with previous edit)
        $height = 1754; // A4 @ 150dpi height

        $publicPath = public_path('blank_a4.png');

        if (!file_exists($publicPath)) {
            // Create once and persist
            $im = imagecreatetruecolor($width, $height);
            $white = imagecolorallocate($im, 255, 255, 255);
            imagefilledrectangle($im, 0, 0, $width, $height, $white);
            imagepng($im, $publicPath);
            imagedestroy($im);
        }

        return $publicPath;
    }

    /**
     * Generate a badge preview on a freshly created blank A4 template and optionally crop
     * to the top-left quarter (where the existing layout places the content).
     * Re-uses existing generateBadgePreview logic without modifying original features.
     *
     * @param array $data Badge data (name, job, company, qr_data)
     * @param bool $crop Whether to crop the final image to the top-left quarter
     * @return ImageInterface|null
     */
    public static function generateBadgePreviewOnBlank(array $data, bool $crop = true): ?ImageInterface
    {
        try {
            $blankPath = self::createBlankA4Template();
            $image = self::generateBadgePreview($blankPath, $data);
            if (!$image) {
                return null;
            }
            if ($crop) {
                return $image;
            }

            return $image;
        } catch (\Throwable $e) {
            Log::error('Failed to generate badge preview on blank A4', [
                'exception' => $e,
                'data' => $data,
            ]);
            return null;
        }
    }
}
