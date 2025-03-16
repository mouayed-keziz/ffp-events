<?php

namespace App\Enums\Fields;

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
}
