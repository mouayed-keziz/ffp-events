<?php

namespace App\Forms;

use App\Models\EventAnnouncement;
use App\Enums\FormField;
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
        if (!$event->exhibitorForms || $event->exhibitorForms->isEmpty()) {
            return [];
        }

        $formData = [];

        foreach ($event->exhibitorForms as $formIndex => $exhibitorForm) {
            $formData[$formIndex] = [
                'title' => [
                    "ar" => $exhibitorForm->getTranslation('title', 'ar'),
                    "fr" => $exhibitorForm->getTranslation('title', 'fr'),
                    "en" => $exhibitorForm->getTranslation('title', 'en'),
                ],
                'description' => [
                    "ar" => $exhibitorForm->getTranslation('description', 'ar'),
                    "fr" => $exhibitorForm->getTranslation('description', 'fr'),
                    "en" => $exhibitorForm->getTranslation('description', 'en'),
                ],
                'sections' => []
            ];

            foreach ($exhibitorForm->sections as $section) {
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

                $formData[$formIndex]['sections'][] = $sectionData;
            }
        }

        return $formData;
    }

    /**
     * Initialize a single field structure (fallback method)
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
        $attributes = [];

        if (!$event->exhibitorForms || $event->exhibitorForms->isEmpty()) {
            return ['rules' => $rules, 'attributes' => $attributes];
        }

        // If a specific step is provided, only validate that form
        if ($currentStep !== null) {
            $exhibitorForm = $event->exhibitorForms[$currentStep] ?? null;
            if (!$exhibitorForm) {
                return ['rules' => $rules, 'attributes' => $attributes];
            }

            foreach ($exhibitorForm->sections as $sectionIndex => $section) {
                foreach ($section['fields'] as $fieldIndex => $field) {
                    $fieldKey = "formData.{$currentStep}.sections.{$sectionIndex}.fields.{$fieldIndex}.answer";

                    $fieldType = FormField::tryFrom($field['type']);
                    if ($fieldType) {
                        $rules[$fieldKey] = implode('|', $fieldType->getValidationRules($field));
                        // Add attribute name using the field's label in current locale
                        $attributes[$fieldKey] = $field['data']['label'][app()->getLocale()] ?? '';
                    }
                }
            }
        }

        return [
            'rules' => $rules,
            'attributes' => $attributes
        ];
    }

    /**
     * Process form data for submission
     */
    public function processFormDataForSubmission(array $formData, bool $shouldCalculatePrice = false): array
    {
        if (empty($formData)) {
            return ['processedData' => $formData, 'filesToProcess' => []];
        }

        $processedFormData = $formData;
        $filesToProcess = [];

        // Process multi-form structure (exhibitor forms)
        foreach ($processedFormData as $formIndex => $form) {
            if (!isset($form['sections']) || !is_array($form['sections'])) {
                continue;
            }

            foreach ($form['sections'] as $sectionIndex => $section) {
                if (!isset($section['fields']) || !is_array($section['fields'])) {
                    continue;
                }

                foreach ($section['fields'] as $fieldIndex => $field) {
                    // First process field answers
                    if (isset($field['type']) && isset($field['answer'])) {
                        $fieldType = FormField::tryFrom($field['type']);
                        if ($fieldType) {
                            // Skip Upload fields for now, we'll handle them separately
                            if ($field['type'] !== FormField::UPLOAD->value) {
                                $processedFormData[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'] =
                                    $fieldType->processFieldAnswer(
                                        $field['answer'],
                                        $field['data'] ?? []
                                    );
                            }
                        }
                    }

                    // Then process file uploads specifically
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
                            $processedFormData[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'] = $fileId;
                        }
                    }
                }
            }
        }

        // Calculate total price if needed
        if ($shouldCalculatePrice) {
            $processedFormData['total_prices'] = $this->calculateTotalPricesAllCurrencies($processedFormData);
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
            // Process the form data with price calculation
            $processResult = $this->processFormDataForSubmission($formData, true);
            $processedData = $processResult['processedData'];
            $filesToProcess = $processResult['filesToProcess'];

            // Get exhibitor ID if user is authenticated as an exhibitor
            $exhibitorId = null;
            if (auth('exhibitor')->check()) {
                $exhibitorId = auth('exhibitor')->user()->id;
            }
            // Create submission with total prices
            $submission = \App\Models\ExhibitorSubmission::create([
                'exhibitor_id' => $exhibitorId,
                'event_announcement_id' => $event->id,
                'answers' => array_values($processedData),
                'total_prices' => $processedData['total_prices'] ?? null,
                'status' => 'pending',
            ]);
            Log::info("Exhibitor Submission created: {$submission->id}");

            // Process files using Media Library
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
