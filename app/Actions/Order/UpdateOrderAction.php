<?php

namespace App\Actions\Order;

use App\Models\Order;

class UpdateOrderAction
{
    public function __invoke(Order $order, array $data): Order
    {
        $order->update($data);

        return $order;
    }
}
