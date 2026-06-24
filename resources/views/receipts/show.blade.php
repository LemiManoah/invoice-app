<x-layouts.app title="Receipt {{ $receipt->receipt_number }}">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('payments.show', $receipt->payment) }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Back to Payment
            </a>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $receipt->receipt_number }}</h1>
        </div>
        <a href="{{ route('receipts.print', $receipt) }}" class="px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-800 transition">
            <i class="fas fa-print mr-2"></i> Print Receipt
        </a>
    </div>

    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Receipt Date</p>
            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $receipt->issued_date->format('M d, Y') }}</p>
        </div>
        <div class="px-8 py-6 space-y-4">
            <div class="flex justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-wider font-bold text-gray-500 dark:text-gray-400">Customer</p>
                    <p class="text-gray-900 dark:text-white">{{ $receipt->payment->invoice->customer->full_name }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs uppercase tracking-wider font-bold text-gray-500 dark:text-gray-400">Invoice</p>
                    <p class="text-gray-900 dark:text-white">{{ $receipt->payment->invoice->invoice_number }}</p>
                </div>
            </div>
            <div class="flex justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-wider font-bold text-gray-500 dark:text-gray-400">Payment Method</p>
                    <p class="text-gray-900 dark:text-white">{{ $receipt->payment->payment_method }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs uppercase tracking-wider font-bold text-gray-500 dark:text-gray-400">Reference</p>
                    <p class="text-gray-900 dark:text-white">{{ $receipt->payment->reference_number ?: 'N/A' }}</p>
                </div>
            </div>
            <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <p class="text-base font-bold text-gray-900 dark:text-white uppercase">Amount Received</p>
                <p class="text-2xl font-black text-green-600 font-mono">{{ $currencyFormatter->formatValue($receipt->payment->amount, 2) }}</p>
            </div>
        </div>
    </div>
</x-layouts.app>
