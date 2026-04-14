<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Currency>
 */
class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word().' Dollar',
            'code' => strtoupper(fake()->unique()->lexify('???')),
            'symbol' => fake()->randomElement(['$', '€', '£', 'UGX', 'KES']),
            'decimal_places' => 2,
            'exchange_rate' => 1.000000,
            'is_default' => false,
            'is_active' => true,
            'sort_order' => 0,
        ];
    }

    public function default(): static
    {
        return $this->state(fn () => ['is_default' => true]);
    }

    public function ugx(): static
    {
        return $this->state(fn () => [
            'name' => 'Ugandan Shilling',
            'code' => 'UGX',
            'symbol' => 'UGX',
            'decimal_places' => 0,
            'exchange_rate' => 1.000000,
            'is_default' => true,
        ]);
    }

    public function usd(): static
    {
        return $this->state(fn () => [
            'name' => 'US Dollar',
            'code' => 'USD',
            'symbol' => '$',
            'decimal_places' => 2,
            'exchange_rate' => 3800.000000,
        ]);
    }

    public function kes(): static
    {
        return $this->state(fn () => [
            'name' => 'Kenyan Shilling',
            'code' => 'KES',
            'symbol' => 'KSh',
            'decimal_places' => 0,
            'exchange_rate' => 30.000000,
        ]);
    }
}
