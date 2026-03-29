<x-layouts.app title="Create Order">
    <div x-data="orderForm()">
        <div class="mb-6">
            <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Back to Orders
            </a>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Create New Order</h1>
        </div>

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Order Items/Garments -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Garment Details</h2>
                        
                        <div class="space-y-8">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="p-4 border border-gray-100 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900/50 relative">
                                    <button type="button" @click="removeItem(index)" class="absolute top-4 right-4 text-red-500 hover:text-red-700 p-1" title="Remove item">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Item *</label>
                                            <select :name="'items['+index+'][product_id]'" x-model="item.product_id" @change="onProductChange(index)"
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                                <option value="">Select Product</option>
                                                <option value="custom">+ Custom Item</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div x-show="item.product_id === 'custom'" x-transition>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Custom Item Name *</label>
                                            <input type="text" :name="'items['+index+'][garment_type]'" x-model="item.custom_name" placeholder="Enter item name"
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                        </div>
                                        <div x-show="item.product_id && item.product_id !== 'custom'" x-transition>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Base Price</label>
                                            <div x-text="item.product_id ? getProductPrice(item.product_id) : '-'"
                                                class="px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-md bg-gray-100 dark:bg-gray-900 text-sm text-gray-600 dark:text-gray-400">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity *</label>
                                            <input type="number" :name="'items['+index+'][quantity]'" x-model.number="item.quantity" required min="1"
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Style Notes</label>
                                            <textarea :name="'items['+index+'][style_notes]'" x-model="item.style_notes" rows="2" placeholder="Lapel style, buttons, fit, etc."
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm"></textarea>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fabric & Color</label>
                                            <input type="text" :name="'items['+index+'][fabric_details]'" x-model="item.fabric_details" placeholder="Fabric code, color name, etc."
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <button type="button" @click="addItem()" class="mt-6 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 transition text-sm font-medium">
                            <i class="fas fa-plus mr-1"></i> Add Another Item
                        </button>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">General Order Notes</label>
                        <textarea name="notes" id="notes" rows="3" placeholder="Any special instructions for the whole order..."
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm"></textarea>
                    </div>
                </div>

                <!-- Right Column: Customer & Dates -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Order Details</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer *</label>
                                <select name="customer_id" id="customer_id" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $selected_customer_id == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="currency_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Currency *</label>
                                <select name="currency_id" id="currency_id" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}" @selected(old('currency_id', $activeCurrency->id) == $currency->id)>
                                            {{ $currency->code }} - {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="order_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Order Date *</label>
                                <input type="date" name="order_date" id="order_date" value="{{ date('Y-m-d') }}" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                            </div>

                            <div>
                                <label for="promised_delivery_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Promised Delivery Date</label>
                                <input type="date" name="promised_delivery_date" id="promised_delivery_date"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                            </div>

                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority *</label>
                                <select name="priority" id="priority" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-8 py-2 px-4 bg-blue-600 text-white rounded-md font-semibold hover:bg-blue-700 transition">
                            Create Order
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function orderForm() {
            const products = @json($products);

            return {
                items: [{
                    product_id: '',
                    custom_name: '',
                    quantity: 1,
                    style_notes: '',
                    fabric_details: ''
                }],

                getProductPrice(productId) {
                    const product = products.find(p => p.id == productId);
                    return product && product.base_price ?  + parseFloat(product.base_price).toFixed(2) : 'N/A';
                },

                onProductChange(index) {
                    const item = this.items[index];
                    if (item.product_id !== 'custom') {
                        item.custom_name = '';
                    }
                },

                addItem() {
                    this.items.push({
                        product_id: '',
                        custom_name: '',
                        quantity: 1,
                        style_notes: '',
                        fabric_details: ''
                    });
                },

                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                }
            }
        }
    </script>
</x-layouts.app>
