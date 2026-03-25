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
                                    <button type="button" @click="removeItem(index)" class="absolute top-4 right-4 text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Garment Type *</label>
                                            <select :name="'items['+index+'][garment_type]'" x-model="item.garment_type" required
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                                <option value="">Select Type</option>
                                                <option value="Suit (2-Piece)">Suit (2-Piece)</option>
                                                <option value="Suit (3-Piece)">Suit (3-Piece)</option>
                                                <option value="Jacket/Blazer">Jacket/Blazer</option>
                                                <option value="Trouser">Trouser</option>
                                                <option value="Waistcoat">Waistcoat</option>
                                                <option value="Shirt">Shirt</option>
                                                <option value="Coat">Coat</option>
                                            </select>
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
                            <i class="fas fa-plus mr-1"></i> Add Another Garment
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
            return {
                items: [{
                    garment_type: '',
                    quantity: 1,
                    style_notes: '',
                    fabric_details: ''
                }],

                addItem() {
                    this.items.push({
                        garment_type: '',
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
