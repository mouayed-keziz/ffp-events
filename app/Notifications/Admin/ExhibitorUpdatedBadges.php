<?php

namespace App\Notifications\Admin;

use App\Mail\Admin\ExhibitorUpdatedBadgesMail;
use App\Models\EventAnnouncement;
use App\Models\Exhibitor;
use App\Models\ExhibitorSubmission;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Notifications\Actions\Action;
use App\Settings\CompanyInformationsSettings;
use Illuminate\Support\Collection;

class ExhibitorUpdatedBadges extends Notification implements ShouldQueue
{
    use Queueable;

    public $event;
    public $exhibitor;
    public $submission;
    public $badges;
    public $locale;
    public $isCompanyEmail;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        EventAnnouncement $event,
        Exhibitor $exhibitor,
        ExhibitorSubmission $submission,
        Collection $badges,
        bool $isCompanyEmail = false
    ) {
        $this->event = $event;
        $this->exhibitor = $exhibitor;
        $this->submission = $submission;
        $this->badges = $badges;
        $this->locale = 'fr'; // Force French locale as requested
        $this->isCompanyEmail = $isCompanyEmail;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // If this is for the company email, only use mail channel
        // Otherwise, only use database channel for admin notifications
        return $this->isCompanyEmail ? ['mail'] : ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): ExhibitorUpdatedBadgesMail
    {
        return (new ExhibitorUpdatedBadgesMail(
            $this->event,
            $this->exhibitor,
            $this->submission,
            $this->badges,
            $notifiable
        ))->to($notifiable->email ?? app(CompanyInformationsSettings::class)->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => "Badges d'exposant mis à jour",
            'body' => "L'exposant {$this->exhibitor->name} a mis à jour {$this->badges->count()} badges pour l'événement {$this->event->title}.",
            'event_id' => $this->event->id,
            'submission_id' => $this->submission->id,
            'exhibitor_id' => $this->exhibitor->id,
            'badges_count' => $this->badges->count(),
        ];
    }
}
