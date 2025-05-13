<?php

namespace App\Notifications\Exhibitor;

use App\Mail\Exhibitor\ExhibitorGeneratedBadgesMail;
use App\Models\EventAnnouncement;
use App\Models\ExhibitorSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Collection;

class ExhibitorGeneratedBadges extends Notification implements ShouldQueue
{
    use Queueable;

    public $event;
    public $submission;
    public $badges;
    public $locale;

    /**
     * Create a new notification instance.
     */
    public function __construct(EventAnnouncement $event, ExhibitorSubmission $submission, Collection $badges, string $locale = null)
    {
        $this->event = $event;
        $this->submission = $submission;
        $this->badges = $badges;
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
    public function toMail(object $notifiable): ExhibitorGeneratedBadgesMail
    {
        return (new ExhibitorGeneratedBadgesMail(
            $this->event,
            $notifiable,
            $this->submission,
            $this->badges,
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

            $translatedMessages[$locale] = __(
                'emails/exhibitor-generated-badges.badge_notice',
                ['event_name' => $translatedTitles[$locale]],
                $locale
            );
        }

        return [
            'event_id' => $this->event->id,
            'event_title' => $translatedTitles,
            'submission_id' => $this->submission->id,
            'badges_count' => $this->badges->count(),
            'type' => 'exhibitor_generated_badges',
            'message' => $translatedMessages,
            'current_locale' => $this->locale,
        ];
    }
}
