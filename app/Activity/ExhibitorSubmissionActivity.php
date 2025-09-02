<?php

namespace App\Activity;

use App\Enums\LogEvent;
use App\Enums\LogName;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ExhibitorSubmissionActivity
{
    /**
     * Log an exhibitor submission creation event.
     *
     * @param Model|null $user
     * @param Model|null $submission
     * @param array|null $properties
     * @return void
     */
    public static function logCreate(?Model $user, ?Model $submission, ?array $properties = []): void
    {
        if ($user === null || $submission === null) {
            return;
        }

        $baseProperties = [
            'email' => $user->email,
            'name' => $user->name,
            'submission_id' => $submission->id,
            'event_id' => $submission->event_announcement_id,
            'event_title' => $submission->eventAnnouncement->title ?? null,
        ];

        activity()
            ->useLog(LogName::ExhibitorSubmissions->value)
            ->event(LogEvent::ExhibitorSubmitted->value)
            ->causedBy($user)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Exhibitor submitted a registration form');
    }

    /**
     * Log an exhibitor submission update event.
     *
     * @param Model|null $user
     * @param Model|null $submission
     * @param array|null $properties
     * @return void
     */
    public static function logUpdate(?Model $user, ?Model $submission, ?array $properties = []): void
    {
        if ($user === null || $submission === null) {
            return;
        }

        $baseProperties = [
            'email' => $user->email,
            'name' => $user->name,
            'submission_id' => $submission->id,
            'event_id' => $submission->event_announcement_id,
            'event_title' => $submission->eventAnnouncement->title ?? null,
        ];

        activity()
            ->useLog(LogName::ExhibitorSubmissions->value)
            ->event(LogEvent::ExhibitorUpdatedSubmission->value)
            ->causedBy($user)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Exhibitor updated a registration form');
    }

    /**
     * Log an exhibitor payment proof submission event.
     *
     * @param Model|null $user
     * @param Model|null $submission
     * @param Model|null $paymentSlice
     * @param array|null $properties
     * @return void
     */
    public static function logPaymentProof(?Model $user, ?Model $submission, ?Model $paymentSlice, ?array $properties = []): void
    {
        if ($user === null || $submission === null || $paymentSlice === null) {
            return;
        }

        $baseProperties = [
            'email' => $user->email,
            'name' => $user->name,
            'submission_id' => $submission->id,
            'event_id' => $submission->event_announcement_id,
            'event_title' => $submission->eventAnnouncement->title ?? null,
            'payment_id' => $paymentSlice->id,
            'payment_amount' => $paymentSlice->price,
            'payment_currency' => $paymentSlice->currency,
        ];

        activity()
            ->useLog(LogName::ExhibitorSubmissions->value)
            ->event(LogEvent::ExhibitorUploadedPaymentProof->value)
            ->causedBy($user)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Exhibitor submitted a payment proof');
    }

    /**
     * Log an exhibitor post-payment form submission event.
     *
     * @param Model|null $user
     * @param Model|null $submission
     * @param array|null $properties
     * @return void
     */
    public static function logPostFormSubmission(?Model $user, ?Model $submission, ?array $properties = []): void
    {
        if ($user === null || $submission === null) {
            return;
        }

        $baseProperties = [
            'email' => $user->email,
            'name' => $user->name,
            'submission_id' => $submission->id,
            'event_id' => $submission->event_announcement_id,
            'event_title' => $submission->eventAnnouncement->title ?? null,
        ];

        activity()
            ->useLog(LogName::ExhibitorSubmissions->value)
            ->event(LogEvent::ExhibitorSubmittedPostForms->value)
            ->causedBy($user)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Exhibitor submitted a post-payment form');
    }

    /**
     * Log an exhibitor request to update their submission.
     *
     * @param Model|null $user
     * @param Model|null $submission
     * @param array|null $properties
     * @return void
     */
    public static function logRequestUpdate(?Model $user, ?Model $submission, ?array $properties = []): void
    {
        if ($user === null || $submission === null) {
            return;
        }

        $baseProperties = [
            'email' => $user->email,
            'name' => $user->name,
            'submission_id' => $submission->id,
            'event_id' => $submission->event_announcement_id,
            'event_title' => $submission->eventAnnouncement->title ?? null,
        ];

        activity()
            ->useLog(LogName::ExhibitorSubmissions->value)
            ->event(LogEvent::ExhibitorRequestedUpdate->value)
            ->causedBy($user)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Exhibitor requested to update their submission');
    }

    /**
     * Log an exhibitor invoice download.
     *
     * @param Model|null $user
     * @param Model|null $submission
     * @param array|null $properties
     * @return void
     */
    public static function logInvoiceDownload(?Model $user, ?Model $submission, ?array $properties = []): void
    {
        if ($user === null || $submission === null) {
            return;
        }

        $baseProperties = [
            'email' => $user->email,
            'name' => $user->name,
            'submission_id' => $submission->id,
            'event_id' => $submission->event_announcement_id,
            'event_title' => $submission->eventAnnouncement->title ?? null,
        ];

        activity()
            ->useLog(LogName::ExhibitorSubmissions->value)
            ->event(LogEvent::ExhibitorDownloadedInvoice->value)
            ->causedBy($user)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Exhibitor downloaded an invoice');
    }

    /**
     * Log when an admin accepts an exhibitor submission.
     *
     * @param Model|null $admin
     * @param Model|null $submission
     * @param array|null $properties
     * @return void
     */
    public static function logSubmissionAccepted(?Model $admin, ?Model $submission, ?array $properties = []): void
    {
        if ($admin === null || $submission === null) {
            return;
        }

        $baseProperties = [
            'admin_email' => $admin->email,
            'admin_name' => $admin->name,
            'submission_id' => $submission->id,
            'event_id' => $submission->event_announcement_id,
            'event_title' => $submission->eventAnnouncement->title ?? null,
            'exhibitor_email' => $submission->exhibitor->email ?? null,
            'exhibitor_name' => $submission->exhibitor->name ?? null,
        ];

        activity()
            ->useLog(LogName::ExhibitorSubmissions->value)
            ->event(LogEvent::ExhibitorSubmissionAccepted->value)
            ->causedBy($admin)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Admin accepted exhibitor submission');
    }

    /**
     * Log when an admin rejects an exhibitor submission.
     *
     * @param Model|null $admin
     * @param Model|null $submission
     * @param array|null $properties
     * @return void
     */
    public static function logSubmissionRejected(?Model $admin, ?Model $submission, ?array $properties = []): void
    {
        if ($admin === null || $submission === null) {
            return;
        }

        $baseProperties = [
            'admin_email' => $admin->email,
            'admin_name' => $admin->name,
            'submission_id' => $submission->id,
            'event_id' => $submission->event_announcement_id,
            'event_title' => $submission->eventAnnouncement->title ?? null,
            'exhibitor_email' => $submission->exhibitor->email ?? null,
            'exhibitor_name' => $submission->exhibitor->name ?? null,
            'rejection_reason' => $submission->rejection_reason ?? null,
        ];

        activity()
            ->useLog(LogName::ExhibitorSubmissions->value)
            ->event(LogEvent::ExhibitorSubmissionRejected->value)
            ->causedBy($admin)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Admin rejected exhibitor submission');
    }

    /**
     * Log when an admin validates a payment proof.
     *
     * @param Model|null $admin
     * @param Model|null $submission
     * @param Model|null $paymentSlice
     * @param array|null $properties
     * @return void
     */
    public static function logPaymentValidated(?Model $admin, ?Model $submission, ?Model $paymentSlice, ?array $properties = []): void
    {
        if ($admin === null || $submission === null || $paymentSlice === null) {
            return;
        }

        $baseProperties = [
            'admin_email' => $admin->email,
            'admin_name' => $admin->name,
            'submission_id' => $submission->id,
            'event_id' => $submission->event_announcement_id,
            'event_title' => $submission->eventAnnouncement->title ?? null,
            'exhibitor_email' => $submission->exhibitor->email ?? null,
            'exhibitor_name' => $submission->exhibitor->name ?? null,
            'payment_id' => $paymentSlice->id,
            'payment_amount' => $paymentSlice->price,
            'payment_currency' => $paymentSlice->currency,
        ];

        activity()
            ->useLog(LogName::ExhibitorSubmissions->value)
            ->event(LogEvent::ExhibitorPaymentValidated->value)
            ->causedBy($admin)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Admin validated payment proof');
    }

    /**
     * Log when an admin rejects a payment proof.
     *
     * @param Model|null $admin
     * @param Model|null $submission
     * @param Model|null $paymentSlice
     * @param array|null $properties
     * @return void
     */
    public static function logPaymentRejected(?Model $admin, ?Model $submission, ?Model $paymentSlice, ?array $properties = []): void
    {
        if ($admin === null || $submission === null || $paymentSlice === null) {
            return;
        }

        $baseProperties = [
            'admin_email' => $admin->email,
            'admin_name' => $admin->name,
            'submission_id' => $submission->id,
            'event_id' => $submission->event_announcement_id,
            'event_title' => $submission->eventAnnouncement->title ?? null,
            'exhibitor_email' => $submission->exhibitor->email ?? null,
            'exhibitor_name' => $submission->exhibitor->name ?? null,
            'payment_id' => $paymentSlice->id,
            'payment_amount' => $paymentSlice->price,
            'payment_currency' => $paymentSlice->currency,
        ];

        activity()
            ->useLog(LogName::ExhibitorSubmissions->value)
            ->event(LogEvent::ExhibitorPaymentRejected->value)
            ->causedBy($admin)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Admin rejected payment proof');
    }

    /**
     * Log when an admin deletes a payment slice.
     *
     * @param Model|null $admin
     * @param Model|null $submission
     * @param array|null $properties
     * @return void
     */
    public static function logPaymentSliceDeleted(?Model $admin, ?Model $submission, ?array $properties = []): void
    {
        if ($admin === null || $submission === null) {
            return;
        }

        $baseProperties = [
            'admin_email' => $admin->email,
            'admin_name' => $admin->name,
            'submission_id' => $submission->id,
            'event_id' => $submission->event_announcement_id,
            'event_title' => $submission->eventAnnouncement->title ?? null,
            'exhibitor_email' => $submission->exhibitor->email ?? null,
            'exhibitor_name' => $submission->exhibitor->name ?? null,
        ];

        activity()
            ->useLog(LogName::ExhibitorSubmissions->value)
            ->event(LogEvent::ExhibitorPaymentSliceDeleted->value)
            ->causedBy($admin)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Admin deleted payment slice');
    }

    /**
     * Log when an admin marks a submission as ready.
     *
     * @param Model|null $admin
     * @param Model|null $submission
     * @param array|null $properties
     * @return void
     */
    public static function logSubmissionMarkedReady(?Model $admin, ?Model $submission, ?array $properties = []): void
    {
        if ($admin === null || $submission === null) {
            return;
        }

        $baseProperties = [
            'admin_email' => $admin->email,
            'admin_name' => $admin->name,
            'submission_id' => $submission->id,
            'event_id' => $submission->event_announcement_id,
            'event_title' => $submission->eventAnnouncement->title ?? null,
            'exhibitor_email' => $submission->exhibitor->email ?? null,
            'exhibitor_name' => $submission->exhibitor->name ?? null,
        ];

        activity()
            ->useLog(LogName::ExhibitorSubmissions->value)
            ->event(LogEvent::ExhibitorSubmissionMarkedReady->value)
            ->causedBy($admin)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Admin marked submission as ready');
    }

    /**
     * Log when an admin archives a submission.
     *
     * @param Model|null $admin
     * @param Model|null $submission
     * @param array|null $properties
     * @return void
     */
    public static function logSubmissionArchived(?Model $admin, ?Model $submission, ?array $properties = []): void
    {
        if ($admin === null || $submission === null) {
            return;
        }

        $baseProperties = [
            'admin_email' => $admin->email,
            'admin_name' => $admin->name,
            'submission_id' => $submission->id,
            'event_id' => $submission->event_announcement_id,
            'event_title' => $submission->eventAnnouncement->title ?? null,
            'exhibitor_email' => $submission->exhibitor->email ?? null,
            'exhibitor_name' => $submission->exhibitor->name ?? null,
        ];

        activity()
            ->useLog(LogName::ExhibitorSubmissions->value)
            ->event(LogEvent::ExhibitorSubmissionArchived->value)
            ->causedBy($admin)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Admin archived submission');
    }

    /**
     * Log when an admin approves an update request.
     *
     * @param Model|null $admin
     * @param Model|null $submission
     * @param array|null $properties
     * @return void
     */
    public static function logUpdateRequestApproved(?Model $admin, ?Model $submission, ?array $properties = []): void
    {
        if ($admin === null || $submission === null) {
            return;
        }

        $baseProperties = [
            'admin_email' => $admin->email,
            'admin_name' => $admin->name,
            'submission_id' => $submission->id,
            'event_id' => $submission->event_announcement_id,
            'event_title' => $submission->eventAnnouncement->title ?? null,
            'exhibitor_email' => $submission->exhibitor->email ?? null,
            'exhibitor_name' => $submission->exhibitor->name ?? null,
            'edit_deadline' => $submission->edit_deadline ?? null,
        ];

        activity()
            ->useLog(LogName::ExhibitorSubmissions->value)
            ->event(LogEvent::ExhibitorUpdateRequestApproved->value)
            ->causedBy($admin)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Admin approved update request');
    }


    /**
     * Log when an exhibitor updates their badges
     * 
     * @param Model|null $exhibitor
     * @param Model|null $submission
     * @param array|null $properties
     * @return void
     */
    public static function logBadgesUpdated(?Model $exhibitor, ?Model $submission, ?array $properties = []): void
    {
        if ($exhibitor === null || $submission === null) {
            return;
        }

        $baseProperties = [
            'email' => $exhibitor->email,
            'name' => $exhibitor->name,
            'submission_id' => $submission->id,
            'event_id' => $submission->event_announcement_id,
            'event_title' => $submission->eventAnnouncement->title ?? null,
            'badge_count' => $properties['badge_count'] ?? 0,
        ];

        activity()
            ->useLog(LogName::ExhibitorSubmissions->value)
            ->event(LogEvent::ExhibitorUpdatedBadges->value)
            ->causedBy($exhibitor)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Exhibitor updated badges for their submission');
    }

    /**
     * Log when an admin denies an update request.
     *
     * @param Model|null $admin
     * @param Model|null $submission
     * @param array|null $properties
     * @return void
     */
    public static function logUpdateRequestDenied(?Model $admin, ?Model $submission, ?array $properties = []): void
    {
        if ($admin === null || $submission === null) {
            return;
        }

        $baseProperties = [
            'admin_email' => $admin->email,
            'admin_name' => $admin->name,
            'submission_id' => $submission->id,
            'event_id' => $submission->event_announcement_id,
            'event_title' => $submission->eventAnnouncement->title ?? null,
            'exhibitor_email' => $submission->exhibitor->email ?? null,
            'exhibitor_name' => $submission->exhibitor->name ?? null,
        ];

        activity()
            ->useLog(LogName::ExhibitorSubmissions->value)
            ->event(LogEvent::ExhibitorUpdateRequestDenied->value)
            ->causedBy($admin)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Admin denied update request');
    }

    /**
     * Send a Meta Pixel CompleteRegistration event for exhibitor flows.
     * Mirrors the payload logic in MetaPixelTest command but runs silently.
     */
    public static function sendMetaPixelCompleteRegistration(
        ?string $clientIp,
        ?string $firstName,
        ?string $lastName,
        ?string $email,
        ?string $phone = null,
        ?string $testEventCode = null
    ): void {
        try {
            $pixelId = config('meta_pixel.pixel_id');
            $accessToken = config('meta_pixel.access_token');

            if (empty($pixelId) || empty($accessToken)) {
                return; // Missing config, skip silently
            }

            $payload = [
                'data' => [[
                    'event_name' => 'Inscription Exposant',
                    'event_time' => time(),
                    'action_source' => 'website',
                    'user_data' => array_filter([
                        'em' => $email ? hash('sha256', strtolower($email)) : null,
                        'ph' => $phone ? hash('sha256', $phone) : null,
                        'fn' => $firstName ? hash('sha256', strtolower($firstName)) : null,
                        'ln' => $lastName ? hash('sha256', strtolower($lastName)) : null,
                        'client_ip_address' => $clientIp,
                    ]),
                ]],
                'access_token' => $accessToken,
            ];

            if (!empty($testEventCode)) {
                $payload['test_event_code'] = $testEventCode;
            }

            $response = Http::post("https://graph.facebook.com/v18.0/{$pixelId}/events", $payload);
            if ($response->successful()) {
                Log::error('exhibitor : Response: ' . $response->body());
            } else {
                Log::error('exhibitor : Error occurred: HTTP ' . $response->status());
                Log::error('Response: ' . $response->body());
            }
        } catch (\Throwable $e) {
            // Swallow all errors; function must not error
        }
    }
}
