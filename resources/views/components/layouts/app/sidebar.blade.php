            <aside :class="{ 'w-full md:w-64': sidebarOpen, 'w-0 md:w-16 hidden md:block': !sidebarOpen }"
                class="bg-sidebar text-sidebar-foreground border-r border-gray-200 dark:border-gray-700 sidebar-transition overflow-hidden">
                <div class="h-full flex flex-col">
                    <nav class="flex-1 overflow-y-auto custom-scrollbar py-4">
                        <ul class="space-y-1 px-2">
                            @if(auth()->user()?->can('dashboard.view'))
                                <x-layouts.sidebar-link href="{{ route('dashboard') }}" icon='fas-house'
                                    :active="request()->routeIs('dashboard*')">Dashboard</x-layouts.sidebar-link>
                            @endif

                            @can('viewAny', \App\Models\Invoice::class)
                                <x-layouts.sidebar-link href="{{ route('invoices.index') }}" icon='fas-file-invoice-dollar'
                                    :active="request()->routeIs('invoices*')">Invoices</x-layouts.sidebar-link>
                            @endcan

                            @can('viewAny', \App\Models\Payment::class)
                                <x-layouts.sidebar-link href="{{ route('payments.index') }}" icon='fas-money-check-dollar'
                                    :active="request()->routeIs('payments*') || request()->routeIs('receipts*')">Payments</x-layouts.sidebar-link>
                            @endcan

                            @can('viewAny', \App\Models\Order::class)
                                <x-layouts.sidebar-link href="{{ route('orders.index') }}" icon='fas-shopping-bag'
                                    :active="request()->routeIs('orders*')">Orders</x-layouts.sidebar-link>
                            @endcan

                            @if(auth()->user()?->can('products.view'))
                                <x-layouts.sidebar-link href="{{ route('products.index') }}" icon='fas-box'
                                    :active="request()->routeIs('products*')">Products</x-layouts.sidebar-link>
                            @endif

                            @can('viewAny', \App\Models\Expense::class)
                                <x-layouts.sidebar-link href="{{ route('expenses.index') }}" icon='fas-money-bill-wave'
                                    :active="request()->routeIs('expenses*')">Expenses</x-layouts.sidebar-link>
                            @endcan

                            @can('viewAny', \App\Models\Customer::class)
                                <x-layouts.sidebar-link href="{{ route('customers.index') }}" icon='fas-users'
                                    :active="request()->routeIs('customers*')">Customers</x-layouts.sidebar-link>
                            @endcan

                            @can('viewAny', \App\Models\User::class)
                                <x-layouts.sidebar-link href="{{ route('users.index') }}" icon='fas-user-shield'
                                    :active="request()->routeIs('users*')">Users</x-layouts.sidebar-link>
                            @endcan


                            @can('viewAny', \App\Models\Currency::class)
                                <x-layouts.sidebar-link href="{{ route('currencies.index') }}" icon='fas-coins'
                                    :active="request()->routeIs('currencies*')">Currencies</x-layouts.sidebar-link>
                            @endcan

                            @if(auth()->user()?->can('reports.view'))
                                <x-layouts.sidebar-link href="{{ route('reports.index') }}" icon='fas-chart-line'
                                    :active="request()->routeIs('reports*')">Reports</x-layouts.sidebar-link>
                            @endif

                            @if(auth()->user()?->can('audit-logs.view'))
                                <x-layouts.sidebar-link href="{{ route('audit-logs.index') }}" icon='fas-clock-rotate-left'
                                    :active="request()->routeIs('audit-logs*')">Audit Logs</x-layouts.sidebar-link>
                            @endif
                        </ul>
                    </nav>
                </div>
            </aside>
