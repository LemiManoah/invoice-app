<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Cash', 'sort_order' => 1],
            ['name' => 'Bank Transfer', 'sort_order' => 2],
            ['name' => 'Mobile Money', 'sort_order' => 3],
            ['name' => 'Card', 'sort_order' => 4],
            ['name' => 'Check', 'sort_order' => 5],
            ['name' => 'Other', 'sort_order' => 6],
        ] as $method) {
            PaymentMethod::query()->updateOrCreate(
                ['name' => $method['name']],
                [
                    'slug' => Str::slug($method['name']),
                    'is_active' => true,
                    'sort_order' => $method['sort_order'],
                    'notes' => null,
                ],
            );
        }
    }
}
