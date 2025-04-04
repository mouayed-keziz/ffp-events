<?php

namespace App\Forms;

use App\Models\EventAnnouncement;
use App\Models\Visitor;
use App\Models\VisitorSubmission;
use App\Enums\FormField;
use App\Notifications\Visitor\VisitorEventRegistrationSuccessful;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
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
        $attributes = [];

        if (!$event->visitorForm) {
            return ['rules' => $rules, 'attributes' => $attributes];
        }

        foreach ($event->visitorForm->sections as $sectionIndex => $section) {
            foreach ($section['fields'] as $fieldIndex => $field) {
                $fieldKey = "formData.{$sectionIndex}.fields.{$fieldIndex}.answer";

                $fieldType = FormField::tryFrom($field['type']);
                if ($fieldType) {
                    $rules[$fieldKey] = implode('|', $fieldType->getValidationRules($field));
                    // Add attribute name using the field's label in current locale
                    $attributes[$fieldKey] = $field['data']['label'][app()->getLocale()] ?? '';
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

        // Process file uploads in the single-form structure and handle field answers
        foreach ($processedFormData as $sectionIndex => $section) {
            if (!isset($section['fields']) || !is_array($section['fields'])) {
                continue;
            }

            foreach ($section['fields'] as $fieldIndex => $field) {
                // First process non-file field answers
                if (isset($field['type']) && isset($field['answer'])) {
                    $fieldType = FormField::tryFrom($field['type']);
                    if ($fieldType) {
                        // Skip Upload fields for now, we'll handle them separately
                        if ($field['type'] !== FormField::UPLOAD->value) {
                            $processedFormData[$sectionIndex]['fields'][$fieldIndex]['answer'] =
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
                        $processedFormData[$sectionIndex]['fields'][$fieldIndex]['answer'] = $fileId;
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
            $visitor = null;
            if (auth('visitor')->check()) {
                $visitorId = auth('visitor')->user()->id;
                $visitor = auth('visitor')->user();
            }

            // Create a new submission with nullable visitor_id
            $submission = VisitorSubmission::create([
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

            // Send notification if visitor is authenticated
            if ($visitor) {
                // Get the current locale for localized notification
                $locale = App::getLocale();

                // Send the notification
                $visitor->notify(new VisitorEventRegistrationSuccessful($event, $submission, $locale));
                Log::info("Visitor notification sent to: {$visitor->email} with locale: {$locale}");
            }

            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}
