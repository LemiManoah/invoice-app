@extends('reports.print.layout')

@section('title', 'Outstanding Balances Report')

@section('content')
    <div class="header">
        <div>
            <h1>Outstanding Balances</h1>
            <p class="meta">{{ $start_date }} to {{ $end_date }}</p>
        </div>
    </div>

    <div class="cards">
        <div class="card"><div class="label">Customers With Balances</div><div class="value">{{ $summary['customers_with_balances'] }}</div></div>
        <div class="card"><div class="label">Total Outstanding</div><div class="value">{{ $currencyFormatter->formatValue($summary['total_outstanding'], 2) }}</div></div>
        <div class="card"><div class="label">Overdue Total</div><div class="value">{{ $currencyFormatter->formatValue($summary['overdue_total'], 2) }}</div></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Customer</th>
                <th>Due Date</th>
                <th>Status</th>
                <th class="text-right">Balance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->customer->full_name }}</td>
                    <td>{{ $invoice->due_date?->format('M d, Y') ?? 'No due date' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $invoice->status)) }}</td>
                    <td class="text-right">{{ $currencyFormatter->formatValue($invoice->balance_due, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="muted">No outstanding invoices found for this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
