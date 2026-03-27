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
<body style="font-family: Arial, sans-serif; padding: 32px; color: #111827;">
    <h1 style="margin-bottom: 4px;">Receipt {{ $receipt->receipt_number }}</h1>
    <p style="margin-top: 0;">Date: {{ $receipt->issued_date->format('M d, Y') }}</p>
    <hr style="margin: 24px 0;">
    <p><strong>Customer:</strong> {{ $receipt->payment->invoice->customer->full_name }}</p>
    <p><strong>Invoice:</strong> {{ $receipt->payment->invoice->invoice_number }}</p>
    <p><strong>Payment Method:</strong> {{ $receipt->payment->payment_method }}</p>
    <p><strong>Reference:</strong> {{ $receipt->payment->reference_number ?: 'N/A' }}</p>
    <p><strong>Amount Received:</strong> {{ $currencyFormatter->formatValue($receipt->payment->amount, 2) }}</p>
</body>
</html>
