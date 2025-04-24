<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="{{ $direction ?? 'ltr' }}" lang="{{ $locale }}">

<head>
    <link rel="preload" as="image"
        href="https://ffp-events.com/wp-content/uploads/2025/03/Untitled-design-37-e1740997899683.png" />
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
    <meta name="x-apple-disable-message-reformatting" />
</head>

<body
    style='background-color:#eee;margin-top:auto;margin-bottom:auto;margin-left:auto;margin-right:auto;font-family:ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";padding-left:0.5rem;padding-right:0.5rem'>
    <div style="display:none;overflow:hidden;line-height:1px;opacity:0;max-height:0;max-width:0">
        Mise à jour de soumission d'exposant - {{ $event->title }}
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
                        Mise à jour de soumission d'exposant
                    </h1>
                    <p style="color:rgb(0,0,0);font-size:14px;line-height:24px;margin:16px 0">
                        Bonjour {{ $admin->name ?? 'Administrateur' }},
                    </p>
                    <p style="color:rgb(0,0,0);font-size:14px;line-height:24px;margin:16px 0">
                        L'exposant <strong>{{ $exhibitor->name }}</strong> a mis à jour sa soumission pour l'événement
                        <strong>{{ $event->title }}</strong>.
                    </p>
                    <p style="color:rgb(0,0,0);font-size:14px;line-height:24px;margin:16px 0">
                        <strong>Détails de la mise à jour:</strong><br>
                        Nom de l'exposant: {{ $exhibitor->name }}<br>
                        Email: {{ $exhibitor->email }}<br>
                        Date de la mise à jour: {{ $submission->updated_at->format('d/m/Y H:i') }}<br>
                        Événement: {{ $event->title }}
                    </p>
                    <p style="color:rgb(0,0,0);font-size:14px;line-height:24px;margin:16px 0">
                        Veuillez consulter le tableau de bord administrateur pour examiner les modifications apportées à
                        cette soumission.
                    </p>
                    <table align="center" width="100%" border="0" cellpadding="0" cellspacing="0"
                        role="presentation" style="margin-top:32px;margin-bottom:32px;text-align:center">
                        <tbody>
                            <tr>
                                <td>
                                    <a href="{{ route('filament.admin.resources.exhibitor-submissions.view', $submission->id) }}"
                                        style="background-color:#f59e0b;border-radius:0.25rem;color:#fff;font-size:12px;font-weight:600;line-height:100%;text-decoration:none;text-align:center;padding-top:12px;padding-bottom:12px;padding-left:24px;padding-right:24px;display:inline-block;max-width:100%"
                                        target="_blank">
                                        Voir la soumission
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="color:rgb(0,0,0);font-size:14px;line-height:24px;margin:16px 0">
                        Merci,<br>
                        L'équipe FFP Events
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
