@php
    $currencyStep = $activeCurrency->decimal_places > 0 ? '0.01' : '1';
@endphp

<x-layouts.app title="Edit Invoice">
    <div x-data="editInvoiceForm({{ \Illuminate\Support\Js::from([
        'items' => $invoice->items->map(static fn ($item): array => [
            'item_name' => $item->item_name,
            'description' => $item->description,
            'quantity' => (int) $item->quantity,
            'unit_price' => (float) $item->unit_price,
        ])->values()->all(),
        'subtotal' => (float) $invoice->subtotal_amount,
        'discount' => (float) $invoice->discount_amount,
        'tax' => (float) $invoice->tax_amount,
        'total' => (float) $invoice->total_amount,
        'currency' => $activeCurrencyConfig,
    ]) }})">
        <div class="mb-6">
            <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Back to Invoice
            </a>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Draft Invoice</h1>
        </div>

        <form action="{{ route('invoices.update', $invoice) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <div class="lg:col-span-3 space-y-6">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Invoice Items</h2>
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
                                            <td class="px-4 py-2"><input type="text" :name="'items['+index+'][item_name]'" x-model="item.item_name" required class="w-64 px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm"></td>
                                            <td class="px-4 py-2"><input type="text" :name="'items['+index+'][description]'" x-model="item.description" class="w-full px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm"></td>
                                            <td class="px-4 py-2"><input type="number" :name="'items['+index+'][quantity]'" x-model.number="item.quantity" @input="calculateTotals()" required min="1" class="w-full px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm text-center"></td>
                                            <td class="px-4 py-2"><input type="number" :name="'items['+index+'][unit_price]'" x-model.number="item.unit_price" @input="calculateTotals()" required step="{{ $currencyStep }}" min="0" class="w-full px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm text-right"></td>
                                            <td class="px-4 py-2 text-right text-sm text-gray-900 dark:text-white font-medium"><span x-text="formatCurrency(item.quantity * item.unit_price)"></span></td>
                                            <td class="px-4 py-2 text-right"><button type="button" @click="removeItem(index)" class="text-red-600 hover:text-red-900 p-1" title="Remove item"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" @click="addItem()" class="mt-4 px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 transition">
                            <i class="fas fa-plus mr-1"></i> Add Item
                        </button>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Invoice Notes</label>
                        <textarea name="notes" id="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">{{ old('notes', $invoice->notes) }}</textarea>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Settings</h2>
                        <div class="space-y-4">
                            <div>
                                <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer *</label>
                                <select name="customer_id" id="customer_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" @selected(old('customer_id', $invoice->customer_id) == $customer->id)>{{ $customer->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="currency_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Currency *</label>
                                <select name="currency_id" id="currency_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}" @selected(old('currency_id', $invoice->currency_id ?? $activeCurrency->id) == $currency->id)>{{ $currency->code }} - {{ $currency->name }}</option>
                                    @endforeach
                                </select>
                                @error('currency_id')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="order_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Linked Order</label>
                                <select name="order_id" id="order_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    <option value="">No linked order</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}" @selected(old('order_id', $invoice->order_id) == $order->id)>{{ $order->order_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="invoice_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Invoice Date *</label>
                                <input type="date" name="invoice_date" id="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                            </div>
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Due Date</label>
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $invoice->due_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Summary</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-gray-500"><span>Subtotal</span><span x-text="formatCurrency(subtotal)"></span></div>
                            <div class="flex justify-between items-center text-gray-500">
                                <span>Discount</span>
                                <input type="number" name="discount_amount" x-model.number="discount" @input="calculateTotals()" step="{{ $currencyStep }}" min="0" class="w-24 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-right">
                            </div>
                            <div class="flex justify-between items-center text-gray-500 border-b border-gray-100 dark:border-gray-700 pb-3">
                                <span>Tax</span>
                                <input type="number" name="tax_amount" x-model.number="tax" @input="calculateTotals()" step="{{ $currencyStep }}" min="0" class="w-24 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-right">
                            </div>
                            <div class="flex justify-between text-lg font-bold text-gray-900 dark:text-white pt-1"><span>Total</span><span x-text="formatCurrency(total)"></span></div>
                        </div>
                        <button type="submit" class="w-full mt-6 py-2 px-4 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700 transition">Update Invoice</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function editInvoiceForm(config) {
            return {
                items: config.items,
                subtotal: config.subtotal,
                discount: config.discount,
                tax: config.tax,
                total: config.total,
                currency: config.currency,

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
