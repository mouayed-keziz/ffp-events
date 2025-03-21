<?php

namespace App\Filament\Components;

use App\Enums\FormField;
use App\Models\ExhibitorSubmission;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\App;

class ExhibitorSubmissionFormDisplay
{
    /**
     * Build the form display for an exhibitor submission with answers
     *
     * @param array $formData The form data with sections and answers
     * @return array Components to display in an Infolist
     */
    public static function make(array $formData): array
    {
        $components = [];

        if (empty($formData)) {
            return $components;
        }

        // Handle multiple forms in exhibitor submissions
        foreach ($formData as $formIndex => $form) {
            if (!isset($form['title']) || !isset($form['sections'])) {
                continue;
            }

            $formTitle = is_array($form['title'])
                ? ($form['title'][App::getLocale()] ?? $form['title']['en'] ?? $form['title']['fr'] ?? 'Form ' . ($formIndex + 1))
                : $form['title'];

            $formDescription = is_array($form['description'] ?? null)
                ? ($form['description'][App::getLocale()] ?? $form['description']['en'] ?? $form['description']['fr'] ?? '')
                : ($form['description'] ?? '');

            // Create a header for the form
            $components[] = TextEntry::make("form_title_{$formIndex}")
                ->label($formTitle)
                ->state($formDescription)
                ->size(TextEntry\TextEntrySize::Large)
                ->weight(FontWeight::Bold)
                ->columnSpanFull();

            // if (!empty($formDescription)) {
            //     $components[] = TextEntry::make("form_description_{$formIndex}")
            //         ->label('')
            //         ->state($formDescription)
            //         ->columnSpanFull();
            // }

            // Process each section within the form
            foreach ($form['sections'] as $sectionIndex => $section) {
                $sectionTitle = is_array($section['title'])
                    ? ($section['title'][App::getLocale()] ?? $section['title']['en'] ?? $section['title']['fr'] ?? 'Section ' . ($sectionIndex + 1))
                    : $section['title'];
                $sectionDescription = is_array($section['description'] ?? null)
                    ? ($section['description'][App::getLocale()] ?? $section['description']['en'] ?? $section['description']['fr'] ?? '')
                    : ($section['description'] ?? '');
                $sectionFields = [];

                foreach ($section['fields'] ?? [] as $fieldIndex => $field) {
                    $component = self::createFieldComponent($field);
                    if ($component) {
                        $sectionFields[] = $component;
                    }
                }

                // Use a fieldset to visually separate sections
                $components[] = Section::make($sectionTitle)
                    ->label($sectionTitle)
                    ->description($sectionDescription)
                    ->collapsible()
                    ->collapsed()
                    ->schema($sectionFields)
                    ->columns(1);

                // Add a spacer between sections
                $components[] = TextEntry::make("section_spacer_{$formIndex}_{$sectionIndex}")
                    ->label('')
                    ->state('')
                    ->columnSpanFull();
            }
        }

        return $components;
    }

    /**
     * Create the appropriate component for a field
     *
     * @param array $field The field definition with type, data and answer
     * @return mixed The appropriate component for the field
     */
    protected static function createFieldComponent(array $field): mixed
    {
        $locale = App::getLocale();
        $label = $field['data']['label'][$locale] ?? $field['data']['label']['en'] ?? $field['data']['label']['fr'] ?? 'Field';
        $answer = $field['answer'] ?? null;

        // Get the FormField enum instance from the field type string
        $fieldType = FormField::tryFrom($field['type']);

        if (!$fieldType) {
            return TextEntry::make('unknown')
                ->label($label)
                ->state('Unsupported field type: ' . $field['type']);
        }

        // Get the exhibitor submission if needed for file uploads
        $exhibitorSubmission = null;
        if ($fieldType === FormField::UPLOAD) {
            $exhibitorSubmission = self::findExhibitorSubmission();
        }

        // Delegate to the enum's createDisplayComponent method
        return $fieldType->createDisplayComponent($field,   $label, $answer, $exhibitorSubmission);
    }

    /**
     * Attempt to find the exhibitor submission from various sources in the request
     * 
     * @return ExhibitorSubmission|null
     */
    protected static function findExhibitorSubmission(): ?ExhibitorSubmission
    {
        $route = request()->route();

        // Try to get the exhibitor submission from the route parameters
        $exhibitorSubmissionId = $route->parameter('exhibitorSubmission') ?? $route->parameter('record');
        if ($exhibitorSubmissionId) {
            $exhibitorSubmission = ExhibitorSubmission::find($exhibitorSubmissionId);
            if ($exhibitorSubmission) {
                return $exhibitorSubmission;
            }
        }

        // If we're on a page that uses Livewire and the exhibitor submission is available in the component
        if (method_exists($route->getController(), 'getRecord')) {
            $exhibitorSubmission = $route->getController()->getRecord();
            if ($exhibitorSubmission instanceof ExhibitorSubmission) {
                return $exhibitorSubmission;
            }
        }

        // Check if we have an existing record property from a Livewire component
        if (request()->has('record') && request()->route('record') instanceof ExhibitorSubmission) {
            return request()->route('record');
        }

        return null;
    }
}
