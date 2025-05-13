<?php

namespace App\Notifications\Exhibitor;

use App\Mail\Exhibitor\ExhibitorPaymentRegistrationRejectedMail;
use App\Models\EventAnnouncement;
use App\Models\Exhibitor;
use App\Models\ExhibitorPaymentSlice;
use App\Models\ExhibitorSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;

class ExhibitorPaymentRegistrationRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public $event;
    public $submission;
    public $paymentSlice;
    public $locale;

    /**
     * Create a new notification instance.
     */
    public function __construct(EventAnnouncement $event, ExhibitorSubmission $submission, ExhibitorPaymentSlice $paymentSlice, string $locale = null)
    {
        $this->event = $event;
        $this->submission = $submission;
        $this->paymentSlice = $paymentSlice;
        $this->locale = $locale ?? App::getLocale();
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
    public function toMail(object $notifiable): ExhibitorPaymentRegistrationRejectedMail
    {
        return (new ExhibitorPaymentRegistrationRejectedMail(
            $this->event,
            $notifiable,
            $this->submission,
            $this->paymentSlice,
            $this->locale
        ))->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Store translations for each supported language
        $translatedTitles = [];
        $translatedMessages = [];
        $translatedReasons = [];

        foreach (['en', 'fr', 'ar'] as $locale) {
            $translatedTitles[$locale] = $this->event->getTranslation('title', $locale, false);

            // Get translated rejection message for each locale
            $translatedMessages[$locale] = __(
                'emails/exhibitor-payment-registration-rejected.rejection_notice',
                ['event_name' => $translatedTitles[$locale]],
                $locale
            );

            // Get translated rejection reason if it exists
            if ($this->submission->rejection_reason) {
                $translatedReasons[$locale] = $this->submission->getTranslation('rejection_reason', $locale, false);
            }
        }

        return [
            'event_id' => $this->event->id,
            'event_title' => $translatedTitles,
            'submission_id' => $this->submission->id,
            'payment_slice_id' => $this->paymentSlice->id,
            'type' => 'exhibitor_payment_registration_rejected',
            'message' => $translatedMessages,
            'reason' => $translatedReasons,
            'current_locale' => $this->locale,
        ];
    }
}
