<?php

namespace App\Mail\Visitor;

use App\Models\EventAnnouncement;
use App\Models\VisitorSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class AnonymousVisitorEventRegistrationSuccessfulMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $submission;
    public $email;
    public $locale;
    public $direction;

    /**
     * Create a new message instance.
     */
    public function __construct(EventAnnouncement $event, VisitorSubmission $submission, string $email, string $locale = null)
    {
        $this->event = $event;
        $this->submission = $submission;
        $this->email = $email;
        $this->locale = $locale ?? App::getLocale();
        $this->direction = $this->locale === 'ar' ? 'rtl' : 'ltr';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('emails/visitor-registration-successful.subject', [
                'event_name' => $this->event->getTranslation('title', $this->locale)
            ], $this->locale),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.visitor.anonymous-visitor-event-registration-successful',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        // Check if the submission has a badge with an image
        if ($this->submission->badge && $this->submission->badge->getFirstMedia('image')) {
            $badgeMedia = $this->submission->badge->getFirstMedia('image');
            if ($badgeMedia && file_exists($badgeMedia->getPath())) {
                $attachments[] = Attachment::fromPath($badgeMedia->getPath())
                    ->as('badge.png')
                    ->withMime('image/png');
            }
        }

        return $attachments;
    }
}
