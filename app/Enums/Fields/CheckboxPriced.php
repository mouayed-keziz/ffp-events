<?php

namespace App\Enums\Fields;

use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\App;

class CheckboxPriced
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

        return array_merge($rules, ['array']);
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

        // For checkbox priced, sum up the prices of all selected options
        foreach ($answer['options'] as $optionData) {
            if (!empty($optionData['selected']) && $optionData['selected'] === true) {
                $optionPrice = floatval($optionData['price'][$preferredCurrency] ?? 0);
                $price += $optionPrice;
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
     * Create a display component for a priced checkbox field
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
                    $optionText = $selectedOption['option'][$locale];

                    // Add price if available
                    if (isset($selectedOption['price'])) {
                        $currencySymbol = $selectedOption['currency'] ?? '€';
                        $optionText .= " ({$currencySymbol}" . number_format($selectedOption['price'], 2) . ")";
                    }

                    $selectedOptions[] = $optionText;
                }
            }
        }

        return TextEntry::make('checkbox_priced')
            ->label($label)
            ->state(empty($selectedOptions) ?
                __('panel/visitor_submissions.no_selection') :
                implode(', ', $selectedOptions));
    }
}
