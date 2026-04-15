@php
    $currencyStep = $activeCurrency->decimal_places > 0 ? '0.01' : '1';
    $initialItems = old('items', $quotation->items->map(fn ($i) => [
        'item_name'   => $i->item_name,
        'description' => $i->description ?? '',
        'quantity'    => $i->quantity,
        'unit_price'  => (float) $i->unit_price,
    ])->values()->all());
@endphp

<x-layouts.app title="Edit Quotation {{ $quotation->quotation_number }}">
    <div x-data="quotationForm({{ \Illuminate\Support\Js::from([
        'items'    => collect($initialItems)->map(static fn ($item) => [
            'item_name'   => (string) ($item['item_name'] ?? ''),
            'description' => (string) ($item['description'] ?? ''),
            'quantity'    => (int) ($item['quantity'] ?? 1),
            'unit_price'  => (float) ($item['unit_price'] ?? 0),
        ])->values()->all(),
        'discount' => (float) old('discount_amount', $quotation->discount_amount),
        'tax'      => (float) old('tax_amount', $quotation->tax_amount),
        'currency' => $activeCurrencyConfig,
    ]) }})">
        <div class="mb-6">
            <a href="{{ route('quotations.show', $quotation) }}" class="mb-2 inline-block text-blue-600 hover:text-blue-900 dark:hover:text-blue-400">
                <i class="fas fa-arrow-left mr-1"></i> Back to Quotation
            </a>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit {{ $quotation->quotation_number }}</h1>
        </div>

        @if($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-300">
                Please correct the highlighted errors and try again.
            </div>
        @endif

        <form action="{{ route('quotations.update', $quotation) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="mb-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Settings</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update the customer, quotation dates, and currency here before revising the quoted items below.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <div>
                            <label for="customer_id" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Customer *</label>
                            <select name="customer_id" id="customer_id" required
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" @selected((int) old('customer_id', $quotation->customer_id) === $customer->id)>
                                        {{ $customer->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="currency_id" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Currency *</label>
                            <select name="currency_id" id="currency_id" required
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}" @selected((int) old('currency_id', $quotation->currency_id) === $currency->id)>
                                        {{ $currency->code }} - {{ $currency->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('currency_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="quotation_date" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Quotation Date *</label>
                            <input type="date" name="quotation_date" id="quotation_date" value="{{ old('quotation_date', $quotation->quotation_date->toDateString()) }}" required
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('quotation_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="valid_until" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Valid Until</label>
                            <input type="date" name="valid_until" id="valid_until" value="{{ old('valid_until', $quotation->valid_until?->toDateString()) }}"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('valid_until')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="mb-4">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Quotation Items</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Revise the offered items, quantities, descriptions, and pricing here before sending the updated quotation.</p>
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
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Item Name</th>
                                <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Description</th>
                                <th class="w-32 px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Qty</th>
                                <th class="w-44 px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Unit Price</th>
                                <th class="w-40 px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Total</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <template x-for="(item, index) in items" :key="index">
                                <tr>
                                    <td class="px-4 py-3 align-top">
                                        <input type="text" :name="'items[' + index + '][item_name]'" x-model="item.item_name" required
                                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <input type="text" :name="'items[' + index + '][description]'" x-model="item.description"
                                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <input type="number" :name="'items[' + index + '][quantity]'" x-model.number="item.quantity" @input="calculateTotals()" required min="1"
                                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-center text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <input type="number" :name="'items[' + index + '][unit_price]'" x-model.number="item.unit_price" @input="calculateTotals()" required step="{{ $currencyStep }}" min="0"
                                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-right text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    </td>
                                    <td class="px-4 py-3 align-top text-right text-sm font-medium text-gray-900 dark:text-white">
                                        <span x-text="formatCurrency(item.quantity * item.unit_price)"></span>
                                    </td>
                                    <td class="px-4 py-3 align-top text-right">
                                        <button type="button" @click="removeItem(index)" class="p-1 text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <button type="button" @click="addItem()" class="mt-4 rounded bg-gray-100 px-3 py-1 text-sm text-gray-700 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300">
                        <i class="fas fa-plus mr-1"></i> Add Item
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white">Notes</h2>
                        <p class="mt-1 mb-4 text-sm text-gray-500 dark:text-gray-400">Keep assumptions, exclusions, delivery commitments, or special terms here so the quotation stays clear.</p>
                        <label for="notes" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                        <textarea name="notes" id="notes" rows="6"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('notes', $quotation->notes) }}</textarea>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <h2 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">Summary</h2>
                        <p class="mt-1 mb-4 text-sm text-gray-500 dark:text-gray-400">Check the revised subtotal, discount, tax, and final quoted amount before you save the update.</p>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-gray-500">
                                <span>Subtotal</span>
                                <span x-text="formatCurrency(subtotal)"></span>
                            </div>
                            <div class="flex items-center justify-between text-gray-500">
                                <span>Discount</span>
                                <input type="number" name="discount_amount" x-model.number="discount" @input="calculateTotals()" step="{{ $currencyStep }}" min="0"
                                    class="w-28 rounded border border-gray-300 px-2 py-1 text-right dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div class="flex items-center justify-between border-b border-gray-100 pb-3 text-gray-500 dark:border-gray-700">
                                <span>Tax</span>
                                <input type="number" name="tax_amount" x-model.number="tax" @input="calculateTotals()" step="{{ $currencyStep }}" min="0"
                                    class="w-28 rounded border border-gray-300 px-2 py-1 text-right dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div class="flex justify-between pt-1 text-lg font-bold text-gray-900 dark:text-white">
                                <span>Total</span>
                                <span x-text="formatCurrency(total)"></span>
                            </div>
                        </div>

                        <button type="submit" class="mt-6 w-full rounded-md bg-blue-600 px-4 py-2 font-semibold text-white transition hover:bg-blue-700">
                            Update Quotation
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function quotationForm(config) {
            return {
                items: config.items,
                subtotal: 0, discount: config.discount, tax: config.tax, total: 0,
                currency: config.currency,
                init() { this.calculateTotals(); },
                addItem() {
                    this.items.push({ item_name: '', description: '', quantity: 1, unit_price: 0 });
                    this.calculateTotals();
                },
                removeItem(index) {
                    if (this.items.length > 1) { this.items.splice(index, 1); this.calculateTotals(); }
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
