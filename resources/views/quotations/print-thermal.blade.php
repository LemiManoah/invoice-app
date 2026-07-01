<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quotation {{ $quotation->quotation_number }}</title>
    <script>window.addEventListener('load', () => window.print());</script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @page { size: {{ $paperWidth }}mm auto; margin: 0; }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: {{ $paperWidth === 58 ? '10px' : '12px' }};
            line-height: 1.4;
            color: #000;
            background: #fff;
            width: {{ $paperWidth }}mm;
            margin: 0 auto;
            padding: 3mm;
        }
        @media print {
            .actions { display: none; }
            body { width: {{ $paperWidth }}mm; }
        }

        .center { text-align: center; }
        .bold { font-weight: 700; }
        .logo { max-height: 14mm; max-width: 100%; object-fit: contain; margin: 0 auto 2mm; display: block; }
        .title { font-size: 1.3em; font-weight: 700; letter-spacing: 0.05em; margin-bottom: 1mm; }

        .rule { border-top: 1px dashed #000; margin: 2mm 0; }
        .rule-solid { border-top: 1px solid #000; margin: 2mm 0; }

        .row { display: flex; justify-content: space-between; gap: 4px; margin-bottom: 0.6mm; }
        .row .label { white-space: nowrap; }
        .row .value { text-align: right; word-break: break-word; }

        .item-row { margin-bottom: 1.2mm; }
        .item-desc { margin-bottom: 0.6mm; word-break: break-word; }

        .amount-box { margin-top: 2mm; padding: 1.5mm 0; }
        .amount-box .row { font-size: 1.15em; font-weight: 700; }

        .footer-text { margin-top: 3mm; font-size: 0.9em; text-align: center; word-break: break-word; }
        .footer-text div { margin-bottom: 0.6mm; }

        .actions { margin-bottom: 4mm; text-align: center; }
        .actions button, .actions a {
            display: inline-block; padding: 6px 12px; border: 0; background: #111827; color: #fff;
            text-decoration: none; cursor: pointer; margin: 0 4px; border-radius: 4px; font-size: 12px;
        }
    </style>
</head>
<body>

<div class="actions">
    <button type="button" onclick="window.print()">Print</button>
    <a href="javascript:window.close()">Close</a>
</div>

<div class="center">
    @if($businessProfile?->logo_path)
        <img src="{{ \Illuminate\Support\Facades\Storage::url($businessProfile->logo_path) }}" alt="Logo" class="logo">
    @endif
    <div class="title">{{ $businessProfile?->name ?: config('app.name') }}</div>
    @if($businessProfile?->address)<div>{{ $businessProfile->address }}</div>@endif
    @if($businessProfile?->contacts)<div>{{ $businessProfile->contacts }}</div>@endif
</div>

<div class="rule-solid"></div>
<div class="center bold">QUOTATION</div>
<div class="rule"></div>

<div class="row"><span class="label">Quotation #:</span><span class="value">{{ $quotation->quotation_number }}</span></div>
<div class="row"><span class="label">Date:</span><span class="value">{{ $quotation->quotation_date->format('M d, Y') }}</span></div>
@if($quotation->valid_until)
    <div class="row"><span class="label">Valid Until:</span><span class="value">{{ $quotation->valid_until->format('M d, Y') }}</span></div>
@endif
<div class="row"><span class="label">Prepared For:</span><span class="value">{{ $quotation->customer->full_name }}</span></div>

<div class="rule"></div>

@foreach($quotation->items as $item)
    <div class="item-row">
        <div class="item-desc bold">{{ $item->item_name }}</div>
        <div class="row">
            <span class="label">{{ $item->quantity }} x {{ $currencyFormatter->formatValue($item->unit_price, 2, $quotation->currency) }}</span>
            <span class="value">{{ $currencyFormatter->formatValue($item->line_total, 2, $quotation->currency) }}</span>
        </div>
    </div>
@endforeach

<div class="rule"></div>

<div class="row"><span class="label">Subtotal:</span><span class="value">{{ $currencyFormatter->formatValue($quotation->subtotal_amount, 2, $quotation->currency) }}</span></div>
@if($quotation->discount_amount > 0)
    <div class="row"><span class="label">Discount:</span><span class="value">-{{ $currencyFormatter->formatValue($quotation->discount_amount, 2, $quotation->currency) }}</span></div>
@endif
@if($quotation->tax_amount > 0)
    <div class="row"><span class="label">Tax:</span><span class="value">{{ $currencyFormatter->formatValue($quotation->tax_amount, 2, $quotation->currency) }}</span></div>
@endif

<div class="rule-solid"></div>

<div class="amount-box">
    <div class="row"><span class="label">TOTAL</span><span class="value">{{ $currencyFormatter->formatValue($quotation->total_amount, 2, $quotation->currency) }}</span></div>
</div>

<div class="rule-solid"></div>

@if($businessProfile?->email || $businessProfile?->location || $businessProfile?->po_box)
    <div class="footer-text">
        @if($businessProfile?->email)<div>{{ $businessProfile->email }}</div>@endif
        @if($businessProfile?->location)<div>{{ $businessProfile->location }}</div>@endif
        @if($businessProfile?->po_box)<div>PO Box: {{ $businessProfile->po_box }}</div>@endif
    </div>
@endif

<div class="footer-text bold">Thank you!</div>

</body>
</html>
