<?php

namespace App\Enums\Fields;

use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\App;

class Checkbox
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
        if (isset($field['data']['options'])) {
            $fieldData['data']['options'] = $field['data']['options'];

            // Initialize the answer with all available options (none selected)
            $fieldData['answer']['options'] = collect($field['data']['options'])->map(function ($option) {
                return [
                    'option' => $option['option'] ?? [],
                    'selected' => false,
                    'value' => $option['option'][app()->getLocale()] ?? ($option['option']['fr'] ?? '')
                ];
            })->toArray();
        }

        return $fieldData;
    }

    public static function getDefaultAnswer(array $field = []): array
    {
        return [
            'options' => []
        ];
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

        return array_merge($rules, ['array']);
    }

    public static function processFieldAnswer($answer, array $fieldData = [])
    {
        if ($answer === null || (is_array($answer) && empty($answer))) {
            return $answer;
        }

        // Use the structured answer with options array for checkboxes
        if (empty($answer['options'])) {
            return ['options' => []];
        }

        // Find all selected options and prepare for submission
        $selectedOptions = [];
        foreach ($answer['options'] as $option) {
            if (!empty($option['selected']) && $option['selected'] === true) {
                $selectedOptions[] = [
                    'option' => $option['option'],
                    'selected' => true,
                    'value' => $option['value']
                ];
            }
        }

        return [
            'options' => $answer['options'],
            'selected_options' => $selectedOptions
        ];
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
     * Create a display component for a checkbox field
     *
     * @param array $field The field definition with type, data and answer
     * @param string $label The field label
     * @param mixed $answer The field answer value
     * @return TextEntry Component suitable for displaying in an Infolist
     */
    public static function createDisplayComponent(array $field, string $label, $answer): TextEntry
    {
        $locale = App::getLocale();
        $selectedOptions = [];

        if (!empty($answer['selected_options'])) {
            foreach ($answer['selected_options'] as $selectedOption) {
                if (isset($selectedOption['option'][$locale])) {
                    $selectedOptions[] = $selectedOption['option'][$locale];
                }
            }
        }

        return TextEntry::make('checkbox')
            ->label($label)
            ->state(empty($selectedOptions) ?
                __('panel/visitor_submissions.no_selection') :
                implode(', ', $selectedOptions));
    }
}
