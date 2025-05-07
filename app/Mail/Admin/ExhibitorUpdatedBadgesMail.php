<?php

namespace App\Mail\Admin;

use App\Models\EventAnnouncement;
use App\Models\Exhibitor;
use App\Models\ExhibitorSubmission;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Collection;

class ExhibitorUpdatedBadgesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $exhibitor;
    public $submission;
    public $badges;
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
        Collection $badges,
        $notifiable = null
    ) {
        $this->event = $event;
        $this->exhibitor = $exhibitor;
        $this->submission = $submission;
        $this->badges = $badges;
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
            subject: "Badges d'exposant mis à jour - {$this->event->title}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.admin.exhibitor-updated-badges',
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
