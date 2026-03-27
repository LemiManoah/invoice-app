<x-layouts.app title="Profit & Loss Report">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('reports.index') }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Back to Reports
            </a>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Profit & Loss Summary</h1>
        </div>
        
        <form action="{{ route('reports.profit-loss') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">From</label>
                <input type="date" name="start_date" value="{{ $start_date }}" 
                    class="px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-[10px] uppercase font-bold text-gray-500 mb-1">To</label>
                <input type="date" name="end_date" value="{{ $end_date }}" 
                    class="px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white">
            </div>
            <button type="submit" class="px-4 py-1.5 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition">
                Filter
            </button>
            <a href="{{ route('reports.profit-loss.print', request()->only('start_date', 'end_date')) }}" class="px-4 py-1.5 bg-gray-700 text-white rounded text-sm hover:bg-gray-800 transition" target="_blank">
                Print
            </a>
        </form>
    </div>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-widest text-center">Financial Summary</h2>
                <p class="text-center text-gray-500 text-sm mt-1">{{ Carbon\Carbon::parse($start_date)->format('M d, Y') }} - {{ Carbon\Carbon::parse($end_date)->format('M d, Y') }}</p>
            </div>
            
            <div class="p-8 space-y-8">
                <!-- Revenue -->
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Total Revenue</h3>
                        <p class="text-sm text-gray-500">Payments collected in this period</p>
                    </div>
                    <span class="text-2xl font-bold text-green-600 font-mono">+{{ $currencyFormatter->formatValue($revenue, 2) }}</span>
                </div>

                <!-- Expenses -->
                <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-8">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Total Expenses</h3>
                        <p class="text-sm text-gray-500">Business spending in this period</p>
                    </div>
                    <span class="text-2xl font-bold text-red-600 font-mono">-{{ $currencyFormatter->formatValue($total_expenses, 2) }}</span>
                </div>

                <!-- Net Position -->
                <div class="flex justify-between items-center pt-2">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase">Net Position</h3>
                        <p class="text-sm text-gray-500 italic">Estimated cash flow position</p>
                    </div>
                    <span @class([
                        'text-3xl font-black font-mono',
                        'text-green-600' => ($revenue - $total_expenses) >= 0,
                        'text-red-600' => ($revenue - $total_expenses) < 0,
                    ])>
                        {{ $currencyFormatter->formatValue($revenue - $total_expenses, 2) }}
                    </span>
                </div>
            </div>

            <div class="px-8 py-4 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700 text-center">
                <p class="text-xs text-gray-400 italic">Note: This is a cash-based estimate and may not include unpaid invoices or future liabilities.</p>
            </div>
        </div>
    </div>
</x-layouts.app>
