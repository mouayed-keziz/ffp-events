<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class ChangeEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $model;
    public $locale;
    public $name;
    public $direction;

    /**
     * Create a new message instance.
     */
    public function __construct($token, $model, $locale = null, $name = null)
    {
        $this->token = $token;
        $this->model = $model;
        $this->locale = $locale ?? App::getLocale();
        $this->name = $name ?? $model;
        $this->direction = $this->locale === 'ar' ? 'rtl' : 'ltr';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        App::setLocale($this->locale);

        return new Envelope(
            subject: __('emails/change-email.subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        App::setLocale($this->locale);

        return new Content(
            view: 'mails.change-email',
            with: [
                'token' => $this->token,
                'model' => $this->model,
                'locale' => $this->locale,
                'direction' => $this->direction,
                'name' => $this->name,
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
