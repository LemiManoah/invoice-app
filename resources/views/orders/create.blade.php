<x-layouts.app title="Create Order">
    @php
        // Build current measurement data per customer, keyed by customer id
        $customerMeasurementsMap = [];
        $pieceFieldMap = [
            'Jacket'    => ['jacket_shoulder'=>'Shoulder','jacket_chest'=>'Chest','jacket_stomach_waist'=>'Stomach','jacket_sleeve'=>'Sleeve','jacket_length'=>'Length','jacket_biceps'=>'Biceps','jacket_wrist'=>'Wrist','jacket_lower_arm'=>'Lower Arm','jacket_hip_line'=>'Hip Line'],
            'Trouser'   => ['trouser_waist'=>'Waist','trouser_thigh_cuff'=>'Thigh Cuff','trouser_length_fit'=>'Length Fit','trouser_ankle_fit'=>'Ankle/Hem','trouser_knee_fit'=>'Knee','trouser_fly_fit'=>'Fly','trouser_hips'=>'Hips'],
            'Waistcoat' => ['waistcoat_chest'=>'Chest','waistcoat_waist'=>'Waist','waistcoat_length'=>'Length'],
            'Skirt'     => ['skirt_waist'=>'Waist','skirt_hip_line'=>'Hip Line','skirt_full_length'=>'Full Length'],
            'Shirt'     => ['shirt_chest'=>'Chest','shirt_waist'=>'Waist','shirt_shoulder'=>'Shoulder','shirt_full_length'=>'Length','shirt_bottom_cut'=>'Bottom/Cut'],
        ];
        $pieceChecks = [
            'Jacket'    => fn($m) => $m->hasJacket(),
            'Trouser'   => fn($m) => $m->hasTrouser(),
            'Waistcoat' => fn($m) => $m->hasWaistcoat(),
            'Skirt'     => fn($m) => $m->hasSkirt(),
            'Shirt'     => fn($m) => $m->hasShirt(),
        ];
        foreach ($customers as $c) {
            $pieceMap = [];
            foreach ($c->measurements->sortByDesc('measurement_date') as $m) {
                foreach ($pieceChecks as $pieceName => $check) {
                    if (!isset($pieceMap[$pieceName]) && $check($m)) {
                        $rows = [];
                        foreach ($pieceFieldMap[$pieceName] as $field => $label) {
                            if ($m->{$field}) $rows[] = [$label, (string) $m->{$field}];
                        }
                        if ($rows) $pieceMap[$pieceName] = ['date' => $m->measurement_date?->format('M d, Y'), 'rows' => $rows];
                    }
                }
            }
            if ($pieceMap) $customerMeasurementsMap[$c->id] = $pieceMap;
        }
    @endphp
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

                    <!-- Record Measurements Toggle -->
                    <div>
                        <button type="button" @click="showMeasurements = !showMeasurements"
                            class="inline-flex items-center gap-2 px-4 py-2 border border-dashed border-blue-400 text-blue-600 dark:text-blue-400 dark:border-blue-600 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900/20 transition text-sm font-medium">
                            <i class="fas fa-ruler-combined"></i>
                            <span x-text="showMeasurements ? 'Hide Measurements' : 'Record Measurements'"></span>
                            <i class="fas fa-chevron-down transition-transform" :class="showMeasurements ? 'rotate-180' : ''"></i>
                        </button>
                        <span class="ml-2 text-xs text-gray-400 dark:text-gray-500">Optional — saved to the selected customer</span>
                    </div>

                    <!-- Measurements Panel -->
                    <template x-if="showMeasurements">
                        <div>
                            <input type="hidden" name="record_measurements" value="1">
                            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-blue-200 dark:border-blue-900/50">
                                <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-3 flex items-center gap-2">
                                    <i class="fas fa-ruler-combined text-blue-500"></i> Garment Measurements
                                    <span class="text-xs font-normal text-gray-500">(all measurements in inches)</span>
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Select the pieces to measure:</p>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <button type="button" @click="togglePiece('jacket')"
                                        :class="hasPiece('jacket') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 border-gray-300 dark:border-gray-600'"
                                        class="px-4 py-1.5 rounded text-xs font-medium border transition">Jacket</button>
                                    <button type="button" @click="togglePiece('trouser')"
                                        :class="hasPiece('trouser') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 border-gray-300 dark:border-gray-600'"
                                        class="px-4 py-1.5 rounded text-xs font-medium border transition">Trouser</button>
                                    <button type="button" @click="togglePiece('waistcoat')"
                                        :class="hasPiece('waistcoat') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 border-gray-300 dark:border-gray-600'"
                                        class="px-4 py-1.5 rounded text-xs font-medium border transition">Waistcoat</button>
                                    <button type="button" @click="togglePiece('skirt')"
                                        :class="hasPiece('skirt') ? 'bg-pink-600 text-white border-pink-600' : 'bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 border-gray-300 dark:border-gray-600'"
                                        class="px-4 py-1.5 rounded text-xs font-medium border transition">Skirt</button>
                                    <button type="button" @click="togglePiece('shirt')"
                                        :class="hasPiece('shirt') ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 border-gray-300 dark:border-gray-600'"
                                        class="px-4 py-1.5 rounded text-xs font-medium border transition">Shirt</button>
                                </div>

                                <!-- Jacket -->
                                <div x-show="hasPiece('jacket')" x-transition class="mb-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-100 dark:border-gray-700">
                                    <h4 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3">Jacket</h4>
                                    <div class="grid grid-cols-3 gap-3">
                                        @foreach(['jacket_shoulder'=>'Shoulder','jacket_chest'=>'Chest','jacket_stomach_waist'=>'Stomach','jacket_sleeve'=>'Sleeve','jacket_length'=>'Length','jacket_biceps'=>'Biceps','jacket_wrist'=>'Wrist','jacket_lower_arm'=>'Lower Arm','jacket_hip_line'=>'Hip Line'] as $f=>$l)
                                        <div>
                                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $l }}</label>
                                            <input type="number" step="0.1" name="{{ $f }}" placeholder="0.0" class="w-full px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-xs focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Trouser -->
                                <div x-show="hasPiece('trouser')" x-transition class="mb-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-100 dark:border-gray-700">
                                    <h4 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3">Trouser</h4>
                                    <div class="grid grid-cols-3 gap-3">
                                        @foreach(['trouser_waist'=>'Waist','trouser_thigh_cuff'=>'Thigh Cuff','trouser_length_fit'=>'Length','trouser_ankle_fit'=>'Ankle/Hem','trouser_knee_fit'=>'Knee','trouser_fly_fit'=>'Fly','trouser_hips'=>'Hips'] as $f=>$l)
                                        <div>
                                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $l }}</label>
                                            <input type="number" step="0.1" name="{{ $f }}" placeholder="0.0" class="w-full px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-xs focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Waistcoat -->
                                <div x-show="hasPiece('waistcoat')" x-transition class="mb-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-100 dark:border-gray-700">
                                    <h4 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3">Waistcoat</h4>
                                    <div class="grid grid-cols-3 gap-3">
                                        @foreach(['waistcoat_chest'=>'Chest Width','waistcoat_waist'=>'Waist Width','waistcoat_length'=>'Length'] as $f=>$l)
                                        <div>
                                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $l }}</label>
                                            <input type="number" step="0.1" name="{{ $f }}" placeholder="0.0" class="w-full px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-xs focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Skirt -->
                                <div x-show="hasPiece('skirt')" x-transition class="mb-4 p-4 bg-pink-50 dark:bg-pink-900/10 rounded-lg border border-pink-100 dark:border-pink-900/30">
                                    <h4 class="text-xs font-semibold text-pink-700 dark:text-pink-400 uppercase tracking-wider mb-3">Skirt</h4>
                                    <div class="grid grid-cols-3 gap-3">
                                        @foreach(['skirt_waist'=>'Waist','skirt_hip_line'=>'Hip Line','skirt_full_length'=>'Full Length'] as $f=>$l)
                                        <div>
                                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $l }}</label>
                                            <input type="number" step="0.1" name="{{ $f }}" placeholder="0.0" class="w-full px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-xs focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Shirt -->
                                <div x-show="hasPiece('shirt')" x-transition class="mb-4 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-100 dark:border-gray-700">
                                    <h4 class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3">Shirt</h4>
                                    <div class="grid grid-cols-3 gap-3">
                                        @foreach(['shirt_chest'=>'Chest','shirt_waist'=>'Waist','shirt_shoulder'=>'Shoulder','shirt_full_length'=>'Length','shirt_bottom_cut'=>'Bottom/Cut'] as $f=>$l)
                                        <div>
                                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $l }}</label>
                                            <input type="number" step="0.1" name="{{ $f }}" placeholder="0.0" class="w-full px-2 py-1.5 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-xs focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Measurement Date & Notes -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Measurement Date *</label>
                                        <input type="date" name="measurement_date" value="{{ date('Y-m-d') }}"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div class="flex items-center pt-5">
                                        <input type="checkbox" name="is_current" id="is_current_order" value="1" checked
                                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="is_current_order" class="ml-2 text-xs text-gray-600 dark:text-gray-400">Set as current measurements</label>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Additional Notes</label>
                                        <textarea name="fitting_notes" rows="2" placeholder="Special fit notes..."
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Right Column: Customer & Dates -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Order Details</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer *</label>
                                <select name="customer_id" id="customer_id" required @change="onCustomerChange($event.target.value)"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $selected_customer_id == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Current Measurements Display -->
                            <div x-show="selectedCustomerMeasurements !== null" x-transition>
                                <div class="rounded-lg border border-gray-200 dark:border-gray-600 overflow-hidden">
                                    <div class="px-3 py-2 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                        <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                                            <i class="fas fa-ruler-combined text-blue-500"></i> Current Measurements
                                        </span>
                                        <span class="text-xs text-gray-400">(inches)</span>
                                    </div>
                                    <div class="p-3 space-y-3">
                                        <template x-for="piece in Object.keys(selectedCustomerMeasurements)" :key="piece">
                                            <div>
                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="text-xs font-semibold text-gray-700 dark:text-gray-300" x-text="piece"></span>
                                                    <span class="text-xs text-gray-400" x-text="selectedCustomerMeasurements[piece].date"></span>
                                                </div>
                                                <div class="grid grid-cols-2 gap-x-4 gap-y-0.5">
                                                    <template x-for="row in selectedCustomerMeasurements[piece].rows" :key="row[0]">
                                                        <div class="flex justify-between text-xs">
                                                            <span class="text-gray-500 dark:text-gray-400" x-text="row[0]"></span>
                                                            <span class="font-medium text-gray-800 dark:text-gray-200" x-text="row[1] + '\"'"></span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
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
            const customers = @json($customers->map(fn($c) => ['id' => $c->id, 'gender' => $c->gender])->values());
            const allCustomerMeasurements = @json($customerMeasurementsMap);

            return {
                items: [{
                    product_id: '',
                    custom_name: '',
                    quantity: 1,
                    style_notes: '',
                    fabric_details: ''
                }],
                showMeasurements: false,
                selectedPieces: [],
                customerGender: '{{ optional($customers->firstWhere("id", $selected_customer_id))->gender }}',
                selectedCustomerMeasurements: allCustomerMeasurements['{{ $selected_customer_id }}'] || null,

                getProductPrice(productId) {
                    const product = products.find(p => p.id == productId);
                    return product && product.base_price ? parseFloat(product.base_price).toFixed(2) : 'N/A';
                },

                onProductChange(index) {
                    const item = this.items[index];
                    if (item.product_id !== 'custom') {
                        item.custom_name = '';
                    }
                },

                onCustomerChange(customerId) {
                    const customer = customers.find(c => c.id == customerId);
                    this.customerGender = customer ? customer.gender : '';
                    this.selectedCustomerMeasurements = allCustomerMeasurements[customerId] || null;
                    if (this.customerGender !== 'Female') {
                        this.selectedPieces = this.selectedPieces.filter(p => p !== 'skirt');
                    }
                },

                togglePiece(piece) {
                    if (this.selectedPieces.includes(piece)) {
                        this.selectedPieces = this.selectedPieces.filter(p => p !== piece);
                    } else {
                        this.selectedPieces.push(piece);
                    }
                },

                hasPiece(piece) {
                    return this.selectedPieces.includes(piece);
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
