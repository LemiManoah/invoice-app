<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

final class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $expenses = [
            [
                'expense_category_id' => 1,
                'expense_date' => now()->subDays(85),
                'amount' => 2500.00,
                'payment_method_id' => $this->paymentMethodId('Bank Transfer'),
                'payment_method' => 'Bank Transfer',
                'vendor_name' => 'Premium Textiles Ltd',
                'reference_number' => 'FAB-001',
                'description' => 'Wool fabric bulk purchase',
                'notes' => 'High-quality merino wool for winter collection',
                'status' => 'valid',
                'created_by' => 1,
            ],
            [
                'expense_category_id' => 1,
                'expense_date' => now()->subDays(60),
                'amount' => 1200.00,
                'payment_method_id' => $this->paymentMethodId('Card'),
                'payment_method' => 'Card',
                'vendor_name' => 'Silk & Co',
                'reference_number' => 'FAB-002',
                'description' => 'Silk lining materials',
                'notes' => 'Various colors for custom orders',
                'status' => 'valid',
                'created_by' => 1,
            ],
            [
                'expense_category_id' => 2,
                'expense_date' => now()->subDays(30),
                'amount' => 3500.00,
                'payment_method_id' => $this->paymentMethodId('Bank Transfer'),
                'payment_method' => 'Bank Transfer',
                'vendor_name' => 'City Properties Inc',
                'reference_number' => 'RENT-MAR',
                'description' => 'Monthly shop rent',
                'notes' => 'March 2026 rent payment',
                'status' => 'valid',
                'created_by' => 1,
            ],
            [
                'expense_category_id' => 2,
                'expense_date' => now()->subDays(15),
                'amount' => 450.00,
                'payment_method_id' => $this->paymentMethodId('Bank Transfer'),
                'payment_method' => 'Bank Transfer',
                'vendor_name' => 'Power Company',
                'reference_number' => 'UTIL-001',
                'description' => 'Electricity bill',
                'notes' => 'Monthly electricity consumption',
                'status' => 'valid',
                'created_by' => 1,
            ],
            [
                'expense_category_id' => 3,
                'expense_date' => now()->subDays(25),
                'amount' => 4500.00,
                'payment_method_id' => $this->paymentMethodId('Bank Transfer'),
                'payment_method' => 'Bank Transfer',
                'vendor_name' => 'Master Tailor - James Wilson',
                'reference_number' => 'SAL-JW',
                'description' => 'Monthly salary',
                'notes' => 'March 2026 salary payment',
                'status' => 'valid',
                'created_by' => 1,
            ],
            [
                'expense_category_id' => 3,
                'expense_date' => now()->subDays(25),
                'amount' => 2800.00,
                'payment_method_id' => $this->paymentMethodId('Bank Transfer'),
                'payment_method' => 'Bank Transfer',
                'vendor_name' => 'Assistant - Maria Garcia',
                'reference_number' => 'SAL-MG',
                'description' => 'Monthly salary',
                'notes' => 'March 2026 salary payment',
                'status' => 'valid',
                'created_by' => 1,
            ],
            [
                'expense_category_id' => 4,
                'expense_date' => now()->subDays(45),
                'amount' => 1200.00,
                'payment_method_id' => $this->paymentMethodId('Card'),
                'payment_method' => 'Card',
                'vendor_name' => 'Sewing Machines Pro',
                'reference_number' => 'EQUIP-001',
                'description' => 'New sewing machine',
                'notes' => 'Industrial grade sewing machine',
                'status' => 'valid',
                'created_by' => 1,
            ],
            [
                'expense_category_id' => 5,
                'expense_date' => now()->subDays(20),
                'amount' => 800.00,
                'payment_method_id' => $this->paymentMethodId('Card'),
                'payment_method' => 'Card',
                'vendor_name' => 'Social Media Ads',
                'reference_number' => 'MKT-001',
                'description' => 'Facebook and Instagram ads',
                'notes' => 'Spring collection promotion',
                'status' => 'valid',
                'created_by' => 1,
            ],
            [
                'expense_category_id' => 5,
                'expense_date' => now()->subDays(10),
                'amount' => 350.00,
                'payment_method_id' => $this->paymentMethodId('Cash'),
                'payment_method' => 'Cash',
                'vendor_name' => 'Local Magazine',
                'reference_number' => 'MKT-002',
                'description' => 'Magazine advertisement',
                'notes' => 'Quarterly business magazine feature',
                'status' => 'valid',
                'created_by' => 1,
            ],
            [
                'expense_category_id' => 6,
                'expense_date' => now()->subDays(35),
                'amount' => 250.00,
                'payment_method_id' => $this->paymentMethodId('Cash'),
                'payment_method' => 'Cash',
                'vendor_name' => 'Office Depot',
                'reference_number' => 'OFF-001',
                'description' => 'Office supplies',
                'notes' => 'Paper, pens, printer ink, etc.',
                'status' => 'valid',
                'created_by' => 1,
            ],
            [
                'expense_category_id' => 7,
                'expense_date' => now()->subDays(12),
                'amount' => 150.00,
                'payment_method_id' => $this->paymentMethodId('Card'),
                'payment_method' => 'Card',
                'vendor_name' => 'Express Delivery Co',
                'reference_number' => 'SHIP-001',
                'description' => 'Customer delivery',
                'notes' => 'Express delivery to Emily Rodriguez',
                'status' => 'valid',
                'created_by' => 1,
            ],
            [
                'expense_category_id' => 8,
                'expense_date' => now()->subDays(40),
                'amount' => 500.00,
                'payment_method_id' => $this->paymentMethodId('Bank Transfer'),
                'payment_method' => 'Bank Transfer',
                'vendor_name' => 'Accounting Firm LLP',
                'reference_number' => 'PROF-001',
                'description' => 'Monthly accounting services',
                'notes' => 'Bookkeeping and tax preparation',
                'status' => 'valid',
                'created_by' => 1,
            ],
            [
                'expense_category_id' => 1,
                'expense_date' => now()->subDays(70),
                'amount' => 300.00,
                'payment_method_id' => $this->paymentMethodId('Cash'),
                'payment_method' => 'Cash',
                'vendor_name' => 'Fabric Store',
                'reference_number' => 'VOID-001',
                'description' => 'Fabric purchase',
                'notes' => 'This expense was voided - wrong item ordered',
                'status' => 'voided',
                'created_by' => 1,
                'voided_at' => now()->subDays(69),
                'voided_by' => 1,
                'void_reason' => 'Wrong item ordered and returned to vendor.',
            ],
        ];

        $expenses = array_map(static fn (array $expense): array => [
            'currency_id' => 1,
            'voided_at' => null,
            'voided_by' => null,
            'void_reason' => null,
            ...$expense,
        ], $expenses);

        Expense::query()->insert($expenses);
    }

    private function paymentMethodId(string $name): ?int
    {
        return PaymentMethod::query()->where('name', $name)->value('id');
    }
}
