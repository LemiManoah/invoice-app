            <aside :class="{ 'w-full md:w-64': sidebarOpen, 'w-0 md:w-16 hidden md:block': !sidebarOpen }"
                class="bg-sidebar text-sidebar-foreground border-r border-gray-200 dark:border-gray-700 sidebar-transition overflow-hidden">
                <!-- Sidebar Content -->
                <div class="h-full flex flex-col">
                    <!-- Sidebar Menu -->
                    <nav class="flex-1 overflow-y-auto custom-scrollbar py-4">
                        <ul class="space-y-1 px-2">
                            <!-- Dashboard -->
                            <x-layouts.sidebar-link href="{{ route('dashboard') }}" icon='fas-house'
                                :active="request()->routeIs('dashboard*')">Dashboard</x-layouts.sidebar-link>

                            <!-- Customers -->
                            <x-layouts.sidebar-link href="{{ route('customers.index') }}" icon='fas-users'
                                :active="request()->routeIs('customers*')">Customers</x-layouts.sidebar-link>

                            <!-- Invoices -->
                            <x-layouts.sidebar-link href="{{ route('invoices.index') }}" icon='fas-file-invoice-dollar'
                                :active="request()->routeIs('invoices*')">Invoices</x-layouts.sidebar-link>

                            <!-- Payments -->
                            <x-layouts.sidebar-link href="{{ route('payments.index') }}" icon='fas-money-check-dollar'
                                :active="request()->routeIs('payments*') || request()->routeIs('receipts*')">Payments</x-layouts.sidebar-link>

                            <!-- Orders -->
                            <x-layouts.sidebar-link href="{{ route('orders.index') }}" icon='fas-shopping-bag'
                                :active="request()->routeIs('orders*')">Orders</x-layouts.sidebar-link>

                            <!-- Expenses -->
                            <x-layouts.sidebar-link href="{{ route('expenses.index') }}" icon='fas-money-bill-wave'
                                :active="request()->routeIs('expenses*')">Expenses</x-layouts.sidebar-link>

                            <!-- Reports -->
                            <x-layouts.sidebar-link href="{{ route('reports.index') }}" icon='fas-chart-line'
                                :active="request()->routeIs('reports*')">Reports</x-layouts.sidebar-link>

                            <!-- Audit -->
                            <x-layouts.sidebar-link href="{{ route('audit-logs.index') }}" icon='fas-clock-rotate-left'
                                :active="request()->routeIs('audit-logs*')">Audit Logs</x-layouts.sidebar-link>
                        </ul>
                    </nav>
                </div>
            </aside>
