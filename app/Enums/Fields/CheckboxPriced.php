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
    public static function getInvoiceDetails(array $field, string $currency = 'DZD'): array
    {
        if (empty($field['answer']['options'])) {
            return [];
        }

        $details = [];
        foreach ($field['answer']['options'] as $option) {
            if (!empty($option['selected']) && $option['selected'] === true) {
                $details[] = [
                    'title' => $option['option']['fr'] ?? '',
                    'quantity' => 1,
                    'price' => $option['price'][$currency] ?? 0,

                ];
            }
        }
        return $details;
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
     * @return \Filament\Infolists\Components\Component
     */
    public static function createDisplayComponent(array $field, string $label, $answer)
    {
        $locale = App::getLocale();

        // Create a Group with title-description entry and checkbox component
        return \Filament\Infolists\Components\Group::make()
            ->schema([
                \App\Infolists\Components\TitleDescriptionEntry::make('heading')
                    ->state([
                        'title' => $label,
                        'description' => $field['data']['description'][$locale] ?? ($field['data']['description']['en'] ?? null),
                    ]),

                \App\Infolists\Components\CheckBoxPricedEntry::make('checkbox_priced')
                    ->label('')
                    ->state($answer)
            ]);
    }

    /**
     * Get label-answer pair for checkbox priced field
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

        $selectedOptions = [];
        if (!empty($field['answer']['options'])) {
            foreach ($field['answer']['options'] as $option) {
                if (!empty($option['selected']) && $option['selected'] === true) {
                    $optionLabel = $option['option'][$language] ??
                        $option['option']['fr'] ??
                        $option['option']['en'] ??
                        'Unknown Option';
                    $selectedOptions[] = $optionLabel;
                }
            }
        }

        $answer = implode(', ', $selectedOptions);

        return [
            'label' => $label,
            'answer' => $answer
        ];
    }
}
