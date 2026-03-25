<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Fabric and Materials', 'description' => 'Fabric, buttons, thread, lining, etc.'],
            ['name' => 'Tailor Labor', 'description' => 'Wages or commissions paid to tailors.'],
            ['name' => 'Transport', 'description' => 'Logistics, delivery, and travel costs.'],
            ['name' => 'Rent', 'description' => 'Workshop or office rent.'],
            ['name' => 'Utilities', 'description' => 'Electricity, water, internet.'],
            ['name' => 'Marketing', 'description' => 'Ads, social media, photography.'],
            ['name' => 'Packaging', 'description' => 'Bags, boxes, labels.'],
            ['name' => 'Equipment and Repairs', 'description' => 'Machines, tools, maintenance.'],
            ['name' => 'Meals and Refreshments', 'description' => 'Staff or client refreshments.'],
            ['name' => 'Miscellaneous', 'description' => 'Other business expenses.'],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::updateOrCreate(['name' => $category['name']], $category);
        }
    }
}
