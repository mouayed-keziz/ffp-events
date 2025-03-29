<?php

namespace App\Notifications\Exhibitor;

use App\Mail\Exhibitor\ExhibitorEditSubmissionRequestAcceptedMail;
use App\Models\EventAnnouncement;
use App\Models\Exhibitor;
use App\Models\ExhibitorSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;

class ExhibitorEditSubmissionRequestAccepted extends Notification
{
    use Queueable;

    public $event;
    public $submission;
    public $locale;
    public $edit_deadline;

    /**
     * Create a new notification instance.
     */
    public function __construct(EventAnnouncement $event, ExhibitorSubmission $submission, string $locale = null, $edit_deadline = null)
    {
        $this->event = $event;
        $this->submission = $submission;
        $this->locale = $locale ?? App::getLocale();
        $this->edit_deadline = $submission->edit_deadline;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): ExhibitorEditSubmissionRequestAcceptedMail
    {
        return (new ExhibitorEditSubmissionRequestAcceptedMail(
            $this->event,
            $notifiable,
            $this->submission,
            $this->edit_deadline,
            $this->locale
        ))->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Store translations for each supported language
        $translatedTitles = [];
        $translatedMessages = [];

        foreach (['en', 'fr', 'ar'] as $locale) {
            $translatedTitles[$locale] = $this->event->getTranslation('title', $locale, false);

            // Get translated acceptance message for each locale
            $translatedMessages[$locale] = __('emails/exhibitor-edit-submission-request-accepted.acceptance_info', [], $locale);
        }

        return [
            'event_id' => $this->event->id,
            'event_title' => $translatedTitles,
            'submission_id' => $this->submission->id,
            'type' => 'exhibitor_edit_submission_request_accepted',
            'message' => $translatedMessages,
            'current_locale' => $this->locale,
            'edit_deadline' => $this->edit_deadline ? $this->edit_deadline->format('Y-m-d H:i:s') : null,
        ];
    }
}
