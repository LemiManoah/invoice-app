@php
    $initialItems = old('items', $invoiceDefaults['items']);
    $initialDiscount = (float) old('discount_amount', $invoiceDefaults['discount_amount']);
    $initialTax = (float) old('tax_amount', $invoiceDefaults['tax_amount']);
    $currencyStep = $activeCurrency->decimal_places > 0 ? '0.01' : '1';
@endphp

<x-layouts.app title="Create Invoice">
    <div x-data="invoiceForm({{ \Illuminate\Support\Js::from([
        'items' => collect($initialItems)->map(static fn (array $item): array => [
            'item_name' => (string) ($item['item_name'] ?? ''),
            'description' => (string) ($item['description'] ?? ''),
            'quantity' => (int) ($item['quantity'] ?? 1),
            'unit_price' => (float) ($item['unit_price'] ?? 0),
        ])->values()->all(),
        'discount' => $initialDiscount,
        'tax' => $initialTax,
        'currency' => $activeCurrencyConfig,
    ]) }})">
        <div class="mb-6">
            <a href="{{ route('invoices.index') }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Back to Invoices
            </a>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Create New Invoice</h1>
            @if($selectedOrder !== null)
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Prefilled from order <span class="font-semibold text-gray-900 dark:text-white">{{ $selectedOrder->order_number }}</span> for {{ $selectedOrder->customer->full_name ?? 'the selected customer' }}.
                </p>
            @endif
        </div>

        @if($selectedOrder !== null)
            <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800 dark:border-blue-900/50 dark:bg-blue-900/20 dark:text-blue-200">
                The invoice has been preloaded with the linked order items and notes. You can still adjust the pricing, descriptions, and notes before saving.
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-300">
                Please correct the highlighted invoice form errors and try again.
            </div>
        @endif

        <form action="{{ route('invoices.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <div class="lg:col-span-3 space-y-6">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Invoice Items</h2>
                            @error('items')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Qty</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Unit Price</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Total</th>
                                        <th class="px-4 py-2"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr>
                                            <td class="px-4 py-2">
                                                <input type="text" :name="'items[' + index + '][item_name]'" x-model="item.item_name" required
                                                    class="w-54 px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="text" :name="'items[' + index + '][description]'" x-model="item.description"
                                                    class="w-full px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="number" :name="'items[' + index + '][quantity]'" x-model.number="item.quantity" @input="calculateTotals()" required min="1"
                                                    class="w-full px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm text-center">
                                            </td>
                                            <td class="px-4 py-2">
                                                <input type="number" :name="'items[' + index + '][unit_price]'" x-model.number="item.unit_price" @input="calculateTotals()" required step="{{ $currencyStep }}" min="0"
                                                    class="w-full px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm text-right">
                                            </td>
                                            <td class="px-4 py-2 text-right text-sm text-gray-900 dark:text-white font-medium">
                                                <span x-text="formatCurrency(item.quantity * item.unit_price)"></span>
                                            </td>
                                            <td class="px-4 py-2 text-right">
                                                <button type="button" @click="removeItem(index)" class="text-red-600 hover:text-red-900 p-1" title="Remove item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        @error('items.*.item_name')
                            <p class="mt-3 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        @error('items.*.quantity')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        @error('items.*.unit_price')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror

                        <button type="button" @click="addItem()" class="mt-4 px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 transition">
                            <i class="fas fa-plus mr-1"></i> Add Item
                        </button>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Invoice Notes</label>
                        <textarea name="notes" id="notes" rows="3" placeholder="Terms, bank details, etc."
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">{{ old('notes', $invoiceDefaults['notes']) }}</textarea>
                        @error('notes')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Settings</h2>

                        <div class="space-y-4">
                            <div>
                                <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer *</label>
                                <select name="customer_id" id="customer_id" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" @selected((string) old('customer_id', $selectedCustomerId) === (string) $customer->id)>
                                            {{ $customer->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="currency_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Currency *</label>
                                <select name="currency_id" id="currency_id" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}" @selected((int) old('currency_id', $activeCurrency->id) === $currency->id)>
                                            {{ $currency->code }} - {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('currency_id')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="invoice_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Invoice Date *</label>
                                <input type="date" name="invoice_date" id="invoice_date" value="{{ old('invoice_date', $invoiceDefaults['invoice_date']) }}" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                @error('invoice_date')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="order_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Link Order</label>
                                <select name="order_id" id="order_id"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    <option value="">No linked order</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}" @selected((string) old('order_id', $selectedOrderId) === (string) $order->id)>
                                            {{ $order->order_number }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('order_id')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Due Date</label>
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $invoiceDefaults['due_date']) }}"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                @error('due_date')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Summary</h2>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-gray-500">
                                <span>Subtotal</span>
                                <span x-text="formatCurrency(subtotal)"></span>
                            </div>
                            <div class="flex justify-between items-center text-gray-500">
                                <span>Discount</span>
                                <input type="number" name="discount_amount" x-model.number="discount" @input="calculateTotals()" step="{{ $currencyStep }}" min="0"
                                    class="w-24 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-right">
                            </div>
                            @error('discount_amount')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <div class="flex justify-between items-center text-gray-500 border-b border-gray-100 dark:border-gray-700 pb-3">
                                <span>Tax</span>
                                <input type="number" name="tax_amount" x-model.number="tax" @input="calculateTotals()" step="{{ $currencyStep }}" min="0"
                                    class="w-24 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-right">
                            </div>
                            @error('tax_amount')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white pt-1">
                                <span>Total</span>
                                <span x-text="formatCurrency(total)"></span>
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-6 py-2 px-4 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700 transition">
                            Save Invoice
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function invoiceForm(config) {
            return {
                items: config.items,
                subtotal: 0,
                discount: config.discount,
                tax: config.tax,
                total: 0,
                currency: config.currency,

                init() {
                    this.calculateTotals();
                },

                addItem() {
                    this.items.push({
                        item_name: '',
                        description: '',
                        quantity: 1,
                        unit_price: 0,
                    });
                    this.calculateTotals();
                },

                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                        this.calculateTotals();
                    }
                },

                calculateTotals() {
                    this.subtotal = this.items.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0);
                    this.total = (this.subtotal - this.discount) + this.tax;
                },

                formatCurrency(amount) {
                    return new Intl.NumberFormat('en-US', {
                        minimumFractionDigits: this.currency.decimal_places,
                        maximumFractionDigits: this.currency.decimal_places,
                    }).format(amount);
                },
            };
        }
    </script>
</x-layouts.app>
