<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'currency_id' => fn () => Currency::query()->first()?->id ?? Currency::factory()->create()->id,
            'payment_date' => now()->toDateString(),
            'amount' => 100,
            'payment_method_id' => fn () => PaymentMethod::query()->firstOrCreate(
                ['name' => 'Cash'],
                [
                    'slug' => 'cash',
                    'is_active' => true,
                    'sort_order' => 1,
                    'notes' => null,
                ],
            )->id,
            'payment_method' => 'Cash',
            'reference_number' => null,
            'notes' => null,
            'status' => 'valid',
        ];
    }

    public function voided(): static
    {
        return $this->state(fn () => [
            'status' => 'voided',
            'voided_at' => now(),
            'void_reason' => 'Test void',
        ]);
    }
}
