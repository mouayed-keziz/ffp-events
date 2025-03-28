<?php

namespace App\Mail\Exhibitor;

use App\Models\EventAnnouncement;
use App\Models\Exhibitor;
use App\Models\ExhibitorSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExhibitorEventRegistrationAcceptedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $exhibitor;
    public $submission;
    public $locale;
    public $direction;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EventAnnouncement $event, Exhibitor $exhibitor, ExhibitorSubmission $submission, string $locale = 'fr', string $direction = 'ltr')
    {
        $this->event = $event;
        $this->exhibitor = $exhibitor;
        $this->submission = $submission;
        $this->locale = $locale;
        $this->direction = $direction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.exhibitor.exhibitor-event-registration-accepted')
            ->subject(__('emails/exhibitor-event-registration-accepted.subject', ['event_name' => $this->event->getTranslation('title', $this->locale)]))
            ->with([
                'event' => $this->event,
                'exhibitor' => $this->exhibitor,
                'submission' => $this->submission,
                'locale' => $this->locale,
                'direction' => $this->direction,
            ]);
    }
}
