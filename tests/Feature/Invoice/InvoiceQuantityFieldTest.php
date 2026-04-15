<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\Invoice;

/*
 * Regression tests for the invoice item "quantity" field.
 *
 * User-reported bugs:
 *   1. "The quantity field doesn't show the value."
 *      — On the edit view, the quantity input is rendered via Alpine `x-model.number`
 *        without the integer value being embedded as a server-rendered attribute. Any
 *        test that only inspects server HTML will not see the value. These tests
 *        assert the Alpine data payload (`$invoice->items->map(...)->quantity`) is
 *        present in the hydration JSON and that the rendered input uses
 *        `x-model.number="item.quantity"` so Alpine can bind it.
 *   2. "The field is too small."
 *      — The Qty <th> carries the Tailwind class `w-24` (≈96px). The Unit Price and
 *        Total columns both use `w-32` (≈128px). These tests assert the Qty column
 *        should match (w-32 or wider). They will FAIL against the current markup,
 *        documenting the bug.
 */

beforeEach(function () {
    $this->currencies = seedBaselineCurrencies();
    $this->admin = adminActor();
    $this->actingAs($this->admin);
});

describe('Invoice create view quantity field', function () {
    it('renders the create form without error', function () {
        Customer::factory()->create();
        $this->get(route('invoices.create'))->assertOk()->assertViewIs('invoices.create');
    });

    it('binds the quantity field via Alpine x-model so hydration can populate it', function () {
        Customer::factory()->create();
        $html = $this->get(route('invoices.create'))->getContent();
        expect($html)->toContain("x-model.number=\"item.quantity\"");
    });

    it('fails because the Qty column is rendered at w-24 (too small) — should be at least w-32', function () {
        Customer::factory()->create();
        $html = $this->get(route('invoices.create'))->getContent();

        // Locate the Qty <th> and assert it uses a sufficiently-wide utility class.
        // The Unit Price / Total columns use w-32; Qty should match or exceed.
        $qtyHeaderRegex = '/<th[^>]*class="[^"]*\b(w-24|w-20|w-16)\b[^"]*"[^>]*>\s*Qty\s*<\/th>/i';
        expect(preg_match($qtyHeaderRegex, $html))
            ->toBe(0, 'Qty column is rendered with a too-narrow width utility (w-24 or smaller). Widen to at least w-32 so the number fits.');
    });

    it('ensures the input itself is not forced into a narrow fixed width', function () {
        Customer::factory()->create();
        $html = $this->get(route('invoices.create'))->getContent();
        // The input should use w-full (expands within the td) — not a fixed w-12/w-16/w-20.
        expect($html)->toMatch('/items\[\'\s*\+\s*index\s*\+\s*\'\]\[quantity\][^>]*class="[^"]*\bw-full\b/');
    });
});

describe('Invoice edit view quantity field', function () {
    it('includes the existing quantity values in the Alpine hydration payload so the field shows the value', function () {
        $invoice = Invoice::factory()->draft()->create(['currency_id' => $this->currencies['ugx']->id]);
        $invoice->items()->create([
            'item_name' => 'Custom Suit',
            'description' => null,
            'quantity' => 7,
            'unit_price' => 250,
            'line_total' => 1750,
        ]);

        $html = $this->get(route('invoices.edit', $invoice))->getContent();

        // Alpine hydration embeds the items collection via Js::from.
        // Depending on Laravel's escaping strategy, the JSON may appear with
        // HTML entities or with unicode-escaped quotes inside JSON.parse(...).
        expect($html)->toMatch('/(?:&quot;|\\\\u0022)quantity(?:&quot;|\\\\u0022):7/')
            ->and($html)->toContain("x-model.number=\"item.quantity\"");
    });

    it('still exposes the qty-too-small bug on the edit view', function () {
        $invoice = Invoice::factory()->draft()->create(['currency_id' => $this->currencies['ugx']->id]);
        $invoice->items()->create(['item_name' => 'X', 'quantity' => 1, 'unit_price' => 10, 'line_total' => 10]);

        $html = $this->get(route('invoices.edit', $invoice))->getContent();
        $qtyHeaderRegex = '/<th[^>]*class="[^"]*\b(w-24|w-20|w-16)\b[^"]*"[^>]*>\s*Qty\s*<\/th>/i';
        expect(preg_match($qtyHeaderRegex, $html))
            ->toBe(0, 'Qty column is rendered with a too-narrow width utility on the edit view as well.');
    });
});

describe('Quantity field validation', function () {
    it('enforces min:1 on the server', function () {
        $customer = Customer::factory()->create();
        $this->post(route('invoices.store'), [
            'customer_id' => $customer->id,
            'currency_id' => $this->currencies['ugx']->id,
            'invoice_date' => now()->toDateString(),
            'items' => [['item_name' => 'X', 'quantity' => -5, 'unit_price' => 10]],
        ])->assertSessionHasErrors('items.0.quantity');
    });

    it('enforces integer typing on quantity', function () {
        $customer = Customer::factory()->create();
        $this->post(route('invoices.store'), [
            'customer_id' => $customer->id,
            'currency_id' => $this->currencies['ugx']->id,
            'invoice_date' => now()->toDateString(),
            'items' => [['item_name' => 'X', 'quantity' => 'abc', 'unit_price' => 10]],
        ])->assertSessionHasErrors('items.0.quantity');
    });

    it('persists quantity as an integer on the InvoiceItem', function () {
        $customer = Customer::factory()->create();
        $this->post(route('invoices.store'), [
            'customer_id' => $customer->id,
            'currency_id' => $this->currencies['ugx']->id,
            'invoice_date' => now()->toDateString(),
            'items' => [['item_name' => 'Suit', 'quantity' => 3, 'unit_price' => 100]],
        ]);

        $item = Invoice::latest('id')->first()->items()->first();
        expect($item->quantity)->toBe(3)
            ->and((float) $item->line_total)->toBe(300.0);
    });
});
