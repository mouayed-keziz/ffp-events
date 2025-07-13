<?php

namespace App\Enums\Fields;

use App\Constants\Countries;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Facades\App;

class CountrySelect
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

        // Don't store countries in data - they will be loaded dynamically when needed

        return $fieldData;
    }

    public static function getDefaultAnswer(array $field = []): array
    {
        return [
            'selected_country_code' => null,
            'selected_country_name' => null
        ];
    }

    public static function getValidationRules(array $field): array
    {
        $rules = [];

        // For country select, we need custom validation rules as strings
        if (isset($field['data']['required']) && $field['data']['required']) {
            $rules[] = 'required';
            $rules[] = 'array';
            // Add a custom rule that will be handled by the field processing
            $rules[] = 'country_select_valid';
        } else {
            $rules[] = 'nullable';
            $rules[] = 'array';
            // Add a custom rule that will be handled by the field processing  
            $rules[] = 'country_select_valid_optional';
        }

        return $rules;
    }

    public static function processFieldAnswer($answer, array $fieldData = [])
    {
        if ($answer === null || (is_array($answer) && empty($answer))) {
            return self::getDefaultAnswer();
        }

        // Handle legacy format with options array (for backward compatibility)
        if (isset($answer['options']) && is_array($answer['options'])) {
            foreach ($answer['options'] as $option) {
                if (!empty($option['selected']) && $option['selected'] === true) {
                    return [
                        'selected_country_code' => $option['code'],
                        'selected_country_name' => $option['name']
                    ];
                }
            }
            return self::getDefaultAnswer();
        }

        // Handle new format with selected_option
        if (isset($answer['selected_option']) && !empty($answer['selected_option'])) {
            return [
                'selected_country_code' => $answer['selected_option']['code'],
                'selected_country_name' => $answer['selected_option']['name']
            ];
        }

        // Handle direct format
        if (isset($answer['selected_country_code'])) {
            return [
                'selected_country_code' => $answer['selected_country_code'],
                'selected_country_name' => $answer['selected_country_name'] ?? self::getCountryName($answer['selected_country_code'])
            ];
        }

        return self::getDefaultAnswer();
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
     * Create a display component for a country select field
     *
     * @param array $field The field definition with type, data and answer
     * @param string $label The field label
     * @param mixed $answer The field answer value
     * @return \Filament\Infolists\Components\Component
     */
    public static function createDisplayComponent(array $field, string $label, $answer)
    {
        $locale = App::getLocale();

        // Create a Group with title-description entry and country select component
        return \Filament\Infolists\Components\Group::make()
            ->schema([
                \App\Infolists\Components\TitleDescriptionEntry::make('heading')
                    ->state([
                        'title' => $label,
                        'description' => $field['data']['description'][$locale] ?? ($field['data']['description']['en'] ?? null),
                    ]),

                \App\Infolists\Components\CountrySelectEntry::make('country_select')
                    ->label('')
                    ->state($answer)
            ]);
    }

    /**
     * Get all countries formatted for options
     * 
     * @return array Countries array with code as key and name as value
     */
    public static function getCountriesOptions(): array
    {
        $locale = App::getLocale();

        // Try to get country translations for current locale, fallback to French
        $countries = trans('countries', [], $locale);

        // If translation doesn't exist for current locale, fallback to French
        if (!is_array($countries) || empty($countries)) {
            $countries = trans('countries', [], 'fr');
        }

        // If still no translations, fallback to the hardcoded values
        if (!is_array($countries) || empty($countries)) {
            $countries = Countries::COUNTRIES;
        }

        return $countries;
    }

    /**
     * Get country name by code
     * 
     * @param string $code Country code
     * @return string|null Country name or null if not found
     */
    public static function getCountryName(string $code): ?string
    {
        $locale = App::getLocale();

        // Try to get country translations for current locale
        $countries = trans('countries', [], $locale);

        // If translation doesn't exist for current locale, fallback to French
        if (!is_array($countries) || empty($countries) || !isset($countries[$code])) {
            $countries = trans('countries', [], 'fr');
        }

        // If still no translations, fallback to the hardcoded values
        if (!is_array($countries) || empty($countries) || !isset($countries[$code])) {
            $countries = Countries::COUNTRIES;
        }

        return $countries[$code] ?? null;
    }

    /**
     * Get label-answer pair for country select field
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

        $answer = $field['answer']['selected_country_name'] ?? '';

        return [
            'label' => $label,
            'answer' => $answer
        ];
    }
}
