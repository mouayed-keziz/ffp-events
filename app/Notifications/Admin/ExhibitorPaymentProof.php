<?php

namespace App\Notifications\Admin;

use App\Mail\Admin\ExhibitorPaymentProofMail;
use App\Models\EventAnnouncement;
use App\Models\Exhibitor;
use App\Models\ExhibitorSubmission;
use App\Models\ExhibitorPaymentSlice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;

class ExhibitorPaymentProof extends Notification implements ShouldQueue
{
    use Queueable;

    public $event;
    public $exhibitor;
    public $submission;
    public $payment;
    public $locale;

    /**
     * Create a new notification instance.
     */
    public function __construct(EventAnnouncement $event, Exhibitor $exhibitor, ExhibitorSubmission $submission, ExhibitorPaymentSlice $payment)
    {
        $this->event = $event;
        $this->exhibitor = $exhibitor;
        $this->submission = $submission;
        $this->payment = $payment;
        $this->locale = 'fr'; // Force French locale as requested
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
    public function toMail(object $notifiable): ExhibitorPaymentProofMail
    {
        return (new ExhibitorPaymentProofMail(
            $this->event,
            $this->exhibitor,
            $this->submission,
            $this->payment,
            $notifiable
        ))->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => "Preuve de paiement soumise",
            'body' => "L'exposant {$this->exhibitor->name} a soumis une preuve de paiement pour l'événement {$this->event->title}.",
            'event_id' => $this->event->id,
            'submission_id' => $this->submission->id,
            'exhibitor_id' => $this->exhibitor->id,
            'payment_id' => $this->payment->id,
        ];
    }
}
