<?php

namespace App\Actions\Order;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CreateOrderAction
{
    public function __invoke(array $data): Order
    {
        $order = new Order($data);
        $order->order_number = 'ORD-'.strtoupper(uniqid());
        $order->status = 'confirmed';
        $order->created_by = Auth::id();
        $order->save();

        foreach ($data['items'] as $item) {
            $order->items()->create($item);
        }

        return $order;
    }
}
