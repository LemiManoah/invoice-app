@extends('reports.print.layout')

@section('title', 'Payments Report')

@section('content')
    <div class="header">
        <div>
            <h1>Payments Report</h1>
            <p class="meta">{{ $start_date }} to {{ $end_date }}</p>
        </div>
    </div>

    <div class="cards">
        <div class="card"><div class="label">Total Collected</div><div class="value">{{ $currencyFormatter->formatValue($summary['total_collected'], 2) }}</div></div>
        <div class="card"><div class="label">Payments Count</div><div class="value">{{ $summary['payments_count'] }}</div></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Customer</th>
                <th>Invoice</th>
                <th>Receipt</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                    <td>{{ $payment->invoice->customer->full_name }}</td>
                    <td>{{ $payment->invoice->invoice_number }}</td>
                    <td>{{ $payment->receipt?->receipt_number ?? '-' }}</td>
                    <td class="text-right">{{ $currencyFormatter->formatValue($payment->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="muted">No payments found for this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
