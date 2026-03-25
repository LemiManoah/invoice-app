<?php

namespace App\Actions\Invoice;

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateInvoiceAction
{
    public function __invoke(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {
            $invoice = new Invoice($data);
            $invoice->invoice_number = 'INV-'.strtoupper(uniqid());
            $invoice->status = 'draft';
            $invoice->created_by = Auth::id();
            $subtotal = 0;
            foreach ($data['items'] as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }
            $invoice->subtotal_amount = $subtotal;
            $invoice->discount_amount = $data['discount_amount'] ?? 0;
            $invoice->tax_amount = $data['tax_amount'] ?? 0;
            $invoice->total_amount = ($subtotal - $invoice->discount_amount) + $invoice->tax_amount;
            $invoice->balance_due = $invoice->total_amount;
            $invoice->save();

            foreach ($data['items'] as $item) {
                $invoice->items()->create([
                    'item_name' => $item['item_name'],
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            return $invoice;
        });
    }
}
