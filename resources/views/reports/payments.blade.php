<x-layouts.app title="Payments Report">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('reports.index') }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Back to Reports
            </a>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Payments Report</h1>
        </div>

        <form action="{{ route('reports.payments') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">From</label>
                <input type="date" name="start_date" value="{{ $start_date }}" class="px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">To</label>
                <input type="date" name="end_date" value="{{ $end_date }}" class="px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white">
            </div>
            <button type="submit" class="px-4 py-1.5 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition">Filter</button>
            <a href="{{ route('reports.payments.print', request()->only('start_date', 'end_date')) }}" class="px-4 py-1.5 bg-gray-700 text-white rounded text-sm hover:bg-gray-800 transition" target="_blank">Print</a>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Total Collected</p>
            <p class="text-xl font-bold text-green-600">{{ $currencyFormatter->formatValue($summary['total_collected'], 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Payments Count</p>
            <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $summary['payments_count'] }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Receipt</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($payments as $payment)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $payment->payment_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $payment->invoice->customer->full_name }}</td>
                            <td class="px-6 py-4 text-sm text-blue-600 dark:text-blue-400"><a href="{{ route('invoices.show', $payment->invoice) }}">{{ $payment->invoice->invoice_number }}</a></td>
                            <td class="px-6 py-4 text-sm text-blue-600 dark:text-blue-400">
                                @if($payment->receipt)
                                    <a href="{{ route('receipts.show', $payment->receipt) }}">{{ $payment->receipt->receipt_number }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-right text-green-600 font-mono font-bold">{{ $currencyFormatter->formatValue($payment->amount, 2, $payment->currency) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No payments found for this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
