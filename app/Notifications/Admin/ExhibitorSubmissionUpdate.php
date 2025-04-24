<?php

namespace App\Notifications\Admin;

use App\Mail\Admin\ExhibitorSubmissionUpdateMail;
use App\Models\EventAnnouncement;
use App\Models\Exhibitor;
use App\Models\ExhibitorSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;
use App\Settings\CompanyInformationsSettings;

class ExhibitorSubmissionUpdate extends Notification implements ShouldQueue
{
    use Queueable;

    public $event;
    public $exhibitor;
    public $submission;
    public $locale;
    public $isCompanyEmail;

    /**
     * Create a new notification instance.
     */
    public function __construct(EventAnnouncement $event, Exhibitor $exhibitor, ExhibitorSubmission $submission, bool $isCompanyEmail = false)
    {
        $this->event = $event;
        $this->exhibitor = $exhibitor;
        $this->submission = $submission;
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
    public function toMail(object $notifiable): ExhibitorSubmissionUpdateMail
    {
        return (new ExhibitorSubmissionUpdateMail(
            $this->event,
            $this->exhibitor,
            $this->submission,
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
            'title' => "Mise à jour de soumission d'exposant",
            'body' => "L'exposant {$this->exhibitor->name} a mis à jour sa soumission pour l'événement {$this->event->title}.",
            'event_id' => $this->event->id,
            'submission_id' => $this->submission->id,
            'exhibitor_id' => $this->exhibitor->id,
        ];
    }
}
