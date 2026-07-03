<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Commande confirmée</title>
</head>
<body style="margin:0; padding:0; background:#F7F1E7; font-family: Arial, Helvetica, sans-serif; color:#241319;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F7F1E7; padding:32px 16px;">
<tr>
<td align="center">
<table role="presentation" width="100%" style="max-width:560px; background:#ffffff; border-radius:14px; overflow:hidden;">

    {{-- En-tête --}}
    <tr>
        <td style="background:#3A1F2B; padding:28px 32px; text-align:center;">
            <div style="color:#E0B45E; font-size:12px; letter-spacing:3px; text-transform:uppercase; margin-bottom:6px;">Blac Joyaux</div>
            <div style="color:#ffffff; font-size:20px; font-weight:bold;">Votre commande est confirmée</div>
        </td>
    </tr>

    {{-- Message principal --}}
    <tr>
        <td style="padding:28px 32px 8px;">
            <p style="font-size:15px; line-height:1.6; margin:0 0 8px;">Bonjour {{ $order->customer_name }},</p>
            <p style="font-size:15px; line-height:1.6; margin:0 0 8px;">
                Merci pour votre confiance. Votre commande <strong>{{ $order->reference }}</strong> vient d'être confirmée par notre équipe et est en cours de préparation dans notre atelier.
            </p>
        </td>
    </tr>

    {{-- Récapitulatif articles --}}
    <tr>
        <td style="padding:16px 32px;">
            <table role="presentation" width="100%" style="border-collapse:collapse;">
                <tr>
                    <td colspan="3" style="font-size:12px; text-transform:uppercase; letter-spacing:1px; color:#6E6258; padding-bottom:8px; border-bottom:2px solid #3A1F2B;">Articles commandés</td>
                </tr>
                @foreach($order->items as $item)
                <tr>
                    <td style="padding:10px 0; border-bottom:1px solid #EFE6D6; font-size:14px;">{{ $item->product_name }}</td>
                    <td style="padding:10px 0; border-bottom:1px solid #EFE6D6; font-size:14px; text-align:center; color:#6E6258;">x{{ $item->quantity }}</td>
                    <td style="padding:10px 0; border-bottom:1px solid #EFE6D6; font-size:14px; text-align:right; white-space:nowrap;">{{ number_format($item->line_total,0,',',' ') }} F</td>
                </tr>
                @endforeach
                <tr>
                    <td style="padding:10px 0; font-size:14px; color:#6E6258;">Sous-total</td>
                    <td></td>
                    <td style="padding:10px 0; font-size:14px; text-align:right;">{{ number_format($order->subtotal,0,',',' ') }} F</td>
                </tr>
                <tr>
                    <td style="padding:0 0 10px; font-size:14px; color:#6E6258;">Livraison</td>
                    <td></td>
                    <td style="padding:0 0 10px; font-size:14px; text-align:right;">{{ $order->delivery_fee > 0 ? number_format($order->delivery_fee,0,',',' ').' F' : 'Offerte' }}</td>
                </tr>
                <tr>
                    <td style="padding-top:10px; border-top:2px solid #3A1F2B; font-size:16px; font-weight:bold;">Total</td>
                    <td style="border-top:2px solid #3A1F2B;"></td>
                    <td style="padding-top:10px; border-top:2px solid #3A1F2B; font-size:16px; font-weight:bold; text-align:right;">{{ number_format($order->total,0,',',' ') }} F CFA</td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- Livraison --}}
    <tr>
        <td style="padding:8px 32px 28px;">
            <table role="presentation" width="100%" style="background:#F7F1E7; border-radius:10px;">
                <tr>
                    <td style="padding:18px 20px;">
                        <div style="font-size:12px; text-transform:uppercase; letter-spacing:1px; color:#6E6258; margin-bottom:10px;">Livraison</div>
                        <p style="margin:0 0 4px; font-size:14px;"><strong>{{ $order->customer_name }}</strong></p>
                        <p style="margin:0 0 4px; font-size:14px;">{{ $order->shipping_address }}, {{ $order->city }}</p>
                        <p style="margin:0 0 4px; font-size:14px;">Tél : {{ $order->customer_phone }}</p>
                        <p style="margin:12px 0 0; font-size:13px; color:#6E6258;">Délai estimé : 1 à 3 jours ouvrés.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- CTA --}}
    <tr>
        <td style="padding:0 32px 32px; text-align:center;">
            <a href="{{ config('app.url') }}" style="display:inline-block; background:#3A1F2B; color:#F7F1E7; text-decoration:none; padding:12px 28px; border-radius:100px; font-size:14px; font-weight:bold;">Visiter la boutique</a>
        </td>
    </tr>

    {{-- Pied de page --}}
    <tr>
        <td style="background:#3A1F2B; padding:20px 32px; text-align:center;">
            <p style="color:#EFE6D6; font-size:12px; margin:0;">Blac Joyaux — L'avenir en main</p>
            <p style="color:#8a7d70; font-size:11px; margin:6px 0 0;">Des questions ? Contactez-nous directement via WhatsApp depuis notre site.</p>
        </td>
    </tr>

</table>
</td>
</tr>
</table>
</body>
</html>