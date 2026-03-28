<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $invoices = [
            [
                'invoice_number' => 'INV-2026-001',
                'customer_id' => 1, // John Anderson
                'currency_id' => 1,
                'order_id' => 1, // Order 1
                'invoice_date' => now()->subDays(58),
                'due_date' => now()->subDays(28),
                'status' => 'paid',
                'subtotal_amount' => 1200.00,
                'discount_amount' => 0.00,
                'tax_amount' => 96.00,
                'total_amount' => 1296.00,
                'amount_paid' => 1296.00,
                'balance_due' => 0.00,
                'notes' => 'Payment received in full',
                'created_by' => 1,
                'issued_at' => now()->subDays(58),
            ],
            [
                'invoice_number' => 'INV-2026-002',
                'customer_id' => 3, // Michael Chen
                'currency_id' => 1,
                'order_id' => 3, // Order 3
                'invoice_date' => now()->subDays(88),
                'due_date' => now()->subDays(58),
                'status' => 'paid',
                'subtotal_amount' => 2800.00,
                'discount_amount' => 100.00,
                'tax_amount' => 216.00,
                'total_amount' => 2916.00,
                'amount_paid' => 2916.00,
                'balance_due' => 0.00,
                'notes' => 'Wedding package discount applied',
                'created_by' => 1,
                'issued_at' => now()->subDays(88),
            ],
            [
                'invoice_number' => 'INV-2026-003',
                'customer_id' => 2, // Sarah Mitchell
                'currency_id' => 2,
                'order_id' => 2, // Order 2
                'invoice_date' => now()->subDays(43),
                'due_date' => now()->subDays(13),
                'status' => 'partially_paid',
                'subtotal_amount' => 3600.00,
                'discount_amount' => 0.00,
                'tax_amount' => 288.00,
                'total_amount' => 3888.00,
                'amount_paid' => 2000.00,
                'balance_due' => 1888.00,
                'notes' => 'Partial payment received, balance due soon',
                'created_by' => 1,
                'issued_at' => now()->subDays(43),
            ],
            [
                'invoice_number' => 'INV-2026-004',
                'customer_id' => 4, // Emily Rodriguez
                'currency_id' => 1,
                'order_id' => null, // Direct invoice, not linked to order
                'invoice_date' => now()->subDays(5),
                'due_date' => now()->addDays(25),
                'status' => 'draft',
                'subtotal_amount' => 0.00,
                'discount_amount' => 0.00,
                'tax_amount' => 0.00,
                'total_amount' => 0.00,
                'amount_paid' => 0.00,
                'balance_due' => 0.00,
                'notes' => 'Draft invoice for consultation services',
                'created_by' => 1,
                'issued_at' => null,
            ],
            [
                'invoice_number' => 'INV-2026-005',
                'customer_id' => 5, // David Thompson
                'currency_id' => 1,
                'order_id' => 5, // Order 5
                'invoice_date' => now()->subDays(8),
                'due_date' => now()->addDays(22),
                'status' => 'issued',
                'subtotal_amount' => 1900.00,
                'discount_amount' => 50.00,
                'tax_amount' => 148.00,
                'total_amount' => 1998.00,
                'amount_paid' => 0.00,
                'balance_due' => 1998.00,
                'notes' => 'Seasonal discount applied',
                'created_by' => 1,
                'issued_at' => now()->subDays(8),
            ],
        ];

        Invoice::insert($invoices);

        // Get the inserted invoices with their IDs
        $insertedInvoices = Invoice::all();

        $invoiceItems = [
            // Invoice 1 - John Anderson's suit
            [
                'invoice_id' => $insertedInvoices[0]->id,
                'item_name' => 'Navy Business Suit',
                'description' => 'Classic two-button navy suit with pinstripes',
                'quantity' => 1,
                'unit_price' => 1200.00,
                'line_total' => 1200.00,
            ],
            // Invoice 2 - Michael Chen's wedding wear
            [
                'invoice_id' => $insertedInvoices[1]->id,
                'item_name' => 'Black Tuxedo with Tails',
                'description' => 'Traditional formal tuxedo',
                'quantity' => 1,
                'unit_price' => 2500.00,
                'line_total' => 2500.00,
            ],
            [
                'invoice_id' => $insertedInvoices[1]->id,
                'item_name' => 'Matching Waistcoat',
                'description' => 'Double-breasted waistcoat',
                'quantity' => 1,
                'unit_price' => 300.00,
                'line_total' => 300.00,
            ],
            // Invoice 3 - Sarah Mitchell's power suits
            [
                'invoice_id' => $insertedInvoices[2]->id,
                'item_name' => 'Charcoal Grey Power Suit',
                'description' => 'Executive cut power suits',
                'quantity' => 2,
                'unit_price' => 1500.00,
                'line_total' => 3000.00,
            ],
            [
                'invoice_id' => $insertedInvoices[2]->id,
                'item_name' => 'White Silk Blouses',
                'description' => 'French cuff blouses',
                'quantity' => 3,
                'unit_price' => 200.00,
                'line_total' => 600.00,
            ],
            // Invoice 5 - David Thompson's seasonal suits
            [
                'invoice_id' => $insertedInvoices[4]->id,
                'item_name' => 'Seasonal Business Suits',
                'description' => 'Classic fit business suits',
                'quantity' => 2,
                'unit_price' => 950.00,
                'line_total' => 1900.00,
            ],
        ];

        InvoiceItem::insert($invoiceItems);
    }
}
