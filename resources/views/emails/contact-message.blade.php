<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Nouveau message de contact</title>
</head>
<body style="margin:0; padding:0; background:#F7F1E7; font-family: Arial, Helvetica, sans-serif; color:#241319;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F7F1E7; padding:32px 16px;">
<tr>
<td align="center">
<table role="presentation" width="100%" style="max-width:560px; background:#ffffff; border-radius:14px; overflow:hidden;">

    <tr>
        <td style="background:#3A1F2B; padding:28px 32px; text-align:center;">
            <div style="color:#E0B45E; font-size:12px; letter-spacing:3px; text-transform:uppercase; margin-bottom:6px;">Blac Joyaux</div>
            <div style="color:#ffffff; font-size:20px; font-weight:bold;">Nouveau message depuis le site</div>
        </td>
    </tr>

    <tr>
        <td style="padding:28px 32px;">
            <table role="presentation" width="100%" style="border-collapse:collapse;">
                <tr>
                    <td style="padding:8px 0; font-size:13px; color:#6E6258; width:120px;">Nom</td>
                    <td style="padding:8px 0; font-size:14px;"><strong>{{ $data['nom'] }}</strong></td>
                </tr>
                <tr>
                    <td style="padding:8px 0; font-size:13px; color:#6E6258;">Email</td>
                    <td style="padding:8px 0; font-size:14px;"><a href="mailto:{{ $data['email'] }}" style="color:#3A1F2B;">{{ $data['email'] }}</a></td>
                </tr>
                @if(!empty($data['telephone']))
                <tr>
                    <td style="padding:8px 0; font-size:13px; color:#6E6258;">Téléphone</td>
                    <td style="padding:8px 0; font-size:14px;">{{ $data['telephone'] }}</td>
                </tr>
                @endif
                @if(!empty($data['sujet']))
                <tr>
                    <td style="padding:8px 0; font-size:13px; color:#6E6258;">Sujet</td>
                    <td style="padding:8px 0; font-size:14px;">{{ $data['sujet'] }}</td>
                </tr>
                @endif
            </table>
        </td>
    </tr>

    <tr>
        <td style="padding:0 32px 28px;">
            <div style="font-size:12px; text-transform:uppercase; letter-spacing:1px; color:#6E6258; margin-bottom:10px; border-top:2px solid #3A1F2B; padding-top:16px;">Message</div>
            <p style="font-size:14px; line-height:1.7; margin:0; white-space:pre-line;">{{ $data['message'] }}</p>
        </td>
    </tr>

    <tr>
        <td style="padding:0 32px 32px; text-align:center;">
            <a href="mailto:{{ $data['email'] }}" style="display:inline-block; background:#3A1F2B; color:#F7F1E7; text-decoration:none; padding:12px 28px; border-radius:100px; font-size:14px; font-weight:bold;">Répondre à {{ $data['nom'] }}</a>
        </td>
    </tr>

    <tr>
        <td style="background:#3A1F2B; padding:20px 32px; text-align:center;">
            <p style="color:#EFE6D6; font-size:12px; margin:0;">Blac Joyaux — Formulaire de contact du site</p>
        </td>
    </tr>

</table>
</td>
</tr>
</table>
</body>
</html>
