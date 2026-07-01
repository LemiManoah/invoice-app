<x-layouts.app title="Quotation {{ $quotation->quotation_number }}">
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <a href="{{ route('quotations.index') }}" class="mb-2 inline-block text-blue-600 hover:text-blue-900 dark:hover:text-blue-400">
                <i class="fas fa-arrow-left mr-1"></i> Back to Quotations
            </a>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $quotation->quotation_number }}</h1>
                <span @class([
                    'px-2 py-1 text-xs rounded font-bold uppercase',
                    'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300'   => $quotation->status === 'draft',
                    'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' => $quotation->status === 'sent',
                    'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' => $quotation->status === 'accepted',
                    'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'    => $quotation->status === 'declined',
                    'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' => $quotation->status === 'converted',
                ])>
                    {{ ucfirst($quotation->status) }}
                </span>
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            @can('send', $quotation)
                <form action="{{ route('quotations.send', $quotation) }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                        <i class="fas fa-paper-plane mr-2"></i> Mark as Sent
                    </button>
                </form>
            @endcan

            @can('convert', $quotation)
                <form action="{{ route('quotations.convert', $quotation) }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-700">
                        <i class="fas fa-file-invoice-dollar mr-2"></i> Convert to Invoice
                    </button>
                </form>
            @endcan

            @can('update', $quotation)
                <a href="{{ route('quotations.edit', $quotation) }}"
                    class="rounded-md bg-yellow-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-yellow-700">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
            @endcan

            @can('print', $quotation)
                <a href="{{ route('quotations.print', $quotation) }}" target="_blank" rel="noopener"
                    class="rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700">
                    <i class="fas fa-print mr-2"></i> Print
                </a>
                <div x-data="{ open: false }" class="relative">
                    <button type="button" @click="open = !open" @click.outside="open = false"
                        class="rounded-md bg-gray-100 dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 transition hover:bg-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-receipt mr-2"></i> Thermal Print
                    </button>
                    <div x-show="open" x-cloak x-transition class="absolute right-0 z-20 mt-2 w-40 rounded-md border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-800">
                        <a href="{{ route('quotations.print.thermal', ['quotation' => $quotation, 'size' => 80]) }}" target="_blank" rel="noopener"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">80mm Roll</a>
                        <a href="{{ route('quotations.print.thermal', ['quotation' => $quotation, 'size' => 58]) }}" target="_blank" rel="noopener"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">58mm Roll</a>
                    </div>
                </div>
            @endcan

            @can('delete', $quotation)
                <button type="button"
                    @click="$dispatch('open-delete-modal', { url: '{{ route('quotations.destroy', $quotation) }}', item: 'quotation {{ $quotation->quotation_number }}' })"
                    class="rounded-md border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50 dark:border-red-800 dark:bg-gray-800 dark:text-red-400">
                    <i class="fas fa-trash mr-2"></i> Delete
                </button>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-900/50 dark:bg-green-900/20 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    @if($quotation->status === 'converted' && $quotation->invoice)
        <div class="mb-6 rounded-md border border-purple-200 bg-purple-50 px-4 py-3 text-sm text-purple-800 dark:border-purple-900/50 dark:bg-purple-900/20 dark:text-purple-300">
            <i class="fas fa-link mr-2"></i>
            This quotation was converted to invoice
            <a href="{{ route('invoices.show', $quotation->invoice) }}" class="font-bold underline">
                {{ $quotation->invoice->invoice_number }}
            </a>.
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Main content --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-100 px-6 py-4 dark:border-gray-700 flex justify-between items-start">
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Bill To</h2>
                        <p class="mt-1 text-base font-bold text-gray-900 dark:text-white">{{ $quotation->customer->full_name }}</p>
                        @if($quotation->customer->phone)<p class="text-sm text-gray-500 dark:text-gray-400">{{ $quotation->customer->phone }}</p>@endif
                        @if($quotation->customer->address)<p class="text-sm text-gray-500 dark:text-gray-400">{{ $quotation->customer->address }}</p>@endif
                    </div>
                    <div class="text-right text-sm">
                        <p class="text-gray-500 dark:text-gray-400">Date: <span class="font-semibold text-gray-900 dark:text-white">{{ $quotation->quotation_date->format('M d, Y') }}</span></p>
                        @if($quotation->valid_until)
                            <p class="text-gray-500 dark:text-gray-400">Valid Until: <span class="font-semibold text-gray-900 dark:text-white">{{ $quotation->valid_until->format('M d, Y') }}</span></p>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Description</th>
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Qty</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Unit Price</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                            @foreach($quotation->items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->item_name }}</p>
                                        @if($item->description)<p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->description }}</p>@endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-right font-mono text-sm text-gray-500 dark:text-gray-400">
                                        {{ $currencyFormatter->formatValue($item->unit_price, 2, $quotation->currency) }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-mono text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $currencyFormatter->formatValue($item->line_total, 2, $quotation->currency) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <td colspan="3" class="px-6 py-2 text-right text-sm font-medium uppercase text-gray-500 dark:text-gray-400">Subtotal</td>
                                <td class="px-6 py-2 text-right font-mono text-sm font-bold text-gray-900 dark:text-white">{{ $currencyFormatter->formatValue($quotation->subtotal_amount, 2, $quotation->currency) }}</td>
                            </tr>
                            @if($quotation->discount_amount > 0)
                                <tr>
                                    <td colspan="3" class="px-6 py-2 text-right text-sm font-medium uppercase text-gray-500 dark:text-gray-400">Discount</td>
                                    <td class="px-6 py-2 text-right font-mono text-sm font-bold text-red-600">-{{ $currencyFormatter->formatValue($quotation->discount_amount, 2, $quotation->currency) }}</td>
                                </tr>
                            @endif
                            @if($quotation->tax_amount > 0)
                                <tr>
                                    <td colspan="3" class="px-6 py-2 text-right text-sm font-medium uppercase text-gray-500 dark:text-gray-400">Tax</td>
                                    <td class="px-6 py-2 text-right font-mono text-sm font-bold text-gray-900 dark:text-white">{{ $currencyFormatter->formatValue($quotation->tax_amount, 2, $quotation->currency) }}</td>
                                </tr>
                            @endif
                            <tr class="bg-gray-100 dark:bg-gray-700">
                                <td colspan="3" class="px-6 py-3 text-right text-base font-bold uppercase text-gray-900 dark:text-white">Total</td>
                                <td class="px-6 py-3 text-right font-mono text-base font-black text-gray-900 dark:text-white">{{ $currencyFormatter->formatValue($quotation->total_amount, 2, $quotation->currency) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if($quotation->notes)
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-900/30">
                    <h3 class="mb-2 text-sm font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Notes</h3>
                    <p class="whitespace-pre-line text-sm text-gray-700 dark:text-gray-300">{{ $quotation->notes }}</p>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">Summary</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Subtotal</span>
                        <span class="font-mono text-sm font-semibold text-gray-900 dark:text-white">{{ $currencyFormatter->formatValue($quotation->subtotal_amount, 2, $quotation->currency) }}</span>
                    </div>
                    @if($quotation->discount_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Discount</span>
                            <span class="font-mono text-sm font-semibold text-red-600">-{{ $currencyFormatter->formatValue($quotation->discount_amount, 2, $quotation->currency) }}</span>
                        </div>
                    @endif
                    @if($quotation->tax_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Tax</span>
                            <span class="font-mono text-sm font-semibold text-gray-900 dark:text-white">{{ $currencyFormatter->formatValue($quotation->tax_amount, 2, $quotation->currency) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between border-t border-gray-100 pt-3 dark:border-gray-700">
                        <span class="text-base font-bold text-gray-900 dark:text-white">Total</span>
                        <span class="font-mono text-lg font-black text-gray-900 dark:text-white">{{ $currencyFormatter->formatValue($quotation->total_amount, 2, $quotation->currency) }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="mb-4 text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">Details</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Currency</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $quotation->currency->code }} — {{ $quotation->currency->name }}</dd>
                    </div>
                    @if($quotation->creator)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Created By</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $quotation->creator->name }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Created At</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $quotation->created_at->format('d M Y, h:i A') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-layouts.app>
