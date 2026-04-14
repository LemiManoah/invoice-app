<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentMethod>
 */
class PaymentMethodFactory extends Factory
{
    protected $model = PaymentMethod::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement(['Cash', 'Card', 'Bank Transfer', 'Mobile Money', 'Cheque']);

        return [
            'name' => $name,
            'slug' => strtolower(str_replace(' ', '-', $name)).'-'.fake()->unique()->numerify('####'),
            'is_active' => true,
            'sort_order' => 0,
            'notes' => null,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
