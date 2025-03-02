<?php

namespace App\Actions;

use App\Models\EventAnnouncement;
use App\Enums\FormField;
use App\Enums\FileUploadType;
use App\Enums\FormInputType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;


class VisitEventFormActions extends BaseFormActions
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
                $fieldType = FormField::tryFrom($field['type']);
                $sectionData['fields'][] = $fieldType
                    ? $fieldType->initializeField($field)
                    : $this->initializeField($field);
            }

            $formData[] = $sectionData;
        }

        return $formData;
    }

    /**
     * Initialize a single field structure (fallback method)
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
        if ($field['type'] === FormField::CHECKBOX->value) {
            $fieldData['answer'] = [];
        } elseif ($field['type'] === FormField::UPLOAD->value) {
            $fieldData['answer'] = null;
        } else {
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
        $fieldType = FormField::tryFrom($field['type']);

        if ($fieldType) {
            return implode('|', $fieldType->getValidationRules($field));
        }

        // Fallback if field type is not recognized
        $fieldRules = [];

        // Check if field is required
        if (Arr::get($field, 'data.required', false)) {
            $fieldRules[] = 'required';
        } else {
            $fieldRules[] = 'nullable';
        }

        return implode('|', $fieldRules);
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
                if (!isset($field['type']) || !isset($field['answer'])) {
                    continue;
                }

                $fieldType = FormField::tryFrom($field['type']);
                if ($fieldType) {
                    $processedFormData[$sectionIndex]['fields'][$fieldIndex]['answer'] =
                        $fieldType->processFieldAnswer($field['answer'], $field['data'] ?? []);
                }
            }
        }

        return $processedFormData;
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
                if (isset($field['type']) && $field['type'] === FormField::UPLOAD->value && isset($field['answer'])) {
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
                        $processedFormData[$sectionIndex]['fields'][$fieldIndex]['answer'] = $fileId;
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
