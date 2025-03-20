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
     * @return Group Component suitable for displaying in an Infolist
     */
    public static function createDisplayComponent(array $field, string $label, $answer, $visitorSubmission = null): Group|TextEntry
    {
        // If no file has been uploaded (no fileId)
        if (empty($answer)) {
            return TextEntry::make('upload')
                ->label($label)
                ->state(__('panel/visitor_submissions.no_file_uploaded'));
        }

        try {
            // Get the visitor submission using the route parameters from the nested resource URL if not provided
            if (!$visitorSubmission) {
                $visitorSubmission = self::findVisitorSubmission();
            }

            // If we still don't have a visitor submission, return an error message
            if (!$visitorSubmission) {
                return TextEntry::make('upload')
                    ->label($label)
                    ->state(__('panel/visitor_submissions.submission_context_not_found'));
            }

            // Get the media directly from the attachments collection by fileId in custom properties
            $media = $visitorSubmission->getMedia('attachments')->filter(function ($media) use ($answer) {
                return isset($media->custom_properties['fileId']) && $media->custom_properties['fileId'] === $answer;
            })->first();

            if (!$media) {
                return TextEntry::make('upload')
                    ->label($label)
                    ->state(__('panel/visitor_submissions.file_not_found') . ' (FileID: ' . $answer . ')');
            }

            // Get file information
            $fileName = $media->file_name;
            $fileUrl = $media->getUrl();

            // Get the file type from custom properties (instead of relying on potentially incorrect mime_type)
            $fileType = $media->custom_properties['fieldData']['file_type'] ?? FileUploadType::ANY;

            // Determine if this is an image or PDF based on the file type
            $lowerFileName = strtolower($fileName);
            $isImage = $fileType === FileUploadType::IMAGE ||
                str_ends_with($lowerFileName, '.jpg') ||
                str_ends_with($lowerFileName, '.jpeg') ||
                str_ends_with($lowerFileName, '.png') ||
                str_ends_with($lowerFileName, '.gif') ||
                str_ends_with($lowerFileName, '.bmp') ||
                str_ends_with($lowerFileName, '.webp');

            $isPdf = $fileType === FileUploadType::PDF || str_ends_with($lowerFileName, '.pdf');

            // Create a group with different components based on file type
            $components = [
                // Always show the file name
                TextEntry::make('file_name')
                    ->label(__('panel/visitor_submissions.file_name'))
                    ->state($fileName),
            ];

            // Add preview component based on file type
            if ($isImage) {
                $components[] = TextEntry::make('file_preview')
                    ->label(__('panel/visitor_submissions.file_preview'))
                    ->view('panel.components.visitor-submissions.image-upload', [
                        'fileName' => $fileName,
                        'fileUrl' => $fileUrl,
                    ]);
            } elseif ($isPdf) {
                // For PDFs, use the PDF Blade template
                $components[] = TextEntry::make('pdf_preview')
                    ->label(__('panel/visitor_submissions.file_preview'))
                    ->view('panel.components.visitor-submissions.pdf-upload', [
                        'fileName' => $fileName,
                        'fileUrl' => $fileUrl,
                    ]);
            } else {
                // For other file types, use the generic file template
                $components[] = TextEntry::make('file_type')
                    ->label(__('panel/visitor_submissions.file_type'))
                    ->view('panel.components.visitor-submissions.other-upload', [
                        'fileName' => $fileName,
                        'fileUrl' => $fileUrl,
                        'fileType' => $fileType,
                    ]);
            }

            // Show any additional metadata from the media
            if (isset($media->custom_properties['fieldLabel'])) {
                $locale = App::getLocale();
                $fieldLabel = $media->custom_properties['fieldLabel'][$locale] ??
                    $media->custom_properties['fieldLabel']['en'] ??
                    $media->custom_properties['fieldLabel']['fr'] ?? null;

                if ($fieldLabel) {
                    $components[] = TextEntry::make('field_label')
                        ->label(__('panel/visitor_submissions.field_label'))
                        ->state($fieldLabel);
                }
            }

            return Group::make($components)->columnSpanFull();
        } catch (\Exception $e) {
            return TextEntry::make('upload')
                ->label($label)
                ->state(__('panel/visitor_submissions.error_loading_file') . ': ' . $e->getMessage());
        }
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
