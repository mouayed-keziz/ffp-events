<?php

namespace App\Enums\Fields;

use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\App;

class RadioPriced
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

            // Initialize the answer with options (first selected if required)
            $isRequired = $fieldData['data']['required'] ?? false;

            $fieldData['answer']['options'] = collect($field['data']['options'])->map(function ($option, $index) use ($isRequired) {
                return [
                    'option' => $option['option'] ?? [],
                    'price' => $option['price'] ?? [],
                    'selected' => ($isRequired && $index === 0), // Select first option if required
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

        // Return the options array with selected info
        if (empty($answer['options'])) {
            return ['options' => []];
        }

        return $answer;
    }

    public static function calculateFieldPrice($answer, array $fieldData, string $preferredCurrency): float
    {
        $price = 0;

        if (empty($answer) || empty($answer['options'])) {
            return $price;
        }

        // For radio priced, find and add the single selected option price
        foreach ($answer['options'] as $optionData) {
            if (!empty($optionData['selected']) && $optionData['selected'] === true) {
                $optionPrice = floatval($optionData['price'][$preferredCurrency] ?? 0);
                $price = $optionPrice;
                break;
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
     * Create a display component for a priced radio field
     *
     * @param array $field The field definition with type, data and answer
     * @param string $label The field label
     * @param mixed $answer The field answer value
     * @return \Filament\Infolists\Components\Component
     */
    public static function createDisplayComponent(array $field, string $label, $answer)
    {
        $locale = App::getLocale();

        // Create a Group with title-description entry and radio component
        return \Filament\Infolists\Components\Group::make()
            ->schema([
                \App\Infolists\Components\TitleDescriptionEntry::make('heading')
                    ->state([
                        'title' => $label,
                        'description' => $field['data']['description'][$locale] ?? ($field['data']['description']['en'] ?? null),
                    ]),

                \App\Infolists\Components\RadioPricedEntry::make('radio_priced')
                    ->label('')
                    ->state($answer)
            ]);
    }

    /**
     * Update radio options based on selection
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
     * Get label-answer pair for radio priced field
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
        if (!empty($field['answer']['options'])) {
            foreach ($field['answer']['options'] as $option) {
                if (!empty($option['selected']) && $option['selected'] === true) {
                    $answer = $option['option'][$language] ??
                        $option['option']['fr'] ??
                        $option['option']['en'] ??
                        'Unknown Option';
                    break;
                }
            }
        }

        return [
            'label' => $label,
            'answer' => $answer
        ];
    }
}
