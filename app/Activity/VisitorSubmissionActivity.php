<?php

namespace App\Activity;

use App\Enums\LogEvent;
use App\Enums\LogName;
use Illuminate\Database\Eloquent\Model;

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
}
