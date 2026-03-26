<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invoice->invoice_number }}</title>
    <script>
        window.addEventListener('load', () => window.print());
    </script>
</head>
<body style="font-family: Arial, sans-serif; padding: 32px; color: #111827;">
    <h1 style="margin-bottom: 4px;">Invoice {{ $invoice->invoice_number }}</h1>
    <p style="margin-top: 0;">Date: {{ $invoice->invoice_date->format('M d, Y') }}</p>
    @if($invoice->due_date)
        <p>Due: {{ $invoice->due_date->format('M d, Y') }}</p>
    @endif
    <hr style="margin: 24px 0;">
    <p><strong>Customer:</strong> {{ $invoice->customer->full_name }}</p>
    <p><strong>Phone:</strong> {{ $invoice->customer->phone }}</p>

    <table style="width: 100%; border-collapse: collapse; margin-top: 24px;">
        <thead>
            <tr>
                <th style="text-align: left; border-bottom: 1px solid #d1d5db; padding: 8px 0;">Item</th>
                <th style="text-align: center; border-bottom: 1px solid #d1d5db; padding: 8px 0;">Qty</th>
                <th style="text-align: right; border-bottom: 1px solid #d1d5db; padding: 8px 0;">Price</th>
                <th style="text-align: right; border-bottom: 1px solid #d1d5db; padding: 8px 0;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td style="padding: 8px 0;">
                        <div>{{ $item->item_name }}</div>
                        @if($item->description)
                            <div style="font-size: 12px; color: #6b7280;">{{ $item->description }}</div>
                        @endif
                    </td>
                    <td style="padding: 8px 0; text-align: center;">{{ $item->quantity }}</td>
                    <td style="padding: 8px 0; text-align: right;">{{ number_format($item->unit_price, 2) }}</td>
                    <td style="padding: 8px 0; text-align: right;">{{ number_format($item->line_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 24px; text-align: right;">
        <p><strong>Subtotal:</strong> {{ number_format($invoice->subtotal_amount, 2) }}</p>
        <p><strong>Discount:</strong> {{ number_format($invoice->discount_amount, 2) }}</p>
        <p><strong>Tax:</strong> {{ number_format($invoice->tax_amount, 2) }}</p>
        <p style="font-size: 18px;"><strong>Total:</strong> {{ number_format($invoice->total_amount, 2) }}</p>
        <p><strong>Paid:</strong> {{ number_format($invoice->amount_paid, 2) }}</p>
        <p><strong>Balance Due:</strong> {{ number_format($invoice->balance_due, 2) }}</p>
    </div>
</body>
</html>
