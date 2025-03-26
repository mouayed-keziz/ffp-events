<?php

namespace App\Notifications\Exhibitor;

use App\Mail\Exhibitor\ExhibitorSubmissionRejectedMail;
use App\Models\EventAnnouncement;
use App\Models\Exhibitor;
use App\Models\ExhibitorSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;

class ExhibitorSubmissionRejected extends Notification
{
    use Queueable;

    public $event;
    public $submission;
    public $locale;

    /**
     * Create a new notification instance.
     */
    public function __construct(EventAnnouncement $event, ExhibitorSubmission $submission, string $locale = null)
    {
        $this->event = $event;
        $this->submission = $submission;
        $this->locale = $locale ?? App::getLocale();
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
    public function toMail(object $notifiable): ExhibitorSubmissionRejectedMail
    {
        return (new ExhibitorSubmissionRejectedMail(
            $this->event,
            $notifiable,
            $this->submission,
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
        $translatedReasons = [];

        foreach (['en', 'fr', 'ar'] as $locale) {
            $translatedTitles[$locale] = $this->event->getTranslation('title', $locale, false);

            // Get translated rejection message for each locale
            $translatedMessages[$locale] = __(
                'emails/exhibitor-submission-rejected.rejection_notice',
                ['event_name' => $translatedTitles[$locale]],
                $locale
            );

            // Get translated rejection reason if it exists
            if ($this->submission->rejection_reason) {
                $translatedReasons[$locale] = $this->submission->getTranslation('rejection_reason', $locale, false);
            }
        }

        return [
            'event_id' => $this->event->id,
            'event_title' => $translatedTitles,
            'submission_id' => $this->submission->id,
            'type' => 'exhibitor_submission_rejected',
            'message' => $translatedMessages,
            'reason' => $translatedReasons,
            'current_locale' => $this->locale,
        ];
    }
}
