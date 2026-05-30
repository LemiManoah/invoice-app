@php
    $selectedCurrencyId = (string) old('currency_id', $invoice->currency_id);
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
        'currencies' => $currencies->mapWithKeys(static fn ($currency): array => [
            (string) $currency->id => [
                'code' => $currency->code,
                'symbol' => $currency->symbol,
                'decimal_places' => (int) $currency->decimal_places,
                'exchange_rate' => (float) $currency->exchange_rate,
            ],
        ])->all(),
        'selectedCurrencyId' => $selectedCurrencyId,
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
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="mb-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Settings</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update the customer, dates, linked order, or billing currency here before changing the draft line items.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 xl:grid-cols-5">
                        <div>
                            <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer *</label>
                            <x-searchable-select
                                name="customer_id"
                                id="customer_id"
                                required
                                placeholder="Select Customer"
                                :selected="old('customer_id', $invoice->customer_id)"
                                :options="$customers->map(fn($c) => ['value' => $c->id, 'label' => $c->full_name])->toArray()"
                            />
                        </div>
                        <div>
                            <label for="currency_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Currency *</label>
                            <div @change="selectedCurrencyId = $event.detail.value">
                                <x-searchable-select
                                    name="currency_id"
                                    id="currency_id"
                                    required
                                    placeholder="Select Currency"
                                    :selected="old('currency_id', $invoice->currency_id ?? $activeCurrency->id)"
                                    :options="$currencies->map(fn($c) => ['value' => $c->id, 'label' => $c->code . ' - ' . $c->name])->toArray()"
                                />
                            </div>
                            @error('currency_id')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="order_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Linked Order</label>
                            <x-searchable-select
                                name="order_id"
                                id="order_id"
                                placeholder="No linked order"
                                :selected="old('order_id', $invoice->order_id)"
                                :options="$orders->map(fn($o) => ['value' => $o->id, 'label' => $o->order_number])->toArray()"
                            />
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
                    <div class="mb-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Invoice Items</h2>
                    </div>
                    <table class="w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
                        <colgroup>
                            <col class="w-[23%]">
                            <col class="w-[31%]">
                            <col class="w-[12%]">
                            <col class="w-[16%]">
                            <col class="w-[14%]">
                            <col class="w-[4%]">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Qty</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-44">Unit Price</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Total</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <template x-for="(item, index) in items" :key="index">
                                <tr>
                                    <td class="px-4 py-3 align-top"><input type="text" :name="'items['+index+'][item_name]'" x-model="item.item_name" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm"></td>
                                    <td class="px-4 py-3 align-top"><input type="text" :name="'items['+index+'][description]'" x-model="item.description" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm"></td>
                                    <td class="px-4 py-3 align-top"><input type="number" :name="'items['+index+'][quantity]'" x-model.number="item.quantity" @input="calculateTotals()" required min="1" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm text-center"></td>
                                    <td class="px-4 py-3 align-top"><input type="number" :name="'items['+index+'][unit_price]'" x-model.number="item.unit_price" @input="calculateTotals()" required :step="currentCurrencyStep" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm text-right"></td>
                                    <td class="px-4 py-3 align-top text-right text-sm text-gray-900 dark:text-white font-medium"><span x-text="formatCurrency(item.quantity * item.unit_price)"></span></td>
                                    <td class="px-4 py-3 align-top text-right"><button type="button" @click="removeItem(index)" class="text-red-600 hover:text-red-900 p-1" title="Remove item"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <button type="button" @click="addItem()" class="mt-4 px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 transition">
                        <i class="fas fa-plus mr-1"></i> Add Item
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Invoice Notes</label>
                        <textarea name="notes" id="notes" rows="6" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">{{ old('notes', $invoice->notes) }}</textarea>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Summary</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-gray-500"><span>Subtotal</span><span x-text="formatCurrency(subtotal)"></span></div>
                            <div class="flex justify-between items-center text-gray-500">
                                <span>Discount</span>
                                <input type="number" name="discount_amount" x-model.number="discount" @input="calculateTotals()" :step="currentCurrencyStep" min="0" class="w-28 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-right">
                            </div>
                            <div class="flex justify-between items-center text-gray-500 border-b border-gray-100 dark:border-gray-700 pb-3">
                                <span>Tax</span>
                                <input type="number" name="tax_amount" x-model.number="tax" @input="calculateTotals()" :step="currentCurrencyStep" min="0" class="w-28 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-right">
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
                currencies: config.currencies,
                selectedCurrencyId: String(config.selectedCurrencyId),

                get currentCurrency() {
                    return this.currencies[String(this.selectedCurrencyId)] ?? Object.values(this.currencies)[0];
                },

                get currentCurrencyStep() {
                    return this.currentCurrency.decimal_places > 0 ? '0.01' : '1';
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
                    const currency = this.currentCurrency;
                    return new Intl.NumberFormat('en-US', {
                        minimumFractionDigits: currency.decimal_places,
                        maximumFractionDigits: currency.decimal_places,
                    }).format(amount);
                },
            };
        }
    </script>
</x-layouts.app>
