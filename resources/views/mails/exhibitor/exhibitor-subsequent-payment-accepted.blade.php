<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="{{ $direction }}" lang="{{ $locale }}">

<head>
    <link rel="preload" as="image"
        href="https://ffp-events.com/wp-content/uploads/2025/03/Untitled-design-37-e1740997899683.png" />
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <meta name="x-apple-disable-message-reformatting" />
    <title>
        {{ __('emails/exhibitor-subsequent-payment-accepted.subject', ['event_name' => $event->getTranslation('title', $locale)]) }}
    </title>
</head>

<body
    style='background-color:#eee;margin-top:auto;margin-bottom:auto;margin-left:auto;margin-right:auto;font-family:ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";padding-left:0.5rem;padding-right:0.5rem'>
    <div style="display:none;overflow:hidden;line-height:1px;opacity:0;max-height:0;max-width:0">
        {{ __('emails/exhibitor-subsequent-payment-accepted.subject', ['event_name' => $event->getTranslation('title', $locale)]) }}
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
                        {{ __('emails/exhibitor-subsequent-payment-accepted.subject', ['event_name' => $event->getTranslation('title', $locale)]) }}
                    </h1>
                    <p style="color:rgb(0,0,0);font-size:14px;line-height:24px;margin:16px 0">
                        {{ __('emails/exhibitor-subsequent-payment-accepted.greeting', ['exhibitor_name' => $exhibitor->name]) }}
                    </p>
                    <p style="color:rgb(0,0,0);font-size:14px;line-height:24px;margin:16px 0">
                        {{ __('emails/exhibitor-subsequent-payment-accepted.intro', [
                            'amount' => $paymentSlice->price,
                            'currency' => $paymentSlice->currency,
                            'event_name' => $event->getTranslation('title', $locale)
                        ]) }}
                    </p>
                    <p style="color:rgb(0,0,0);font-size:14px;line-height:24px;margin:16px 0">
                        {{ __('emails/exhibitor-subsequent-payment-accepted.payment_details') }}
                    </p>
                    <ul style="color:rgb(0,0,0);font-size:14px;line-height:24px;margin:16px 0;padding-left:24px;">
                        @php
                            // Get the media item for the payment proof
                            $media = null;
                            $uploadDate = null;
                            if ($paymentSlice->hasMedia('attachement')) {
                                $media = $paymentSlice->getFirstMedia('attachement');
                                $uploadDate = $media ? $media->created_at : null;
                            }
                        @endphp
                        <li>{{ __('emails/exhibitor-subsequent-payment-accepted.amount', [
                            'amount' => $paymentSlice->price,
                            'currency' => $paymentSlice->currency,
                        ]) }}
                        </li>
                        <li>{{ __('emails/exhibitor-subsequent-payment-accepted.date', [
                            'date' => $uploadDate ? $uploadDate->format('d/m/Y') : '-',
                        ]) }}
                        </li>
                        <li>{{ __('emails/exhibitor-subsequent-payment-accepted.status') }}</li>
                    </ul>
                    <p style="color:rgb(0,0,0);font-size:14px;line-height:24px;margin:16px 0">
                        {{ __('emails/exhibitor-subsequent-payment-accepted.confirmation_notice') }}
                    </p>
                    <p style="color:rgb(0,0,0);font-size:14px;line-height:24px;margin:16px 0">
                        {{ __('emails/exhibitor-subsequent-payment-accepted.assistance') }}
                    </p>
                    <hr
                        style="border-width:1px;border-style:solid;border-color:rgb(234,234,234);margin-top:26px;margin-bottom:26px;margin-left:0px;margin-right:0px;width:100%;border:none;border-top:1px solid #eaeaea" />
                    <p style="color:rgb(102,102,102);font-size:12px;line-height:24px;margin:16px 0">
                        {{ __('emails/exhibitor-subsequent-payment-accepted.regards') }}<br />
                        {{ __('emails/exhibitor-subsequent-payment-accepted.team') }}
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>