<x-layouts.app title="Customers">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Customers</h1>
        @can('create', \App\Models\Customer::class)
            <a href="{{ route('customers.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i> Add Customer
            </a>
        @endcan
    </div>

    <form action="{{ route('customers.index') }}" method="GET" class="mb-6">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row gap-3">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, phone, email, or code"
                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-700 transition text-sm">Search</button>
            </div>
        </div>
    </form>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($customers as $customer)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $customer->customer_code }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $customer->full_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $customer->phone }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $customer->email ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-wrap gap-2">
                                    @can('view', $customer)
                                        <a href="{{ route('customers.show', $customer) }}"
                                            class="inline-flex items-center rounded-md border border-blue-200 px-2.5 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-50 dark:border-blue-800 dark:text-blue-300 dark:hover:bg-blue-900/30">
                                            View
                                        </a>
                                    @endcan
                                    @can('update', $customer)
                                        <a href="{{ route('customers.edit', $customer) }}"
                                            class="inline-flex items-center rounded-md border border-amber-200 px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-50 dark:border-amber-800 dark:text-amber-300 dark:hover:bg-amber-900/30">
                                            Edit
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400">
                                No customers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
