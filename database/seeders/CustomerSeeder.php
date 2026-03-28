<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'customer_code' => 'CUST001',
                'full_name' => 'John Anderson',
                'phone' => '+1-555-0101',
                'email' => 'john.anderson@email.com',
                'address' => '123 Business Ave, Suite 100, New York, NY 10001',
                'gender' => 'male',
                'date_of_birth' => '1985-03-15',
                'notes' => 'Regular customer, prefers classic business suits',
                'created_by' => 1,
            ],
            [
                'customer_code' => 'CUST002',
                'full_name' => 'Sarah Mitchell',
                'phone' => '+1-555-0102',
                'email' => 'sarah.mitchell@email.com',
                'address' => '456 Fashion Blvd, Apt 2B, Los Angeles, CA 90028',
                'gender' => 'female',
                'date_of_birth' => '1990-07-22',
                'notes' => 'Executive client, needs power suits for board meetings',
                'created_by' => 1,
            ],
            [
                'customer_code' => 'CUST003',
                'full_name' => 'Michael Chen',
                'phone' => '+1-555-0103',
                'email' => 'michael.chen@email.com',
                'address' => '789 Corporate Plaza, Floor 15, Chicago, IL 60601',
                'gender' => 'male',
                'date_of_birth' => '1982-11-08',
                'notes' => 'Wedding client, ordered tuxedo and formal wear',
                'created_by' => 1,
            ],
            [
                'customer_code' => 'CUST004',
                'full_name' => 'Emily Rodriguez',
                'phone' => '+1-555-0104',
                'email' => 'emily.rodriguez@email.com',
                'address' => '321 Style Street, Miami, FL 33101',
                'gender' => 'female',
                'date_of_birth' => '1988-05-30',
                'notes' => 'Fashion-forward client, likes modern cuts and colors',
                'created_by' => 1,
            ],
            [
                'customer_code' => 'CUST005',
                'full_name' => 'David Thompson',
                'phone' => '+1-555-0105',
                'email' => 'david.thompson@email.com',
                'address' => '555 Executive Drive, Boston, MA 02101',
                'gender' => 'male',
                'date_of_birth' => '1979-09-12',
                'notes' => 'Long-term client, orders seasonal wardrobe updates',
                'created_by' => 1,
            ],
        ];

        Customer::insert($customers);
    }
}
