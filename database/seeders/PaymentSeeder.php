<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $payments = [
            // Payment for Invoice 1 - John Anderson (fully paid)
            [
                'invoice_id' => 1,
                'payment_date' => now()->subDays(50),
                'amount' => 1296.00,
                'payment_method' => 'credit_card',
                'reference_number' => 'CC-1234567890',
                'notes' => 'Full payment via credit card',
                'status' => 'valid',
                'received_by' => 1,
                'voided_at' => null,
                'voided_by' => null,
                'void_reason' => null,
            ],
            // Payment for Invoice 2 - Michael Chen (fully paid)
            [
                'invoice_id' => 2,
                'payment_date' => now()->subDays(80),
                'amount' => 1500.00,
                'payment_method' => 'bank_transfer',
                'reference_number' => 'BT-WEDDING-001',
                'notes' => 'Initial payment for wedding package',
                'status' => 'valid',
                'received_by' => 1,
                'voided_at' => null,
                'voided_by' => null,
                'void_reason' => null,
            ],
            [
                'invoice_id' => 2,
                'payment_date' => now()->subDays(60),
                'amount' => 1416.00,
                'payment_method' => 'cash',
                'reference_number' => 'CASH-002',
                'notes' => 'Final payment in cash',
                'status' => 'valid',
                'received_by' => 1,
                'voided_at' => null,
                'voided_by' => null,
                'void_reason' => null,
            ],
            // Payments for Invoice 3 - Sarah Mitchell (partially paid)
            [
                'invoice_id' => 3,
                'payment_date' => now()->subDays(35),
                'amount' => 1000.00,
                'payment_method' => 'credit_card',
                'reference_number' => 'CC-9876543210',
                'notes' => 'Initial payment for power suits',
                'status' => 'valid',
                'received_by' => 1,
                'voided_at' => null,
                'voided_by' => null,
                'void_reason' => null,
            ],
            [
                'invoice_id' => 3,
                'payment_date' => now()->subDays(20),
                'amount' => 1000.00,
                'payment_method' => 'bank_transfer',
                'reference_number' => 'BT-SUIT-002',
                'notes' => 'Second installment',
                'status' => 'valid',
                'received_by' => 1,
                'voided_at' => null,
                'voided_by' => null,
                'void_reason' => null,
            ],
            // Voided payment example (for demonstration)
            [
                'invoice_id' => 1,
                'payment_date' => now()->subDays(55),
                'amount' => 100.00,
                'payment_method' => 'check',
                'reference_number' => 'CHK-VOID-001',
                'notes' => 'This payment was voided due to insufficient funds',
                'status' => 'voided',
                'received_by' => 1,
                'voided_at' => now()->subDays(54),
                'voided_by' => 1,
                'void_reason' => 'Insufficient funds',
            ],
        ];

        Payment::insert($payments);
    }
}
