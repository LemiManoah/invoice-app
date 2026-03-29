<x-layouts.app title="Currencies">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Currencies</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage how money is displayed across invoices, payments, expenses, receipts, and reports.</p>
        </div>
        @can('create', \App\Models\Currency::class)
            <a href="{{ route('currencies.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm">
                Add Currency
            </a>
        @endcan
    </div>

    <form action="{{ route('currencies.index') }}" method="GET" class="mb-6">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row gap-3">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search currency"
                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-700 transition text-sm">Search</button>
            </div>
        </div>
    </form>

    @error('currency')
        <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-300">
            {{ $message }}
        </div>
    @enderror

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Currency</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Display</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($currencies as $currency)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $currency->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $currency->code }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <span class="font-medium text-gray-900 dark:text-white">{{ $currency->symbol }}</span>
                                <span class="mx-1">|</span>
                                {{ $currency->decimal_places }} decimals
                                <span class="mx-1">|</span>
                                Rate: {{ number_format($currency->exchange_rate, 4) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-2">
                                    <span @class([
                                        'px-2 py-1 text-xs rounded-full font-medium',
                                        'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' => $currency->is_active,
                                        'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' => ! $currency->is_active,
                                    ])>
                                        {{ $currency->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    @if($currency->is_default)
                                        <span class="px-2 py-1 text-xs rounded-full font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                            Default
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-wrap gap-2">
                                    @if(! $currency->is_default)
                                        @can('update', $currency)
                                            <form action="{{ route('currencies.default', $currency) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center rounded-md border border-blue-200 px-2.5 py-1.5 text-xs font-medium text-blue-700 hover:bg-blue-50 dark:border-blue-800 dark:text-blue-300 dark:hover:bg-blue-900/30">
                                                    Make Default
                                                </button>
                                            </form>
                                        @endcan
                                    @endif
                                    @can('update', $currency)
                                        <a href="{{ route('currencies.edit', $currency) }}"
                                            class="inline-flex items-center rounded-md border border-amber-200 px-2.5 py-1.5 text-xs font-medium text-amber-700 hover:bg-amber-50 dark:border-amber-800 dark:text-amber-300 dark:hover:bg-amber-900/30">
                                            Edit
                                        </a>
                                    @endcan
                                    @can('delete', $currency)
                                        <button type="button" @click="$dispatch('open-delete-modal', { url: '{{ route('currencies.destroy', $currency) }}', item: '{{ addslashes($currency->code) }}' })"
                                            class="inline-flex items-center rounded-md border border-red-200 px-2.5 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50 dark:border-red-800 dark:text-red-300 dark:hover:bg-red-900/30">
                                            Delete
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No currencies found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($currencies->hasPages())
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                {{ $currencies->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
