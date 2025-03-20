<?php

namespace App\Enums\Fields;

use App\Enums\FormInputType;
use Filament\Infolists\Components\TextEntry;

class Input
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

        return $fieldData;
    }

    public static function getDefaultAnswer(array $field = []): string
    {
        if (isset($field['data']['type'])) {
            $inputType = FormInputType::tryFrom($field['data']['type']);
            if ($inputType) {
                return $inputType->getDefaultAnswer();
            }
        }
        return '';
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

        // Add input-specific rules
        if (isset($field['data']['type'])) {
            $inputType = FormInputType::tryFrom($field['data']['type']);
            if ($inputType) {
                return array_merge($rules, $inputType->getValidationRules());
            }
        }

        return array_merge($rules, ['string']);
    }

    public static function processFieldAnswer($answer, array $fieldData = [])
    {
        return $answer;
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
     * Create a display component for an input field
     *
     * @param array $field The field definition with type, data and answer
     * @param string $label The field label
     * @param mixed $answer The field answer value
     * @return TextEntry Component suitable for displaying in an Infolist
     */
    public static function createDisplayComponent(array $field, string $label, $answer): TextEntry
    {
        return TextEntry::make('input')
            ->label($label)
            ->state($answer ?? '');
    }
}
