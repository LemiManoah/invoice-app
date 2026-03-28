<x-layouts.app title="Expense Report">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('reports.index') }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Back to Reports
            </a>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Expense Report</h1>
        </div>
        
        <form action="{{ route('reports.expenses') }}" method="GET" class="flex flex-wrap items-end gap-3">
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
            <a href="{{ route('reports.expenses.print', request()->only('start_date', 'end_date')) }}" class="px-4 py-1.5 bg-gray-700 text-white rounded text-sm hover:bg-gray-800 transition" target="_blank">
                Print
            </a>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Expenses by Category -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">By Category</h2>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-gray-700">
                    @foreach($by_category as $category)
                        <div class="px-6 py-4 flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $category['name'] }}</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white font-mono">{{ $currencyFormatter->formatValue($category['total'], 2) }}</span>
                        </div>
                    @endforeach
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center font-bold">
                        <span class="text-sm text-gray-900 dark:text-white uppercase">Total</span>
                        <span class="text-lg text-red-600 font-mono">{{ $currencyFormatter->formatValue($expenses->sum('amount'), 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expense Transactions List -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Transactions</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($expenses as $expense)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $expense->expense_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                        <p class="font-medium">{{ $expense->description }}</p>
                                        <p class="text-xs text-gray-500">{{ $expense->category->name }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600 font-mono font-bold">
                                        {{ $currencyFormatter->formatValue($expense->amount, 2, $expense->currency) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No expenses recorded for this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
