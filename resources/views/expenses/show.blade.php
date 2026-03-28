<x-layouts.app title="Expense Details">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('expenses.index') }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Back to Expenses
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Expense Details</h1>
            <p class="text-gray-500 dark:text-gray-400">Category: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $expense->category->name }}</span></p>
        </div>
        <div class="flex flex-wrap gap-2">
            @if($expense->status === 'valid')
                @can('update', $expense)
                    <a href="{{ route('expenses.edit', $expense) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                @endcan
                @can('void', $expense)
                    <button type="button" @click="$dispatch('open-modal', 'void-expense')" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        <i class="fas fa-ban mr-2"></i> Void
                    </button>
                @endcan
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Description</h2>
                    <p class="mt-2 text-gray-700 dark:text-gray-300 text-lg leading-relaxed">{{ $expense->description }}</p>
                </div>
                <div class="p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Financial Info</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold">Amount</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $currencyFormatter->formatValue($expense->amount, 2, $expense->currency) }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold">Payment Method</p>
                            <p class="text-lg text-gray-900 dark:text-white">{{ $expense->payment_method }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold">Vendor</p>
                            <p class="text-lg text-gray-900 dark:text-white">{{ $expense->vendor_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold">Reference #</p>
                            <p class="text-lg text-gray-900 dark:text-white">{{ $expense->reference_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($expense->notes)
                <div class="bg-gray-50 dark:bg-gray-900/30 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Internal Notes:</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $expense->notes }}</p>
                </div>
            @endif

            @if($expense->status === 'voided')
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/30 rounded-lg p-6">
                    <h3 class="text-sm font-bold text-red-600 dark:text-red-400 uppercase tracking-wider mb-2">Void Information:</h3>
                    <p class="text-sm text-red-700 dark:text-red-300">Voided on: {{ $expense->voided_at?->format('M d, Y H:i') ?? 'N/A' }}</p>
                    <p class="text-sm text-red-700 dark:text-red-300">Voided by: {{ $expense->voider->name ?? 'System' }}</p>
                    <p class="text-sm text-red-700 dark:text-red-300 mt-2 italic">Reason: {{ $expense->void_reason ?? 'No reason recorded.' }}</p>
                </div>
            @endif
        </div>

        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 uppercase tracking-wider">Audit</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold">Expense Date</p>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $expense->expense_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 font-bold">Status</p>
                        <span @class([
                            'px-2 py-1 text-xs rounded-full font-medium',
                            'bg-green-100 text-green-800' => $expense->status === 'valid',
                            'bg-red-100 text-red-800' => $expense->status === 'voided',
                        ])>
                            {{ ucfirst($expense->status) }}
                        </span>
                    </div>
                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                        <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Recorded By</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $expense->creator->name ?? 'System' }}</p>
                        <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold mb-1">Created At</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $expense->created_at?->format('M d, Y H:i') ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Void Expense Modal -->
    @can('void', $expense)
        <x-modal name="void-expense" maxWidth="md">
            <form action="{{ route('expenses.void', $expense) }}" method="POST" class="p-6">
                @csrf
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Void Expense
                </h2>

            <div class="space-y-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Are you sure you want to void this expense? It will no longer be counted in financial summaries.
                </p>

                <div>
                    <label for="void_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reason for Voiding *</label>
                    <textarea name="void_reason" id="void_reason" rows="3" required
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" x-on:click="$dispatch('close-modal', 'void-expense')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                    Back
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                    Confirm Void
                </button>
            </div>
            </form>
        </x-modal>
    @endcan
</x-layouts.app>
