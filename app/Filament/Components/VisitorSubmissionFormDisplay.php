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
     * Create an upload field component
     */
    protected static function createUploadComponent(array $field, string $label, $answer): Components\Component
    {
        if (empty($answer)) {
            return TextEntry::make('upload')
                ->label($label)
                ->state(__('panel/visitor_submissions.no_file_uploaded'));
        }
        
        try {
            // Get the media directly from the attachments collection
            $media = Media::where('uuid', $answer)
                ->where('collection_name', 'attachments')
                ->first();
            
            if (!$media) {
                return TextEntry::make('upload')
                    ->label($label)
                    ->state(__('panel/visitor_submissions.file_not_found'));
            }
            
            // Get properties from the media custom properties
            $customProperties = $media->custom_properties ?? [];
            $fileName = $media->file_name;
            $fileUrl = $media->getUrl();
            $mimeType = $media->mime_type;
            $isImage = str_contains($mimeType, 'image/');
            $isPdf = str_contains($mimeType, 'pdf');
            
            // Create a group with different components based on file type
            $group = Group::make([
                // File name
                TextEntry::make('upload.filename')
                    ->label(__('panel/visitor_submissions.file_name'))
                    ->state($fileName),
                
                // Display different components based on file type
                $isImage 
                    ? ImageEntry::make('upload.preview')
                        ->label(__('panel/visitor_submissions.file_preview'))
                        ->src($fileUrl)
                        ->extraImgAttributes(['class' => 'max-w-sm rounded-lg shadow-md'])
                    : ($isPdf 
                        ? TextEntry::make('upload.pdf-preview')
                            ->label(__('panel/visitor_submissions.file_preview'))
                            ->html('<div class="flex items-center"><svg class="w-8 h-8 text-red-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M23 13v-2h-2.06A9 9 0 0 0 18 4.95 9 9 0 0 0 11 2c-5 0-9 4-9 9a9 9 0 0 0 9 9 9 9 0 0 0 6-2.3v3.3h2v-6a1 1 0 0 0-1-1H8v2h5.56A7 7 0 0 1 11 18a7 7 0 0 1-7-7 7 7 0 0 1 7-7c3.5 0 6.4 2.6 6.92 6H16v2h7Z"/></svg>' . __('panel/visitor_submissions.pdf_file') . '</div>')
                        : TextEntry::make('upload.file-type')
                            ->label(__('panel/visitor_submissions.file_type'))
                            ->state($mimeType)
                    ),
                
                // Show any additional properties stored in the media
                isset($customProperties['description']) 
                    ? TextEntry::make('upload.description')
                        ->label(__('panel/visitor_submissions.file_description'))
                        ->state($customProperties['description'])
                    : null,
                
                // Links for view/download
                TextEntry::make('upload.actions')
                    ->label(__('panel/visitor_submissions.actions.title'))
                    ->html(function() use ($fileUrl, $isImage, $isPdf) {
                        $downloadLink = '<a href="' . $fileUrl . '" class="text-primary-600 hover:text-primary-500" target="_blank" download>' . 
                            __('panel/visitor_submissions.actions.download') . 
                            '</a>';
                            
                        $viewLink = '';
                        if ($isImage || $isPdf) {
                            $viewLink = '<a href="' . $fileUrl . '" class="text-primary-600 hover:text-primary-500 mr-4" target="_blank">' . 
                                __('panel/visitor_submissions.actions.view') . 
                                '</a>';
                        }
                        
                        return '<div class="flex items-center">' . $viewLink . $downloadLink . '</div>';
                    }),
            ]);
            
            return $group->columnSpanFull();
        } catch (\Exception $e) {
            return TextEntry::make('upload')
                ->label($label)
                ->state(__('panel/visitor_submissions.error_loading_file') . ': ' . $e->getMessage());
        }
    }
}
