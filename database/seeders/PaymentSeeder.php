<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

final class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $payments = [
            [
                'invoice_id' => 1,
                'currency_id' => 1,
                'payment_date' => now()->subDays(50),
                'amount' => 1296.00,
                'payment_method_id' => $this->paymentMethodId('Card'),
                'payment_method' => 'Card',
                'reference_number' => 'CC-1234567890',
                'notes' => 'Full payment via card',
                'status' => 'valid',
                'received_by' => 1,
                'voided_at' => null,
                'voided_by' => null,
                'void_reason' => null,
            ],
            [
                'invoice_id' => 2,
                'currency_id' => 1,
                'payment_date' => now()->subDays(80),
                'amount' => 1500.00,
                'payment_method_id' => $this->paymentMethodId('Bank Transfer'),
                'payment_method' => 'Bank Transfer',
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
                'currency_id' => 1,
                'payment_date' => now()->subDays(60),
                'amount' => 1416.00,
                'payment_method_id' => $this->paymentMethodId('Cash'),
                'payment_method' => 'Cash',
                'reference_number' => 'CASH-002',
                'notes' => 'Final payment in cash',
                'status' => 'valid',
                'received_by' => 1,
                'voided_at' => null,
                'voided_by' => null,
                'void_reason' => null,
            ],
            [
                'invoice_id' => 3,
                'currency_id' => 2,
                'payment_date' => now()->subDays(35),
                'amount' => 1000.00,
                'payment_method_id' => $this->paymentMethodId('Card'),
                'payment_method' => 'Card',
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
                'currency_id' => 2,
                'payment_date' => now()->subDays(20),
                'amount' => 1000.00,
                'payment_method_id' => $this->paymentMethodId('Bank Transfer'),
                'payment_method' => 'Bank Transfer',
                'reference_number' => 'BT-SUIT-002',
                'notes' => 'Second installment',
                'status' => 'valid',
                'received_by' => 1,
                'voided_at' => null,
                'voided_by' => null,
                'void_reason' => null,
            ],
            [
                'invoice_id' => 1,
                'currency_id' => 1,
                'payment_date' => now()->subDays(55),
                'amount' => 100.00,
                'payment_method_id' => $this->paymentMethodId('Check'),
                'payment_method' => 'Check',
                'reference_number' => 'CHK-VOID-001',
                'notes' => 'This payment was voided due to insufficient funds',
                'status' => 'voided',
                'received_by' => 1,
                'voided_at' => now()->subDays(54),
                'voided_by' => 1,
                'void_reason' => 'Insufficient funds',
            ],
        ];

        Payment::query()->insert($payments);
    }

    private function paymentMethodId(string $name): ?int
    {
        return PaymentMethod::query()->where('name', $name)->value('id');
    }
}
