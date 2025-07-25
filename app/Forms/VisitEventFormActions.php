<?php

namespace App\Forms;

use App\Models\Badge;
use App\Models\EventAnnouncement;
use App\Models\Visitor;
use App\Models\VisitorSubmission;
use App\Enums\FormField;
use App\Notifications\Visitor\VisitorEventRegistrationSuccessful;
use App\Services\BadgeService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
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
    public function saveFormSubmission(EventAnnouncement $event, array $formData, string $badgeCompany = '', string $badgePosition = ''): bool
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

            // Generate a badge for the visitor submission
            // Only generate badge if visitor is authenticated (so we have a name)
            if ($visitor) {
                $badge = $this->generateBadgeForVisitorSubmission($event, $submission, $processedData, $badgeCompany, $badgePosition);

                if ($badge) {
                    Log::info("Badge generated for visitor: {$visitor->name}");

                    // Get the current locale for localized notification
                    $locale = App::getLocale();

                    // Send the notification with badge
                    $visitor->notify(new VisitorEventRegistrationSuccessful($event, $submission, $locale));
                    Log::info("Visitor notification sent to: {$visitor->email} with locale: {$locale}");
                }
            }

            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * Save anonymous form submission with badge information and email
     */
    public function saveAnonymousFormSubmission(EventAnnouncement $event, array $formData, string $badgeCompany = '', string $badgePosition = '', string $anonymousEmail = ''): bool
    {
        try {
            // Process the form data (handle file uploads, translatable fields, etc.)
            $processResult = $this->processFormDataForSubmission($formData);
            $processedData = $processResult['processedData'];
            $filesToProcess = $processResult['filesToProcess'];

            // Create a new anonymous submission
            $submission = VisitorSubmission::create([
                'visitor_id' => null, // Anonymous submission
                'anonymous_email' => $anonymousEmail,
                'event_announcement_id' => $event->id,
                'answers' => $processedData,
                'status' => 'approved',
            ]);

            Log::info("Anonymous Visitor Submission created: {$submission->id} for email: {$anonymousEmail}");

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

            // Generate a badge for the anonymous visitor submission
            $badge = $this->generateBadgeForAnonymousSubmission($event, $submission, $processedData, $badgeCompany, $badgePosition, $anonymousEmail);

            if ($badge) {
                Log::info("Badge generated for anonymous visitor: {$anonymousEmail}");

                // Get the current locale for localized notification
                $locale = App::getLocale();

                // Send email notification to anonymous user
                $this->sendAnonymousNotification($event, $submission, $anonymousEmail, $locale);
                Log::info("Anonymous visitor notification sent to: {$anonymousEmail} with locale: {$locale}");
            }

            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * Generate a badge for a visitor submission
     */
    protected function generateBadgeForVisitorSubmission(EventAnnouncement $event, VisitorSubmission $submission, array $processedData, string $badgeCompany = '', string $badgePosition = ''): ?Badge
    {
        try {
            // Get the badge template path from the event announcement
            $templatePath = BadgeService::getTemplatePath($event->id, 'visitor');
            if (!$templatePath) {
                Log::warning("No badge template found for event: {$event->id}");
                return null;
            }

            // Use visitor name directly from the visitor model
            $name = $submission->visitor ? $submission->visitor->name : 'Unknown';
            $email = $submission->visitor ? $submission->visitor->email : 'unknown@unknown.com';
            // Generate QR code data (random unique code)
            $qrData = Str::uuid()->toString();

            // Generate badge image using BadgeService - for visitor badge, include name, company, and job
            $badgeImage = BadgeService::generateBadgePreview($templatePath, [
                'name' => $name,
                'company' => $badgeCompany,
                'job' => $badgePosition, // This should be the French job title
                'qr_data' => $qrData
            ]);

            if (!$badgeImage) {
                Log::warning("Failed to generate badge image for submission: {$submission->id}");
                return null;
            }

            // Create temporary file to save the image
            $tempFile = tempnam(sys_get_temp_dir(), 'badge_');
            $tempFilePath = $tempFile . '.png';
            $badgeImage->toPng()->save($tempFilePath);

            // Create badge record - for visitor badge, include company and position
            $badge = Badge::create([
                'code' => $qrData,
                'name' => $name,
                'email' => $email,
                'company' => $badgeCompany,
                'position' => $badgePosition, // Store the French job title
                'visitor_submission_id' => $submission->id
            ]);

            // Add the generated image to the badge media library
            $badge->addMedia($tempFilePath)
                ->usingName('badge_' . $submission->id)
                ->toMediaCollection('image');

            // Remove temporary file
            if (file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }

            Log::info("Badge created for visitor submission: {$submission->id}");
            return $badge;
        } catch (\Exception $e) {
            Log::error("Error generating badge: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate a badge for an anonymous visitor submission
     */
    protected function generateBadgeForAnonymousSubmission(EventAnnouncement $event, VisitorSubmission $submission, array $processedData, string $badgeCompany = '', string $badgePosition = '', string $anonymousEmail = ''): ?Badge
    {
        try {
            // Get the badge template path from the event announcement
            $templatePath = BadgeService::getTemplatePath($event->id, 'visitor');
            if (!$templatePath) {
                Log::warning("No badge template found for event: {$event->id}");
                return null;
            }

            // For anonymous submissions, use 'Anonymous Visitor' as name
            $name = 'Anonymous Visitor';
            $email = $anonymousEmail;
            // Generate QR code data (random unique code)
            $qrData = Str::uuid()->toString();

            // Generate badge image using BadgeService - for visitor badge, include name, company, and job
            $badgeImage = BadgeService::generateBadgePreview($templatePath, [
                'name' => $name,
                'company' => $badgeCompany,
                'job' => $badgePosition, // This should be the French job title
                'qr_data' => $qrData
            ]);

            if (!$badgeImage) {
                Log::warning("Failed to generate badge image for anonymous submission: {$submission->id}");
                return null;
            }

            // Create temporary file to save the image
            $tempFile = tempnam(sys_get_temp_dir(), 'badge_');
            $tempFilePath = $tempFile . '.png';

            // Convert image to binary and save to temp file
            $badgeImage->save($tempFilePath, 'png');

            // Create a badge record
            $badge = Badge::create([
                'qr_data' => $qrData,
                'visitor_submission_id' => $submission->id
            ]);

            // Add the generated image to the badge media library
            $badge->addMedia($tempFilePath)
                ->usingName('badge_' . $submission->id)
                ->toMediaCollection('image');

            // Remove temporary file
            if (file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }

            Log::info("Badge created for anonymous visitor submission: {$submission->id}");
            return $badge;
        } catch (\Exception $e) {
            Log::error("Error generating badge for anonymous submission: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Send notification to anonymous user via email
     */
    protected function sendAnonymousNotification(EventAnnouncement $event, VisitorSubmission $submission, string $email, string $locale): void
    {
        try {
            // Create a simple anonymous mail notification
            $anonymousMail = new \App\Mail\Visitor\AnonymousVisitorEventRegistrationSuccessfulMail(
                $event,
                $submission,
                $email,
                $locale
            );

            \Illuminate\Support\Facades\Mail::to($email)->send($anonymousMail);
        } catch (\Exception $e) {
            Log::error("Error sending anonymous notification: " . $e->getMessage());
        }
    }
}
