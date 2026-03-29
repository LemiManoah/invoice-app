<x-layouts.app title="Reports">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Business Reports</h1>
        <p class="text-gray-500 dark:text-gray-400">Select a report to view business performance</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <!-- Sales Report -->
        <a href="{{ route('reports.sales') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition shadow-sm group">
            <div class="text-gray-500 dark:text-gray-400 mb-3 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition">
                <i class="fas fa-file-invoice-dollar fa-lg"></i>
            </div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Sales Report</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">View invoiced amounts, payments received, and outstanding balances over a specific period.</p>
            <span class="inline-flex mt-4 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded text-sm group-hover:bg-gray-200 dark:group-hover:bg-gray-600 transition">Open report</span>
        </a>

        <!-- Expense Report -->
        <a href="{{ route('reports.expenses') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition shadow-sm group">
            <div class="text-gray-500 dark:text-gray-400 mb-3 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition">
                <i class="fas fa-money-bill-wave fa-lg"></i>
            </div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Expense Report</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">Track business spending categorized by type to see where your money is going.</p>
            <span class="inline-flex mt-4 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded text-sm group-hover:bg-gray-200 dark:group-hover:bg-gray-600 transition">Open report</span>
        </a>

        <!-- Payments Report -->
        <a href="{{ route('reports.payments') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition shadow-sm group">
            <div class="text-gray-500 dark:text-gray-400 mb-3 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition">
                <i class="fas fa-money-check-dollar fa-lg"></i>
            </div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Payments Report</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">Review payment collections, receipt links, and payment volume for any period.</p>
            <span class="inline-flex mt-4 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded text-sm group-hover:bg-gray-200 dark:group-hover:bg-gray-600 transition">Open report</span>
        </a>

        <!-- Outstanding Balances -->
        <a href="{{ route('reports.outstanding-balances') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition shadow-sm group">
            <div class="text-gray-500 dark:text-gray-400 mb-3 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition">
                <i class="fas fa-hand-holding-dollar fa-lg"></i>
            </div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Outstanding Balances</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">See all unpaid and overdue invoices with balances still due.</p>
            <span class="inline-flex mt-4 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded text-sm group-hover:bg-gray-200 dark:group-hover:bg-gray-600 transition">Open report</span>
        </a>

        <!-- Customer Statement -->
        <a href="{{ route('reports.customer-statement') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition shadow-sm group">
            <div class="text-gray-500 dark:text-gray-400 mb-3 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition">
                <i class="fas fa-address-card fa-lg"></i>
            </div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Customer Statement</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">View invoice and payment history for one customer and confirm the current balance.</p>
            <span class="inline-flex mt-4 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded text-sm group-hover:bg-gray-200 dark:group-hover:bg-gray-600 transition">Open report</span>
        </a>

        <!-- Profit & Loss -->
        <a href="{{ route('reports.profit-loss') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition shadow-sm group">
            <div class="text-gray-500 dark:text-gray-400 mb-3 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition">
                <i class="fas fa-chart-line fa-lg"></i>
            </div>
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Profit & Loss</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">A high-level summary of collected revenue vs. expenses to estimate net business position.</p>
            <span class="inline-flex mt-4 px-3 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded text-sm group-hover:bg-gray-200 dark:group-hover:bg-gray-600 transition">Open report</span>
        </a>
    </div>
</x-layouts.app>
