<?php

declare(strict_types=1);

namespace App\Actions\Order;

use App\Models\Order;
use App\Models\Product;
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
                $itemData = $this->processItem($item);
                $order->items()->create($itemData);
            }

            return $order;
        });
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    private function processItem(array $item): array
    {
        $productId = $item['product_id'] ?? null;

        if ($productId === 'custom') {
            $item['garment_type'] = $item['garment_type'] ?? '';
            $item['product_id'] = null;
        } elseif ($productId) {
            $product = Product::find($productId);
            if ($product) {
                $item['garment_type'] = $product->name;
                $item['product_id'] = (int) $productId;
            }
        }

        return $item;
    }
}
