<x-layouts.app title="Edit Order">
    <div class="mb-6">
        <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Order
        </a>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Order</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
        <form action="{{ route('orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer *</label>
                    <select name="customer_id" id="customer_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" @selected(old('customer_id', $order->customer_id) == $customer->id)>{{ $customer->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="currency_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Currency *</label>
                    <select name="currency_id" id="currency_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        @foreach($currencies as $currency)
                            <option value="{{ $currency->id }}" @selected(old('currency_id', $order->currency_id ?? $activeCurrency->id) == $currency->id)>{{ $currency->code }} - {{ $currency->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="order_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Order Date *</label>
                    <input type="date" name="order_date" id="order_date" value="{{ old('order_date', $order->order_date->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                </div>
                <div>
                    <label for="promised_delivery_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Promised Delivery Date</label>
                    <input type="date" name="promised_delivery_date" id="promised_delivery_date" value="{{ old('promised_delivery_date', $order->promised_delivery_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                    <select name="status" id="status" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        @foreach(['draft', 'confirmed', 'in_cutting', 'in_stitching', 'in_finishing', 'awaiting_fitting', 'ready_for_delivery', 'delivered', 'cancelled'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $order->status) === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority *</label>
                    <select name="priority" id="priority" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                        @foreach(['low', 'medium', 'high', 'urgent'] as $priority)
                            <option value="{{ $priority }}" @selected(old('priority', $order->priority) === $priority)>{{ ucfirst($priority) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">{{ old('notes', $order->notes) }}</textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Update Order</button>
            </div>
        </form>
    </div>
</x-layouts.app>
