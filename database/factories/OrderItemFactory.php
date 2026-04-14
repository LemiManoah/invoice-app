<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_id' => null,
            'garment_type' => fake()->randomElement(['suit', 'shirt', 'trouser']),
            'description' => fake()->sentence(),
            'quantity' => 1,
            'unit_price' => 100,
            'style_notes' => null,
            'fabric_details' => null,
            'color' => null,
            'lining_details' => null,
            'button_details' => null,
            'monogram_text' => null,
            'urgent_flag' => false,
        ];
    }
}
