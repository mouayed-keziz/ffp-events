<?php

namespace App\Enums\Fields;

use App\Enums\FileUploadType;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Models\VisitorSubmission;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\App;

class Upload
{
    public static function initializeField(array $field): array
    {
        $fieldData = [
            'type' => $field['type'],
            'data' => [
                'label' => $field['data']['label'],
                'description' => $field['data']['description'] ?? null,
            ],
            'answer' => self::getDefaultAnswer($field)
        ];

        if (isset($field['data']['type'])) {
            $fieldData['data']['type'] = $field['data']['type'];
        }
        if (isset($field['data']['required'])) {
            $fieldData['data']['required'] = $field['data']['required'];
        }
        if (isset($field['data']['file_type'])) {
            $fieldData['data']['file_type'] = $field['data']['file_type'];
        }

        return $fieldData;
    }

    public static function getDefaultAnswer(array $field = []): ?string
    {
        return null;
    }

    public static function getValidationRules(array $field): array
    {
        $rules = [];

        // Check if field is required
        if (isset($field['data']['required']) && $field['data']['required']) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        // Add file upload specific rules
        $fileTypeValue = $field['data']['file_type'] ?? FileUploadType::ANY->value;
        $fileType = FileUploadType::tryFrom($fileTypeValue) ?? FileUploadType::ANY;

        return array_merge($rules, $fileType->getValidationRules());
    }

    public static function processFieldAnswer($answer, array $fieldData = [])
    {
        // If the answer is a string (likely a fileId from previous processing)
        // we should preserve it as is
        if (is_string($answer) && !empty($answer)) {
            return $answer;
        }

        // If the answer is a TemporaryUploadedFile, it will be handled by
        // the FormActions class, so we can just return it
        if ($answer instanceof TemporaryUploadedFile) {
            return $answer;
        }

        // For null or empty answers, return as is
        return $answer;
    }

    public static function calculateFieldPrice($answer, array $fieldData, string $preferredCurrency): float
    {
        return 0;
    }

    public static function isPriced(): bool
    {
        return false;
    }

    public static function getInvoiceDetails(array $field, string $currency = 'DZD'): array
    {
        return [];
    }

    public static function needsQuantity(): bool
    {
        return false;
    }

    /**
     * Create a display component for an upload field
     *
     * @param array $field The field definition with type, data and answer
     * @param string $label The field label
     * @param mixed $answer The field answer value (fileId)
     * @param VisitorSubmission|null $visitorSubmission The visitor submission model
     * @return Group|TextEntry Component suitable for displaying in an Infolist
     */
    public static function createDisplayComponent(array $field, string $label, $answer, $visitorSubmission = null): Group
    {
        // If no visitor submission was provided, try to find it
        if (!$visitorSubmission) {
            $visitorSubmission = self::findVisitorSubmission();
        }

        // Display title and description if present
        $components = [];

        // Add title and description component if available
        if (isset($field['data']['label']) || isset($field['data']['description'])) {
            $titleData = [];

            // Get localized label and description if available
            $locale = app()->getLocale();

            if (isset($field['data']['label'])) {
                // If label is an array with translations, get the appropriate one
                if (is_array($field['data']['label']) && isset($field['data']['label'][$locale])) {
                    $titleData['title'] = $field['data']['label'][$locale];
                } else {
                    $titleData['title'] = $field['data']['label'];
                }
            }

            if (isset($field['data']['description'])) {
                // If description is an array with translations, get the appropriate one
                if (is_array($field['data']['description']) && isset($field['data']['description'][$locale])) {
                    $titleData['description'] = $field['data']['description'][$locale];
                } else {
                    $titleData['description'] = "";
                }
            }

            if (!empty($titleData)) {
                $components[] = \App\Infolists\Components\TitleDescriptionEntry::make('title_description')
                    ->state($titleData);
            }
        }

        // Prepare upload component state
        $uploadState = ['answer' => $answer];

        // If no file has been uploaded or the visitor submission is not found
        if (empty($answer) || !$visitorSubmission) {
            $components[] = \App\Infolists\Components\UploadEntry::make('upload')
                ->label($label)
                ->state($uploadState);

            return Group::make($components)->columnSpanFull();
        }

        try {
            // Get the media directly from the attachments collection by fileId in custom properties
            $media = $visitorSubmission->getMedia('attachments')->filter(function ($media) use ($answer) {
                return isset($media->custom_properties['fileId']) && $media->custom_properties['fileId'] === $answer;
            })->first();

            if (!$media) {
                $uploadState['fileUrl'] = null;
                $components[] = \App\Infolists\Components\UploadEntry::make('upload')
                    ->label($label)
                    ->state($uploadState);

                return Group::make($components)->columnSpanFull();
            }

            // Get file information
            $fileName = $media->file_name;
            $fileUrl = $media->getUrl();

            // Get the file type from custom properties (instead of relying on potentially incorrect mime_type)
            $fileType = $media->custom_properties['fieldData']['file_type'] ?? 'any';

            // Determine if this is an image or PDF based on the file type
            $lowerFileName = strtolower($fileName);
            $isImage = $fileType === 'image' ||
                str_ends_with($lowerFileName, '.jpg') ||
                str_ends_with($lowerFileName, '.jpeg') ||
                str_ends_with($lowerFileName, '.png') ||
                str_ends_with($lowerFileName, '.gif') ||
                str_ends_with($lowerFileName, '.bmp') ||
                str_ends_with($lowerFileName, '.webp');

            $isPdf = $fileType === 'pdf' || str_ends_with($lowerFileName, '.pdf');

            // Get any additional metadata from the media
            $fieldLabel = null;
            if (isset($media->custom_properties['fieldLabel'])) {
                $locale = app()->getLocale();
                if (is_array($media->custom_properties['fieldLabel'])) {
                    $fieldLabel = $media->custom_properties['fieldLabel'][$locale] ??
                        $media->custom_properties['fieldLabel']['en'] ??
                        $media->custom_properties['fieldLabel']['fr'] ??
                        json_encode($media->custom_properties['fieldLabel']);
                } else {
                    $fieldLabel = $media->custom_properties['fieldLabel'];
                }
            }

            // Build the complete state for the UploadEntry component
            $uploadState = [
                'answer' => $answer,
                'media' => $media,
                'fileName' => $fileName,
                'fileUrl' => $fileUrl,
                'fileType' => $fileType,
                'isImage' => $isImage,
                'isPdf' => $isPdf,
                'fieldLabel' => $fieldLabel,
            ];

            $components[] = \App\Infolists\Components\UploadEntry::make('upload')
                ->label($label)
                ->state($uploadState);

            return Group::make($components)->columnSpanFull();
        } catch (\Exception $e) {
            $uploadState['error'] = $e->getMessage();
            $components[] = \App\Infolists\Components\UploadEntry::make('upload')
                ->label($label)
                ->state($uploadState);

            return Group::make($components)->columnSpanFull();
        }
    }

    /**
     * Get label-answer pair for upload field
     *
     * @param array $field The field definition with type, data and answer
     * @param string $language Language code (default: 'fr')
     * @return array Array with 'label' and 'answer' keys
     */
    public static function getLabelAnswerPair(array $field, string $language = 'fr'): array
    {
        $label = $field['data']['label'][$language] ??
            $field['data']['label']['fr'] ??
            $field['data']['label']['en'] ??
            'Unknown Field';

        $answer = !empty($field['answer']) ? 'File uploaded' : 'No file uploaded';

        return [
            'label' => $label,
            'answer' => $answer
        ];
    }

    /**
     * Attempt to find the visitor submission from various sources in the request
     * 
     * @return VisitorSubmission|null
     */
    protected static function findVisitorSubmission(): ?VisitorSubmission
    {
        $route = request()->route();

        // Try to get the visitor submission from the route parameters
        // URL pattern: /admin/event-announcements/{record}/visitor-submissions/{visitorSubmission}
        $visitorSubmissionId = $route->parameter('visitorSubmission');
        if ($visitorSubmissionId) {
            $visitorSubmission = VisitorSubmission::find($visitorSubmissionId);
            if ($visitorSubmission) {
                return $visitorSubmission;
            }
        }

        // If we're on a page that uses Livewire and the visitor submission is available in the component
        if (method_exists($route->getController(), 'getRecord')) {
            $visitorSubmission = $route->getController()->getRecord();
            if ($visitorSubmission instanceof VisitorSubmission) {
                return $visitorSubmission;
            }
        }

        // Check if we have an existing record property from a Livewire component
        if (request()->has('record') && request()->route('record') instanceof VisitorSubmission) {
            return request()->route('record');
        }

        return null;
    }
}
