@extends('reports.print.layout')

@section('title', 'Customer Statement')

@section('content')
    <div class="header">
        <div>
            <h1>Customer Statement</h1>
            <p class="meta">{{ $customer?->full_name ?? 'No customer selected' }}</p>
            <p class="meta">{{ $start_date }} to {{ $end_date }}</p>
        </div>
    </div>

    @if($customer)
        <div class="cards">
            <div class="card"><div class="label">Total Invoiced</div><div class="value">{{ $currencyFormatter->formatValue($summary['total_invoiced'], 2) }}</div></div>
            <div class="card"><div class="label">Total Paid</div><div class="value">{{ $currencyFormatter->formatValue($summary['total_paid'], 2) }}</div></div>
            <div class="card"><div class="label">Current Balance</div><div class="value">{{ $currencyFormatter->formatValue($summary['balance_due'], 2) }}</div></div>
        </div>

        <div class="section">
            <h2 class="section-title">Invoices</h2>
            <table>
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Date</th>
                        <th class="text-right">Total</th>
                        <th class="text-right">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                            <td class="text-right">{{ $currencyFormatter->formatValue($invoice->total_amount, 2, $invoice->currency) }}</td>
                            <td class="text-right">{{ $currencyFormatter->formatValue($invoice->balance_due, 2, $invoice->currency) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="muted">No invoices in this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2 class="section-title">Payments</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Invoice</th>
                        <th>Receipt</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                            <td>{{ $payment->invoice->invoice_number }}</td>
                            <td>{{ $payment->receipt?->receipt_number ?? '-' }}</td>
                            <td class="text-right">{{ $currencyFormatter->formatValue($payment->amount, 2, $payment->currency) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="muted">No payments in this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <p class="muted">No customer selected.</p>
    @endif
@endsection
