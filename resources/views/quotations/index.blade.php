<x-layouts.app title="Quotations">
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Quotations</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage quotations and convert them into invoices.</p>
        </div>
        @can('create', \App\Models\Quotation::class)
            <a href="{{ route('quotations.create') }}"
                class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> New Quotation
            </a>
        @endcan
    </div>

    {{-- Filters --}}
    <form method="GET" class="mb-4 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search number or customer..."
            class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
        <select name="status" class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <option value="">All Statuses</option>
            @foreach(['draft','sent','accepted','declined','converted'] as $s)
                <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-md bg-gray-800 px-4 py-2 text-sm font-medium text-white hover:bg-gray-900 dark:bg-gray-700 dark:hover:bg-gray-600">
            Filter
        </button>
        @if($search || $status)
            <a href="{{ route('quotations.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">Clear</a>
        @endif
    </form>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Number</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Valid Until</th>
                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                @forelse($quotations as $quotation)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40">
                        <td class="px-6 py-4 text-sm font-medium text-blue-600 dark:text-blue-400">
                            <a href="{{ route('quotations.show', $quotation) }}">{{ $quotation->quotation_number }}</a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $quotation->customer->full_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $quotation->quotation_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $quotation->valid_until?->format('M d, Y') ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-mono font-semibold text-gray-900 dark:text-white">
                            {{ $currencyFormatter->formatValue($quotation->total_amount, 2, $quotation->currency) }}
                        </td>
                        <td class="px-6 py-4">
                            <span @class([
                                'inline-block px-2 py-0.5 rounded text-xs font-bold uppercase',
                                'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300'   => $quotation->status === 'draft',
                                'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' => $quotation->status === 'sent',
                                'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' => $quotation->status === 'accepted',
                                'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'    => $quotation->status === 'declined',
                                'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' => $quotation->status === 'converted',
                            ])>
                                {{ ucfirst($quotation->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @can('print', $quotation)
                                    <div x-data="{ open: false }" class="relative inline-block">
                                        <button type="button" @click="open = !open" @click.outside="open = false"
                                            class="inline-flex items-center rounded-md border border-purple-200 px-2.5 py-1.5 text-xs font-medium text-purple-700 hover:bg-purple-50 dark:border-purple-800 dark:text-purple-300 dark:hover:bg-purple-900/30">
                                            Thermal
                                        </button>
                                        <div x-show="open" x-cloak x-transition class="absolute right-0 z-20 mt-1 w-32 rounded-md border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
                                            <a href="{{ route('quotations.print.thermal', ['quotation' => $quotation, 'size' => 80]) }}" target="_blank" rel="noopener"
                                                class="block px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">80mm Roll</a>
                                            <a href="{{ route('quotations.print.thermal', ['quotation' => $quotation, 'size' => 58]) }}" target="_blank" rel="noopener"
                                                class="block px-3 py-1.5 text-xs text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">58mm Roll</a>
                                        </div>
                                    </div>
                                @endcan
                                <a href="{{ route('quotations.show', $quotation) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                            No quotations found. <a href="{{ route('quotations.create') }}" class="text-blue-600 hover:underline">Create one.</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $quotations->withQueryString()->links() }}
    </div>
</x-layouts.app>
