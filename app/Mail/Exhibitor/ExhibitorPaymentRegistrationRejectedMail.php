<?php

namespace App\Mail\Exhibitor;

use App\Models\EventAnnouncement;
use App\Models\Exhibitor;
use App\Models\ExhibitorPaymentSlice;
use App\Models\ExhibitorSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class ExhibitorPaymentRegistrationRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $exhibitor;
    public $submission;
    public $paymentSlice;
    public $locale;
    public $direction;

    /**
     * Create a new message instance.
     */
    public function __construct(EventAnnouncement $event, Exhibitor $exhibitor, ExhibitorSubmission $submission, ExhibitorPaymentSlice $paymentSlice, string $locale = null)
    {
        $this->event = $event;
        $this->exhibitor = $exhibitor;
        $this->submission = $submission;
        $this->paymentSlice = $paymentSlice;
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
            subject: __('emails/exhibitor-payment-registration-rejected.subject', [
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
            view: 'mails.exhibitor.exhibitor-payment-registration-rejected',
            with: [
                'event' => $this->event,
                'exhibitor' => $this->exhibitor,
                'submission' => $this->submission,
                'paymentSlice' => $this->paymentSlice,
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
        return [];
    }
}
