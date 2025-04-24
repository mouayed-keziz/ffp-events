<?php

namespace App\Forms;

use App\Models\EventAnnouncement;
use App\Enums\FormField;
use App\Enums\SubmissionStatus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Models\ExhibitorSubmission;
use Illuminate\Support\Facades\App;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
                'image' => $exhibitorForm->image,
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
        // try {
        // Process the form data with price calculation
        $processResult = $this->processFormDataForSubmission($formData, true);
        $processedData = $processResult['processedData'];
        $filesToProcess = $processResult['filesToProcess'];

        // Get exhibitor ID if user is authenticated as an exhibitor
        $exhibitorId = null;
        $exhibitor = null;
        if (auth('exhibitor')->check()) {
            $exhibitorId = auth('exhibitor')->user()->id;
            $exhibitor = auth('exhibitor')->user();
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

        // Send notification if exhibitor is authenticated
        if ($exhibitor) {
            // Get the current locale for localized notification
            $locale = App::getLocale();

            // Send the notification
            $exhibitor->notify(new \App\Notifications\Exhibitor\ExhibitorSubmissionProcessing($event, $submission, $locale));
            Log::info("Exhibitor notification sent to: {$exhibitor->email} with locale: {$locale}");
        }

        // Get all admin and super_admin users for database notifications
        $adminUsers = \App\Models\User::role(['admin', 'super_admin'])->get();

        // Send a single email to the company email from settings
        $companySettings = app(\App\Settings\CompanyInformationsSettings::class);
        // Create a temporary instance that can receive notifications
        $companyUser = new \stdClass();
        $companyUser->email = $companySettings->email;
        $companyUser->name = $companySettings->name;

        // Send email notification to company email only
        \Illuminate\Support\Facades\Notification::route('mail', $companySettings->email)
            ->notify(new \App\Notifications\Admin\NewExhibitorSubmission(
                $event,
                $exhibitor,
                $submission,
                true
            ));

        // Send database notifications to all admins and super_admins
        foreach ($adminUsers as $admin) {
            // Send database notification only
            $admin->notify(new \App\Notifications\Admin\NewExhibitorSubmission(
                $event,
                $exhibitor,
                $submission
            ));

            // Also send direct database notification for Filament panel
            \Filament\Notifications\Notification::make()
                ->title("Nouvelle soumission d'exposant")
                ->body("L'exposant {$exhibitor->name} a soumis une demande pour l'événement {$event->title}.")
                ->actions([
                    \Filament\Notifications\Actions\Action::make('voir')
                        ->label('Voir la soumission')
                        ->url(route('filament.admin.resources.exhibitor-submissions.view', $submission->id)),
                    // \Filament\Notifications\Actions\Action::make('traiter')
                    //     ->label('Traiter la demande')
                    //     ->url(route('filament.admin.resources.exhibitor-submissions.edit', $submission->id))
                    //     ->color('success'),
                ])
                ->icon('heroicon-o-document-text')
                ->iconColor('warning')
                ->sendToDatabase($admin);

            Log::info("Admin notification sent to: {$admin->email} for new exhibitor submission");
        }

        return true;
        // } catch (\Exception $e) {
        //     report($e);
        //     return false;
        // }
    }

    /**
     * Transform a submission's answers back into interactive formData for editing
     * 
     * @param ExhibitorSubmission $submission The submission to transform
     * @param EventAnnouncement $event The event that contains the form structure
     * @return array The formData with answers included ready for user interaction
     */
    public function transformSubmissionToFormData(ExhibitorSubmission $submission, EventAnnouncement $event): array
    {
        // First, get the empty form structure
        $formStructure = $this->initFormData($event);

        // If the structure is empty or submission doesn't have answers, return empty form
        if (empty($formStructure) || empty($submission->answers)) {
            return $formStructure;
        }

        $answers = $submission->answers;

        // Iterate through the forms and merge the answers
        foreach ($formStructure as $formIndex => $form) {
            if (isset($answers[$formIndex])) {
                foreach ($form['sections'] as $sectionIndex => $section) {
                    if (isset($answers[$formIndex]['sections'][$sectionIndex])) {
                        foreach ($section['fields'] as $fieldIndex => $field) {
                            // For file uploads, check if file exists in the media library
                            if (
                                $field['type'] === FormField::UPLOAD->value &&
                                isset($answers[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'])
                            ) {

                                $fileId = $answers[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'];

                                // Check if this fileId exists in the media library
                                $mediaExists = $submission->getMedia('attachments')
                                    ->filter(function (Media $media) use ($fileId) {
                                        return isset($media->custom_properties['fileId']) &&
                                            $media->custom_properties['fileId'] === $fileId;
                                    })
                                    ->isNotEmpty();

                                // Only set the answer if the file exists
                                if ($mediaExists) {
                                    $formStructure[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'] = $fileId;
                                }
                            }
                            // For all other field types, just copy the answers over
                            else if (isset($answers[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'])) {
                                $formStructure[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'] =
                                    $answers[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'];
                            }
                        }
                    }
                }
            }
        }

        return $formStructure;
    }

    /**
     * Update an existing submission with new form data
     * 
     * @param ExhibitorSubmission $submission The submission to update
     * @param array $formData The new form data
     * @return bool Whether the update was successful
     */
    public function updateExistingSubmission(ExhibitorSubmission $submission, array $formData): bool
    {
        try {
            // Process the form data with price calculation
            $processResult = $this->processFormDataForSubmission($formData, true);
            $processedData = $processResult['processedData'];
            $filesToProcess = $processResult['filesToProcess'];

            // Track fileIds being replaced so we can remove old files later
            $replacedFileIds = [];

            // Find and mark file replacements
            foreach ($processedData as $formIndex => $form) {
                if (!isset($form['sections'])) continue;

                foreach ($form['sections'] as $sectionIndex => $section) {
                    if (!isset($section['fields'])) continue;

                    foreach ($section['fields'] as $fieldIndex => $field) {
                        // Only interested in upload fields
                        if ($field['type'] !== FormField::UPLOAD->value || !isset($field['answer'])) continue;

                        // Check if this is a new file (UUID from processFormDataForSubmission)
                        if (is_string($field['answer']) && Str::isUuid($field['answer'])) {
                            // Check if there was a previous file for this field position in the original answers
                            $oldAnswers = $submission->answers;

                            if (isset($oldAnswers[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'])) {
                                $oldFileId = $oldAnswers[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'];
                                $replacedFileIds[] = $oldFileId;
                            }
                        }
                    }
                }
            }

            // Update the submission with the new answers
            $submission->answers = array_values($processedData);
            $submission->total_prices = $processedData['total_prices'] ?? null;
            $submission->status = SubmissionStatus::PENDING->value;
            $submission->edit_deadline = null;
            $submission->update_requested_at = null;
            $submission->save();


            // Remove media that's been replaced
            if (!empty($replacedFileIds)) {
                $submission->getMedia('attachments')
                    ->filter(function (Media $media) use ($replacedFileIds) {
                        return isset($media->custom_properties['fileId']) &&
                            in_array($media->custom_properties['fileId'], $replacedFileIds);
                    })
                    ->each(function (Media $media) {
                        $media->delete();
                    });
            }

            // Process new files using Media Library
            foreach ($filesToProcess as $fileInfo) {
                $media = $submission->addMedia($fileInfo['file']->getRealPath())
                    ->usingFileName($fileInfo['file']->getClientOriginalName())
                    ->withCustomProperties([
                        'fileId' => $fileInfo['fileId'],
                        'fileType' => $fileInfo['fieldData']['file_type'] ?? null,
                        'fieldLabel' => $fileInfo['fieldData']['label'] ?? null,
                    ])
                    ->toMediaCollection('attachments');
                Log::info("Media added to updated submission: {$media->id} with fileId: {$fileInfo['fileId']}");
            }

            Log::info("Exhibitor Submission updated: {$submission->id}");
            return true;
        } catch (\Exception $e) {
            report($e);
            Log::error("Failed to update submission: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Initialize form data structure based on the event's post-payment forms
     */
    public function initPostFormData(EventAnnouncement $event): array
    {
        if (!$event->exhibitorPostPaymentForms || $event->exhibitorPostPaymentForms->isEmpty()) {
            return [];
        }

        $formData = [];

        foreach ($event->exhibitorPostPaymentForms as $formIndex => $exhibitorForm) {
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
     * Get validation rules for post-payment forms
     */
    public function getPostFormValidationRules(EventAnnouncement $event, int $currentStep = null): array
    {
        $rules = [];
        $attributes = [];

        if (!$event->exhibitorPostPaymentForms || $event->exhibitorPostPaymentForms->isEmpty()) {
            return ['rules' => $rules, 'attributes' => $attributes];
        }

        // If a specific step is provided, only validate that form
        if ($currentStep !== null) {
            $exhibitorForm = $event->exhibitorPostPaymentForms[$currentStep] ?? null;
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
     * Transform a submission's post_answers back into interactive formData for editing
     * 
     * @param ExhibitorSubmission $submission The submission to transform
     * @param EventAnnouncement $event The event that contains the form structure
     * @return array The formData with post_answers included ready for user interaction
     */
    public function transformPostSubmissionToFormData(ExhibitorSubmission $submission, EventAnnouncement $event): array
    {
        // First, get the empty form structure
        $formStructure = $this->initPostFormData($event);

        // If the structure is empty or submission doesn't have post_answers, return empty form
        if (empty($formStructure) || empty($submission->post_answers)) {
            return $formStructure;
        }

        $answers = $submission->post_answers;

        // Iterate through the forms and merge the answers
        foreach ($formStructure as $formIndex => $form) {
            if (isset($answers[$formIndex])) {
                foreach ($form['sections'] as $sectionIndex => $section) {
                    if (isset($answers[$formIndex]['sections'][$sectionIndex])) {
                        foreach ($section['fields'] as $fieldIndex => $field) {
                            // For file uploads, check if file exists in the media library
                            if (
                                $field['type'] === FormField::UPLOAD->value &&
                                isset($answers[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'])
                            ) {
                                $fileId = $answers[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'];

                                // Check if this fileId exists in the media library
                                $mediaExists = $submission->getMedia('post_attachments')
                                    ->filter(function (Media $media) use ($fileId) {
                                        return isset($media->custom_properties['fileId']) &&
                                            $media->custom_properties['fileId'] === $fileId;
                                    })
                                    ->isNotEmpty();

                                // Only set the answer if the file exists
                                if ($mediaExists) {
                                    $formStructure[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'] = $fileId;
                                }
                            }
                            // For all other field types, just copy the answers over
                            else if (isset($answers[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'])) {
                                $formStructure[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'] =
                                    $answers[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'];
                            }
                        }
                    }
                }
            }
        }

        return $formStructure;
    }

    /**
     * Save the post-payment form submission to the database
     */
    public function savePostFormSubmission(EventAnnouncement $event, array $formData, ExhibitorSubmission $submission): bool
    {
        try {
            // Process the form data with price calculation
            $processResult = $this->processFormDataForSubmission($formData, true);
            $processedData = $processResult['processedData'];
            $filesToProcess = $processResult['filesToProcess'];

            // Update the submission with the post_answers
            $submission->post_answers = array_values($processedData);
            $submission->save();

            Log::info("Exhibitor Post-Payment Submission updated: {$submission->id}");

            // Process files using Media Library in a separate collection for post forms
            foreach ($filesToProcess as $fileInfo) {
                $media = $submission->addMedia($fileInfo['file']->getRealPath())
                    ->usingFileName($fileInfo['file']->getClientOriginalName())
                    ->withCustomProperties([
                        'fileId' => $fileInfo['fileId'],
                        'fileType' => $fileInfo['fieldData']['file_type'] ?? null,
                        'fieldLabel' => $fileInfo['fieldData']['label'] ?? null,
                    ])
                    ->toMediaCollection('post_attachments'); // Use a different collection for post form attachments

                Log::info("Media added to post submission: {$media->id} with fileId: {$fileInfo['fileId']}");
            }

            return true;
        } catch (\Exception $e) {
            report($e);
            Log::error("Failed to update post-payment submission: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update an existing submission's post_answers with new form data
     * 
     * @param ExhibitorSubmission $submission The submission to update
     * @param array $formData The new post-payment form data
     * @return bool Whether the update was successful
     */
    public function updateExistingPostSubmission(ExhibitorSubmission $submission, array $formData): bool
    {
        try {
            // Process the form data with price calculation
            $processResult = $this->processFormDataForSubmission($formData, true);
            $processedData = $processResult['processedData'];
            $filesToProcess = $processResult['filesToProcess'];

            // Track fileIds being replaced so we can remove old files later
            $replacedFileIds = [];

            // Find and mark file replacements
            foreach ($processedData as $formIndex => $form) {
                if (!isset($form['sections'])) continue;

                foreach ($form['sections'] as $sectionIndex => $section) {
                    if (!isset($section['fields'])) continue;

                    foreach ($section['fields'] as $fieldIndex => $field) {
                        // Only interested in upload fields
                        if ($field['type'] !== FormField::UPLOAD->value || !isset($field['answer'])) continue;

                        // Check if this is a new file (UUID from processFormDataForSubmission)
                        if (is_string($field['answer']) && Str::isUuid($field['answer'])) {
                            // Check if there was a previous file for this field position in the original post_answers
                            $oldAnswers = $submission->post_answers;
                            if (isset($oldAnswers[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'])) {
                                $oldFileId = $oldAnswers[$formIndex]['sections'][$sectionIndex]['fields'][$fieldIndex]['answer'];
                                $replacedFileIds[] = $oldFileId;
                            }
                        }
                    }
                }
            }

            // Update the submission with the new post_answers
            $submission->post_answers = array_values($processedData);
            $submission->save();

            // Remove media that's been replaced
            if (!empty($replacedFileIds)) {
                $submission->getMedia('post_attachments')
                    ->filter(function (Media $media) use ($replacedFileIds) {
                        return isset($media->custom_properties['fileId']) &&
                            in_array($media->custom_properties['fileId'], $replacedFileIds);
                    })
                    ->each(function (Media $media) {
                        $media->delete();
                    });
            }

            // Process new files using Media Library
            foreach ($filesToProcess as $fileInfo) {
                $media = $submission->addMedia($fileInfo['file']->getRealPath())
                    ->usingFileName($fileInfo['file']->getClientOriginalName())
                    ->withCustomProperties([
                        'fileId' => $fileInfo['fileId'],
                        'fileType' => $fileInfo['fieldData']['file_type'] ?? null,
                        'fieldLabel' => $fileInfo['fieldData']['label'] ?? null,
                    ])
                    ->toMediaCollection('post_attachments');

                Log::info("Media added to updated post submission: {$media->id} with fileId: {$fileInfo['fileId']}");
            }

            Log::info("Exhibitor Post Submission updated: {$submission->id}");
            return true;
        } catch (\Exception $e) {
            report($e);
            Log::error("Failed to update post submission: " . $e->getMessage());
            return false;
        }
    }
}
