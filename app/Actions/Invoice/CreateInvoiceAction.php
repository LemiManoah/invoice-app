<?php

declare(strict_types=1);

namespace App\Actions\Invoice;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final readonly class CreateInvoiceAction
{
    public function __construct(
        private CreateAuditLogAction $createAuditLog,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): Invoice
    {
        return DB::transaction(function () use ($data): Invoice {
            $subtotal = collect($data['items'])->sum(
                static fn (array $item): float => (float) $item['quantity'] * (float) $item['unit_price']
            );

            $invoice = new Invoice($data);
            $invoice->invoice_number = 'INV-'.strtoupper(uniqid());
            $invoice->status = 'draft';
            $invoice->created_by = Auth::id();
            $invoice->subtotal_amount = $subtotal;
            $invoice->discount_amount = $data['discount_amount'] ?? 0;
            $invoice->tax_amount = $data['tax_amount'] ?? 0;
            $invoice->total_amount = ($subtotal - (float) $invoice->discount_amount) + (float) $invoice->tax_amount;
            $invoice->balance_due = $invoice->total_amount;
            $invoice->save();

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
            $this->createAuditLog->handle('invoice.created', $invoice, null, $invoice->toArray());

            return $invoice;
        });
    }
}
