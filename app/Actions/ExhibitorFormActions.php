<?php

namespace App\Actions;

use App\Models\EventAnnouncement;
use App\Enums\FormField;
use App\Models\ExhibitorForm;
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
                $fieldType = FormField::tryFrom($field['type']);

                if ($fieldType) {
                    $rules[$fieldKey] = implode('|', $fieldType->getValidationRules($field));
                }

                // Only ecommerce fields need quantity validation
                if ($fieldType && $fieldType->needsQuantity()) {
                    // For ecommerce, validate each product's quantity
                    if ($field['type'] === FormField::ECOMMERCE->value) {
                        foreach ($field['data']['products'] ?? [] as $product) {
                            $productQuantityKey = "{$formPrefix}.sections.{$sectionIndex}.fields.{$fieldIndex}.answer.{$product['product_id']}.quantity";
                            $productSelectedKey = "{$formPrefix}.sections.{$sectionIndex}.fields.{$fieldIndex}.answer.{$product['product_id']}.selected";

                            $rules[$productQuantityKey] = "required_if:{$productSelectedKey},1|integer|min:1";
                        }
                    }
                }
            }
        }

        return $rules;
    }

    /**
     * Process form data for saving, specific to exhibitor form
     * Override to ensure pricing calculations are included and titles are preserved
     */
    public function processFormDataForSubmission(array $formData, bool $shouldCalculatePrice = true): array
    {
        $processedFormData = $formData;
        $filesToProcess = [];

        // Process file uploads and field answers in the multi-form structure
        foreach ($processedFormData as $formIndex => $form) {
            if (!isset($form['sections']) || !is_array($form['sections'])) {
                continue;
            }

            foreach ($form['sections'] as $sectionIndex => $section) {
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
                            $processedFormData[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'] = $fileId;
                        }
                    }

                    // Now process the field answer using the FormField enum's enhanced method
                    if (isset($field['type']) && isset($field['answer'])) {
                        $fieldType = FormField::tryFrom($field['type']);
                        if ($fieldType) {
                            $processedFormData[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'] =
                                $fieldType->processFieldAnswer(
                                    $field['answer'],
                                    $field['data'] ?? []
                                );
                        }
                    }
                }
            }
        }

        // Now calculate total price if applicable
        if ($shouldCalculatePrice) {
            $totalPrices = $this->calculateTotalPricesAllCurrencies($processedFormData);
            $processedFormData['total_prices'] = $totalPrices;
        }

        // Preserve the form titles and descriptions which are translatable
        foreach ($processedFormData as $formIndex => $form) {
            if (isset($formData[$formIndex]['title'])) {
                $processedFormData[$formIndex]['title'] = $formData[$formIndex]['title'];
            }
            if (isset($formData[$formIndex]['description'])) {
                $processedFormData[$formIndex]['description'] = $formData[$formIndex]['description'];
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

            // Get exhibitor ID if user is authenticated as an exhibitor
            $exhibitorId = null;
            if (auth('exhibitor')->check()) {
                $exhibitorId = auth('exhibitor')->user()->id;
            }
            dd($processedData);
            // Create a new submission with nullable exhibitor_id
            // $submission = \App\Models\ExhibitorSubmission::create([
            //     'exhibitor_id' => $exhibitorId,
            //     'event_announcement_id' => $event->id,
            //     'answers' => $processedData,
            //     'status' => 'pending',
            // ]);
            // Log::info("Exhibitor Submission created: {$submission->id}");

            // // Process any files by adding them to the Spatie Media Library
            // foreach ($filesToProcess as $fileInfo) {
            //     $media = $submission->addMedia($fileInfo['file']->getRealPath())
            //         ->usingFileName($fileInfo['file']->getClientOriginalName())
            //         ->withCustomProperties([
            //             'fileId' => $fileInfo['fileId'],
            //             'fileType' => $fileInfo['fieldData']['file_type'] ?? null,
            //             'fieldLabel' => $fileInfo['fieldData']['label'] ?? null,
            //         ])
            //         ->toMediaCollection('attachments');
            //     Log::info("Media added: {$media->id} with fileId: {$fileInfo['fileId']}");
            // }

            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}
