<?php

namespace App\Actions;

use App\Models\EventAnnouncement;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

abstract class BaseFormActions
{
    /**
     * Initialize a single field structure
     */
    protected function initializeField(array $field): array
    {
        $fieldData = [
            'type' => $field['type'],
            'data' => [
                'label' => $field['data']['label'],
                'description' => $field['data']['description'] ?? null,
            ],
            'answer' => null
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
        if (isset($field['data']['plan_tier_id'])) {
            $fieldData['data']['plan_tier_id'] = $field['data']['plan_tier_id'];
        }
        if (isset($field['data']['products'])) {
            $fieldData['data']['products'] = $field['data']['products'];
        }

        // Initialize answer based on field type
        switch ($field['type']) {
            case \App\Enums\FormField::CHECKBOX->value:
            case \App\Enums\FormField::CHECKBOX_PRICED->value:
                $fieldData['answer'] = [];
                break;
            case \App\Enums\FormField::UPLOAD->value:
                $fieldData['answer'] = null;
                break;
            case \App\Enums\FormField::SELECT_PRICED->value:
            case \App\Enums\FormField::RADIO_PRICED->value:
            case \App\Enums\FormField::ECOMMERCE->value:
            case \App\Enums\FormField::PLAN_TIER->value:
                $fieldData['answer'] = '';
                $fieldData['quantity'] = 1;
                break;
            default:
                $fieldData['answer'] = '';
        }

        return $fieldData;
    }

    /**
     * Get validation rules for a specific field
     */
    protected function getFieldValidationRules(array $field): string
    {
        $fieldRules = [];

        // Check if field is required
        if (Arr::get($field, 'data.required', false)) {
            $fieldRules[] = 'required';
        } else {
            $fieldRules[] = 'nullable';
        }

        // Add specific validation rules based on field type
        switch ($field['type']) {
            case \App\Enums\FormField::INPUT->value:
                $fieldRules = array_merge($fieldRules, $this->getInputFieldRules($field));
                break;
            case \App\Enums\FormField::UPLOAD->value:
                $fieldRules = array_merge($fieldRules, $this->getFileUploadRules($field));
                break;
            case \App\Enums\FormField::CHECKBOX->value:
            case \App\Enums\FormField::CHECKBOX_PRICED->value:
                $fieldRules[] = 'array';
                break;
        }

        return implode('|', $fieldRules);
    }

    /**
     * Get validation rules for input fields based on input type
     */
    protected function getInputFieldRules(array $field): array
    {
        $rules = [];

        switch ($field['data']['type'] ?? '') {
            case \App\Enums\FormInputType::EMAIL->value:
                $rules[] = 'email';
                break;
            case \App\Enums\FormInputType::NUMBER->value:
                $rules[] = 'numeric';
                break;
            case \App\Enums\FormInputType::PHONE->value:
                $rules[] = 'string';
                break;
            case \App\Enums\FormInputType::DATE->value:
                $rules[] = 'date';
                break;
            default:
                $rules[] = 'string';
                break;
        }

        return $rules;
    }

    /**
     * Get validation rules for file upload fields
     */
    protected function getFileUploadRules(array $field): array
    {
        $rules = ['file'];

        // Add file type validation based on field definition
        $fileType = $field['data']['file_type'] ?? \App\Enums\FileUploadType::ANY;

        if ($fileType === \App\Enums\FileUploadType::IMAGE) {
            $rules[] = 'mimes:jpg,jpeg,png,gif,bmp,webp';
            $rules[] = 'max:10240'; // 10MB max for images
        } elseif ($fileType === \App\Enums\FileUploadType::PDF) {
            $rules[] = 'mimes:pdf';
            $rules[] = 'max:20480'; // 20MB max for PDFs
        } else {
            // For any file type, set a general size limit
            $rules[] = 'max:25600'; // 25MB general limit
        }

        return $rules;
    }

    /**
     * Find the option translations for a given answer value
     */
    protected function findOptionTranslations(array $options, $answerValue): array
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

    /**
     * Process form data for saving, handling file uploads through Spatie Media Library
     */
    protected function processFormDataCommon(array $formData, array $processedFormData): array
    {
        $filesToProcess = [];

        // Process choice fields and other data types
        $processedFormData = $this->processFormData($processedFormData);

        // Return both the processed form data and the files to be processed
        return [
            'processedData' => $processedFormData,
            'filesToProcess' => $filesToProcess
        ];
    }

    /**
     * Calculate the total price based on selected options
     */
    public function calculateTotalPrice(array $formData, string $preferredCurrency): float
    {
        $total = 0;

        foreach ($formData as $form) {
            if (!isset($form['sections'])) {
                continue;
            }

            foreach ($form['sections'] as $section) {
                foreach ($section['fields'] as $field) {
                    $price = 0;
                    $quantity = $field['quantity'] ?? 1;

                    switch ($field['type']) {
                        case \App\Enums\FormField::SELECT_PRICED->value:
                        case \App\Enums\FormField::RADIO_PRICED->value:
                            if (!empty($field['answer'])) {
                                $price = $this->getPriceForOption(
                                    $field['data']['options'] ?? [],
                                    $field['answer'],
                                    $preferredCurrency
                                );
                            }
                            break;

                        case \App\Enums\FormField::CHECKBOX_PRICED->value:
                            if (is_array($field['answer'])) {
                                foreach ($field['answer'] as $answer) {
                                    $price += $this->getPriceForOption(
                                        $field['data']['options'] ?? [],
                                        $answer,
                                        $preferredCurrency
                                    );
                                }
                            }
                            break;

                        case \App\Enums\FormField::ECOMMERCE->value:
                            if (!empty($field['answer']) && isset($field['data']['products'])) {
                                foreach ($field['data']['products'] as $product) {
                                    if ($product['product'][app()->getLocale()] === $field['answer']) {
                                        $price = $product['price'][$preferredCurrency] ?? 0;
                                        break;
                                    }
                                }
                            }
                            break;

                        case \App\Enums\FormField::PLAN_TIER->value:
                            // This would require querying the database for the selected plan
                            $price = 0; // Placeholder
                            break;
                    }

                    $total += $price * $quantity;
                }
            }
        }

        return $total;
    }

    /**
     * Get price for a selected option in the preferred currency
     */
    protected function getPriceForOption(array $options, $selectedOption, string $preferredCurrency): float
    {
        $currentLocale = app()->getLocale();

        foreach ($options as $option) {
            if (
                (is_array($selectedOption) && isset($selectedOption[$currentLocale]) && $option['option'][$currentLocale] === $selectedOption[$currentLocale]) ||
                (!is_array($selectedOption) && isset($option['option'][$currentLocale]) && $option['option'][$currentLocale] === $selectedOption)
            ) {
                return floatval($option['price'][$preferredCurrency] ?? 0);
            }
        }

        return 0;
    }

    /**
     * Process form data before saving
     */
    abstract public function processFormData(array $formData): array;

    /**
     * Process form data for submission including file handling
     */
    abstract public function processFormDataForSubmission(array $formData): array;

    /**
     * Initialize form data structure
     */
    abstract public function initFormData(EventAnnouncement $event): array;

    /**
     * Get validation rules for the form
     */
    abstract public function getValidationRules(EventAnnouncement $event, int $currentStep = null): array;

    /**
     * Save the form submission to the database
     */
    abstract public function saveFormSubmission(EventAnnouncement $event, array $formData): bool;
}
