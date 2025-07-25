<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="{{ $direction ?? 'ltr' }}" lang="{{ $locale }}">

<head>
    <link rel="preload" as="image"
        href="https://ffp-events.com/wp-content/uploads/2025/03/Untitled-design-37-e1740997899683.png" />
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <meta name="x-apple-disable-message-reformatting" />
    <title>
        {{ __('emails/visitor-registration-successful.subject', ['event_name' => $event->getTranslation('title', $locale)], $locale) }}
    </title>
</head>

<body
    style='background-color:#eee;margin-top:auto;margin-bottom:auto;margin-left:auto;margin-right:auto;font-family:ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";padding-left:0.5rem;padding-right:0.5rem'>
    <div style="display:none;overflow:hidden;line-height:1px;opacity:0;max-height:0;max-width:0">
        {{ __('emails/visitor-registration-successful.subject', ['event_name' => $event->getTranslation('title', $locale)], $locale) }}
    </div>
    <table align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
        style="border-width:1px;border-style:solid;border-color:rgb(234,234,234);border-radius:0.25rem;margin-top:40px;margin-bottom:40px;margin-left:auto;margin-right:auto;padding:20px;max-width:465px; background-color: #fff;">
        <tbody>
            <tr style="width:100%">
                <td>
                    <table align="center" width="100%" border="0" cellpadding="0" cellspacing="0"
                        role="presentation" style="margin-top:32px">
                        <tbody>
                            <tr>
                                <td>
                                    <img alt="FFP Events"
                                        src="https://ffp-events.com/wp-content/uploads/2025/03/Untitled-design-37-e1740997899683.png"
                                        style="margin-top:0px;margin-bottom:0px;margin-left:auto;margin-right:auto;display:block;outline:none;border:none;text-decoration:none"
                                        width="200" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <h1
                        style="color:rgb(0,0,0);font-size:24px;font-weight:400;text-align:center;padding:0px;margin-top:30px;margin-bottom:30px;margin-left:0px;margin-right:0px">
                        {{ __('emails/visitor-registration-successful.subject', ['event_name' => $event->getTranslation('title', $locale)], $locale) }}
                    </h1>
                    <p style="color:rgb(0,0,0);font-size:14px;line-height:24px;margin:16px 0">
                        {{ __('emails/visitor-registration-successful.greeting', ['name' => 'Anonymous Visitor'], $locale) }}
                    </p>
                    <p style="color:rgb(0,0,0);font-size:14px;line-height:24px;margin:16px 0">
                        {{ __('emails/visitor-registration-successful.approval_notice', ['event_name' => $event->getTranslation('title', $locale)], $locale) }}
                    </p>
                    <p style="color:rgb(0,0,0);font-size:14px;line-height:24px;margin:16px 0">
                        <strong>{{ __('emails/visitor-registration-successful.participation_details', [], $locale) }}</strong><br>
                        {{ __('emails/visitor-registration-successful.event', [], $locale) }}
                        {{ $event->getTranslation('title', $locale) }}<br>
                        @if ($event->start_date)
                            {{ __('emails/visitor-registration-successful.date', [], $locale) }}
                            {{ $event->start_date->format('d/m/Y') }}
                            @if ($event->end_date && $event->end_date != $event->start_date)
                                - {{ $event->end_date->format('d/m/Y') }}
                            @endif
                            <br>
                        @endif
                        @if ($event->location)
                            {{ __('emails/visitor-registration-successful.location', [], $locale) }}
                            {{ $event->location }}<br>
                        @endif
                        {{ __('emails/visitor-registration-successful.status', [], $locale) }}
                        {{ __('emails/visitor-registration-successful.status_confirmed', [], $locale) }}
                    </p>
                    <p style="color:rgb(0,0,0);font-size:14px;line-height:24px;margin:16px 0">
                        {{ __('emails/visitor-registration-successful.badge_available', [], $locale) }}
                    </p>
                    <table align="center" width="100%" border="0" cellpadding="0" cellspacing="0"
                        role="presentation" style="text-align:center;margin-top:32px;margin-bottom:32px">
                        <tbody>
                            <tr>
                                <td>
                                    <a href="{{ route('event_details', ['id' => $event->id]) }}"
                                        style="background-color:#EB7530;border-radius:0.25rem;color:rgb(255,255,255);font-size:12px;font-weight:600;text-decoration-line:none;text-align:center;padding-left:1.25rem;padding-right:1.25rem;padding-top:0.75rem;padding-bottom:0.75rem;line-height:100%;text-decoration:none;display:inline-block;max-width:100%;mso-padding-alt:0px;padding:12px 20px 12px 20px"
                                        target="_blank"><span><!--[if mso]><i style="mso-font-width:500%;mso-text-raise:18" hidden>&#8202;&#8202;</i><![endif]--></span><span
                                            style="max-width:100%;display:inline-block;line-height:120%;mso-padding-alt:0px;mso-text-raise:9px">{{ __('emails/visitor-registration-successful.event_page_link', [], $locale) }}</span><span><!--[if mso]><i style="mso-font-width:500%" hidden>&#8202;&#8202;&#8203;</i><![endif]--></span></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    @if ($submission->badge && $submission->badge->getFirstMedia('image'))
                        <p style="color:rgb(0,0,0);font-size:14px;line-height:24px;margin:16px 0">
                            {{ __('emails/visitor-registration-successful.badge_attached', [], $locale) }}
                        </p>
                        <p style="color:rgb(0,0,0);font-size:14px;line-height:24px;margin:16px 0">
                            {{ __('emails/visitor-registration-successful.badge_instructions', [], $locale) }}
                        </p>
                    @endif
                    <p style="color:rgb(0,0,0);font-size:14px;line-height:24px;margin:16px 0">
                        {{ __('emails/visitor-registration-successful.questions', [], $locale) }}
                    </p>
                    <hr
                        style="border-width:1px;border-style:solid;border-color:rgb(234,234,234);margin-top:26px;margin-bottom:26px;margin-left:0px;margin-right:0px;width:100%;border:none;border-top:1px solid #eaeaea" />
                    <p style="color:rgb(102,102,102);font-size:12px;line-height:24px;margin:16px 0">
                        {{ __('emails/visitor-registration-successful.regards', [], $locale) }}<br />
                        {{ __('emails/visitor-registration-successful.team', [], $locale) }}
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
