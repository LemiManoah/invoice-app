<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default user
        User::query()->firstOrCreate([
            'email' => 'admin@suits.com',
        ], [
            'name' => 'Admin User',
            'password' => bcrypt('password'),
        ]);

        // Seed data in correct order to respect foreign key constraints
        $this->call([
            RoleAndPermissionSeeder::class,
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
