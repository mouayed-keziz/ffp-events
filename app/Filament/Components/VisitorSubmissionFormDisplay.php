<?php

namespace App\Filament\Components;

use App\Enums\FormField;
use App\Models\VisitorSubmission;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

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
            $sectionDescription = $section['description'][App::getLocale()] ?? $section['description']['en'] ?? $section['description']['fr'] ?? null;
            $sectionFields = [];

            foreach ($section['fields'] as $fieldIndex => $field) {
                $component = self::createFieldComponent($field);
                if ($component) {
                    $sectionFields[] = $component;
                }
            }

            // Use a fieldset to visually separate sections instead of a collapsible section
            $components[] = Section::make($sectionTitle)
                ->label($sectionTitle)
                ->collapsible()
                ->collapsed()
                ->description($sectionDescription)
                ->schema($sectionFields)
                ->columns([
                    'default' => 1,
                    'md' => 1,
                ]);

            // Add a spacer between sections
            $components[] = TextEntry::make("section_spacer_{$sectionIndex}")
                ->label('')
                ->state('')
                ->columnSpanFull();
        }

        return $components;
    }

    /**
     * Create the appropriate component for a field by delegating to the FormField enum
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

        // Get the visitor submission if needed for file uploads
        $visitorSubmission = null;
        if ($fieldType === FormField::UPLOAD) {
            $visitorSubmission = self::findVisitorSubmission();
        }

        // Delegate to the enum's createDisplayComponent method
        return $fieldType->createDisplayComponent($field, $label, $answer, $visitorSubmission);
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
