<?php

namespace App\Actions\Order;

use App\Models\Order;

class DeleteOrderAction
{
    public function __invoke(Order $order): void
    {
        $order->delete();
    }
}
