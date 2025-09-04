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
            padding: 20px;
        }

        .invoice-header {
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 20px;
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
            border: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }

        .info-label {
            font-weight: bold;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .info-value {
            margin-bottom: 15px;
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
        <!-- Header -->
        <div class="invoice-header">
            <h1 class="invoice-title">Facture de vente</h1>

            <div class="invoice-info">
                <div class="invoice-info-left">
                    <div class="info-label">Date:</div>
                    <div class="info-value">{{ $invoice['date'] }}</div>

                    <div class="info-label">Code:</div>
                    <div class="info-value">{{ $invoice['code'] }}</div>

                    <div class="info-label">Contrat:</div>
                    <div class="info-value">{{ $invoice['type'] }}</div>

                    <div class="info-label">CODE MECeF / DGI:</div>
                    <div class="info-value">{{ $invoice['mecef_code'] }}</div>
                </div>

                <div class="invoice-info-right">
                    <!-- QR Code simulé -->
                    <div class="qr-code">
                        QR CODE
                    </div>

                    <div style="margin-right: 100px;">
                        <div class="info-label">Adressée à:</div>
                        <div class="info-value">
                            <strong>Nom:</strong> {{ $invoice['client']['name'] }}<br>
                            <strong>Adresse:</strong> {{ $invoice['client']['address'] }}<br>
                            <strong>Code client:</strong> {{ $invoice['client']['code'] }}<br>
                            @if($invoice['client']['contact'])
                                <strong>Contact client:</strong> {{ $invoice['client']['contact'] }}<br>
                            @endif
                        </div>

                        <div class="info-label">Demande d'examen:</div>
                        <div class="info-value">{{ $invoice['exam_request'] }}</div>
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
                    @foreach($invoice['items'] as $index => $item)
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
            <div class="signature-container">
                <div class="signature-image">
                    <img src="{{ public_path('images/signature.png') }}" alt="Signature">
                </div>

                <!-- Cachet PAYÉ SVG -->
                <div class="paid-stamp">
                    <svg width="120" height="50" xmlns="http://www.w3.org/2000/svg">
                        <!-- Rectangle de fond -->
                        <rect x="5" y="5" width="110" height="40" fill="none" stroke="#dc3545" stroke-width="3" rx="8"/>
                        <!-- Texte PAYÉ -->
                        <text x="60" y="30" text-anchor="middle" font-family="Arial, sans-serif" font-size="18" font-weight="bold" fill="#dc3545">PAYÉ</text>
                        <!-- Effet tampon avec rotation -->
                        <g transform="rotate(-10, 60, 25)">
                            <rect x="10" y="10" width="100" height="30" fill="none" stroke="#dc3545" stroke-width="2" rx="5"/>
                            <text x="60" y="28" text-anchor="middle" font-family="Arial, sans-serif" font-size="14" font-weight="bold" fill="#dc3545">PAYÉ</text>
                        </g>
                    </svg>
                </div>
            </div>

            <div class="empty-space"></div>
        </div>

        <!-- Note importante -->
        @if($invoice['note'])
        <div class="invoice-note no-break">
            <div class="note-label">Note importante :</div>
            <div class="note-content">{{ $invoice['note'] }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="invoice-footer">
            {{ $invoice['footer'] }}
        </div>
    </div>
</body>
</html>
