<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Invoice {{ $invoice->invoice_number }}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <style type="text/css">
            html {
                font-family: sans-serif;
                line-height: 1.15;
                margin: 0;
            }

            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
                font-weight: 400;
                line-height: 1.5;
                color: #212529;
                text-align: left;
                background-color: #fff;
                font-size: 14px;
                margin: 20px auto;
                max-width: 1000px;
            }

            h4 {
                margin-top: 0;
                margin-bottom: 0.5rem;
            }

            p {
                margin-top: 0;
                margin-bottom: 1rem;
            }

            strong {
                font-weight: bolder;
            }

            img {
                vertical-align: middle;
                border-style: none;
            }

            table {
                border-collapse: collapse;
            }

            th {
                text-align: inherit;
            }

            h4, .h4 {
                margin-bottom: 0.5rem;
                font-weight: 500;
                line-height: 1.2;
            }

            h4, .h4 {
                font-size: 1.5rem;
            }

            .table {
                width: 100%;
                margin-bottom: 1rem;
                color: #212529;
            }

            .table th,
            .table td {
                padding: 0.75rem;
                vertical-align: top;
            }

            .table.table-items td {
                border-top: 1px solid #dee2e6;
            }

            .table thead th {
                vertical-align: bottom;
                border-bottom: 2px solid #dee2e6;
            }

            .mt-5 {
                margin-top: 3rem !important;
            }

            .pr-0,
            .px-0 {
                padding-right: 0 !important;
            }

            .pl-0,
            .px-0 {
                padding-left: 0 !important;
            }

            .text-right {
                text-align: right !important;
            }

            .text-center {
                text-align: center !important;
            }

            .text-uppercase {
                text-transform: uppercase !important;
            }
            * {
                font-family: "DejaVu Sans", sans-serif;
            }
            body, h1, h2, h3, h4, h5, h6, table, th, tr, td, p, div {
                line-height: 1.1;
            }
            .party-header {
                font-size: 1.5rem;
                font-weight: 400;
            }
            .total-amount {
                font-size: 12px;
                font-weight: 700;
            }
            .border-0 {
                border: none !important;
            }
            .cool-gray {
                color: #6B7280;
            }

            /* Print actions */
            .actions { margin-bottom: 16px; font-size: 14px; }
            .actions button, .actions a { 
                padding: 8px 12px; border: 0; background: #111827; 
                color: white; text-decoration: none; cursor: pointer; 
                margin-right: 8px; border-radius: 4px; display: inline-block;
            }
            @media print {
                .actions { display: none; }
                body { margin: 12px; }
            }
        </style>
    </head>

    <body>
        <div class="actions">
            <button type="button" onclick="window.print()">Print</button>
            <a href="javascript:window.close()">Close</a>
        </div>

        {{-- Header --}}
        @if(config('app.logo'))
            <img src="{{ config('app.logo') }}" alt="logo" height="100">
        @endif

        <table class="table mt-5">
            <tbody>
                <tr>
                    <td class="border-0 pl-0" width="70%">
                        <h4 class="text-uppercase">
                            <strong>Invoice {{ $invoice->invoice_number }}</strong>
                        </h4>
                    </td>
                    <td class="border-0 pl-0">
                        @if($invoice->status)
                            <h4 class="text-uppercase cool-gray">
                                <strong>{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</strong>
                            </h4>
                        @endif
                        <p>Invoice Number: <strong>{{ $invoice->invoice_number }}</strong></p>
                        <p>Date: <strong>{{ $invoice->invoice_date->format('M d, Y') }}</strong></p>
                        @if($invoice->due_date)
                            <p>Due Date: <strong>{{ $invoice->due_date->format('M d, Y') }}</strong></p>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Seller - Buyer --}}
        <table class="table">
            <thead>
                <tr>
                    <th class="border-0 pl-0 party-header" width="48.5%">
                        From
                    </th>
                    <th class="border-0" width="3%"></th>
                    <th class="border-0 pl-0 party-header">
                        Bill To
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-0">
                        <p class="seller-name">
                            <strong>{{ config('app.name') }}</strong>
                        </p>
                    </td>
                    <td class="border-0"></td>
                    <td class="px-0">
                        @if($invoice->customer->full_name)
                            <p class="buyer-name">
                                <strong>{{ $invoice->customer->full_name }}</strong>
                            </p>
                        @endif

                        @if($invoice->customer->address)
                            <p class="buyer-address">
                                Address: {{ $invoice->customer->address }}
                            </p>
                        @endif

                        @if($invoice->customer->phone)
                            <p class="buyer-phone">
                                Phone: {{ $invoice->customer->phone }}
                            </p>
                        @endif

                        @if($invoice->customer->email)
                            <p class="buyer-email">
                                Email: {{ $invoice->customer->email }}
                            </p>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Table --}}
        <table class="table table-items">
            <thead>
                <tr>
                    <th scope="col" class="border-0 pl-0">Description</th>
                    <th scope="col" class="text-center border-0">Quantity</th>
                    <th scope="col" class="text-right border-0">Price</th>
                    <th scope="col" class="text-right border-0 pr-0">Line Total</th>
                </tr>
            </thead>
            <tbody>
                {{-- Items --}}
                @foreach($invoice->items as $item)
                <tr>
                    <td class="pl-0">
                        {{ $item->item_name }}

                        @if($item->description)
                            <p class="cool-gray" style="margin-top: 0.25rem; font-size: 0.85em;">{{ $item->description }}</p>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">
                        {{ $currencyFormatter->formatValue($item->unit_price, 2, $invoice->currency) }}
                    </td>
                    <td class="text-right pr-0">
                        {{ $currencyFormatter->formatValue($item->line_total, 2, $invoice->currency) }}
                    </td>
                </tr>
                @endforeach
                {{-- Summary --}}
                <tr>
                    <td colspan="2" class="border-0"></td>
                    <td class="text-right pl-0">Subtotal</td>
                    <td class="text-right pr-0">
                        {{ $currencyFormatter->formatValue($invoice->subtotal_amount, 2, $invoice->currency) }}
                    </td>
                </tr>
                
                @if($invoice->discount_amount > 0)
                    <tr>
                        <td colspan="2" class="border-0"></td>
                        <td class="text-right pl-0">Discount</td>
                        <td class="text-right pr-0" style="color: red;">
                            -{{ $currencyFormatter->formatValue($invoice->discount_amount, 2, $invoice->currency) }}
                        </td>
                    </tr>
                @endif
                
                @if($invoice->tax_amount > 0)
                    <tr>
                        <td colspan="2" class="border-0"></td>
                        <td class="text-right pl-0">Tax</td>
                        <td class="text-right pr-0">
                            {{ $currencyFormatter->formatValue($invoice->tax_amount, 2, $invoice->currency) }}
                        </td>
                    </tr>
                @endif
                
                <tr>
                    <td colspan="2" class="border-0"></td>
                    <td class="text-right pl-0"><strong>Invoice Total</strong></td>
                    <td class="text-right pr-0 total-amount">
                        {{ $currencyFormatter->formatValue($invoice->total_amount, 2, $invoice->currency) }}
                    </td>
                </tr>

                @if($invoice->amount_paid > 0)
                    <tr>
                        <td colspan="2" class="border-0"></td>
                        <td class="text-right pl-0">Amount Paid</td>
                        <td class="text-right pr-0" style="color: green;">
                            {{ $currencyFormatter->formatValue($invoice->amount_paid, 2, $invoice->currency) }}
                        </td>
                    </tr>
                @endif

                <tr>
                    <td colspan="2" class="border-0"></td>
                    <td class="text-right pl-0"><strong>Balance Due</strong></td>
                    <td class="text-right pr-0 total-amount" style="color: #dc2626;">
                        {{ $currencyFormatter->formatValue($invoice->balance_due, 2, $invoice->currency) }}
                    </td>
                </tr>
            </tbody>
        </table>

        @if($invoice->notes)
            <p>
                <strong>Notes</strong>: {!! nl2br(e($invoice->notes)) !!}
            </p>
        @endif

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
