<?php

namespace App\Actions\Invoice;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class UpdateInvoiceAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLog,
    ) {
    }

    public function __invoke(Invoice $invoice, array $data): Invoice
    {
        return DB::transaction(function () use ($invoice, $data) {
            $before = $invoice->load('items')->toArray();
            $subtotal = 0;

            foreach ($data['items'] as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $invoice->update([
                'customer_id' => $data['customer_id'],
                'order_id' => $data['order_id'] ?? null,
                'invoice_date' => $data['invoice_date'],
                'due_date' => $data['due_date'] ?? null,
                'notes' => $data['notes'] ?? '',
                'subtotal_amount' => $subtotal,
                'discount_amount' => $data['discount_amount'] ?? 0,
                'tax_amount' => $data['tax_amount'] ?? 0,
                'total_amount' => ($subtotal - ($data['discount_amount'] ?? 0)) + ($data['tax_amount'] ?? 0),
                'balance_due' => ($subtotal - ($data['discount_amount'] ?? 0)) + ($data['tax_amount'] ?? 0),
                'amount_paid' => 0,
            ]);

            $invoice->items()->delete();
            foreach ($data['items'] as $item) {
                $invoice->items()->create([
                    'item_name' => $item['item_name'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            $invoice->load('items');

            ($this->createAuditLog)('invoice.updated', $invoice, $before, $invoice->toArray());

            return $invoice;
        });
    }
}
