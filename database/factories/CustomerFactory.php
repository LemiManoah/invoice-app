<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'customer_code' => 'CUST-'.strtoupper(fake()->unique()->bothify('#####')),
            'full_name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'alternative_phone' => null,
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->address(),
            'gender' => fake()->randomElement(['male', 'female']),
            'date_of_birth' => fake()->date(),
            'notes' => null,
            'created_by' => null,
        ];
    }
}
