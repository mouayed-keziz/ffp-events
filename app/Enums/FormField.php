<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Arr;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Str;

enum FormField: string implements HasLabel
{
    case INPUT = "input";
    case SELECT = "select";
    case CHECKBOX = "checkbox";
    case RADIO = "radio";
    case UPLOAD = "upload";
    case SELECT_PRICED = "select_priced";
    case CHECKBOX_PRICED = "checkbox_priced";
    case RADIO_PRICED = "radio_priced";
    case ECOMMERCE = "ecommerce";
    case PLAN_TIER = "plan_tier";

    public function getLabel(): ?string
    {
        return trans('panel/forms.form_fields.' . $this->value);
    }

    public function getIcon(): ?string
    {
        $icons = [
            self::INPUT->value           => 'heroicon-o-pencil',
            self::SELECT->value          => 'heroicon-o-selector',
            self::CHECKBOX->value        => 'heroicon-o-check',
            self::RADIO->value           => 'heroicon-o-dot-circle',
            self::UPLOAD->value          => 'heroicon-o-cloud-upload',
            self::SELECT_PRICED->value   => 'heroicon-o-currency-dollar',
            self::CHECKBOX_PRICED->value => 'heroicon-o-clipboard-check',
            self::RADIO_PRICED->value    => 'heroicon-o-document-text',
            self::ECOMMERCE->value       => 'heroicon-o-shopping-cart',
            self::PLAN_TIER->value       => 'heroicon-o-table-cells',
        ];

        return $icons[$this->value] ?? 'heroicon-o-question-mark';
    }

    /**
     * Initialize a field structure based on its type
     */
    public function initializeField(array $field): array
    {
        $fieldData = [
            'type' => $field['type'],
            'data' => [
                'label' => $field['data']['label'],
                'description' => $field['data']['description'] ?? null,
            ],
            'answer' => $this->getDefaultAnswer($field)
        ];

        // Copy any additional field-specific data
        if (isset($field['data']['type'])) {
            $fieldData['data']['type'] = $field['data']['type'];
        }
        if (isset($field['data']['required'])) {
            $fieldData['data']['required'] = $field['data']['required'];
        }
        if (isset($field['data']['options'])) {
            $fieldData['data']['options'] = $field['data']['options'];
        }
        if (isset($field['data']['file_type'])) {
            $fieldData['data']['file_type'] = $field['data']['file_type'];
        }

        return $fieldData;
    }

    /**
     * Get default answer for this field type
     */
    public function getDefaultAnswer(array $field = [])
    {
        return match ($this) {
            self::CHECKBOX => [],
            self::UPLOAD => null,
            self::INPUT => $this->getInputDefaultAnswer($field),
            default => '',
        };
    }

    /**
     * Get input-specific default answer
     */
    private function getInputDefaultAnswer(array $field): string
    {
        if (isset($field['data']['type'])) {
            $inputType = FormInputType::tryFrom($field['data']['type']);
            if ($inputType) {
                return $inputType->getDefaultAnswer();
            }
        }
        return '';
    }

    /**
     * Get validation rules for this field type
     */
    public function getValidationRules(array $field): array
    {
        $rules = [];

        // Check if field is required
        if (Arr::get($field, 'data.required', false)) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        // Add field-specific rules
        $additionalRules = match ($this) {
            self::INPUT => $this->getInputValidationRules($field),
            self::UPLOAD => $this->getFileUploadValidationRules($field),
            self::CHECKBOX => ['array'],
            default => []
        };

        return array_merge($rules, $additionalRules);
    }

    /**
     * Get validation rules for input fields
     */
    private function getInputValidationRules(array $field): array
    {
        if (isset($field['data']['type'])) {
            $inputType = FormInputType::tryFrom($field['data']['type']);
            if ($inputType) {
                return $inputType->getValidationRules();
            }
        }
        return ['string'];
    }

    /**
     * Get validation rules for file upload fields
     */
    private function getFileUploadValidationRules(array $field): array
    {
        $fileTypeValue = $field['data']['file_type'] ?? FileUploadType::ANY->value;
        $fileType = FileUploadType::tryFrom($fileTypeValue) ?? FileUploadType::ANY;

        return $fileType->getValidationRules();
    }

    /**
     * Process field answer for submission
     */
    public function processFieldAnswer($answer, array $fieldData = [])
    {
        if ($this === self::UPLOAD && $answer instanceof TemporaryUploadedFile) {
            // Generate and return a unique identifier for the file
            return (string) Str::uuid();
        }

        if (in_array($this, [self::SELECT, self::RADIO]) && !empty($answer)) {
            return $this->findOptionTranslations($fieldData['options'] ?? [], $answer);
        }

        if ($this === self::CHECKBOX && is_array($answer)) {
            $translatedAnswers = [];
            foreach ($answer as $selectedValue) {
                $translatedAnswers[] = $this->findOptionTranslations($fieldData['options'] ?? [], $selectedValue);
            }
            return $translatedAnswers;
        }

        return $answer;
    }

    /**
     * Find the option translations for a given answer value
     */
    private function findOptionTranslations(array $options, $answerValue): array
    {
        $currentLocale = app()->getLocale();

        // Find the option with matching value in current locale
        foreach ($options as $option) {
            if (isset($option['option'][$currentLocale]) && $option['option'][$currentLocale] === $answerValue) {
                return $option['option'];
            }
        }

        // Fallback: Return the answer value keyed by current locale
        return [$currentLocale => $answerValue];
    }
}
