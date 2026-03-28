<x-layouts.app title="Edit Currency">
    <div class="mb-6">
        <a href="{{ route('currencies.index') }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
            Back to Currencies
        </a>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Edit Currency</h1>
    </div>

    <div class="max-w-3xl">
        <form action="{{ route('currencies.update', $currency) }}" method="POST" class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Currency Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $currency->name) }}" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Code *</label>
                    <input type="text" name="code" id="code" value="{{ old('code', $currency->code) }}" maxlength="3" required
                        class="w-full px-3 py-2 uppercase border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    @error('code')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="symbol" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Symbol / Label *</label>
                    <input type="text" name="symbol" id="symbol" value="{{ old('symbol', $currency->symbol) }}" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    @error('symbol')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="decimal_places" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Decimal Places *</label>
                    <input type="number" name="decimal_places" id="decimal_places" min="0" max="4" value="{{ old('decimal_places', $currency->decimal_places) }}" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    @error('decimal_places')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="exchange_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Exchange Rate (vs Base) *</label>
                    <input type="number" name="exchange_rate" id="exchange_rate" min="0" step="0.000001" value="{{ old('exchange_rate', $currency->exchange_rate) }}" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    @error('exchange_rate')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort Order *</label>
                    <input type="number" name="sort_order" id="sort_order" min="0" value="{{ old('sort_order', $currency->sort_order) }}" required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white text-sm">
                    @error('sort_order')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <label class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $currency->is_active))
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Active currency</span>
                </label>
                <label class="flex items-center gap-3">
                    <input type="hidden" name="is_default" value="0">
                    <input type="checkbox" name="is_default" value="1" @checked(old('is_default', $currency->is_default))
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Default currency</span>
                </label>
            </div>

            @error('is_default')
                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror

            <div class="flex justify-end gap-3">
                <a href="{{ route('currencies.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Update Currency
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
