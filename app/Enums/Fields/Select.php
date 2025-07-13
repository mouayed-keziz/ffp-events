<?php

namespace App\Enums\Fields;

use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\App;

class Select
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

        return $rules;
    }

    public static function processFieldAnswer($answer, array $fieldData = [])
    {
        if ($answer === null || (is_array($answer) && empty($answer))) {
            return $answer;
        }

        // Use the structured answer with options array
        if (empty($answer['options'])) {
            return ['options' => []];
        }

        // Find the selected option and prepare for submission
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

        // For select, we only expect one selected option
        return [
            'options' => $answer['options'],
            'selected_option' => $selectedOptions[0] ?? null
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

    public static function getInvoiceDetails(array $field, string $currency = 'DZD'): array
    {
        return [];
    }

    public static function needsQuantity(): bool
    {
        return false;
    }

    /**
     * Update select options based on selection
     * 
     * @param array $options Current options array
     * @param mixed $selectedValue Value to be selected
     * @return array Updated options with selected state
     */
    public static function updateOptions(array $options, $selectedValue): array
    {
        if (empty($options)) {
            return [];
        }

        foreach ($options as $index => $option) {
            $isCurrentOptionSelected = $option['value'] == $selectedValue;
            $options[$index]['selected'] = $isCurrentOptionSelected;
        }

        return $options;
    }

    /**
     * Create a display component for a select field
     *
     * @param array $field The field definition with type, data and answer
     * @param string $label The field label
     * @param mixed $answer The field answer value
     * @return \Filament\Infolists\Components\Component
     */
    public static function createDisplayComponent(array $field, string $label, $answer)
    {
        $locale = App::getLocale();

        // Create a Group with title-description entry and select component
        return \Filament\Infolists\Components\Group::make()
            ->schema([
                \App\Infolists\Components\TitleDescriptionEntry::make('heading')
                    ->state([
                        'title' => $label,
                        'description' => $field['data']['description'][$locale] ?? ($field['data']['description']['en'] ?? null),
                    ]),

                \App\Infolists\Components\SelectEntry::make('select')
                    ->label('')
                    ->state($answer)
            ]);
    }

    /**
     * Get label-answer pair for select field
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

        $answer = '';
        if (!empty($field['answer']['selected_option'])) {
            $answer = $field['answer']['selected_option']['option'][$language] ??
                $field['answer']['selected_option']['option']['fr'] ??
                $field['answer']['selected_option']['option']['en'] ??
                '';
        }

        return [
            'label' => $label,
            'answer' => $answer
        ];
    }
}
