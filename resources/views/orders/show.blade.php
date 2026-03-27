<x-layouts.app title="Order {{ $order->order_number }}">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Back to Orders
            </a>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $order->order_number }}</h1>
                <span @class([
                    'px-2 py-1 text-xs rounded-full font-medium',
                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' => $order->status === 'draft',
                    'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' => $order->status === 'confirmed',
                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' => in_array($order->status, ['in_cutting', 'in_stitching', 'in_finishing']),
                    'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' => $order->status === 'delivered',
                ])>
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            @if(!$order->invoice)
                @can('create', \App\Models\Invoice::class)
                    <a href="{{ route('invoices.create', ['customer_id' => $order->customer_id, 'order_id' => $order->id]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                        <i class="fas fa-file-invoice-dollar mr-2"></i> Generate Invoice
                    </a>
                @endcan
            @else
                @can('view', $order->invoice)
                    <a href="{{ route('invoices.show', $order->invoice) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                        <i class="fas fa-file-invoice mr-2"></i> View Invoice
                    </a>
                @endcan
            @endif

            @can('update', $order)
                <a href="{{ route('orders.edit', $order) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition">
                    <i class="fas fa-edit mr-2"></i> Edit Order
                </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details & Items -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-start">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Customer Details</h2>
                        <p class="mt-2 font-semibold text-gray-900 dark:text-white">
                            <a href="{{ route('customers.show', $order->customer) }}" class="hover:text-blue-600 transition">
                                {{ $order->customer->full_name }}
                            </a>
                        </p>
                        <p class="text-gray-500 dark:text-gray-400">{{ $order->customer->phone }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-500 dark:text-gray-400">Order Date: <span class="font-semibold text-gray-900 dark:text-white">{{ $order->order_date->format('M d, Y') }}</span></p>
                        @if($order->promised_delivery_date)
                            <p class="text-gray-500 dark:text-gray-400">Promised: <span class="font-semibold text-gray-900 dark:text-white">{{ $order->promised_delivery_date->format('M d, Y') }}</span></p>
                        @endif
                        <p class="mt-2">
                            <span @class([
                                'px-2 py-0.5 text-[10px] uppercase font-bold rounded',
                                'bg-red-100 text-red-600' => $order->priority === 'urgent' || $order->priority === 'high',
                                'bg-blue-100 text-blue-600' => $order->priority === 'medium',
                                'bg-gray-100 text-gray-600' => $order->priority === 'low',
                            ])>
                                {{ $order->priority }} Priority
                            </span>
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Garment Type</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Style & Fabric Notes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $item->garment_type }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($item->style_notes)
                                            <p class="text-sm text-gray-900 dark:text-white"><span class="font-bold text-xs uppercase text-gray-400">Style:</span> {{ $item->style_notes }}</p>
                                        @endif
                                        @if($item->fabric_details)
                                            <p class="text-sm text-gray-600 dark:text-gray-400"><span class="font-bold text-xs uppercase text-gray-400">Fabric:</span> {{ $item->fabric_details }}</p>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($order->notes)
                <div class="bg-gray-50 dark:bg-gray-900/30 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">General Order Notes:</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Sidebar: Production Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 uppercase tracking-wider">Workflow</h2>
                
                <div class="space-y-6">
                    <div>
                        <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-2">Assignee</p>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                {{ substr($order->assignee->name ?? 'U', 0, 1) }}
                            </div>
                            <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $order->assignee->name ?? 'Unassigned' }}</span>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold mb-2">Production Status</p>
                        @can('update', $order)
                            <form action="{{ route('orders.update', $order) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="customer_id" value="{{ $order->customer_id }}">
                                <input type="hidden" name="order_date" value="{{ $order->order_date->format('Y-m-d') }}">
                                <input type="hidden" name="priority" value="{{ $order->priority }}">
                                
                                <select name="status" onchange="this.form.submit()" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="in_cutting" {{ $order->status === 'in_cutting' ? 'selected' : '' }}>In Cutting</option>
                                    <option value="in_stitching" {{ $order->status === 'in_stitching' ? 'selected' : '' }}>In Stitching</option>
                                    <option value="in_finishing" {{ $order->status === 'in_finishing' ? 'selected' : '' }}>In Finishing</option>
                                    <option value="ready_for_delivery" {{ $order->status === 'ready_for_delivery' ? 'selected' : '' }}>Ready for Delivery</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>
                        @else
                            <p class="text-sm text-gray-900 dark:text-white font-medium">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                        @endcan
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                        <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Created By</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $order->creator->name ?? 'System' }} on {{ $order->created_at?->format('M d, Y') ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
