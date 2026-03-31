<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt {{ $receipt->receipt_number }}</title>
    <script>window.addEventListener('load', () => window.print());</script>
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
        @media print { body { padding: 20px; } }

        /* Header */
        .doc-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 32px;
        }
        .doc-title {
            font-size: 56px; font-weight: 900;
            color: #111827; line-height: 1; letter-spacing: -1px;
        }
        .doc-logo { max-height: 84px; max-width: 180px; object-fit: contain; }
        .doc-logo-fallback {
            width: 80px; height: 80px; background: #e5e7eb; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; font-weight: 700; color: #111827;
        }

        /* FROM / RECEIVED FROM / META row */
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

        /* Table */
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #f97316; }
        thead th {
            padding: 10px 12px; color: #fff;
            font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;
            text-align: left;
        }
        thead th.right { text-align: right; }
        tbody tr { border-bottom: 1px solid #e5e7eb; }
        tbody td { padding: 11px 12px; font-size: 13px; vertical-align: top; }
        tbody td.right { text-align: right; }

        /* Amount received */
        .amount-label {
            background: #111827; color: #f97316;
            font-size: 14px; font-weight: 800; text-transform: uppercase;
            text-align: right; padding: 12px;
        }
        .amount-value {
            background: #111827; color: #f97316;
            font-size: 14px; font-weight: 800;
            text-align: right; padding: 12px;
            min-width: 140px;
        }

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

{{-- Header --}}
<div class="doc-header">
    <div class="doc-title">RECEIPT</div>
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

{{-- FROM / RECEIVED FROM / META --}}
<div class="info-row">
    <div class="info-col">
        <div class="info-label">Receipt From:</div>
        @if($businessProfile)
            <div class="info-name">{{ $businessProfile->name }}</div>
            @if($businessProfile->address)<div class="info-detail">{{ $businessProfile->address }}</div>@endif
            @if($businessProfile->contacts)<div class="info-detail">{{ $businessProfile->contacts }}</div>@endif
            @if($businessProfile->email)<div class="info-detail">{{ $businessProfile->email }}</div>@endif
            @if($businessProfile->location)<div class="info-detail">{{ $businessProfile->location }}</div>@endif
        @endif
    </div>
    <div class="info-col">
        <div class="info-label">Received From:</div>
        <div class="info-name">{{ $receipt->payment->invoice->customer->full_name }}</div>
        @if($receipt->payment->invoice->customer->phone)
            <div class="info-detail">{{ $receipt->payment->invoice->customer->phone }}</div>
        @endif
        @if($receipt->payment->invoice->customer->address)
            <div class="info-detail">{{ $receipt->payment->invoice->customer->address }}</div>
        @endif
    </div>
    <div class="info-col text-right">
        <div class="meta-line">Number: <strong>{{ $receipt->receipt_number }}</strong></div>
        <div class="meta-line">Date: <strong>{{ $receipt->issued_date->format('M d, Y') }}</strong></div>
        <div class="meta-line">Invoice: <strong>{{ $receipt->payment->invoice->invoice_number }}</strong></div>
    </div>
</div>

{{-- Payment details --}}
<table>
    <thead>
        <tr>
            <th>Description</th>
            <th style="width: 160px;">Payment Method</th>
            <th style="width: 140px;">Reference</th>
            <th class="right" style="width: 140px;">Amount</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Payment for invoice {{ $receipt->payment->invoice->invoice_number }}</td>
            <td>{{ $receipt->payment->payment_method }}</td>
            <td>{{ $receipt->payment->reference_number ?: '—' }}</td>
            <td class="right">{{ $currencyFormatter->formatValue($receipt->payment->amount, 2) }}</td>
        </tr>

        {{-- Gap --}}
        <tr style="border: none;">
            <td colspan="4" style="padding: 10px; border: none;"></td>
        </tr>

        {{-- Amount received --}}
        <tr style="border: none;">
            <td colspan="2" style="border: none;"></td>
            <td class="amount-label">AMOUNT RECEIVED</td>
            <td class="amount-value">{{ $currencyFormatter->formatValue($receipt->payment->amount, 2) }}</td>
        </tr>
    </tbody>
</table>

@if($businessProfile?->signature_path)
    <div class="signature-section">
        <div>
            <img src="{{ \Illuminate\Support\Facades\Storage::url($businessProfile->signature_path) }}" alt="Signature" class="signature-img">
            <div class="signature-line">Authorised Signature</div>
        </div>
    </div>
@endif

@include('print.partials.business-footer')
</body>
</html>
