<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed data in correct order to respect foreign key constraints
        $this->call([
            RoleAndPermissionSeeder::class,
            UserSeeder::class,
            CurrencySeeder::class,
            PaymentMethodSeeder::class,
            ExpenseCategorySeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            CustomerSeeder::class,
            MeasurementSeeder::class,
            OrderSeeder::class,
            InvoiceSeeder::class,
            PaymentSeeder::class,
            ExpenseSeeder::class,
        ]);
    }
}
