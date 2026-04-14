<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;

beforeEach(function () {
    $this->currencies = seedBaselineCurrencies();
    $this->admin = adminActor();
    $this->actingAs($this->admin);
});

describe('OrderController CRUD', function () {
    it('creates an order with items', function () {
        $customer = Customer::factory()->create();

        $response = $this->post(route('orders.store'), [
            'customer_id' => $customer->id,
            'currency_id' => $this->currencies['ugx']->id,
            'order_date' => now()->toDateString(),
            'promised_delivery_date' => now()->addDays(7)->toDateString(),
            'priority' => 'medium',
            'items' => [
                ['product_id' => 'custom', 'garment_type' => 'suit', 'quantity' => 2, 'unit_price' => 500],
            ],
        ]);

        $order = Order::latest('id')->first();
        $response->assertRedirect(route('orders.show', $order));
        expect($order->order_number)->toStartWith('ORD-')
            ->and($order->status)->toBe('confirmed')
            ->and($order->items)->toHaveCount(1)
            ->and($order->items->first()->product_id)->toBeNull();
    });

    it('requires customer_id, currency_id, order_date, priority, and items', function () {
        $this->post(route('orders.store'), [])
            ->assertSessionHasErrors(['customer_id', 'currency_id', 'order_date', 'priority', 'items']);
    });

    it('rejects promised_delivery_date before order_date', function () {
        $customer = Customer::factory()->create();

        $this->post(route('orders.store'), [
            'customer_id' => $customer->id,
            'currency_id' => $this->currencies['ugx']->id,
            'order_date' => now()->toDateString(),
            'promised_delivery_date' => now()->subDay()->toDateString(),
            'priority' => 'medium',
            'items' => [['product_id' => 'custom', 'quantity' => 1, 'unit_price' => 10]],
        ])->assertSessionHasErrors('promised_delivery_date');
    });

    it('rejects items with quantity less than 1', function () {
        $customer = Customer::factory()->create();

        $this->post(route('orders.store'), [
            'customer_id' => $customer->id,
            'currency_id' => $this->currencies['ugx']->id,
            'order_date' => now()->toDateString(),
            'priority' => 'medium',
            'items' => [['product_id' => 'custom', 'quantity' => 0, 'unit_price' => 10]],
        ])->assertSessionHasErrors('items.0.quantity');
    });

    it('shows the order index and detail pages', function () {
        $order = Order::factory()->create();
        $this->get(route('orders.index'))->assertOk();
        $this->get(route('orders.show', $order))->assertOk();
    });

    it('deletes an order', function () {
        $order = Order::factory()->create();
        $this->delete(route('orders.destroy', $order))->assertRedirect(route('orders.index'));
        expect(Order::find($order->id))->toBeNull();
    });

    it('stores currency_id on the order', function () {
        $customer = Customer::factory()->create();
        $this->post(route('orders.store'), [
            'customer_id' => $customer->id,
            'currency_id' => $this->currencies['kes']->id,
            'order_date' => now()->toDateString(),
            'priority' => 'medium',
            'items' => [['product_id' => 'custom', 'quantity' => 1, 'unit_price' => 10]],
        ]);

        expect(Order::latest('id')->first()->currency_id)->toBe($this->currencies['kes']->id);
    });
});

describe('Order <-> Invoice linkage', function () {
    it('prevents creating a second invoice from the same order', function () {
        $customer = Customer::factory()->create();
        $order = Order::factory()->for($customer)->create();
        Invoice::factory()->for($customer)->create(['order_id' => $order->id]);

        $this->post(route('invoices.store'), [
            'customer_id' => $customer->id,
            'order_id' => $order->id,
            'currency_id' => $this->currencies['ugx']->id,
            'invoice_date' => now()->toDateString(),
            'items' => [['item_name' => 'X', 'quantity' => 1, 'unit_price' => 10]],
        ])->assertSessionHasErrors('order_id');
    });

    it('rejects creating an invoice when the order belongs to a different customer', function () {
        $customerA = Customer::factory()->create();
        $customerB = Customer::factory()->create();
        $orderForA = Order::factory()->for($customerA)->create();

        $this->post(route('invoices.store'), [
            'customer_id' => $customerB->id,
            'order_id' => $orderForA->id,
            'currency_id' => $this->currencies['ugx']->id,
            'invoice_date' => now()->toDateString(),
            'items' => [['item_name' => 'X', 'quantity' => 1, 'unit_price' => 10]],
        ])->assertSessionHasErrors('order_id');
    });
});

describe('Order authorization', function () {
    it('blocks users without orders.view', function () {
        $this->actingAs(userWithPermissions([]));
        $this->get(route('orders.index'))->assertForbidden();
    });
});
