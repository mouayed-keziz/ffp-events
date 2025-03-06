<?php

namespace App\Actions;

use App\Models\EventAnnouncement;
use App\Enums\FormField;
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
     * This is a legacy method that shouldn't be needed with the enhanced enums
     */
    protected function initializeField(array $field): array
    {
        return [
            'type' => $field['type'],
            'data' => [
                'label' => $field['data']['label'] ?? '',
                'description' => $field['data']['description'] ?? null,
            ],
            'answer' => null
        ];
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

                $fieldType = FormField::tryFrom($field['type']);
                if ($fieldType) {
                    $rules[$fieldKey] = implode('|', $fieldType->getValidationRules($field));
                }
            }
        }

        return $rules;
    }

    /**
     * Process form data for saving, specific to visitor form
     * Simplified to rely on the FormField enum's processFieldAnswer method
     */
    public function processFormDataForSubmission(array $formData, bool $shouldCalculatePrice = false): array
    {
        $processedFormData = $formData;
        $filesToProcess = [];

        // Process file uploads in the single-form structure and handle field answers
        foreach ($processedFormData as $sectionIndex => $section) {
            if (!isset($section['fields']) || !is_array($section['fields'])) {
                continue;
            }

            foreach ($section['fields'] as $fieldIndex => $field) {
                // Process file uploads first
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

                // Now process the field answer using the FormField enum's enhanced method
                if (isset($field['type']) && isset($field['answer'])) {
                    $fieldType = FormField::tryFrom($field['type']);
                    if ($fieldType) {
                        // if its file upload then put the answer as file id
                        if ($fieldType === FormField::UPLOAD) {
                            $processedFormData[$sectionIndex]['fields'][$fieldIndex]['answer'] = $fileId;
                        } else {
                            $processedFormData[$sectionIndex]['fields'][$fieldIndex]['answer'] =
                                $fieldType->processFieldAnswer(
                                    $field['answer'],
                                    $field['data'] ?? []
                                );
                        }
                    }
                }
            }
        }

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
            Log::info("Visitor Submission created: {$submission->id}");

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
