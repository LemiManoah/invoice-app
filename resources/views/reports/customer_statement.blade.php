<x-layouts.app title="Customer Statement">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('reports.index') }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Back to Reports
            </a>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Customer Statement</h1>
        </div>
        @if($customer)
            <a href="{{ route('reports.customer-statement.print', request()->only('customer_id', 'start_date', 'end_date')) }}" class="px-4 py-2 bg-gray-700 text-white rounded text-sm hover:bg-gray-800 transition self-start md:self-auto" target="_blank">
                Print
            </a>
        @endif
    </div>

    <form action="{{ route('reports.customer-statement') }}" method="GET" class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer</label>
                <select name="customer_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    <option value="">Select Customer</option>
                    @foreach($customers as $listCustomer)
                        <option value="{{ $listCustomer->id }}" @selected(request('customer_id') == $listCustomer->id)>{{ $listCustomer->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From</label>
                <input type="date" name="start_date" value="{{ $start_date }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To</label>
                <input type="date" name="end_date" value="{{ $end_date }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
            </div>
        </div>
        <div class="mt-4 flex justify-end">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Load Statement</button>
        </div>
    </form>

    @if($customer)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Total Invoiced</p>
                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $currencyFormatter->formatValue($summary['total_invoiced'], 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Total Paid</p>
                <p class="text-xl font-bold text-green-600">{{ $currencyFormatter->formatValue($summary['total_paid'], 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Current Balance</p>
                <p class="text-xl font-bold text-red-600">{{ $currencyFormatter->formatValue($summary['balance_due'], 2) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Invoices</h2>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($invoices as $invoice)
                        <div class="px-6 py-4 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-bold text-blue-600 dark:text-blue-400"><a href="{{ route('invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $invoice->invoice_date->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900 dark:text-white font-mono">{{ $currencyFormatter->formatValue($invoice->total_amount, 2, $invoice->currency) }}</p>
                                <p class="text-xs text-red-600">Bal {{ $currencyFormatter->formatValue($invoice->balance_due, 2, $invoice->currency) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">No invoices in this period.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Payments</h2>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($payments as $payment)
                        <div class="px-6 py-4 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $payment->payment_date->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $payment->invoice->invoice_number }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-green-600 font-mono">{{ $currencyFormatter->formatValue($payment->amount, 2, $payment->currency) }}</p>
                                @if($payment->receipt)
                                    <a href="{{ route('receipts.show', $payment->receipt) }}" class="text-xs text-blue-600 dark:text-blue-400">{{ $payment->receipt->receipt_number }}</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">No payments in this period.</div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</x-layouts.app>
