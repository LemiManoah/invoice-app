<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@suits.com',
        ]);

        // Seed data in correct order to respect foreign key constraints
        $this->call([
            ExpenseCategorySeeder::class,
            CustomerSeeder::class,
            MeasurementSeeder::class,
            OrderSeeder::class,
            InvoiceSeeder::class,
            PaymentSeeder::class,
            ExpenseSeeder::class,
        ]);
    }
}
