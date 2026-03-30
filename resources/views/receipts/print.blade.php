<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $receipt->receipt_number }}</title>
    <script>
        window.addEventListener('load', () => window.print());
    </script>
</head>
<body style="font-family: Arial, sans-serif; padding: 32px; color: #111827; max-width: 920px; margin: 0 auto;">
    <style>
        .document-brand { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; padding-bottom: 18px; border-bottom: 2px solid #111827; }
        .document-brand__logo-wrap { width: 72px; height: 72px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .document-brand__logo { max-width: 72px; max-height: 72px; object-fit: contain; }
        .document-brand__logo-fallback { width: 72px; height: 72px; display: flex; align-items: center; justify-content: center; border-radius: 16px; background: #e5e7eb; color: #111827; font-size: 24px; font-weight: 700; }
        .document-brand__content { display: flex; flex-direction: column; gap: 6px; }
        .document-brand__name { font-size: 28px; font-weight: 700; color: #111827; }
        .document-brand__meta { font-size: 12px; color: #6B7280; }
        .receipt-shell { border: 1px solid #d1d5db; border-radius: 18px; overflow: hidden; }
        .receipt-header { padding: 24px; background: #f8fafc; border-bottom: 1px solid #e5e7eb; }
        .receipt-title { margin: 0; font-size: 28px; }
        .receipt-meta { margin-top: 6px; color: #6b7280; }
        .receipt-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; padding: 24px; }
        .receipt-card { border: 1px solid #e5e7eb; border-radius: 14px; background: #ffffff; padding: 16px; }
        .receipt-label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.18em; color: #6b7280; margin-bottom: 8px; }
        .receipt-value { font-size: 16px; font-weight: 600; color: #111827; }
        .receipt-amount { grid-column: 1 / -1; background: #eff6ff; border-color: #bfdbfe; }
        .receipt-amount .receipt-value { font-size: 24px; color: #1d4ed8; }
        .document-footer { margin-top: 28px; padding-top: 14px; border-top: 1px solid #d1d5db; font-size: 11px; color: #4b5563; display: flex; flex-wrap: wrap; gap: 8px 14px; }
        @media print { body { padding: 16px; } }
    </style>

    @include('print.partials.business-header')

    <div class="receipt-shell">
        <div class="receipt-header">
            <h1 class="receipt-title">Receipt {{ $receipt->receipt_number }}</h1>
            <p class="receipt-meta">Issued on {{ $receipt->issued_date->format('M d, Y') }}</p>
        </div>

        <div class="receipt-grid">
            <div class="receipt-card">
                <div class="receipt-label">Customer</div>
                <div class="receipt-value">{{ $receipt->payment->invoice->customer->full_name }}</div>
            </div>

            <div class="receipt-card">
                <div class="receipt-label">Invoice</div>
                <div class="receipt-value">{{ $receipt->payment->invoice->invoice_number }}</div>
            </div>

            <div class="receipt-card">
                <div class="receipt-label">Payment Method</div>
                <div class="receipt-value">{{ $receipt->payment->payment_method }}</div>
            </div>

            <div class="receipt-card">
                <div class="receipt-label">Reference</div>
                <div class="receipt-value">{{ $receipt->payment->reference_number ?: 'N/A' }}</div>
            </div>

            <div class="receipt-card receipt-amount">
                <div class="receipt-label">Amount Received</div>
                <div class="receipt-value">{{ $currencyFormatter->formatValue($receipt->payment->amount, 2) }}</div>
            </div>
        </div>
    </div>

    @include('print.partials.business-footer')
</body>
</html>
