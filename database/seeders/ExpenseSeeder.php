<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $expenses = [
            // Fabric purchases
            [
                'expense_category_id' => 1, // Fabrics & Materials
                'expense_date' => now()->subDays(85),
                'amount' => 2500.00,
                'payment_method' => 'bank_transfer',
                'vendor_name' => 'Premium Textiles Ltd',
                'reference_number' => 'FAB-001',
                'description' => 'Wool fabric bulk purchase',
                'notes' => 'High-quality merino wool for winter collection',
                'status' => 'approved',
                'created_by' => 1,
            ],
            [
                'expense_category_id' => 1,
                'expense_date' => now()->subDays(60),
                'amount' => 1200.00,
                'payment_method' => 'credit_card',
                'vendor_name' => 'Silk & Co',
                'reference_number' => 'FAB-002',
                'description' => 'Silk lining materials',
                'notes' => 'Various colors for custom orders',
                'status' => 'approved',
                'created_by' => 1,
            ],
            // Rent expenses
            [
                'expense_category_id' => 2, // Rent & Utilities
                'expense_date' => now()->subDays(30),
                'amount' => 3500.00,
                'payment_method' => 'bank_transfer',
                'vendor_name' => 'City Properties Inc',
                'reference_number' => 'RENT-MAR',
                'description' => 'Monthly shop rent',
                'notes' => 'March 2026 rent payment',
                'status' => 'approved',
                'created_by' => 1,
            ],
            [
                'expense_category_id' => 2,
                'expense_date' => now()->subDays(15),
                'amount' => 450.00,
                'payment_method' => 'bank_transfer',
                'vendor_name' => 'Power Company',
                'reference_number' => 'UTIL-001',
                'description' => 'Electricity bill',
                'notes' => 'Monthly electricity consumption',
                'status' => 'approved',
                'created_by' => 1,
            ],
            // Salaries
            [
                'expense_category_id' => 3, // Salaries & Wages
                'expense_date' => now()->subDays(25),
                'amount' => 4500.00,
                'payment_method' => 'bank_transfer',
                'vendor_name' => 'Master Tailor - James Wilson',
                'reference_number' => 'SAL-JW',
                'description' => 'Monthly salary',
                'notes' => 'March 2026 salary payment',
                'status' => 'approved',
                'created_by' => 1,
            ],
            [
                'expense_category_id' => 3,
                'expense_date' => now()->subDays(25),
                'amount' => 2800.00,
                'payment_method' => 'bank_transfer',
                'vendor_name' => 'Assistant - Maria Garcia',
                'reference_number' => 'SAL-MG',
                'description' => 'Monthly salary',
                'notes' => 'March 2026 salary payment',
                'status' => 'approved',
                'created_by' => 1,
            ],
            // Equipment
            [
                'expense_category_id' => 4, // Equipment & Tools
                'expense_date' => now()->subDays(45),
                'amount' => 1200.00,
                'payment_method' => 'credit_card',
                'vendor_name' => 'Sewing Machines Pro',
                'reference_number' => 'EQUIP-001',
                'description' => 'New sewing machine',
                'notes' => 'Industrial grade sewing machine',
                'status' => 'approved',
                'created_by' => 1,
            ],
            // Marketing
            [
                'expense_category_id' => 5, // Marketing & Advertising
                'expense_date' => now()->subDays(20),
                'amount' => 800.00,
                'payment_method' => 'credit_card',
                'vendor_name' => 'Social Media Ads',
                'reference_number' => 'MKT-001',
                'description' => 'Facebook and Instagram ads',
                'notes' => 'Spring collection promotion',
                'status' => 'approved',
                'created_by' => 1,
            ],
            [
                'expense_category_id' => 5,
                'expense_date' => now()->subDays(10),
                'amount' => 350.00,
                'payment_method' => 'cash',
                'vendor_name' => 'Local Magazine',
                'reference_number' => 'MKT-002',
                'description' => 'Magazine advertisement',
                'notes' => 'Quarterly business magazine feature',
                'status' => 'approved',
                'created_by' => 1,
            ],
            // Office Supplies
            [
                'expense_category_id' => 6, // Office Supplies
                'expense_date' => now()->subDays(35),
                'amount' => 250.00,
                'payment_method' => 'cash',
                'vendor_name' => 'Office Depot',
                'reference_number' => 'OFF-001',
                'description' => 'Office supplies',
                'notes' => 'Paper, pens, printer ink, etc.',
                'status' => 'approved',
                'created_by' => 1,
            ],
            // Shipping
            [
                'expense_category_id' => 7, // Shipping & Delivery
                'expense_date' => now()->subDays(12),
                'amount' => 150.00,
                'payment_method' => 'credit_card',
                'vendor_name' => 'Express Delivery Co',
                'reference_number' => 'SHIP-001',
                'description' => 'Customer delivery',
                'notes' => 'Express delivery to Emily Rodriguez',
                'status' => 'approved',
                'created_by' => 1,
            ],
            // Professional Services
            [
                'expense_category_id' => 8, // Professional Services
                'expense_date' => now()->subDays(40),
                'amount' => 500.00,
                'payment_method' => 'bank_transfer',
                'vendor_name' => 'Accounting Firm LLP',
                'reference_number' => 'PROF-001',
                'description' => 'Monthly accounting services',
                'notes' => 'Bookkeeping and tax preparation',
                'status' => 'approved',
                'created_by' => 1,
            ],
            // Voided expense example (kept simple)
            [
                'expense_category_id' => 1,
                'expense_date' => now()->subDays(70),
                'amount' => 300.00,
                'payment_method' => 'cash',
                'vendor_name' => 'Fabric Store',
                'reference_number' => 'VOID-001',
                'description' => 'Fabric purchase',
                'notes' => 'This expense was voided - wrong item ordered',
                'status' => 'voided',
                'created_by' => 1,
            ],
        ];

        Expense::insert($expenses);
    }
}
