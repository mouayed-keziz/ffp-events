<?php

namespace App\Notifications\Exhibitor;

use App\Mail\Exhibitor\ExhibitorSubsequentPaymentAcceptedMail;
use App\Models\EventAnnouncement;
use App\Models\ExhibitorPaymentSlice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;

class ExhibitorSubsequentPaymentAccepted extends Notification implements ShouldQueue
{
    use Queueable;

    public $event;
    public $paymentSlice;
    public $locale;

    /**
     * Create a new notification instance.
     */
    public function __construct(EventAnnouncement $event, ExhibitorPaymentSlice $paymentSlice, string $locale = null)
    {
        $this->event = $event;
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
    public function toMail(object $notifiable): ExhibitorSubsequentPaymentAcceptedMail
    {
        return (new ExhibitorSubsequentPaymentAcceptedMail(
            $this->event,
            $notifiable,
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

        foreach (['en', 'fr', 'ar'] as $locale) {
            $translatedTitles[$locale] = $this->event->getTranslation('title', $locale, false);

            $translatedMessages[$locale] = __(
                'emails/exhibitor-subsequent-payment-accepted.confirmation_notice',
                ['event_name' => $translatedTitles[$locale]],
                $locale
            );
        }

        return [
            'event_id' => $this->event->id,
            'event_title' => $translatedTitles,
            'payment_slice_id' => $this->paymentSlice->id,
            'type' => 'exhibitor_subsequent_payment_accepted',
            'message' => $translatedMessages,
            'current_locale' => $this->locale,
        ];
    }
}
