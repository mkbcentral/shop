<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header .invoice-number {
            font-size: 18px;
            opacity: 0.9;
            margin-top: 5px;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .message {
            color: #555;
            margin-bottom: 25px;
        }
        .details-box {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .details-box h3 {
            margin: 0 0 15px 0;
            color: #4f46e5;
            font-size: 16px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            color: #666;
        }
        .detail-value {
            font-weight: 600;
            color: #333;
        }
        .total-row {
            background-color: #4f46e5;
            color: white;
            padding: 12px 15px;
            border-radius: 6px;
            margin-top: 15px;
        }
        .total-row .detail-label,
        .total-row .detail-value {
            color: white;
        }
        .cta-button {
            display: inline-block;
            background-color: #4f46e5;
            color: white;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 10px 0;
        }
        .cta-button:hover {
            background-color: #4338ca;
        }
        .due-date-notice {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            color: #92400e;
        }
        .due-date-notice.overdue {
            background-color: #fee2e2;
            border-color: #dc2626;
            color: #991b1b;
        }
        .paid-notice {
            background-color: #d1fae5;
            border: 1px solid #10b981;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            color: #065f46;
        }
        .attachment-notice {
            background-color: #e0f2fe;
            border: 1px solid #0ea5e9;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            color: #0369a1;
        }
        .attachment-notice svg {
            vertical-align: middle;
            margin-right: 8px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .footer a {
            color: #4f46e5;
            text-decoration: none;
        }
        .company-name {
            font-weight: 600;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Facture</h1>
            <div class="invoice-number">N° {{ $invoice->invoice_number }}</div>
        </div>

        <div class="content">
            <p class="greeting">Bonjour {{ $invoice->sale->client->name ?? 'Cher client' }},</p>

            <p class="message">
                Veuillez trouver ci-joint votre facture émise par <strong>{{ $organizationName }}</strong>.
            </p>

            <div class="details-box">
                <h3>📋 Récapitulatif</h3>
                <div class="detail-row">
                    <span class="detail-label">Numéro de facture</span>
                    <span class="detail-value">{{ $invoice->invoice_number }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date d'émission</span>
                    <span class="detail-value">{{ $invoice->invoice_date->format('d/m/Y') }}</span>
                </div>
                @if($invoice->due_date)
                <div class="detail-row">
                    <span class="detail-label">Date d'échéance</span>
                    <span class="detail-value">{{ $invoice->due_date->format('d/m/Y') }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Nombre d'articles</span>
                    <span class="detail-value">{{ $invoice->sale->items->count() }}</span>
                </div>
                @if($invoice->tax > 0)
                <div class="detail-row">
                    <span class="detail-label">Taxes</span>
                    <span class="detail-value">{{ format_currency($invoice->tax) }}</span>
                </div>
                @endif
                <div class="total-row">
                    <div class="detail-row" style="border: none; padding: 0;">
                        <span class="detail-label">Total à payer</span>
                        <span class="detail-value">{{ format_currency($invoice->total) }}</span>
                    </div>
                </div>
            </div>

            @if($invoice->status === 'paid')
            <div class="paid-notice">
                ✅ <strong>Facture payée</strong> - Merci pour votre règlement.
            </div>
            @elseif($invoice->due_date)
                @if($invoice->isOverdue())
                <div class="due-date-notice overdue">
                    ⚠️ <strong>Échéance dépassée</strong> - Cette facture était due le <strong>{{ $invoice->due_date->format('d/m/Y') }}</strong>. Merci de procéder au règlement dans les plus brefs délais.
                </div>
                @else
                <div class="due-date-notice">
                    ⏰ <strong>Échéance :</strong> Cette facture est à régler avant le <strong>{{ $invoice->due_date->format('d/m/Y') }}</strong>.
                </div>
                @endif
            @endif

            <div class="attachment-notice">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"/>
                </svg>
                <strong>Pièce jointe :</strong> Le document PDF détaillé est joint à cet email.
            </div>

            <p class="message">
                Pour toute question concernant cette facture, n'hésitez pas à nous contacter.
            </p>
        </div>

        <div class="footer">
            <p class="company-name">{{ $organizationName }}</p>
            @if($invoice->organization?->address)
                <p>{{ $invoice->organization->address }}</p>
            @endif
            @if($invoice->organization?->phone)
                <p>Tél: {{ $invoice->organization->phone }}</p>
            @endif
            <p style="margin-top: 15px; font-size: 11px; color: #999;">
                Merci pour votre confiance.
            </p>
        </div>
    </div>
</body>
</html>
