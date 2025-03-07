<?php

namespace App\Forms;

use App\Enums\FormField;
use App\Models\EventAnnouncement;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

abstract class BaseFormActions
{
    // Supported currencies - can be overridden in child classes
    protected array $supportedCurrencies = ['DZD', 'EUR', 'USD'];

    /**
     * Initialize form data structure based on the event's forms
     */
    abstract public function initFormData(EventAnnouncement $event): array;

    /**
     * Get validation rules for the form
     */
    abstract public function getValidationRules(EventAnnouncement $event, int $currentStep = null): array;

    /**
     * Process form data before saving
     */
    public function processFormData(array $formData): array
    {
        if (empty($formData)) {
            return $formData;
        }

        // Process nested form data - handles both single form and multi-form structures
        $isSingleForm = isset($formData[0]['fields']) || isset($formData[0]['sections']);

        if ($isSingleForm) {
            return $this->processSingleForm($formData);
        } else {
            return $this->processMultiForm($formData);
        }
    }

    /**
     * Process a single form structure (visitor form)
     */
    protected function processSingleForm(array $formData): array
    {
        $processedFormData = $formData;

        foreach ($processedFormData as $sectionIndex => $section) {
            if (!isset($section['fields']) || !is_array($section['fields'])) {
                continue;
            }

            foreach ($section['fields'] as $fieldIndex => $field) {
                // Skip fields without answers
                if (!isset($field['answer']) || !isset($field['type'])) {
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
     * Process a multi-form structure (exhibitor forms)
     */
    protected function processMultiForm(array $formData): array
    {
        $processedFormData = $formData;

        foreach ($processedFormData as $formIndex => $form) {
            if (!isset($form['sections']) || !is_array($form['sections'])) {
                continue;
            }

            foreach ($form['sections'] as $sectionIndex => $section) {
                if (!isset($section['fields']) || !is_array($section['fields'])) {
                    continue;
                }

                foreach ($section['fields'] as $fieldIndex => $field) {
                    // Skip fields without answers
                    if (!isset($field['answer']) || !isset($field['type'])) {
                        continue;
                    }

                    $fieldType = FormField::tryFrom($field['type']);
                    if ($fieldType) {
                        $processedFormData[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'] =
                            $fieldType->processFieldAnswer($field['answer'], $field['data'] ?? []);
                    }
                }
            }
        }

        return $processedFormData;
    }

    /**
     * Process form data for submission (handle file uploads)
     * 
     * This method is meant to be overridden by child classes for visitor and exhibitor specific handling
     * 
     * @param array $formData The form data to process
     * @param bool $shouldCalculatePrice Whether to calculate pricing information
     * @return array
     */
    public function processFormDataForSubmission(array $formData, bool $shouldCalculatePrice = false): array
    {
        if (empty($formData)) {
            return ['processedData' => $formData, 'filesToProcess' => []];
        }

        $processedFormData = $formData;
        $filesToProcess = [];

        // Determine if it's a single form or multi-form structure
        $isSingleForm = isset($formData[0]['fields']) || isset($formData[0]['sections']);

        if ($isSingleForm) {
            $result = $this->processSingleFormForSubmission($processedFormData);
        } else {
            $result = $this->processMultiFormForSubmission($processedFormData);
        }

        $processedFormData = $result['processedData'];
        $filesToProcess = $result['filesToProcess'];

        // Calculate total price if applicable (for exhibitor forms)
        if ($shouldCalculatePrice) {
            $totalPrices = $this->calculateTotalPricesAllCurrencies($processedFormData);
            $processedFormData['total_prices'] = $totalPrices;
        }

        return [
            'processedData' => $processedFormData,
            'filesToProcess' => $filesToProcess
        ];
    }

    /**
     * Process a single form structure for submission (visitor form)
     */
    protected function processSingleFormForSubmission(array $formData): array
    {
        $processedFormData = $formData;
        $filesToProcess = [];

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

                // Process field answers with translations and pricing if needed
                if (isset($field['type']) && isset($field['answer'])) {
                    $fieldType = FormField::tryFrom($field['type']);
                    if ($fieldType) {
                        $processedFormData[$sectionIndex]['fields'][$fieldIndex]['answer'] =
                            $fieldType->processFieldAnswer(
                                $field['answer'],
                                $field['data'] ?? []
                            );
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
     * Process a multi-form structure for submission (exhibitor forms)
     */
    protected function processMultiFormForSubmission(array $formData): array
    {
        $processedFormData = $formData;
        $filesToProcess = [];

        foreach ($processedFormData as $formIndex => $form) {
            if (!isset($form['sections']) || !is_array($form['sections'])) {
                continue;
            }

            foreach ($form['sections'] as $sectionIndex => $section) {
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
                            $processedFormData[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'] = $fileId;
                        }
                    }

                    // Process field answers with translations and pricing if needed
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
     * Calculate total price for the specified currency
     */
    public function calculateTotalPrice(array $formData, string $preferredCurrency): float
    {
        $total = 0;

        // Handle visitor form structure (sections with fields)
        if (isset($formData[0]['fields'])) {
            foreach ($formData as $section) {
                foreach ($section['fields'] ?? [] as $field) {
                    if (!isset($field['type'])) {
                        continue;
                    }

                    $fieldType = FormField::tryFrom($field['type']);
                    if ($fieldType && $fieldType->isPriced()) {
                        $fieldTotal = $fieldType->calculateFieldPrice(
                            $field['answer'] ?? null,
                            $field['data'] ?? [],
                            $preferredCurrency
                        );
                        $total += $fieldTotal;
                    }
                }
            }
            return $total;
        }

        // Handle exhibitor form structure (multiple forms with sections and fields)
        foreach ($formData as $formIndex => $form) {
            if (!is_array($form) || !isset($form['sections'])) {
                continue;
            }

            foreach ($form['sections'] as $section) {
                foreach ($section['fields'] ?? [] as $field) {
                    if (!isset($field['type'])) {
                        continue;
                    }

                    $fieldType = FormField::tryFrom($field['type']);
                    if ($fieldType && $fieldType->isPriced()) {
                        $fieldData = $field['data'] ?? [];

                        // For fields with quantity, include it in the calculation
                        if ($fieldType->needsQuantity()) {
                            $fieldData['quantity'] = $field['quantity'] ?? 1;
                        }

                        $fieldTotal = $fieldType->calculateFieldPrice(
                            $field['answer'] ?? null,
                            $fieldData,
                            $preferredCurrency
                        );
                        $total += $fieldTotal;
                    }
                }
            }
        }

        return $total;
    }

    /**
     * Calculate the total price for all supported currencies
     */
    public function calculateTotalPricesAllCurrencies(array $formData): array
    {
        $totals = array_fill_keys($this->supportedCurrencies, 0);

        foreach ($this->supportedCurrencies as $currency) {
            $totals[$currency] = $this->calculateTotalPrice($formData, $currency);
        }

        return $totals;
    }
}
