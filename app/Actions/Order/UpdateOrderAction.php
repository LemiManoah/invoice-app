<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Models\Order;

final readonly class UpdateOrderAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Order $order, array $data): Order
    {
        $order->update($data);

        return $order;
    }
}
