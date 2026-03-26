@extends('reports.print.layout')

@section('title', 'Invoice '.$invoice->invoice_number)

@section('content')
    <div class="header">
        <div>
            <h1>Invoice {{ $invoice->invoice_number }}</h1>
            <p class="meta">Date: {{ $invoice->invoice_date->format('M d, Y') }}</p>
            @if($invoice->due_date)
                <p class="meta">Due Date: {{ $invoice->due_date->format('M d, Y') }}</p>
            @endif
            <p class="meta">Status: {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</p>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Customer</div>
        <p><strong>{{ $invoice->customer->full_name }}</strong></p>
        <p class="muted">{{ $invoice->customer->phone }}</p>
        @if($invoice->customer->email)
            <p class="muted">{{ $invoice->customer->email }}</p>
        @endif
        @if($invoice->customer->address)
            <p class="muted">{{ $invoice->customer->address }}</p>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Items</div>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Description</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Line Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->description ?: '-' }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">{{ number_format($item->line_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Totals</div>
        <table>
            <tbody>
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">{{ number_format($invoice->subtotal_amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Discount</td>
                    <td class="text-right">{{ number_format($invoice->discount_amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Tax</td>
                    <td class="text-right">{{ number_format($invoice->tax_amount, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Total</strong></td>
                    <td class="text-right"><strong>{{ number_format($invoice->total_amount, 2) }}</strong></td>
                </tr>
                <tr>
                    <td>Paid</td>
                    <td class="text-right">{{ number_format($invoice->amount_paid, 2) }}</td>
                </tr>
                <tr>
                    <td>Balance Due</td>
                    <td class="text-right">{{ number_format($invoice->balance_due, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($invoice->notes)
        <div class="section">
            <div class="section-title">Notes</div>
            <p>{{ $invoice->notes }}</p>
        </div>
    @endif
@endsection
