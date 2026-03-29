<x-layouts.app title="Dashboard">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">

        <!-- Invoices Today -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Invoices Issued</p>
            <div class="flex items-center justify-between">
                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['invoices_issued_today'] }}</span>
                <div class="p-2 bg-indigo-50 dark:bg-indigo-900/20 rounded text-indigo-600">
                    <i class="fas fa-file-invoice text-sm"></i>
                </div>
            </div>
        </div>

        <!-- Expenses Today -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Expenses Today</p>
            <div class="flex items-center justify-between">
                <span class="text-xl font-bold text-red-600">{{ $currencyFormatter->formatValue($stats['expenses_today'], 2) }}</span>
                <div class="p-2 bg-red-50 dark:bg-red-900/20 rounded text-red-600">
                    <i class="fas fa-money-bill-wave text-sm"></i>
                </div>
            </div>
        </div>

        <!-- Collected Today -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Collected Today</p>
            <div class="flex items-center justify-between">
                <span class="text-xl font-bold text-green-600">{{ $currencyFormatter->formatValue($stats['collected_today'], 2) }}</span>
                <div class="p-2 bg-green-50 dark:bg-green-900/20 rounded text-green-600">
                    <i class="fas fa-cash-register text-sm"></i>
                </div>
            </div>
        </div>

        <!-- Unpaid Balances -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Unpaid Balances</p>
            <div class="flex items-center justify-between">
                <span class="text-xl font-bold text-red-600">{{ $currencyFormatter->formatValue($stats['unpaid_balances'], 2) }}</span>
                <div class="p-2 bg-red-50 dark:bg-red-900/20 rounded text-red-600">
                    <i class="fas fa-hand-holding-usd text-sm"></i>
                </div>
            </div>
        </div>

        <!-- Overdue Invoices -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Overdue Invoices</p>
            <div class="flex items-center justify-between">
                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['overdue_invoices'] }}</span>
                <div class="p-2 bg-yellow-50 dark:bg-yellow-900/20 rounded text-yellow-600">
                    <i class="fas fa-triangle-exclamation text-sm"></i>
                </div>
            </div>
        </div>

        <!-- Active Orders -->
        <div class="bg-white dark:bg-gray-800 p-5 rounded-lg border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Active Orders</p>
            <div class="flex items-center justify-between">
                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['active_orders'] }}</span>
                <div class="p-2 bg-emerald-50 dark:bg-emerald-900/20 rounded text-emerald-600">
                    <i class="fas fa-cut text-sm"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Orders -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Recent Orders</h2>
                <a href="{{ route('orders.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 uppercase">View All</a>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($recent_orders as $order)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-900/50 transition">
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">
                                <a href="{{ route('orders.show', $order) }}">{{ $order->customer->full_name }}</a>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->order_number }} • {{ $order->order_date->format('M d, Y') }}</p>
                        </div>
                        <span @class([
                            'px-2 py-0.5 text-[10px] uppercase font-bold rounded',
                            'bg-blue-100 text-blue-800' => $order->status === 'confirmed',
                            'bg-yellow-100 text-yellow-800' => in_array($order->status, ['in_cutting', 'in_stitching', 'in_finishing']),
                        ])>
                            {{ str_replace('_', ' ', $order->status) }}
                        </span>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">No recent orders.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Recent Payments</h2>
                <a href="{{ route('invoices.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 uppercase">View Invoices</a>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($recent_payments as $payment)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-900/50 transition">
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ $payment->invoice->customer->full_name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $payment->invoice->invoice_number }} • {{ $payment->payment_method }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-green-600 font-mono">+{{ $currencyFormatter->formatValue($payment->amount, 2) }}</p>
                            <p class="text-[10px] text-gray-400">{{ $payment->payment_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">No recent payments.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>
