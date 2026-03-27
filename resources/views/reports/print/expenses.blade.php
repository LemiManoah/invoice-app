@extends('reports.print.layout')

@section('title', 'Expense Report')

@section('content')
    <div class="header">
        <div>
            <h1>Expense Report</h1>
            <p class="meta">{{ $start_date }} to {{ $end_date }}</p>
        </div>
    </div>

    <div class="cards">
        <div class="card"><div class="label">Total Expenses</div><div class="value">{{ $currencyFormatter->formatValue($expenses->sum('amount'), 2) }}</div></div>
        <div class="card"><div class="label">Categories</div><div class="value">{{ count($by_category) }}</div></div>
    </div>

    <div class="section">
        <h2 class="section-title">By Category</h2>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($by_category as $category)
                    <tr>
                        <td>{{ $category['name'] }}</td>
                        <td class="text-right">{{ $currencyFormatter->formatValue($category['total'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2 class="section-title">Transactions</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                    <tr>
                        <td>{{ $expense->expense_date->format('M d, Y') }}</td>
                        <td>{{ $expense->description }}</td>
                        <td>{{ $expense->category->name }}</td>
                        <td class="text-right">{{ $currencyFormatter->formatValue($expense->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="muted">No expenses recorded for this period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
