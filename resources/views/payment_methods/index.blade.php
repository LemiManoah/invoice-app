<x-layouts.app title="Payment Methods">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Payment Methods</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage the allowed methods used in payments and expenses.</p>
        </div>
        @can('create', \App\Models\PaymentMethod::class)
            <a href="{{ route('payment-methods.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm">
                Add Payment Method
            </a>
        @endcan
    </div>

    <form action="{{ route('payment-methods.index') }}" method="GET" class="mb-6">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row gap-3">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search payment method"
                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-700 transition text-sm">Search</button>
            </div>
        </div>
    </form>

    @error('payment_method')
        <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-300">
            {{ $message }}
        </div>
    @enderror

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Payments</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Expenses</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($paymentMethods as $paymentMethod)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $paymentMethod->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $paymentMethod->slug }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span @class([
                                    'px-2 py-1 text-xs rounded-full font-medium',
                                    'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' => $paymentMethod->is_active,
                                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' => ! $paymentMethod->is_active,
                                ])>
                                    {{ $paymentMethod->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">{{ $paymentMethod->payments_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">{{ $paymentMethod->expenses_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-wrap gap-2">
                                    @can('update', $paymentMethod)
                                        <a href="{{ route('payment-methods.edit', $paymentMethod) }}"
                                            class="inline-flex items-center rounded-md border border-amber-200 px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-50 dark:border-amber-800 dark:text-amber-300 dark:hover:bg-amber-900/30">
                                            Edit
                                        </a>
                                    @endcan
                                    @can('delete', $paymentMethod)
                                        <button type="button" @click="$dispatch('open-delete-modal', { url: '{{ route('payment-methods.destroy', $paymentMethod) }}', item: '{{ addslashes($paymentMethod->name) }}' })"
                                            class="inline-flex items-center rounded-md border border-red-200 px-2.5 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50 dark:border-red-800 dark:text-red-300 dark:hover:bg-red-900/30">
                                            Delete
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No payment methods found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($paymentMethods->hasPages())
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                {{ $paymentMethods->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
