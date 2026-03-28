<?php

declare(strict_types=1);

namespace App\Actions\Invoice;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Collection;

final readonly class PrepareInvoiceCreateDataAction
{
    /**
     * @return array{
     *     customers: Collection<int, Customer>,
     *     orders: Collection<int, Order>,
     *     selectedCustomerId: int|null,
     *     selectedOrderId: int|null,
     *     selectedOrder: Order|null,
     *     invoiceDefaults: array{
     *         items: array<int, array{item_name: string, description: string, quantity: int, unit_price: float}>,
     *         notes: string|null,
     *         invoice_date: string,
     *         due_date: string|null,
     *         discount_amount: float,
     *         tax_amount: float
     *     }
     * }
     */
    public function handle(?int $customerId, ?int $orderId): array
    {
        $selectedOrder = null;

        if ($orderId !== null) {
            $selectedOrder = Order::query()
                ->with(['customer', 'items', 'invoice'])
                ->whereKey($orderId)
                ->whereDoesntHave('invoice')
                ->first();

            if ($selectedOrder !== null) {
                $customerId = $selectedOrder->customer_id;
            }
        }

        $customers = Customer::query()->orderBy('full_name')->get();
        $orders = $this->ordersForCustomer($customerId, $selectedOrder?->id);

        return [
            'customers' => $customers,
            'orders' => $orders,
            'selectedCustomerId' => $customerId,
            'selectedOrderId' => $selectedOrder?->id,
            'selectedOrder' => $selectedOrder,
            'invoiceDefaults' => [
                'items' => $this->defaultItems($selectedOrder),
                'notes' => $selectedOrder?->notes,
                'invoice_date' => now()->format('Y-m-d'),
                'due_date' => null,
                'discount_amount' => 0.0,
                'tax_amount' => 0.0,
            ],
        ];
    }

    /**
     * @return Collection<int, Order>
     */
    private function ordersForCustomer(?int $customerId, ?int $selectedOrderId): Collection
    {
        if ($customerId === null) {
            return collect();
        }

        return Order::query()
            ->where('customer_id', $customerId)
            ->where(function ($query) use ($selectedOrderId): void {
                $query->whereDoesntHave('invoice');

                if ($selectedOrderId !== null) {
                    $query->orWhere('id', $selectedOrderId);
                }
            })
            ->orderByDesc('order_date')
            ->get();
    }

    /**
     * @return array<int, array{item_name: string, description: string, quantity: int, unit_price: float}>
     */
    private function defaultItems(?Order $order): array
    {
        if ($order === null || $order->items->isEmpty()) {
            return [[
                'item_name' => '',
                'description' => '',
                'quantity' => 1,
                'unit_price' => 0.0,
            ]];
        }

        return $order->items
            ->map(fn ($item): array => [
                'item_name' => (string) $item->garment_type,
                'description' => $this->buildItemDescription($item),
                'quantity' => (int) $item->quantity,
                'unit_price' => (float) $item->unit_price,
            ])
            ->values()
            ->all();
    }

    private function buildItemDescription(object $item): string
    {
        $parts = array_values(array_filter([
            $item->description,
            $item->style_notes !== null ? sprintf('Style: %s', $item->style_notes) : null,
            $item->fabric_details !== null ? sprintf('Fabric: %s', $item->fabric_details) : null,
            $item->color !== null ? sprintf('Color: %s', $item->color) : null,
            $item->lining_details !== null ? sprintf('Lining: %s', $item->lining_details) : null,
            $item->button_details !== null ? sprintf('Buttons: %s', $item->button_details) : null,
            $item->monogram_text !== null ? sprintf('Monogram: %s', $item->monogram_text) : null,
            $item->urgent_flag ? 'Urgent order' : null,
        ]));

        return implode(' | ', $parts);
    }
}
