<?php

namespace App\Enums\Fields;

use App\Enums\FileUploadType;

class Upload
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
        if (isset($field['data']['file_type'])) {
            $fieldData['data']['file_type'] = $field['data']['file_type'];
        }

        return $fieldData;
    }

    public static function getDefaultAnswer(array $field = []): ?string
    {
        return null;
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

        // Add file upload specific rules
        $fileTypeValue = $field['data']['file_type'] ?? FileUploadType::ANY->value;
        $fileType = FileUploadType::tryFrom($fileTypeValue) ?? FileUploadType::ANY;

        return array_merge($rules, $fileType->getValidationRules());
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
}
