@extends('reports.print.layout')

@section('title', 'Profit & Loss Report')

@section('content')
    <div class="header">
        <div>
            <h1>Profit & Loss Summary</h1>
            <p class="meta">{{ $start_date }} to {{ $end_date }}</p>
        </div>
    </div>

    <div class="cards">
        <div class="card"><div class="label">Revenue</div><div class="value">{{ $currencyFormatter->formatValue($revenue, 2) }}</div></div>
        <div class="card"><div class="label">Expenses</div><div class="value">{{ $currencyFormatter->formatValue($total_expenses, 2) }}</div></div>
        <div class="card"><div class="label">Net Position</div><div class="value">{{ $currencyFormatter->formatValue($revenue - $total_expenses, 2) }}</div></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Metric</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total Revenue</td>
                <td class="text-right">{{ $currencyFormatter->formatValue($revenue, 2) }}</td>
            </tr>
            <tr>
                <td>Total Expenses</td>
                <td class="text-right">{{ $currencyFormatter->formatValue($total_expenses, 2) }}</td>
            </tr>
            <tr>
                <td>Net Position</td>
                <td class="text-right">{{ $currencyFormatter->formatValue($revenue - $total_expenses, 2) }}</td>
            </tr>
        </tbody>
    </table>
@endsection
