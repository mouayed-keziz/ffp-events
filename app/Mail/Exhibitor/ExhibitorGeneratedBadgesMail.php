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
     * Build a ZIP file containing PNG badge images, mirroring Volt logic.
     *
     * @param \Illuminate\Support\Collection $badges
     * @return string Full path to the generated ZIP file
     */
    private function generateBadgesZip(Collection $badges): string
    {
        $zipFileName = 'badges_' . $this->submission->id . '_' . time() . '.zip';
        $zipFilePath = storage_path('app/temp/' . $zipFileName);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
            foreach ($badges as $badge) {
                if (!$badge->hasMedia('image')) {
                    continue;
                }

                $mediaItems = $badge->getMedia('image');
                $pngMedia = $mediaItems->first(function ($m) {
                    $fileName = strtolower($m->file_name ?? '');
                    $mime = strtolower($m->mime_type ?? '');
                    // Only accept explicit PNG
                    return (is_string($fileName) && str_ends_with($fileName, '.png'))
                        || (is_string($mime) && str_contains($mime, 'image/png'));
                });

                if (!$pngMedia) {
                    // No PNG available: skip to avoid attaching PDFs
                    continue;
                }

                $badgePath = method_exists($pngMedia, 'getPath') ? $pngMedia->getPath() : $badge->getFirstMediaPath('image');
                if (is_string($badgePath) && file_exists($badgePath)) {
                    $zip->addFile($badgePath, "badge_{$badge->code}.png");
                }
            }
            $zip->close();
        }

        return $zipFilePath;
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

        // Build ZIP using the same logic as the Volt component
        $zipFilePath = $this->generateBadgesZip($this->badges);

        if (!file_exists($zipFilePath)) {
            return [];
        }

        // Create an attachment from the zip file
        $safeTitle = (string) $this->event->getTranslation('title', $this->locale);
        $safeTitle = preg_replace('/[\\\/:*?"<>|]+/', '-', $safeTitle) ?: 'badges';

        $attachment = Attachment::fromPath($zipFilePath)
            ->as("badges_{$safeTitle}.zip")
            ->withMime('application/zip');

        // Delete the temporary zip file after sending
        register_shutdown_function(function () use ($zipFilePath) {
            if (file_exists($zipFilePath)) {
                @unlink($zipFilePath);
            }
        });

        return [$attachment];
    }
}
