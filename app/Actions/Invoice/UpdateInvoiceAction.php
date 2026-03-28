<?php

declare(strict_types=1);

namespace App\Actions\Invoice;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

final readonly class UpdateInvoiceAction
{
    public function __construct(
        private CreateAuditLogAction $createAuditLog,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Invoice $invoice, array $data): Invoice
    {
        return DB::transaction(function () use ($invoice, $data): Invoice {
            $before = $invoice->load('items')->toArray();
            $subtotal = collect($data['items'])->sum(
                static fn (array $item): float => (float) $item['quantity'] * (float) $item['unit_price']
            );

            $invoice->update([
                'customer_id' => $data['customer_id'],
                'order_id' => $data['order_id'] ?? null,
                'invoice_date' => $data['invoice_date'],
                'due_date' => $data['due_date'] ?? null,
                'notes' => $data['notes'] ?? '',
                'subtotal_amount' => $subtotal,
                'discount_amount' => $data['discount_amount'] ?? 0,
                'tax_amount' => $data['tax_amount'] ?? 0,
                'total_amount' => ($subtotal - (float) ($data['discount_amount'] ?? 0)) + (float) ($data['tax_amount'] ?? 0),
                'balance_due' => ($subtotal - (float) ($data['discount_amount'] ?? 0)) + (float) ($data['tax_amount'] ?? 0),
                'amount_paid' => 0,
            ]);

            $invoice->items()->delete();

            foreach ($data['items'] as $item) {
                $invoice->items()->create([
                    'item_name' => $item['item_name'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => (float) $item['quantity'] * (float) $item['unit_price'],
                ]);
            }

            $invoice->load('items');
            $this->createAuditLog->handle('invoice.updated', $invoice, $before, $invoice->toArray());

            return $invoice;
        });
    }
}
