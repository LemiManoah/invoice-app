<x-layouts.app title="Payment {{ $payment->id }}">
    <div class="mb-6">
        <a href="{{ route('payments.index') }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Payments
        </a>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Payment Details</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6 space-y-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Customer</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $payment->invoice->customer->full_name }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Invoice</p>
                <p class="text-lg font-semibold text-blue-600 dark:text-blue-400"><a href="{{ route('invoices.show', $payment->invoice) }}">{{ $payment->invoice->invoice_number }}</a></p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Method</p>
                <p class="text-gray-900 dark:text-white">{{ $payment->payment_method }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Reference</p>
                <p class="text-gray-900 dark:text-white">{{ $payment->reference_number ?: 'N/A' }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6 space-y-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Payment Date</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $payment->payment_date->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Amount</p>
                <p class="text-2xl font-bold {{ $payment->status === 'valid' ? 'text-green-600' : 'text-red-600' }}">{{ $currencyFormatter->formatValue($payment->amount, 2, $payment->currency) }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</p>
                <p class="text-gray-900 dark:text-white">{{ ucfirst($payment->status) }}</p>
            </div>
            @if($payment->receipt)
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Receipt</p>
                    <p class="text-blue-600 dark:text-blue-400"><a href="{{ route('receipts.show', $payment->receipt) }}">{{ $payment->receipt->receipt_number }}</a></p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
