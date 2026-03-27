@extends('reports.print.layout')

@section('title', 'Sales Report')

@section('content')
    <div class="header">
        <div>
            <h1>Sales Report</h1>
            <p class="meta">{{ $start_date }} to {{ $end_date }}</p>
        </div>
    </div>

    <div class="cards">
        <div class="card"><div class="label">Total Invoiced</div><div class="value">{{ $currencyFormatter->formatValue($summary['total_invoiced'], 2) }}</div></div>
        <div class="card"><div class="label">Total Paid</div><div class="value">{{ $currencyFormatter->formatValue($summary['total_paid'], 2) }}</div></div>
        <div class="card"><div class="label">Balance Due</div><div class="value">{{ $currencyFormatter->formatValue($summary['total_balance'], 2) }}</div></div>
        <div class="card"><div class="label">Invoice Count</div><div class="value">{{ $summary['invoice_count'] }}</div></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Invoice #</th>
                <th>Customer</th>
                <th class="text-right">Total</th>
                <th class="text-right">Paid</th>
                <th class="text-right">Balance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->customer->full_name }}</td>
                    <td class="text-right">{{ $currencyFormatter->formatValue($invoice->total_amount, 2) }}</td>
                    <td class="text-right">{{ $currencyFormatter->formatValue($invoice->amount_paid, 2) }}</td>
                    <td class="text-right">{{ $currencyFormatter->formatValue($invoice->balance_due, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="muted">No data found for this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
