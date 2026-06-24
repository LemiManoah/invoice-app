<!DOCTYPE html>
<html lang="en">
<head>
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #111827;
            background: #fff;
            padding: 36px;
            max-width: 860px;
            margin: 0 auto;
        }

        /* Print button */
        .actions { margin-bottom: 20px; }
        .actions button, .actions a {
            padding: 8px 14px; border: 0; background: #111827;
            color: white; text-decoration: none; cursor: pointer;
            margin-right: 8px; border-radius: 4px; display: inline-block; font-size: 13px;
        }
        @media print { .actions { display: none; } body { padding: 20px; } }

        /* Header: large INVOICE left, logo right */
        .doc-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 32px;
        }
        .doc-title {
            font-size: 56px;
            font-weight: 900;
            color: #111827;
            line-height: 1;
            letter-spacing: -1px;
        }
        .doc-logo { max-height: 84px; max-width: 180px; object-fit: contain; }
        .doc-logo-fallback {
            width: 80px; height: 80px; background: #e5e7eb; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; font-weight: 700; color: #111827;
        }

        /* FROM / BILL TO / META row */
        .info-row {
            display: flex;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
            margin-bottom: 28px;
        }
        .info-col { flex: 1; padding-right: 20px; }
        .info-col.text-right { text-align: right; padding-right: 0; }
        .info-label {
            font-size: 11px; font-weight: 700; text-transform: uppercase;
            color: #f97316; letter-spacing: 0.06em; margin-bottom: 7px;
        }
        .info-name { font-size: 15px; font-weight: 700; margin-bottom: 3px; }
        .info-detail { font-size: 12px; color: #4b5563; margin-bottom: 2px; line-height: 1.5; }
        .meta-line { font-size: 12px; margin-bottom: 4px; }
        .meta-line strong { font-weight: 700; }
        .status-badge {
            display: inline-block; margin-top: 6px;
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.08em; color: #6b7280;
        }

        /* Items table */
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #f97316; }
        thead th {
            padding: 10px 12px; color: #fff;
            font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;
            text-align: left;
        }
        thead th.right { text-align: right; }
        thead th.center { text-align: center; }
        tbody tr { border-bottom: 1px solid #e5e7eb; }
        tbody td { padding: 11px 12px; font-size: 13px; vertical-align: top; }
        tbody td.right { text-align: right; }
        tbody td.center { text-align: center; }
        .item-desc { color: #6b7280; font-size: 11px; margin-top: 3px; }

        /* Summary rows */
        .summary-td { padding: 5px 12px; font-size: 13px; }
        .summary-label { text-align: right; color: #4b5563; }
        .summary-value { text-align: right; font-weight: 600; min-width: 140px; }
        .summary-total .summary-label,
        .summary-total .summary-value { font-weight: 700; font-size: 13px; }

        /* Balance due */
        .balance-due-label {
            background: #111827; color: #f97316;
            font-size: 14px; font-weight: 800; text-transform: uppercase;
            text-align: right; padding: 12px;
        }
        .balance-due-value {
            background: #111827; color: #f97316;
            font-size: 14px; font-weight: 800;
            text-align: right; padding: 12px;
        }

        /* Notes */
        .notes { margin-top: 24px; font-size: 12px; color: #4b5563; line-height: 1.6; }
        .notes strong { color: #111827; }

        /* Signature */
        .signature-section { margin-top: 40px; display: flex; justify-content: flex-end; }
        .signature-img { max-height: 64px; max-width: 180px; object-fit: contain; display: block; margin: 0 auto 6px; }
        .signature-line { border-top: 1px solid #111827; padding-top: 4px; font-size: 11px; color: #4b5563; width: 180px; text-align: center; }

        /* Footer */
        .document-footer {
            margin-top: 28px; padding-top: 12px;
            border-top: 1px solid #d1d5db;
            font-size: 11px; color: #4b5563;
            display: flex; flex-wrap: wrap; gap: 6px 14px;
        }
    </style>
</head>
<body>

<div class="actions">
    <button type="button" onclick="window.print()">Print</button>
    <a href="javascript:window.close()">Close</a>
</div>

{{-- Header --}}
<div class="doc-header">
    <div class="doc-title">INVOICE</div>
    <div>
        @if($businessProfile?->logo_path)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($businessProfile->logo_path) }}" alt="Logo" class="doc-logo">
        @else
            <div class="doc-logo-fallback">
                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($businessProfile?->name ?: config('app.name'), 0, 2)) }}
            </div>
        @endif
    </div>
</div>

{{-- FROM / BILL TO / META --}}
<div class="info-row">
    <div class="info-col">
        <div class="info-label">Invoice From:</div>
        @if($businessProfile)
            <div class="info-name">{{ $businessProfile->name }}</div>
            @if($businessProfile->address)<div class="info-detail">{{ $businessProfile->address }}</div>@endif
            @if($businessProfile->contacts)<div class="info-detail">{{ $businessProfile->contacts }}</div>@endif
            @if($businessProfile->email)<div class="info-detail">{{ $businessProfile->email }}</div>@endif
            @if($businessProfile->location)<div class="info-detail">{{ $businessProfile->location }}</div>@endif
        @endif
    </div>
    <div class="info-col">
        <div class="info-label">Bill To:</div>
        <div class="info-name">{{ $invoice->customer->full_name }}</div>
        @if($invoice->customer->phone)<div class="info-detail">{{ $invoice->customer->phone }}</div>@endif
        @if($invoice->customer->address)<div class="info-detail">{{ $invoice->customer->address }}</div>@endif
        @if($invoice->customer->email)<div class="info-detail">{{ $invoice->customer->email }}</div>@endif
    </div>
    <div class="info-col text-right">
        <div class="meta-line">Number: <strong>{{ $invoice->invoice_number }}</strong></div>
        <div class="meta-line">Date: <strong>{{ $invoice->invoice_date->format('M d, Y') }}</strong></div>
        <div class="meta-line">Due date: <strong>{{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'On receipt' }}</strong></div>
        @if($invoice->status)
            <div class="status-badge">{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</div>
        @endif
    </div>
</div>

{{-- Items --}}
<table>
    <thead>
        <tr>
            <th>Description</th>
            <th class="center" style="width:80px;">Quantity</th>
            <th class="right" style="width:130px;">Unit Price</th>
            <th class="right" style="width:140px;">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoice->items as $item)
        <tr>
            <td>
                {{ $item->item_name }}
                @if($item->description)<div class="item-desc">{{ $item->description }}</div>@endif
            </td>
            <td class="center">{{ $item->quantity }}</td>
            <td class="right">{{ $currencyFormatter->formatValue($item->unit_price, 2, $invoice->currency) }}</td>
            <td class="right">{{ $currencyFormatter->formatValue($item->line_total, 2, $invoice->currency) }}</td>
        </tr>
        @endforeach

        {{-- Gap before totals --}}
        <tr style="border: none;">
            <td colspan="4" style="padding: 10px; border: none;"></td>
        </tr>

        {{-- Subtotal --}}
        <tr style="border: none;">
            <td colspan="2" style="border: none;"></td>
            <td class="summary-td summary-label" style="border-top: 1px solid #e5e7eb;">SUBTOTAL:</td>
            <td class="summary-td summary-value" style="border-top: 1px solid #e5e7eb;">{{ $currencyFormatter->formatValue($invoice->subtotal_amount, 2, $invoice->currency) }}</td>
        </tr>

        @if($invoice->discount_amount > 0)
        <tr style="border: none;">
            <td colspan="2" style="border: none;"></td>
            <td class="summary-td summary-label">DISCOUNT:</td>
            <td class="summary-td summary-value" style="color: #dc2626;">-{{ $currencyFormatter->formatValue($invoice->discount_amount, 2, $invoice->currency) }}</td>
        </tr>
        @endif

        @if($invoice->tax_amount > 0)
        <tr style="border: none;">
            <td colspan="2" style="border: none;"></td>
            <td class="summary-td summary-label">TAX:</td>
            <td class="summary-td summary-value">{{ $currencyFormatter->formatValue($invoice->tax_amount, 2, $invoice->currency) }}</td>
        </tr>
        @endif

        <tr class="summary-total" style="border: none;">
            <td colspan="2" style="border: none;"></td>
            <td class="summary-td summary-label">TOTAL:</td>
            <td class="summary-td summary-value">{{ $currencyFormatter->formatValue($invoice->total_amount, 2, $invoice->currency) }}</td>
        </tr>

        @if($invoice->amount_paid > 0)
        <tr style="border: none;">
            <td colspan="2" style="border: none;"></td>
            <td class="summary-td summary-label">PAID:</td>
            <td class="summary-td summary-value" style="color: #16a34a;">{{ $currencyFormatter->formatValue($invoice->amount_paid, 2, $invoice->currency) }}</td>
        </tr>
        @endif

        {{-- Balance Due --}}
        <tr style="border: none;">
            <td colspan="2" style="border: none;"></td>
            <td class="balance-due-label">BALANCE DUE</td>
            <td class="balance-due-value">{{ $currencyFormatter->formatValue($invoice->balance_due, 2, $invoice->currency) }}</td>
        </tr>
    </tbody>
</table>

@if($invoice->notes)
    <div class="notes"><strong>Notes:</strong> {!! nl2br(e($invoice->notes)) !!}</div>
@endif

@if($businessProfile?->signature_path)
    <div class="signature-section">
        <div>
            <img src="{{ \Illuminate\Support\Facades\Storage::url($businessProfile->signature_path) }}" alt="Signature" class="signature-img">
            <div class="signature-line">Authorised Signature</div>
        </div>
    </div>
@endif

@include('print.partials.business-footer')

<script type="text/php">
    if (isset($pdf) && $PAGE_COUNT > 1) {
        $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
        $size = 10;
        $font = $fontMetrics->getFont("Verdana");
        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
        $x = ($pdf->get_width() - $width);
        $y = $pdf->get_height() - 35;
        $pdf->page_text($x, $y, $text, $font, $size);
    }
</script>
</body>
</html>
