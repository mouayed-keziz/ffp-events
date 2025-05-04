<?php

namespace App\Mail\Visitor;

use App\Models\EventAnnouncement;
use App\Models\Visitor;
use App\Models\VisitorSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class VisitorEventRegistrationSuccessfulMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $visitor;
    public $submission;
    public $locale;
    public $direction;

    /**
     * Create a new message instance.
     */
    public function __construct(EventAnnouncement $event, Visitor $visitor, VisitorSubmission $submission, string $locale = null)
    {
        $this->event = $event;
        $this->visitor = $visitor;
        $this->submission = $submission;
        $this->locale = $locale ?? App::getLocale();
        $this->direction = $this->locale === 'ar' ? 'rtl' : 'ltr';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        App::setLocale($this->locale);

        return new Envelope(
            subject: __('emails/visitor-registration-successful.subject', [
                'event_name' => $this->event->title
            ]),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        App::setLocale($this->locale);

        return new Content(
            view: 'mails.visitor.visitor-event-registration-successful',
            with: [
                'event' => $this->event,
                'visitor' => $this->visitor,
                'submission' => $this->submission,
                'locale' => $this->locale,
                'direction' => $this->direction,
            ]
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

        // Get the badge from the visitor submission
        if ($this->submission && $badge = $this->submission->badge) {
            // If badge has an image, attach it to the email
            $badgeMedia = $badge->getFirstMedia('image');
            if ($badgeMedia && file_exists($badgeMedia->getPath())) {
                // Get the event name in French
                $eventName = $this->event->getTranslation('title', 'fr', false);

                // Get the visitor's name
                $visitorName = $badge->name;

                // Create the filename in the required format: {event_name in fr} - {nom}.png
                $filename = $eventName . ' - ' . $visitorName . '.png';

                $attachments[] = Attachment::fromPath($badgeMedia->getPath())
                    ->as($filename)
                    ->withMime('image/png');
            }
        }

        return $attachments;
    }
}
