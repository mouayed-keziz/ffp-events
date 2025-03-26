<?php

namespace App\Notifications\Visitor;

use App\Mail\Visitor\VisitorEventRegistrationSuccessfulMail;
use App\Models\EventAnnouncement;
use App\Models\Visitor;
use App\Models\VisitorSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;

class VisitorEventRegistrationSuccessful extends Notification
{
    use Queueable;

    protected $event;
    protected $submission;
    public $locale; // Changed from protected to public

    /**
     * Create a new notification instance.
     */
    public function __construct(EventAnnouncement $event, VisitorSubmission $submission, string $locale = null)
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
    public function toMail(object $notifiable): VisitorEventRegistrationSuccessfulMail
    {
        return (new VisitorEventRegistrationSuccessfulMail(
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

            // Get translated approval message for each locale
            $translatedMessages[$locale] = __('emails/visitor-registration-successful.approval_notice', [
                'event_name' => $this->event->getTranslation('title', $locale, false)
            ], $locale);
        }

        return [
            'event_id' => $this->event->id,
            'event_title' => $translatedTitles,
            'submission_id' => $this->submission->id,
            'type' => 'visitor_registration_successful',
            'message' => $translatedMessages,
            'current_locale' => $this->locale,
        ];
    }
}
