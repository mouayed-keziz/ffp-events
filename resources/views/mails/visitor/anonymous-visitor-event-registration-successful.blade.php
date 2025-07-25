<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $direction }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        {{ __('emails/visitor-registration-successful.subject', ['event_name' => $event->getTranslation('title', $locale)], $locale) }}
    </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            {{ $direction === 'rtl' ? 'direction: rtl; text-align: right;' : 'direction: ltr; text-align: left;' }}
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .header {
            background-color: #2c5aa0;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            background-color: white;
            padding: 30px;
            margin: 20px 0;
        }

        .event-details {
            background-color: #f8f9fa;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #2c5aa0;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2c5aa0;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }

        @media (max-width: 600px) {
            .container {
                padding: 10px;
            }

            .content {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ __('emails/visitor-registration-successful.subject', ['event_name' => $event->getTranslation('title', $locale)], $locale) }}
            </h1>
        </div>

        <div class="content">
            <p>{{ __('emails/visitor-registration-successful.greeting', ['name' => 'Anonymous Visitor'], $locale) }}</p>

            <p>{{ __('emails/visitor-registration-successful.approval_notice', ['event_name' => $event->getTranslation('title', $locale)], $locale) }}
            </p>

            <div class="event-details">
                <h3>{{ __('emails/visitor-registration-successful.participation_details', [], $locale) }}</h3>

                <p><strong>{{ __('emails/visitor-registration-successful.event', [], $locale) }}</strong>
                    {{ $event->getTranslation('title', $locale) }}</p>

                @if ($event->start_date)
                    <p><strong>{{ __('emails/visitor-registration-successful.date', [], $locale) }}</strong>
                        {{ $event->start_date->format('d/m/Y') }}
                        @if ($event->end_date && $event->end_date != $event->start_date)
                            - {{ $event->end_date->format('d/m/Y') }}
                        @endif
                    </p>
                @endif

                @if ($event->getTranslation('location', $locale))
                    <p><strong>{{ __('emails/visitor-registration-successful.location', [], $locale) }}</strong>
                        {{ $event->getTranslation('location', $locale) }}</p>
                @endif

                <p><strong>{{ __('emails/visitor-registration-successful.status', [], $locale) }}</strong>
                    {{ __('emails/visitor-registration-successful.status_confirmed', [], $locale) }}</p>
            </div>

            <p>{{ __('emails/visitor-registration-successful.badge_available', [], $locale) }}</p>

            <a href="{{ route('event_details', ['id' => $event->id]) }}" class="button">
                {{ __('emails/visitor-registration-successful.event_page_link', [], $locale) }}
            </a>

            @if ($submission->badge && $submission->badge->getFirstMedia('image'))
                <p>{{ __('emails/visitor-registration-successful.badge_attached', [], $locale) }}</p>
                <p>{{ __('emails/visitor-registration-successful.badge_instructions', [], $locale) }}</p>
            @endif

            <p>{{ __('emails/visitor-registration-successful.questions', [], $locale) }}</p>
        </div>

        <div class="footer">
            <p>{{ __('emails/visitor-registration-successful.regards', [], $locale) }}</p>
            <p>{{ __('emails/visitor-registration-successful.team', [], $locale) }}</p>
        </div>
    </div>
</body>

</html>
