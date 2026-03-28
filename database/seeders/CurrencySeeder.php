<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

final class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            [
                'name' => 'Ugandan Shilling',
                'code' => 'UGX',
                'symbol' => 'UGX',
                'decimal_places' => 0,
                'exchange_rate' => 1.0,
                'is_default' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'US Dollar',
                'code' => 'USD',
                'symbol' => '$',
                'decimal_places' => 2,
                'exchange_rate' => 3800.0,
                'is_default' => false,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Kenyan Shilling',
                'code' => 'KES',
                'symbol' => 'KES',
                'decimal_places' => 0,
                'exchange_rate' => 30.0,
                'is_default' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($currencies as $attributes) {
            Currency::query()->updateOrCreate(
                ['code' => $attributes['code']],
                $attributes,
            );
        }
    }
}
