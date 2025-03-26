<?php

namespace App\Notifications\Exhibitor;

use App\Mail\Exhibitor\ExhibitorSubmissionProcessingMail;
use App\Models\EventAnnouncement;
use App\Models\Exhibitor;
use App\Models\ExhibitorSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;

class ExhibitorSubmissionProcessing extends Notification
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
    public function toMail(object $notifiable): ExhibitorSubmissionProcessingMail
    {
        return (new ExhibitorSubmissionProcessingMail(
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

        foreach (['en', 'fr', 'ar'] as $locale) {
            $translatedTitles[$locale] = $this->event->getTranslation('title', $locale, false);

            // Get translated processing message for each locale
            $translatedMessages[$locale] = __('emails/exhibitor-submission-processing.processing_info', [], $locale);
        }

        return [
            'event_id' => $this->event->id,
            'event_title' => $translatedTitles,
            'submission_id' => $this->submission->id,
            'type' => 'exhibitor_submission_processing',
            'message' => $translatedMessages,
            'current_locale' => $this->locale,
        ];
    }
}
