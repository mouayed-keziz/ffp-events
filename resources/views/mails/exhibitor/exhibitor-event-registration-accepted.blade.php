<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $direction }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('emails/exhibitor-event-registration-accepted.subject', ['event_name' => $event->getTranslation('title', $locale)]) }}</title>
</head>
<body>
    <p>{{ __('emails/exhibitor-event-registration-accepted.greeting', ['exhibitor_name' => $exhibitor->name]) }}</p>
    <p>{{ __('emails/exhibitor-event-registration-accepted.intro', ['event_name' => $event->getTranslation('title', $locale)]) }}</p>
    <p>{{ __('emails/exhibitor-event-registration-accepted.details') }}</p>
    <ul>
        <li>{{ __('emails/exhibitor-event-registration-accepted.event_name', ['event_name' => $event->getTranslation('title', $locale)]) }}</li>
        <li>{{ __('emails/exhibitor-event-registration-accepted.date', ['date' => $event->start_date->format('d/m/Y')]) }}</li>
        <li>{{ __('emails/exhibitor-event-registration-accepted.location', ['location' => $event->location]) }}</li>
        <li>{{ __('emails/exhibitor-event-registration-accepted.status') }}</li>
    </ul>
    <p>{{ __('emails/exhibitor-event-registration-accepted.finalize') }}</p>
    <p><a href="{{ route('payment.upload', ['submission' => $submission->id]) }}">{{ __('emails/exhibitor-event-registration-accepted.link', ['payment_link' => route('payment.upload', ['submission' => $submission->id])]) }}</a></p>
    <p>{{ __('emails/exhibitor-event-registration-accepted.confirmation') }}</p>
    <p>{{ __('emails/exhibitor-event-registration-accepted.salutation') }}</p>
    <p>{{ __('emails/exhibitor-event-registration-accepted.team') }}</p>
</body>
</html>
