<x-layouts.app title="{{ $customer->full_name }}">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('customers.index') }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Back to Customers
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $customer->full_name }}</h1>
            <p class="text-gray-500 dark:text-gray-400">Customer Code: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $customer->customer_code }}</span></p>
        </div>
        <div class="flex flex-wrap gap-2">
            @can('create', \App\Models\Order::class)
                <a href="{{ route('orders.create', ['customer_id' => $customer->id]) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    <i class="fas fa-shopping-bag mr-2"></i> New Order
                </a>
            @endcan
            @can('create', \App\Models\Invoice::class)
                <a href="{{ route('invoices.create', ['customer_id' => $customer->id]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                    <i class="fas fa-file-invoice-dollar mr-2"></i> New Invoice
                </a>
            @endcan
            @can('update', $customer)
                <a href="{{ route('customers.edit', $customer) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition">
                    <i class="fas fa-edit mr-2"></i> Edit Profile
                </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar: Customer Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-100 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <i class="fas fa-user-circle mr-2 text-blue-500"></i> Contact Information
                </h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold">Phone Number</p>
                        <p class="text-gray-900 dark:text-white">{{ $customer->phone }}</p>
                    </div>
                    @if($customer->alternative_phone)
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold">Alternative Phone</p>
                            <p class="text-gray-900 dark:text-white">{{ $customer->alternative_phone }}</p>
                        </div>
                    @endif
                    @if($customer->email)
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold">Email Address</p>
                            <p class="text-gray-900 dark:text-white">{{ $customer->email }}</p>
                        </div>
                    @endif
                    @if($customer->gender)
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold">Gender</p>
                            <p class="text-gray-900 dark:text-white">{{ $customer->gender }}</p>
                        </div>
                    @endif
                    @if($customer->date_of_birth)
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold">Date of Birth</p>
                            <p class="text-gray-900 dark:text-white">{{ $customer->date_of_birth->format('M d, Y') }}</p>
                        </div>
                    @endif
                    @if($customer->address)
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold">Address</p>
                            <p class="text-gray-900 dark:text-white leading-relaxed">{{ $customer->address }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($customer->notes)
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-100 dark:border-yellow-900/30 shadow-sm rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-yellow-800 dark:text-yellow-400 mb-2 flex items-center">
                        <i class="fas fa-sticky-note mr-2"></i> Important Notes
                    </h2>
                    <p class="text-yellow-700 dark:text-yellow-300 whitespace-pre-line">{{ $customer->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Main Content Area -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lifetime Orders</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $customer->orders->count() }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Invoiced</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $currencyFormatter->formatValue($customer->invoices->sum('total_amount'), 2) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-xs font-bold text-red-500 uppercase tracking-wider">Outstanding Balance</p>
                    <p class="text-2xl font-bold text-red-600">{{ $currencyFormatter->formatValue($customer->invoices->sum('balance_due'), 2) }}</p>
                </div>
            </div>

            <!-- Tabs/Sections -->
            <div x-data="{ activeTab: 'orders' }">
                <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <button type="button" @click="activeTab = 'orders'" 
                                :class="activeTab === 'orders' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                            Recent Orders
                        </button>
                        <button type="button" @click="activeTab = 'invoices'" 
                                :class="activeTab === 'invoices' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                            Invoices
                        </button>
                        <button type="button" @click="activeTab = 'measurements'" 
                                :class="activeTab === 'measurements' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                            Measurements
                        </button>
                        <button type="button" @click="activeTab = 'payments'" 
                                :class="activeTab === 'payments' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                            Payments
                        </button>
                    </nav>
                </div>

                <!-- Orders Tab -->
                <div x-show="activeTab === 'orders'" class="space-y-4">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Order #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($customer->orders->take(5) as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600 dark:text-blue-400">
                                            <a href="{{ route('orders.show', $order) }}">{{ $order->order_number }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $order->order_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                            <a href="{{ route('orders.show', $order) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No orders found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Invoices Tab -->
                <div x-show="activeTab === 'invoices'" x-cloak class="space-y-4">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Inv #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($customer->invoices->take(5) as $invoice)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                            <a href="{{ route('invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-semibold">
                                            {{ $currencyFormatter->formatValue($invoice->total_amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span @class([
                                                'px-2 py-1 rounded-full text-xs font-medium',
                                                'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' => $invoice->status === 'paid',
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' => $invoice->status === 'partially_paid',
                                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' => $invoice->status === 'draft',
                                            ])>
                                                {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                            <a href="{{ route('invoices.show', $invoice) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No invoices found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Measurements Tab -->
                <div x-show="activeTab === 'measurements'" x-cloak class="space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Measurement History</h3>
                        @can('create', \App\Models\Measurement::class)
                            <a href="{{ route('customers.measurements.create', $customer) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-sm font-medium">
                                <i class="fas fa-plus mr-1"></i> New Measurements
                            </a>
                        @endcan
                    </div>
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Neck</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Chest</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Waist</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($customer->measurements as $measurement)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $measurement->measurement_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $measurement->neck ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $measurement->chest ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $measurement->waist ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($measurement->is_current)
                                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                    Current
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                            @can('update', $measurement)
                                                <a href="{{ route('measurements.edit', $measurement) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No measurements found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payments Tab -->
                <div x-show="activeTab === 'payments'" x-cloak class="space-y-4">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-100 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Invoice</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Receipt</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($customer->payments->sortByDesc('payment_date')->take(10) as $payment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $payment->payment_date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400">
                                            <a href="{{ route('invoices.show', $payment->invoice) }}">{{ $payment->invoice->invoice_number }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-blue-400">
                                            @if($payment->receipt)
                                                <a href="{{ route('receipts.show', $payment->receipt) }}">{{ $payment->receipt->receipt_number }}</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-mono {{ $payment->status === 'valid' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $currencyFormatter->formatValue($payment->amount, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No payments found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
