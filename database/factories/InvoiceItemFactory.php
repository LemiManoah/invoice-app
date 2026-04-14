<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    protected $model = InvoiceItem::class;

    public function definition(): array
    {
        $quantity = 1;
        $unitPrice = 1000;

        return [
            'invoice_id' => Invoice::factory(),
            'item_name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'line_total' => $quantity * $unitPrice,
        ];
    }
}
