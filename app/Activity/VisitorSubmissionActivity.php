<?php

namespace App\Activity;

use App\Enums\LogEvent;
use App\Enums\LogName;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class VisitorSubmissionActivity
{
    /**
     * Log a visitor submission creation event.
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
            ->useLog(LogName::VisitorSubmissions->value)
            ->event(LogEvent::VisitorSubmitted->value)
            ->causedBy($user)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Visitor submitted a registration form');
    }

    /**
     * Log a visitor submission update event.
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

        // We should create a VisitorUpdatedSubmission event in the future if needed
        activity()
            ->useLog(LogName::VisitorSubmissions->value)
            ->event(LogEvent::Modification->value)
            ->causedBy($user)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Visitor updated a registration form');
    }

    /**
     * Log a visitor attendance registration event.
     *
     * @param Model|null $user
     * @param Model|null $submission
     * @param array|null $properties
     * @return void
     */
    public static function logAttendance(?Model $user, ?Model $submission, ?array $properties = []): void
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
            ->useLog(LogName::VisitorSubmissions->value)
            ->event(LogEvent::VisitorSubmitted->value)
            ->causedBy($user)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Visitor registered attendance');
    }

    /**
     * Log an anonymous visitor submission creation event.
     *
     * @param Model|null $submission
     * @param array|null $properties
     * @return void
     */
    public static function logAnonymousCreate(?Model $submission, ?array $properties = []): void
    {
        if ($submission === null) {
            return;
        }

        $baseProperties = [
            'email' => $submission->anonymous_email,
            'submission_id' => $submission->id,
            'event_id' => $submission->event_announcement_id,
            'event_title' => $submission->eventAnnouncement->title ?? null,
            'is_anonymous' => true,
        ];

        activity()
            ->useLog(LogName::VisitorSubmissions->value)
            ->event(LogEvent::VisitorSubmitted->value)
            ->performedOn($submission)
            ->withProperties(array_merge($baseProperties, $properties ?? []))
            ->log('Anonymous visitor registered attendance');
    }

    /**
     * Send a Meta Pixel CompleteRegistration event for visitor flows.
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
                    'event_name' => 'Inscription Visiteur',
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
                Log::error('visitor : Response: ' . $response->body());
            } else {
                Log::error('visitor : Error occurred: HTTP ' . $response->status());
                Log::error('Response: ' . $response->body());
            }
        } catch (\Throwable $e) {
            // Swallow all errors; function must not error
        }
    }

    /**
     * Send a Meta Pixel CompleteRegistration event for anonymous visitor flows.
     */
    public static function sendMetaPixelCompleteRegistrationAnonymous(
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
                return;
            }

            $payload = [
                'data' => [[
                    'event_name' => 'Inscription Visiteur Anonyme',
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
                Log::error('visitor anonymos : Response: ' . $response->body());
            } else {
                Log::error('visitor anonymos : Error occurred: HTTP ' . $response->status());
                Log::error('Response: ' . $response->body());
            }
        } catch (\Throwable $e) {
            // Swallow all errors
        }
    }
}
