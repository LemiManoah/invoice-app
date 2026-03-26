<x-layouts.app title="Reports">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Business Reports</h1>
        <p class="text-gray-500 dark:text-gray-400">Select a report to view business performance</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <!-- Sales Report -->
        <a href="{{ route('reports.sales') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 transition shadow-sm group">
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-lg flex items-center justify-center text-blue-600 mb-4 group-hover:scale-110 transition">
                <i class="fas fa-file-invoice-dollar fa-lg"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Sales Report</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">View invoiced amounts, payments received, and outstanding balances over a specific period.</p>
            <span class="inline-flex mt-4 px-3 py-1.5 bg-blue-600 text-white rounded text-sm">Open report</span>
        </a>

        <!-- Expense Report -->
        <a href="{{ route('reports.expenses') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-red-500 dark:hover:border-red-500 transition shadow-sm group">
            <div class="w-12 h-12 bg-red-50 dark:bg-red-900/20 rounded-lg flex items-center justify-center text-red-600 mb-4 group-hover:scale-110 transition">
                <i class="fas fa-money-bill-wave fa-lg"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Expense Report</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">Track business spending categorized by type to see where your money is going.</p>
            <span class="inline-flex mt-4 px-3 py-1.5 bg-red-600 text-white rounded text-sm">Open report</span>
        </a>

        <!-- Payments Report -->
        <a href="{{ route('reports.payments') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-emerald-500 dark:hover:border-emerald-500 transition shadow-sm group">
            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg flex items-center justify-center text-emerald-600 mb-4 group-hover:scale-110 transition">
                <i class="fas fa-money-check-dollar fa-lg"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Payments Report</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">Review payment collections, receipt links, and payment volume for any period.</p>
            <span class="inline-flex mt-4 px-3 py-1.5 bg-emerald-600 text-white rounded text-sm">Open report</span>
        </a>

        <!-- Outstanding Balances -->
        <a href="{{ route('reports.outstanding-balances') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-amber-500 dark:hover:border-amber-500 transition shadow-sm group">
            <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/20 rounded-lg flex items-center justify-center text-amber-600 mb-4 group-hover:scale-110 transition">
                <i class="fas fa-hand-holding-dollar fa-lg"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Outstanding Balances</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">See all unpaid and overdue invoices with balances still due.</p>
            <span class="inline-flex mt-4 px-3 py-1.5 bg-amber-600 text-white rounded text-sm">Open report</span>
        </a>

        <!-- Customer Statement -->
        <a href="{{ route('reports.customer-statement') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-sky-500 dark:hover:border-sky-500 transition shadow-sm group">
            <div class="w-12 h-12 bg-sky-50 dark:bg-sky-900/20 rounded-lg flex items-center justify-center text-sky-600 mb-4 group-hover:scale-110 transition">
                <i class="fas fa-address-card fa-lg"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Customer Statement</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">View invoice and payment history for one customer and confirm the current balance.</p>
            <span class="inline-flex mt-4 px-3 py-1.5 bg-sky-600 text-white rounded text-sm">Open report</span>
        </a>

        <!-- Profit & Loss -->
        <a href="{{ route('reports.profit-loss') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-green-500 dark:hover:border-green-500 transition shadow-sm group">
            <div class="w-12 h-12 bg-green-50 dark:bg-green-900/20 rounded-lg flex items-center justify-center text-green-600 mb-4 group-hover:scale-110 transition">
                <i class="fas fa-chart-line fa-lg"></i>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Profit & Loss</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">A high-level summary of collected revenue vs. expenses to estimate net business position.</p>
            <span class="inline-flex mt-4 px-3 py-1.5 bg-green-600 text-white rounded text-sm">Open report</span>
        </a>
    </div>
</x-layouts.app>
