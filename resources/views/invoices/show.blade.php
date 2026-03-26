<x-layouts.app title="Invoice {{ $invoice->invoice_number }}">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('invoices.index') }}" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Back to Invoices
            </a>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</h1>
                <span @class([
                    'px-2 py-1 text-xs rounded-full font-medium',
                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' => $invoice->status === 'draft',
                    'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' => $invoice->status === 'issued',
                    'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' => $invoice->status === 'paid',
                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' => $invoice->status === 'partially_paid',
                    'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300' => $invoice->status === 'overdue',
                    'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' => $invoice->status === 'cancelled',
                ])>
                    {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                </span>
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            @can('issue', $invoice)
                <form action="{{ route('invoices.issue', $invoice) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        <i class="fas fa-paper-plane mr-2"></i> Issue Invoice
                    </button>
                </form>
            @endcan

            @can('update', $invoice)
                <a href="{{ route('invoices.edit', $invoice) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
            @endcan

            @can('create', [\App\Models\Payment::class, $invoice])
                <button type="button" @click="$dispatch('open-modal', 'record-payment')" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    <i class="fas fa-money-bill-wave mr-2"></i> Record Payment
                </button>
            @endcan

            @can('print', $invoice)
                <a href="{{ route('invoices.print', $invoice) }}" target="_blank" rel="noopener" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                    <i class="fas fa-print mr-2"></i> Print
                </a>
            @endcan

            @can('cancel', $invoice)
                <button type="button" @click="$dispatch('open-modal', 'cancel-invoice')" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                    <i class="fas fa-times mr-2"></i> Cancel
                </button>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Invoice Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-start">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Bill To:</h2>
                        <p class="mt-2 font-semibold text-gray-900 dark:text-white">{{ $invoice->customer->full_name }}</p>
                        <p class="text-gray-500 dark:text-gray-400">{{ $invoice->customer->phone }}</p>
                        @if($invoice->customer->address)
                            <p class="text-gray-500 dark:text-gray-400 max-w-xs">{{ $invoice->customer->address }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-gray-500 dark:text-gray-400">Date: <span class="font-semibold text-gray-900 dark:text-white">{{ $invoice->invoice_date->format('M d, Y') }}</span></p>
                        @if($invoice->due_date)
                            <p class="text-gray-500 dark:text-gray-400">Due Date: <span class="font-semibold text-gray-900 dark:text-white">{{ $invoice->due_date->format('M d, Y') }}</span></p>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Qty</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($invoice->items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->item_name }}</p>
                                        @if($item->description)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->description }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm text-gray-500 dark:text-gray-400 font-mono">
                                        {{ number_format($item->unit_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm text-gray-900 dark:text-white font-semibold font-mono">
                                        {{ number_format($item->line_total, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <td colspan="3" class="px-6 py-2 text-right text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Subtotal</td>
                                <td class="px-6 py-2 text-right text-sm font-bold text-gray-900 dark:text-white font-mono">{{ number_format($invoice->subtotal_amount, 2) }}</td>
                            </tr>
                            @if($invoice->discount_amount > 0)
                                <tr>
                                    <td colspan="3" class="px-6 py-2 text-right text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Discount</td>
                                    <td class="px-6 py-2 text-right text-sm font-bold text-red-600 font-mono">-{{ number_format($invoice->discount_amount, 2) }}</td>
                                </tr>
                            @endif
                            @if($invoice->tax_amount > 0)
                                <tr>
                                    <td colspan="3" class="px-6 py-2 text-right text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Tax</td>
                                    <td class="px-6 py-2 text-right text-sm font-bold text-gray-900 dark:text-white font-mono">{{ number_format($invoice->tax_amount, 2) }}</td>
                                </tr>
                            @endif
                            <tr class="bg-gray-100 dark:bg-gray-700">
                                <td colspan="3" class="px-6 py-3 text-right text-base font-bold text-gray-900 dark:text-white uppercase">Grand Total</td>
                                <td class="px-6 py-3 text-right text-base font-bold text-gray-900 dark:text-white font-mono">{{ number_format($invoice->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if($invoice->notes)
                <div class="bg-gray-50 dark:bg-gray-900/30 border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Notes:</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $invoice->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Sidebar: Payment Info & History -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 uppercase tracking-wider">Payment Summary</h2>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Total Invoiced</span>
                        <span class="font-bold text-gray-900 dark:text-white font-mono">{{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 dark:text-gray-400">Total Paid</span>
                        <span class="font-bold text-green-600 font-mono">{{ number_format($invoice->amount_paid, 2) }}</span>
                    </div>
                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900 dark:text-white">Balance Due</span>
                        <span class="text-xl font-black text-red-600 font-mono">{{ number_format($invoice->balance_due, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Payment History</h2>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($invoice->payments as $payment)
                        <div class="p-4 flex justify-between items-center gap-4">
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $payment->payment_date->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-tighter">{{ $payment->payment_method }} - {{ $payment->reference_number ?? 'No Ref' }}</p>
                                @if($payment->receipt)
                                    @can('view', $payment->receipt)
                                        <a href="{{ route('receipts.show', $payment->receipt) }}" class="text-xs text-blue-600 dark:text-blue-400">{{ $payment->receipt->receipt_number }}</a>
                                    @endcan
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold {{ $payment->status === 'valid' ? 'text-green-600' : 'text-red-600' }} font-mono">{{ number_format($payment->amount, 2) }}</p>
                                @if($payment->status === 'voided')
                                    <p class="text-[10px] bg-red-100 text-red-600 px-1 rounded inline-block">VOIDED</p>
                                @else
                                    @can('void', $payment)
                                        <button type="button" @click="$dispatch('open-modal', 'void-payment-{{ $payment->id }}')" class="mt-1 text-[11px] text-red-600 hover:text-red-800">
                                            Void payment
                                        </button>
                                    @endcan
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-sm text-gray-500 dark:text-gray-400">No payments recorded yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Record Payment Modal -->
    @can('create', [\App\Models\Payment::class, $invoice])
        <x-modal name="record-payment" maxWidth="lg">
            <form action="{{ route('payments.store', $invoice) }}" method="POST" class="p-6">
                @csrf
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Record Payment for {{ $invoice->invoice_number }}
                </h2>

            <div class="space-y-4">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                    <input type="number" name="amount" id="amount" step="0.01" min="0.01" max="{{ $invoice->balance_due }}" value="{{ $invoice->balance_due }}" required
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                </div>

                <div>
                    <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Date</label>
                    <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}" required
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method</label>
                    <select name="payment_method" id="payment_method" required
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Mobile Money">Mobile Money</option>
                        <option value="Card">Card</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div>
                    <label for="reference_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reference Number</label>
                    <input type="text" name="reference_number" id="reference_number"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                    <textarea name="notes" id="notes" rows="2"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" x-on:click="$dispatch('close-modal', 'record-payment')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    Record Payment
                </button>
            </div>
            </form>
        </x-modal>
    @endcan

    @foreach($invoice->payments->where('status', 'valid') as $payment)
        @can('void', $payment)
            <x-modal name="void-payment-{{ $payment->id }}" maxWidth="md">
                <form action="{{ route('payments.void', $payment) }}" method="POST" class="p-6">
                    @csrf
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        Void Payment
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        This keeps the payment in history but removes it from the invoice balance.
                    </p>
                    <div>
                        <label for="void_reason_{{ $payment->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reason *</label>
                        <textarea name="void_reason" id="void_reason_{{ $payment->id }}" rows="3" required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" x-on:click="$dispatch('close-modal', 'void-payment-{{ $payment->id }}')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                            Back
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                            Confirm Void
                        </button>
                    </div>
                </form>
            </x-modal>
        @endcan
    @endforeach

    <!-- Cancel Invoice Modal -->
    @can('cancel', $invoice)
        <x-modal name="cancel-invoice" maxWidth="md">
            <form action="{{ route('invoices.cancel', $invoice) }}" method="POST" class="p-6">
                @csrf
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Cancel Invoice {{ $invoice->invoice_number }}
                </h2>

            <div class="space-y-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Are you sure you want to cancel this invoice? This action cannot be undone.
                </p>

                <div>
                    <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reason for Cancellation *</label>
                    <textarea name="cancellation_reason" id="cancellation_reason" rows="3" required
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" x-on:click="$dispatch('close-modal', 'cancel-invoice')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                    Back
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                    Confirm Cancellation
                </button>
            </div>
            </form>
        </x-modal>
    @endcan
</x-layouts.app>
