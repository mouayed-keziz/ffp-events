<?php

namespace App\Actions;

use App\Models\EventAnnouncement;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;


class VisitEventFormActions
{
    /**
     * Initialize form data structure based on the event's visitor form
     */
    public function initFormData(EventAnnouncement $event): array
    {
        if (!$event->visitorForm) {
            return [];
        }

        $formData = [];

        // Create the structured formData with sections and fields
        foreach ($event->visitorForm->sections as $section) {
            $sectionData = [
                'title' => $section['title'],
                'fields' => []
            ];

            foreach ($section['fields'] as $field) {
                $sectionData['fields'][] = $this->initializeField($field);
            }

            $formData[] = $sectionData;
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

        // Initialize answer based on field type
        if ($field['type'] === \App\Enums\FormField::CHECKBOX->value) {
            $fieldData['answer'] = [];
        } elseif ($field['type'] === \App\Enums\FormField::UPLOAD->value) {
            $fieldData['answer'] = null;
        } else {
            $fieldData['answer'] = '';
        }

        return $fieldData;
    }

    /**
     * Get validation rules for the form
     */
    public function getValidationRules(EventAnnouncement $event): array
    {
        $rules = [];

        if (!$event->visitorForm) {
            return $rules;
        }

        foreach ($event->visitorForm->sections as $sectionIndex => $section) {
            foreach ($section['fields'] as $fieldIndex => $field) {
                $fieldKey = "formData.{$sectionIndex}.fields.{$fieldIndex}.answer";
                $rules[$fieldKey] = $this->getFieldValidationRules($field);
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

        foreach ($processedFormData as $sectionIndex => $section) {
            if (!isset($section['fields']) || !is_array($section['fields'])) {
                continue;
            }

            foreach ($section['fields'] as $fieldIndex => $field) {
                // Process file uploads
                if (isset($field['type']) && $field['type'] === \App\Enums\FormField::UPLOAD->value && isset($field['answer'])) {
                    if ($field['answer'] instanceof TemporaryUploadedFile) {
                        // Store file and save path
                        $processedFormData[$sectionIndex]['fields'][$fieldIndex]['answer'] =
                            $this->processFileUpload($field['answer']);
                    }
                    continue;
                }

                // Process choice fields
                if (!isset($field['answer']) || !in_array($field['type'] ?? '', [
                    \App\Enums\FormField::SELECT->value,
                    \App\Enums\FormField::CHECKBOX->value,
                    \App\Enums\FormField::RADIO->value
                ])) {
                    continue;
                }

                // Process based on field type
                switch ($field['type']) {
                    case \App\Enums\FormField::SELECT->value:
                    case \App\Enums\FormField::RADIO->value:
                        // For single select/radio, find the matching option and convert to translatable array
                        $processedFormData[$sectionIndex]['fields'][$fieldIndex]['answer'] =
                            $this->findOptionTranslations($field['data']['options'] ?? [], $field['answer']);
                        break;

                    case \App\Enums\FormField::CHECKBOX->value:
                        // For checkboxes (array of values), convert each selected value
                        if (is_array($field['answer'])) {
                            $translatedAnswers = [];
                            foreach ($field['answer'] as $answer) {
                                $translatedAnswers[] = $this->findOptionTranslations($field['data']['options'] ?? [], $answer);
                            }
                            $processedFormData[$sectionIndex]['fields'][$fieldIndex]['answer'] = $translatedAnswers;
                        }
                        break;
                }
            }
        }

        return $processedFormData;
    }

    /**
     * Process a file upload and return the stored path
     */
    protected function processFileUpload(TemporaryUploadedFile $file): string
    {
        // Store the file in a permanent location
        $path = $file->store('form-uploads', 'public');
        return $path;
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
        foreach ($processedFormData as $sectionIndex => $section) {
            if (!isset($section['fields']) || !is_array($section['fields'])) {
                continue;
            }

            foreach ($section['fields'] as $fieldIndex => $field) {
                // Process file uploads
                if (isset($field['type']) && $field['type'] === \App\Enums\FormField::UPLOAD->value && isset($field['answer'])) {
                    if ($field['answer'] instanceof TemporaryUploadedFile) {
                        // Generate unique identifier for the file
                        $fileId = (string) \Illuminate\Support\Str::uuid();

                        // Save file information for later processing
                        $filesToProcess[] = [
                            'file' => $field['answer'],
                            'fileId' => $fileId,
                        ];

                        // Replace the file in form data with the identifier
                        $processedFormData[$sectionIndex]['fields'][$fieldIndex]['answer'] = $fileId;
                    }
                    continue;
                }

                // Process other field types as before
                // ... existing code for processing other field types ...
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
     * Save the form submission to the database
     */
    public function saveFormSubmission(EventAnnouncement $event, array $formData): bool
    {
        try {
            // Process the form data (handle file uploads, translatable fields, etc.)
            $processResult = $this->processFormDataForSubmission($formData);
            $processedData = $processResult['processedData'];
            $filesToProcess = $processResult['filesToProcess'];

            // Get visitor ID if user is authenticated as a visitor
            $visitorId = null;
            if (auth('visitor')->check()) {
                $visitorId = auth('visitor')->user()->id;
            }

            // Create a new submission with nullable visitor_id
            $submission = \App\Models\VisitorSubmission::create([
                'visitor_id' => $visitorId,
                'event_announcement_id' => $event->id,
                'answers' => $processedData,
                'status' => 'approved',
            ]);
            Log::info("Submission created: {$submission->id}");
            // Process any files by adding them to the Spatie Media Library
            foreach ($filesToProcess as $fileInfo) {
                $media = $submission->addMedia($fileInfo['file']->getRealPath())
                    ->usingFileName($fileInfo['file']->getClientOriginalName())
                    ->withCustomProperties([
                        'file_id' => $fileInfo['fileId'],
                    ])
                    ->toMediaCollection('attachments');
                Log::info("Media added: {$media->id}");
            }

            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}
