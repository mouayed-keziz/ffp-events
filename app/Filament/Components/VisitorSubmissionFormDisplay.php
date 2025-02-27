<?php

namespace App\Filament\Components;

use App\Enums\FormField;
use App\Models\VisitorSubmission;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Infolists\Components;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class VisitorSubmissionFormDisplay
{
    /**
     * Build the form display for a visitor submission with answers
     *
     * @param array $formData The form data with sections and answers
     * @return array Components to display in an Infolist
     */
    public static function make(array $formData): array
    {
        $components = [];

        foreach ($formData as $sectionIndex => $section) {
            $sectionTitle = $section['title'][App::getLocale()] ?? $section['title']['en'] ?? $section['title']['fr'] ?? 'Section';

            $sectionFields = [];

            foreach ($section['fields'] as $fieldIndex => $field) {
                $component = self::createFieldComponent($field);
                if ($component) {
                    $sectionFields[] = $component;
                }
            }

            $components[] = InfolistSection::make($sectionTitle)
                ->schema($sectionFields)
                ->collapsible();
        }

        return $components;
    }

    /**
     * Create the appropriate component for a field
     *
     * @param array $field The field definition with type, data and answer
     * @return Components\Component|null The appropriate component for the field
     */
    protected static function createFieldComponent(array $field): ?Components\Component
    {
        $locale = App::getLocale();
        $label = $field['data']['label'][$locale] ?? $field['data']['label']['en'] ?? $field['data']['label']['fr'] ?? 'Field';
        $answer = $field['answer'] ?? null;

        return match ($field['type']) {
            FormField::INPUT->value => self::createInputComponent($field, $label, $answer),
            FormField::SELECT->value => self::createSelectComponent($field, $label, $answer),
            FormField::CHECKBOX->value => self::createCheckboxComponent($field, $label, $answer),
            FormField::RADIO->value => self::createRadioComponent($field, $label, $answer),
            FormField::UPLOAD->value => self::createUploadComponent($field, $label, $answer),
            default => TextEntry::make('unknown')
                ->label($label)
                ->state('Unsupported field type: ' . $field['type'])
        };
    }

    /**
     * Create an input field component
     */
    protected static function createInputComponent(array $field, string $label, $answer): Components\Component
    {
        return TextEntry::make('input')
            ->label($label)
            ->state($answer);
    }

    /**
     * Create a select field component
     */
    protected static function createSelectComponent(array $field, string $label, $answer): Components\Component
    {
        $locale = App::getLocale();

        // If the answer is an array with locale keys
        if (is_array($answer) && isset($answer[$locale])) {
            $displayValue = $answer[$locale];
        }
        // If the answer is just a plain value
        else {
            // Try to find the matching option to display its translated value
            if (isset($field['data']['options']) && is_array($field['data']['options'])) {
                foreach ($field['data']['options'] as $option) {
                    // For single select, the answer might be the exact option value
                    if (isset($option['option'][$locale]) && $option['option'][$locale] == $answer) {
                        $displayValue = $option['option'][$locale];
                        break;
                    }
                }
            }

            // Default fallback
            if (!isset($displayValue)) {
                $displayValue = $answer;
            }
        }

        return TextEntry::make('select')
            ->label($label)
            ->state($displayValue);
    }

    /**
     * Create a checkbox field component
     */
    protected static function createCheckboxComponent(array $field, string $label, $answer): Components\Component
    {
        $locale = App::getLocale();
        $displayValues = [];

        // Handle multiple checkbox answers
        if (is_array($answer)) {
            foreach ($answer as $value) {
                if (is_array($value) && isset($value[$locale])) {
                    $displayValues[] = $value[$locale];
                } else {
                    $displayValues[] = $value;
                }
            }
        }

        return TextEntry::make('checkbox')
            ->label($label)
            ->state(implode(', ', $displayValues));
    }

    /**
     * Create a radio field component
     */
    protected static function createRadioComponent(array $field, string $label, $answer): Components\Component
    {
        $locale = App::getLocale();

        // If the answer is an array with locale keys
        if (is_array($answer) && isset($answer[$locale])) {
            $displayValue = $answer[$locale];
        } else {
            $displayValue = $answer;
        }

        return TextEntry::make('radio')
            ->label($label)
            ->state($displayValue);
    }

    /**
     * Create an upload field component that displays files from the Media Library
     */
    protected static function createUploadComponent(array $field, string $label, $answer): Components\Component
    {
        // If no file has been uploaded (no fileId)
        if (empty($answer)) {
            return TextEntry::make('upload')
                ->label($label)
                ->state(__('panel/visitor_submissions.no_file_uploaded'));
        }

        try {
            // Get the visitor submission using the route parameters from the nested resource URL
            $visitorSubmission = null;
            $route = request()->route();

            // Try to get the visitor submission from the route parameters
            // URL pattern: /admin/event-announcements/{record}/visitor-submissions/{visitorSubmission}
            $visitorSubmissionId = $route->parameter('visitorSubmission');

            if ($visitorSubmissionId) {
                $visitorSubmission = VisitorSubmission::find($visitorSubmissionId);
            }

            // If we're on a page that uses Livewire and the visitor submission is available in the component
            if (!$visitorSubmission && method_exists($route->getController(), 'getRecord')) {
                $visitorSubmission = $route->getController()->getRecord();
            }

            // Check if we have an existing record property from a Livewire component
            if (!$visitorSubmission && request()->has('record') && request()->route('record') instanceof VisitorSubmission) {
                $visitorSubmission = request()->route('record');
            }

            // If we still don't have a visitor submission, return an error message
            if (!$visitorSubmission) {
                return TextEntry::make('upload')
                    ->label($label)
                    ->state(__('panel/visitor_submissions.submission_context_not_found') . ' (ID attempted: ' . $visitorSubmissionId . ')');
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
            $fileType = $media->custom_properties['fieldData']['file_type'] ?? \App\Enums\FileUploadType::ANY;

            // Determine if this is an image or PDF based on the file type
            $lowerFileName = strtolower($fileName);
            $isImage = $fileType === \App\Enums\FileUploadType::IMAGE ||
                str_ends_with($lowerFileName, '.jpg') ||
                str_ends_with($lowerFileName, '.jpeg') ||
                str_ends_with($lowerFileName, '.png') ||
                str_ends_with($lowerFileName, '.gif') ||
                str_ends_with($lowerFileName, '.bmp') ||
                str_ends_with($lowerFileName, '.webp');

            $isPdf = $fileType === \App\Enums\FileUploadType::PDF || str_ends_with($lowerFileName, '.pdf');

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
}
