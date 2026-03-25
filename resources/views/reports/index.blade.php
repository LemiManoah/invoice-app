<x-layouts.app title="Reports">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Business Reports</h1>
        <p class="text-gray-500 dark:text-gray-400">Select a report to view business performance</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Sales Report -->
        <a href="{{ route('reports.sales') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 transition shadow-sm group">
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-lg flex items-center justify-center text-blue-600 mb-4 group-hover:scale-110 transition">
                <i class="fas fa-file-invoice-dollar fa-lg"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Sales Report</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">View invoiced amounts, payments received, and outstanding balances over a specific period.</p>
        </a>

        <!-- Expense Report -->
        <a href="{{ route('reports.expenses') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-red-500 dark:hover:border-red-500 transition shadow-sm group">
            <div class="w-12 h-12 bg-red-50 dark:bg-red-900/20 rounded-lg flex items-center justify-center text-red-600 mb-4 group-hover:scale-110 transition">
                <i class="fas fa-money-bill-wave fa-lg"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Expense Report</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">Track business spending categorized by type to see where your money is going.</p>
        </a>

        <!-- Profit & Loss -->
        <a href="{{ route('reports.profit-loss') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-green-500 dark:hover:border-green-500 transition shadow-sm group">
            <div class="w-12 h-12 bg-green-50 dark:bg-green-900/20 rounded-lg flex items-center justify-center text-green-600 mb-4 group-hover:scale-110 transition">
                <i class="fas fa-chart-line fa-lg"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Profit & Loss</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">A high-level summary of collected revenue vs. expenses to estimate net business position.</p>
        </a>
    </div>
</x-layouts.app>
