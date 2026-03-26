<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final readonly class CreateOrderAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): Order
    {
        return DB::transaction(function () use ($data): Order {
            $order = new Order($data);
            $order->order_number = 'ORD-'.strtoupper(uniqid());
            $order->status = 'confirmed';
            $order->created_by = Auth::id();
            $order->save();

            foreach ($data['items'] as $item) {
                $order->items()->create($item);
            }

            return $order;
        });
    }
}
