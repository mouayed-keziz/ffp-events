<?php

namespace App\Mail\Exhibitor;

use App\Models\EventAnnouncement;
use App\Models\Exhibitor;
use App\Models\ExhibitorSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use App\Models\Badge;
use ZipArchive;

class ExhibitorGeneratedBadgesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $exhibitor;
    public $submission;
    public $badges;
    public $locale;
    public $direction;

    /**
     * Create a new message instance.
     */
    public function __construct(EventAnnouncement $event, Exhibitor $exhibitor, ExhibitorSubmission $submission, Collection $badges, string $locale = null)
    {
        $this->event = $event;
        $this->exhibitor = $exhibitor;
        $this->submission = $submission;
        $this->badges = $badges;
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
            subject: __('emails/exhibitor-generated-badges.subject', [
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
            view: 'mails.exhibitor.exhibitor-generated-badges',
            with: [
                'event' => $this->event,
                'exhibitor' => $this->exhibitor,
                'submission' => $this->submission,
                'badges' => $this->badges,
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
        // Return empty array if no badges
        if ($this->badges->isEmpty()) {
            return [];
        }

        // Create a temporary zip file
        $zipFileName = 'badges_' . $this->exhibitor->id . '_' . time() . '.zip';
        $zipFilePath = storage_path('app/temp/' . $zipFileName);

        // Ensure the temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            foreach ($this->badges as $badge) {
                if ($badge->hasMedia('image')) {
                    $badgePath = $badge->getFirstMediaPath('image');
                    $badgeFileName = "badge_{$badge->code}.pdf";
                    if (file_exists($badgePath)) {
                        $zip->addFile($badgePath, $badgeFileName);
                    }
                }
            }
            $zip->close();

            // Create an attachment from the zip file
            $attachment = Attachment::fromPath($zipFilePath)
                ->as("badges_" . $this->event->getTranslation('title', $this->locale) . ".zip")
                ->withMime('application/zip');

            // Register a callback to delete the temporary zip file after sending
            register_shutdown_function(function () use ($zipFilePath) {
                if (file_exists($zipFilePath)) {
                    unlink($zipFilePath);
                }
            });

            return [$attachment];
        }

        return [];
    }
}
