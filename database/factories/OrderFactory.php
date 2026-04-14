<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'order_number' => 'ORD-'.strtoupper(fake()->unique()->bothify('########')),
            'customer_id' => Customer::factory(),
            'currency_id' => fn () => Currency::query()->first()?->id ?? Currency::factory()->create()->id,
            'order_date' => now()->toDateString(),
            'promised_delivery_date' => now()->addDays(14)->toDateString(),
            'actual_completion_date' => null,
            'status' => 'confirmed',
            'priority' => 'medium',
            'notes' => null,
            'assigned_to' => null,
            'created_by' => null,
        ];
    }
}
