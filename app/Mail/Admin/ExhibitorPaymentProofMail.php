<?php

namespace App\Mail\Admin;

use App\Models\EventAnnouncement;
use App\Models\Exhibitor;
use App\Models\ExhibitorSubmission;
use App\Models\ExhibitorPaymentSlice;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class ExhibitorPaymentProofMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $exhibitor;
    public $submission;
    public $payment;
    public $admin;
    public $locale;
    public $direction;

    /**
     * Create a new message instance.
     */
    public function __construct(
        EventAnnouncement $event,
        Exhibitor $exhibitor,
        ExhibitorSubmission $submission,
        ExhibitorPaymentSlice $payment,
        $notifiable = null
    ) {
        $this->event = $event;
        $this->exhibitor = $exhibitor;
        $this->submission = $submission;
        $this->payment = $payment;
        $this->admin = $notifiable;
        $this->locale = 'fr'; // Force French locale as requested
        $this->direction = 'ltr';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Preuve de paiement soumise - {$this->event->title}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.admin.exhibitor-payment-proof',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
