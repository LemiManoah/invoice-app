<x-layouts.app title="Orders">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Orders</h1>
        @can('create', \App\Models\Order::class)
            <a href="{{ route('orders.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm">
                <i class="fas fa-plus mr-2"></i> Create Order
            </a>
        @endcan
    </div>

    <form action="{{ route('orders.index') }}" method="GET" class="mb-6">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by order #, customer name, email or phone"
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                <select name="status" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    <option value="">All statuses</option>
                    <option value="draft" @selected($status === 'draft')>Draft</option>
                    <option value="confirmed" @selected($status === 'confirmed')>Confirmed</option>
                    <option value="in_cutting" @selected($status === 'in_cutting')>In Cutting</option>
                    <option value="in_stitching" @selected($status === 'in_stitching')>In Stitching</option>
                    <option value="in_finishing" @selected($status === 'in_finishing')>In Finishing</option>
                    <option value="ready" @selected($status === 'ready')>Ready</option>
                    <option value="delivered" @selected($status === 'delivered')>Delivered</option>
                    <option value="cancelled" @selected($status === 'cancelled')>Cancelled</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-700 transition text-sm">Filter</button>
            </div>
        </div>
    </form>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Promised Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600 dark:text-blue-400">
                                <a href="{{ route('orders.show', $order) }}">{{ $order->order_number }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $order->customer->full_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $order->order_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $order->promised_delivery_date ? $order->promised_delivery_date->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span @class([
                                    'px-2 py-1 text-xs rounded-full font-medium',
                                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' => $order->status === 'draft',
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' => $order->status === 'confirmed',
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' => in_array($order->status, ['in_cutting', 'in_stitching', 'in_finishing']),
                                    'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' => $order->status === 'delivered',
                                ])>
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-wrap gap-2">
                                    @can('view', $order)
                                        <a href="{{ route('orders.show', $order) }}"
                                            class="inline-flex items-center rounded-md border border-slate-200 px-2.5 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                                            View
                                        </a>
                                    @endcan
                                    @if($order->invoice)
                                        @can('view', $order->invoice)
                                            <a href="{{ route('invoices.show', $order->invoice) }}"
                                                class="inline-flex items-center rounded-md border border-indigo-200 px-2.5 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-50 dark:border-indigo-800 dark:text-indigo-300 dark:hover:bg-indigo-900/30">
                                                Invoice
                                            </a>
                                        @endcan
                                    @else
                                        @can('create', \App\Models\Invoice::class)
                                            <a href="{{ route('invoices.create', ['customer_id' => $order->customer_id, 'order_id' => $order->id]) }}"
                                                class="inline-flex items-center rounded-md border border-indigo-200 px-2.5 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-50 dark:border-indigo-800 dark:text-indigo-300 dark:hover:bg-indigo-900/30">
                                                Generate Invoice
                                            </a>
                                        @endcan
                                    @endif
                                    @can('update', $order)
                                        <a href="{{ route('orders.edit', $order) }}"
                                            class="inline-flex items-center rounded-md border border-amber-200 px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-50 dark:border-amber-800 dark:text-amber-300 dark:hover:bg-amber-900/30">
                                            Edit
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
