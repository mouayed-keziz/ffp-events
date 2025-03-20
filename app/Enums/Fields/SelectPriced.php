<?php

namespace App\Enums\Fields;

use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\App;

class SelectPriced
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
                    'price' => $option['price'] ?? [],
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

        // Just return the structured answer with options array
        if (empty($answer['options'])) {
            return ['options' => []];
        }

        // Return the options array with selected info
        return $answer;
    }

    public static function calculateFieldPrice($answer, array $fieldData, string $preferredCurrency): float
    {
        $price = 0;

        if (empty($answer) || empty($answer['options'])) {
            return $price;
        }

        // For select priced, find and add the single selected option price
        foreach ($answer['options'] as $optionData) {
            if (!empty($optionData['selected']) && $optionData['selected'] === true) {
                $optionPrice = floatval($optionData['price'][$preferredCurrency] ?? 0);
                $price = $optionPrice;
                break;
            }
        }

        return $price;
    }

    public static function isPriced(): bool
    {
        return true;
    }

    public static function needsQuantity(): bool
    {
        return false;
    }

    /**
     * Update select priced options based on selection
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
     * Create a display component for a priced select field
     *
     * @param array $field The field definition with type, data and answer
     * @param string $label The field label
     * @param mixed $answer The field answer value
     * @return TextEntry Component suitable for displaying in an Infolist
     */
    public static function createDisplayComponent(array $field, string $label, $answer): TextEntry
    {
        $locale = App::getLocale();

        if (!empty($answer) && isset($answer['selected_option']['option'][$locale])) {
            $selectedOption = $answer['selected_option']['option'][$locale];
            $price = '';

            if (isset($answer['selected_option']['price'])) {
                $currencySymbol = $answer['selected_option']['currency'] ?? '€';
                $price = " ({$currencySymbol}" . number_format($answer['selected_option']['price'], 2) . ")";
            }

            return TextEntry::make('select_priced')
                ->label($label)
                ->state($selectedOption . $price);
        }

        return TextEntry::make('select_priced')
            ->label($label)
            ->state(__('panel/visitor_submissions.no_selection'));
    }
}
