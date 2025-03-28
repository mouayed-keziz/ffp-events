<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $direction }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('emails/exhibitor-payment-registration-rejected.subject', ['event_name' => $event->getTranslation('title', $locale)]) }}</title>
</head>
<body>
    <p>{{ __('emails/exhibitor-payment-registration-rejected.greeting', ['exhibitor_name' => $exhibitor->name]) }}</p>
    <p>{{ __('emails/exhibitor-payment-registration-rejected.intro', ['event_name' => $event->getTranslation('title', $locale)]) }}</p>
    <p>{{ __('emails/exhibitor-payment-registration-rejected.payment_details') }}</p>
    <ul>
        <li>{{ __('emails/exhibitor-payment-registration-rejected.amount', ['amount' => $submission->amount, 'currency' => $submission->currency]) }}</li>
        <li>{{ __('emails/exhibitor-payment-registration-rejected.date', ['date' => $submission->payment_date->format('d/m/Y')]) }}</li>
        <li>{{ __('emails/exhibitor-payment-registration-rejected.status') }}</li>
        <li>{{ __('emails/exhibitor-payment-registration-rejected.reason_details', ['rejection_reason' => $submission->rejection_reason]) }}</li>
    </ul>
    <p>{{ __('emails/exhibitor-payment-registration-rejected.instructions') }}</p>
    <p><a href="{{ route('upload_payment_proof', ['id' => $event->id]) }}">{{ __('emails/exhibitor-payment-registration-rejected.link', ['event_page_link' => route('upload_payment_proof', ['id' => $event->id])]) }}</a></p>
    <p>{{ __('emails/exhibitor-payment-registration-rejected.assistance') }}</p>
    <p>{{ __('emails/exhibitor-payment-registration-rejected.regards') }}</p>
    <p>{{ __('emails/exhibitor-payment-registration-rejected.team') }}</p>
</body>
</html>
