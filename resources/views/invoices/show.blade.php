<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture de vente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 10px;
        }

        .company-logo {
            text-align: center;
            margin-bottom: 0px;
            /* border-bottom: 1px solid #e9ecef; */
            padding-bottom: 0px;
        }

        .company-logo img {
            max-height: 120px;
            max-width: 100%;
            height: auto;
        }

        .company-logo-placeholder {
            color: #007bff;
            margin: 0;
            padding: 20px;
            border: 2px solid #007bff;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
        }

        .invoice-header {
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #495057;
            margin-bottom: 20px;
            text-align: center;
        }

        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .invoice-info-left,
        .invoice-info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .invoice-info-right {
            text-align: right;
            position: relative;
        }

        .qr-code {
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
            color: #6c757d;
            border-radius: 4px;
        }

        .info-label {
            font-weight: bold;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .info-label-2 {
            font-weight: bold;
            color: #000000;
            margin-bottom: 5px;
        }

        .info-value {
            margin-bottom: 15px;
            color: #333;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            background: #fff;
        }

        .invoice-table th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            color: #495057;
        }

        .invoice-table td {
            border: 1px solid #dee2e6;
            padding: 12px;
            color: #333;
        }

        .invoice-table th:last-child,
        .invoice-table td:last-child {
            text-align: right;
        }

        .invoice-table th:nth-child(2),
        .invoice-table td:nth-child(2),
        .invoice-table th:nth-child(3),
        .invoice-table td:nth-child(3),
        .invoice-table th:nth-child(4),
        .invoice-table td:nth-child(4) {
            text-align: center;
        }

        .totals-section {
            width: 300px;
            margin-left: auto;
            border-top: 2px solid #e9ecef;
            padding-top: 15px;
        }

        .total-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .total-label,
        .total-value {
            display: table-cell;
            padding: 5px 0;
        }

        .total-label {
            text-align: right;
            padding-right: 20px;
            font-weight: bold;
            color: #6c757d;
        }

        .total-value {
            text-align: right;
            font-weight: bold;
        }

        .final-total {
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
            font-size: 14px;
        }

        .final-total .total-label,
        .final-total .total-value {
            color: #000;
            font-size: 14px;
        }

        .signature-section {
            /* margin-top: 140px; */
            display: table;
            /* width: 100%; */
            margin-left: 365px;
        }

        .signature-container {
            display: table-cell;
            width: 300px;
            vertical-align: top;
            position: right;
            padding-right: 20px;
        }

        .signature-image {
            /* border: 1px solid #dee2e6; */
            padding: 10px;
            background: #fff;
            text-align: center;
            margin-bottom: 15px;
            /* border-radius: 4px; */
        }

        .signature-image img {
            max-width: 250px;
            max-height: 100px;
        }

        .signature-placeholder {
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-style: italic;
            color: #6c757d;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 4px;
        }

        .paid-stamp {
            text-align: center;
            margin-top: 10px;
        }

        .paid-stamp svg {
            display: block;
            margin: 0 auto;
        }

        .empty-space {
            display: table-cell;
            width: 100%;
        }

        .invoice-note {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
            border-radius: 4px;
        }

        .note-label {
            font-weight: bold;
            margin-bottom: 10px;
            color: #495057;
        }

        .note-content {
            line-height: 1.5;
            color: #6c757d;
        }

        .invoice-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            line-height: 1.3;
        }

        .amount {
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }

        @media print {
            body {
                padding: 10px;
            }
        }

        /* Styles pour DomPDF */
        .page-break {
            page-break-after: always;
        }

        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Logo d'en-tête -->
        <div class="company-logo">
            @if (isset($invoice['images']['header_logo']) && !empty($invoice['images']['header_logo']))
                <img src="{{ $invoice['images']['header_logo'] }}" alt="Logo de l'entreprise">
            @else
                <div class="company-logo-placeholder">
                    CENTRE ADECHINA<br>
                    <span style="font-size: 14px; font-weight: normal;">Anatomie Pathologique</span>
                </div>
            @endif
        </div>

        <!-- Header -->
        <div class="invoice-header" style="margin-top : 30px;">

            <div class="invoice-info">
                <div class="invoice-info-left">
                    <div class="info-label-2" style="font-weight: bold; font-size : 20px;">
                        {{ $invoice['status_invoice'] != 1 ? 'Facture de vente' : 'Facture d\'avoir' }}
                    </div>

                    <div>
                        <span class="info-label">
                            Date:
                        </span>
                        <span class="info-value">
                            {{ $invoice['date'] }}
                        </span>
                    </div>

                    <div>
                        <span class="info-label">
                            Code:
                        </span>
                        <span class="info-value">
                            {{ $invoice['code'] }}
                            @if ($invoice['invoice_paid'] == 1)
                                <span style="text-transform: uppercase; font-weight: bold;">[Payé]</span>
                            @else
                                <span style="text-transform: uppercase; font-weight: bold;">[En attente]</span>
                            @endif
                        </span>
                    </div>

                    <div>
                        @if ($invoice['invoice_paid'] != 1)
                            <span class="info-label">
                                Contrat:
                            </span>
                            <span class="info-value">
                                {{ $invoice['type'] }}
                            </span>
                        @else
                            <span class="info-label">
                                Reférence:
                            </span>
                            <span class="info-value">
                                {{ $invoice['reference_code'] }}
                            </span>
                        @endif
                    </div>

                    <div>
                        <span class="info-label">
                            CODE MECeF / DGI:
                        </span>
                        <span class="info-value">
                            {{ $invoice['mecef_code'] }}
                        </span>
                    </div>
                </div>

                <div class="invoice-info-right">
                    @if (!empty($invoice['mecef_code']))
                        <div class="qr-code">
                            <img src="data:image/png;base64,{{ $invoice['qr_code'] }}" alt="QR Code" height="80"
                                width="80">
                        </div>
                    @endif

                    <div style="margin-right: 100px;">
                        <div class="info-label">Adressée à:</div>
                        <div class="info-value">
                            <strong>Nom:</strong> {{ $invoice['client']['name'] }}<br>
                            <strong>Adresse:</strong> {{ $invoice['client']['address'] }}<br>
                            <strong>Code client:</strong> {{ $invoice['client']['code'] }}<br>
                            @if ($invoice['client']['contact'])
                                <strong>Contact client:</strong> {{ $invoice['client']['contact'] }}<br>
                            @endif
                        </div>

                        <div>
                            <span class="info-label">
                                Demande d'examen:
                            </span>
                            <span class="info-value">
                                {{ $invoice['exam_request'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table des articles -->
        <div class="no-break">
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Désignation</th>
                        <th>Quantité</th>
                        <th>Prix</th>
                        <th>Remise</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice['items'] as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item['designation'] }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td class="amount">{{ number_format($item['price'], 0, ',', ' ') }}</td>
                            <td class="amount">{{ number_format($item['discount'], 0, ',', ' ') }}</td>
                            <td class="amount">{{ number_format($item['total'], 0, ',', ' ') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totaux -->
        <div class="totals-section no-break">
            <div class="total-row">
                <div class="total-label">Sous-total :</div>
                <div class="total-value amount">{{ number_format($invoice['subtotal'], 0, ',', ' ') }}</div>
            </div>

            <div class="total-row final-total">
                <div class="total-label">Montant TTC :</div>
                <div class="total-value amount">{{ number_format($invoice['total_ttc'], 0, ',', ' ') }} FCFA</div>
            </div>
        </div>

        <!-- Section signature et cachet PAYÉ -->
        <div class="signature-section no-break">
            <div class="signature-container" style="margin-left: 100px; position: relative;">
                <div class="signature-image">
                    @if (isset($invoice['images']['signature']) && !empty($invoice['images']['signature']))
                        <img src="{{ $invoice['images']['signature'] }}" alt="Signature">

                        @if (isset($invoice['images']['signature']) && !empty($invoice['images']['signature']))
                            <img src="{{ public_path('adminassets/images/paid_img.png') }}" alt="Signature" width="100">
                        @endif
                    @else
                        <div class="signature-placeholder">
                            [Signature]
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Note importante -->
        @if ($invoice['note'])
            <div class="invoice-note no-break" style="position: fixed; bottom: 60px;">
                <div class="note-label">Note importante :</div>
                <div class="note-content">{{ $invoice['note'] }}</div>
            </div>
        @endif

        <!-- Footer -->
        <div class="invoice-footer" style="position: fixed; bottom: 0;">
            {{ $invoice['footer'] }}
        </div>
    </div>
</body>

</html>
