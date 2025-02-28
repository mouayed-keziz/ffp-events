<?php

namespace App\Actions;

use App\Models\EventAnnouncement;
use App\Models\ExhibitorForm;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ExhibitorFormActions extends BaseFormActions
{
    /**
     * Initialize form data structure based on the event's exhibitor forms
     */
    public function initFormData(EventAnnouncement $event): array
    {
        $exhibitorForms = $event->exhibitorForms;

        if ($exhibitorForms->isEmpty()) {
            return [];
        }

        $formData = [];

        foreach ($exhibitorForms as $formIndex => $exhibitorForm) {
            $formData[$formIndex] = [
                'title' => $exhibitorForm->title,
                'description' => $exhibitorForm->description,
                'sections' => []
            ];

            // Create the structured formData with sections and fields
            foreach ($exhibitorForm->sections as $section) {
                $sectionData = [
                    'title' => $section['title'],
                    'fields' => []
                ];

                foreach ($section['fields'] as $field) {
                    $sectionData['fields'][] = $this->initializeField($field);
                }

                $formData[$formIndex]['sections'][] = $sectionData;
            }
        }

        return $formData;
    }

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
     * Get validation rules for the form
     */
    public function getValidationRules(EventAnnouncement $event, int $currentStep = null): array
    {
        $rules = [];
        $exhibitorForms = $event->exhibitorForms;

        if ($exhibitorForms->isEmpty()) {
            return $rules;
        }

        // If we're validating a specific form step
        if ($currentStep !== null) {
            $exhibitorForm = $exhibitorForms[$currentStep] ?? null;
            if (!$exhibitorForm) {
                return $rules;
            }

            return $this->getFormValidationRules($exhibitorForm, "formData.{$currentStep}");
        }

        // Validating all forms
        foreach ($exhibitorForms as $formIndex => $exhibitorForm) {
            $formRules = $this->getFormValidationRules($exhibitorForm, "formData.{$formIndex}");
            $rules = array_merge($rules, $formRules);
        }

        return $rules;
    }

    /**
     * Get validation rules for a specific exhibitor form
     */
    protected function getFormValidationRules(ExhibitorForm $exhibitorForm, string $formPrefix): array
    {
        $rules = [];

        foreach ($exhibitorForm->sections as $sectionIndex => $section) {
            foreach ($section['fields'] as $fieldIndex => $field) {
                $fieldKey = "{$formPrefix}.sections.{$sectionIndex}.fields.{$fieldIndex}.answer";
                $rules[$fieldKey] = $this->getFieldValidationRules($field);

                // For fields that have quantity, add validation for it
                if (in_array($field['type'], [
                    \App\Enums\FormField::SELECT_PRICED->value,
                    \App\Enums\FormField::RADIO_PRICED->value,
                    \App\Enums\FormField::ECOMMERCE->value,
                    \App\Enums\FormField::PLAN_TIER->value,
                ])) {
                    $quantityKey = "{$formPrefix}.sections.{$sectionIndex}.fields.{$fieldIndex}.quantity";
                    $rules[$quantityKey] = 'required|integer|min:1';
                }
            }
        }

        return $rules;
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
     * Process form data before saving
     */
    public function processFormData(array $formData): array
    {
        if (empty($formData)) {
            return $formData;
        }

        $processedFormData = $formData;

        foreach ($processedFormData as $formIndex => $form) {
            foreach ($form['sections'] as $sectionIndex => $section) {
                if (!isset($section['fields']) || !is_array($section['fields'])) {
                    continue;
                }

                foreach ($section['fields'] as $fieldIndex => $field) {
                    // Process choice fields
                    if (!isset($field['answer']) || !in_array($field['type'] ?? '', [
                        \App\Enums\FormField::SELECT->value,
                        \App\Enums\FormField::CHECKBOX->value,
                        \App\Enums\FormField::RADIO->value,
                        \App\Enums\FormField::SELECT_PRICED->value,
                        \App\Enums\FormField::CHECKBOX_PRICED->value,
                        \App\Enums\FormField::RADIO_PRICED->value,
                    ])) {
                        continue;
                    }

                    // Process based on field type
                    switch ($field['type']) {
                        case \App\Enums\FormField::SELECT->value:
                        case \App\Enums\FormField::RADIO->value:
                        case \App\Enums\FormField::SELECT_PRICED->value:
                        case \App\Enums\FormField::RADIO_PRICED->value:
                            // For single select/radio, find the matching option and convert to translatable array
                            $processedFormData[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'] =
                                $this->findOptionTranslations($field['data']['options'] ?? [], $field['answer']);
                            break;

                        case \App\Enums\FormField::CHECKBOX->value:
                        case \App\Enums\FormField::CHECKBOX_PRICED->value:
                            // For checkboxes (array of values), convert each selected value
                            if (is_array($field['answer'])) {
                                $translatedAnswers = [];
                                foreach ($field['answer'] as $answer) {
                                    $translatedAnswers[] = $this->findOptionTranslations($field['data']['options'] ?? [], $answer);
                                }
                                $processedFormData[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'] = $translatedAnswers;
                            }
                            break;
                    }
                }
            }
        }

        return $processedFormData;
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
    public function processFormDataForSubmission(array $formData): array
    {
        if (empty($formData)) {
            return $formData;
        }

        $processedFormData = $formData;
        $filesToProcess = [];

        // First pass: identify all files and replace them with unique identifiers
        foreach ($processedFormData as $formIndex => $form) {
            foreach ($form['sections'] as $sectionIndex => $section) {
                if (!isset($section['fields']) || !is_array($section['fields'])) {
                    continue;
                }

                foreach ($section['fields'] as $fieldIndex => $field) {
                    // Process file uploads
                    if (isset($field['type']) && $field['type'] === \App\Enums\FormField::UPLOAD->value && isset($field['answer'])) {
                        if ($field['answer'] instanceof TemporaryUploadedFile) {
                            // Generate unique identifier for the file
                            $fileId = (string) Str::uuid();

                            // Save file information for later processing
                            $filesToProcess[] = [
                                'file' => $field['answer'],
                                'fileId' => $fileId,
                                'fieldData' => $field['data'] ?? [],
                            ];

                            // Replace the file in form data with the identifier
                            $processedFormData[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'] = $fileId;
                        }
                    }
                }
            }
        }

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
                            // We'll handle it separately or implement later
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
     * Save the form submission to the database
     */
    public function saveFormSubmission(EventAnnouncement $event, array $formData): bool
    {
        try {
            // Process the form data (handle file uploads, translatable fields, etc.)
            $processResult = $this->processFormDataForSubmission($formData);
            $processedData = $processResult['processedData'];
            $filesToProcess = $processResult['filesToProcess'];

            // Get exhibitor ID if user is authenticated as an exhibitor
            $exhibitorId = null;
            if (auth('exhibitor')->check()) {
                $exhibitorId = auth('exhibitor')->user()->id;
            }

            // Create a new submission with nullable exhibitor_id
            $submission = \App\Models\ExhibitorSubmission::create([
                'exhibitor_id' => $exhibitorId,
                'event_announcement_id' => $event->id,
                'answers' => $processedData,
                'status' => 'pending',
            ]);
            Log::info("Exhibitor Submission created: {$submission->id}");

            // Process any files by adding them to the Spatie Media Library
            foreach ($filesToProcess as $fileInfo) {
                $media = $submission->addMedia($fileInfo['file']->getRealPath())
                    ->usingFileName($fileInfo['file']->getClientOriginalName())
                    ->withCustomProperties([
                        'fileId' => $fileInfo['fileId'],
                        'fileType' => $fileInfo['fieldData']['file_type'] ?? null,
                        'fieldLabel' => $fileInfo['fieldData']['label'] ?? null,
                    ])
                    ->toMediaCollection('attachments');
                Log::info("Media added: {$media->id} with fileId: {$fileInfo['fileId']}");
            }

            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}
