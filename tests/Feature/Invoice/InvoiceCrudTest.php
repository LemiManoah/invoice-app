<?php

declare(strict_types=1);

use App\Actions\Invoice\CancelInvoiceAction;
use App\Actions\Invoice\CreateInvoiceAction;
use App\Actions\Invoice\IssueInvoiceAction;
use App\Actions\Invoice\UpdateInvoiceAction;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;

beforeEach(function () {
    $this->currencies = seedBaselineCurrencies();
    $this->admin = adminActor();
    $this->actingAs($this->admin);
});

describe('InvoiceController@store / CreateInvoiceAction', function () {
    it('creates an invoice with items and correctly calculates totals', function () {
        $customer = Customer::factory()->create();

        $response = $this->post(route('invoices.store'), [
            'customer_id' => $customer->id,
            'currency_id' => $this->currencies['ugx']->id,
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->addDays(30)->toDateString(),
            'items' => [
                ['item_name' => 'Suit', 'description' => 'Two-piece', 'quantity' => 2, 'unit_price' => 500],
                ['item_name' => 'Shirt', 'description' => 'White', 'quantity' => 3, 'unit_price' => 100],
            ],
            'discount_amount' => 100,
            'tax_amount' => 50,
        ]);

        $invoice = Invoice::latest('id')->first();

        $response->assertRedirect(route('invoices.show', $invoice));
        expect((float) $invoice->subtotal_amount)->toBe(1300.0)
            ->and((float) $invoice->discount_amount)->toBe(100.0)
            ->and((float) $invoice->tax_amount)->toBe(50.0)
            ->and((float) $invoice->total_amount)->toBe(1250.0)
            ->and((float) $invoice->balance_due)->toBe(1250.0)
            ->and((float) $invoice->amount_paid)->toBe(0.0)
            ->and($invoice->status)->toBe('draft')
            ->and($invoice->invoice_number)->toStartWith('INV-')
            ->and($invoice->items)->toHaveCount(2);
    });

    it('requires customer_id, currency_id, invoice_date and items', function () {
        $response = $this->post(route('invoices.store'), []);
        $response->assertSessionHasErrors(['customer_id', 'currency_id', 'invoice_date', 'items']);
    });

    it('rejects due_date before invoice_date', function () {
        $customer = Customer::factory()->create();
        $response = $this->post(route('invoices.store'), [
            'customer_id' => $customer->id,
            'currency_id' => $this->currencies['ugx']->id,
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->subDays(1)->toDateString(),
            'items' => [['item_name' => 'X', 'quantity' => 1, 'unit_price' => 100]],
        ]);
        $response->assertSessionHasErrors('due_date');
    });

    it('rejects items with quantity less than 1', function () {
        $customer = Customer::factory()->create();
        $response = $this->post(route('invoices.store'), [
            'customer_id' => $customer->id,
            'currency_id' => $this->currencies['ugx']->id,
            'invoice_date' => now()->toDateString(),
            'items' => [['item_name' => 'X', 'quantity' => 0, 'unit_price' => 100]],
        ]);
        $response->assertSessionHasErrors('items.0.quantity');
    });

    it('rejects creating an invoice for an order that already has one', function () {
        $customer = Customer::factory()->create();
        $order = Order::factory()->for($customer)->create();
        Invoice::factory()->for($customer)->create(['order_id' => $order->id]);

        $response = $this->post(route('invoices.store'), [
            'customer_id' => $customer->id,
            'order_id' => $order->id,
            'currency_id' => $this->currencies['ugx']->id,
            'invoice_date' => now()->toDateString(),
            'items' => [['item_name' => 'X', 'quantity' => 1, 'unit_price' => 100]],
        ]);
        $response->assertSessionHasErrors('order_id');
    });

    it('records currency_id on the invoice so downstream currency handling works', function () {
        $customer = Customer::factory()->create();

        $this->post(route('invoices.store'), [
            'customer_id' => $customer->id,
            'currency_id' => $this->currencies['usd']->id,
            'invoice_date' => now()->toDateString(),
            'items' => [['item_name' => 'X', 'quantity' => 1, 'unit_price' => 10]],
        ]);

        expect(Invoice::latest('id')->first()->currency_id)->toBe($this->currencies['usd']->id);
    });
});

describe('InvoiceController@index', function () {
    it('shows an authenticated user the invoice list', function () {
        Invoice::factory()->count(3)->create();
        $this->get(route('invoices.index'))->assertOk()->assertViewIs('invoices.index');
    });

    it('filters by status', function () {
        Invoice::factory()->draft()->create();
        Invoice::factory()->issued()->create();

        $response = $this->get(route('invoices.index', ['status' => 'draft']));
        $response->assertOk();
        $invoices = $response->viewData('invoices');
        expect($invoices->pluck('status')->unique()->all())->toBe(['draft']);
    });

    it('searches by invoice_number', function () {
        $target = Invoice::factory()->create(['invoice_number' => 'INV-FINDME-001']);
        Invoice::factory()->create(['invoice_number' => 'INV-OTHER']);

        $response = $this->get(route('invoices.index', ['search' => 'FINDME']));
        $response->assertOk();
        $ids = $response->viewData('invoices')->pluck('id')->all();
        expect($ids)->toContain($target->id)->and($ids)->toHaveCount(1);
    });
});

describe('InvoiceController@show', function () {
    it('returns the show view with the invoice', function () {
        $invoice = Invoice::factory()->create();
        $this->get(route('invoices.show', $invoice))
            ->assertOk()
            ->assertViewHas('invoice');
    });
});

describe('InvoiceController@edit / @update', function () {
    it('allows editing a draft invoice', function () {
        $customer = Customer::factory()->create();
        $invoice = Invoice::factory()->for($customer)->draft()->create();

        $this->get(route('invoices.edit', $invoice))->assertOk();

        $response = $this->put(route('invoices.update', $invoice), [
            'customer_id' => $customer->id,
            'currency_id' => $this->currencies['ugx']->id,
            'invoice_date' => now()->toDateString(),
            'items' => [
                ['item_name' => 'Replaced', 'quantity' => 4, 'unit_price' => 250],
            ],
            'discount_amount' => 0,
            'tax_amount' => 0,
        ]);

        $response->assertRedirect(route('invoices.show', $invoice));
        $invoice->refresh();
        expect((float) $invoice->total_amount)->toBe(1000.0)
            ->and($invoice->items()->count())->toBe(1)
            ->and($invoice->items()->first()->item_name)->toBe('Replaced');
    });

    it('redirects when trying to edit a non-draft invoice', function () {
        $invoice = Invoice::factory()->issued()->create();
        $this->get(route('invoices.edit', $invoice))
            ->assertRedirect(route('invoices.show', $invoice));
    });
});

describe('InvoiceController@issue', function () {
    it('issues a draft invoice with items', function () {
        $invoice = Invoice::factory()->draft()->create();
        $invoice->items()->create(['item_name' => 'X', 'quantity' => 1, 'unit_price' => 100, 'line_total' => 100]);

        $this->post(route('invoices.issue', $invoice))->assertRedirect();
        $invoice->refresh();
        expect($invoice->status)->toBe('issued')
            ->and($invoice->issued_at)->not->toBeNull();
    });

    it('fails to issue a draft invoice with no items', function () {
        $invoice = Invoice::factory()->draft()->create();
        /** @var IssueInvoiceAction $action */
        $action = app(IssueInvoiceAction::class);
        expect(fn () => $action->handle($invoice))
            ->toThrow(\Illuminate\Validation\ValidationException::class);
    });

    it('fails to issue an already-issued invoice', function () {
        $invoice = Invoice::factory()->issued()->create();
        $invoice->items()->create(['item_name' => 'X', 'quantity' => 1, 'unit_price' => 100, 'line_total' => 100]);

        /** @var IssueInvoiceAction $action */
        $action = app(IssueInvoiceAction::class);
        expect(fn () => $action->handle($invoice))
            ->toThrow(\Illuminate\Validation\ValidationException::class);
    });
});

describe('InvoiceController@cancel', function () {
    it('cancels an invoice without valid payments', function () {
        $invoice = Invoice::factory()->issued()->create();

        $this->post(route('invoices.cancel', $invoice), ['cancellation_reason' => 'Customer walked out'])
            ->assertRedirect();

        $invoice->refresh();
        expect($invoice->status)->toBe('cancelled')
            ->and($invoice->cancellation_reason)->toBe('Customer walked out')
            ->and($invoice->cancelled_by)->toBe($this->admin->id);
    });

    it('refuses to cancel an invoice that has a valid payment', function () {
        $invoice = Invoice::factory()->issued()->create();
        Payment::factory()->for($invoice)->create(['status' => 'valid']);

        /** @var CancelInvoiceAction $action */
        $action = app(CancelInvoiceAction::class);
        expect(fn () => $action->handle($invoice, 'test'))
            ->toThrow(\Illuminate\Validation\ValidationException::class);
    });

    it('requires a cancellation_reason', function () {
        $invoice = Invoice::factory()->issued()->create();
        $this->post(route('invoices.cancel', $invoice), [])
            ->assertSessionHasErrors('cancellation_reason');
    });
});

describe('InvoiceController@print', function () {
    it('renders the print view', function () {
        $invoice = Invoice::factory()->issued()->create();
        $this->get(route('invoices.print', $invoice))
            ->assertOk()
            ->assertViewIs('invoices.print');
    });
});

describe('Invoice model computed methods', function () {
    it('canAcceptPayments is true for issued invoices with balance', function () {
        $invoice = Invoice::factory()->issued()->create(['balance_due' => 500]);
        expect($invoice->canAcceptPayments())->toBeTrue();
    });

    it('canAcceptPayments is false for draft or paid invoices', function () {
        expect(Invoice::factory()->draft()->create()->canAcceptPayments())->toBeFalse();
        expect(Invoice::factory()->paid()->create()->canAcceptPayments())->toBeFalse();
    });

    it('canBeCancelled is false when any valid payment exists', function () {
        $invoice = Invoice::factory()->issued()->create();
        Payment::factory()->for($invoice)->create(['status' => 'valid']);
        expect($invoice->fresh()->canBeCancelled())->toBeFalse();
    });

    it('canBeCancelled is true when only voided payments exist', function () {
        $invoice = Invoice::factory()->issued()->create();
        Payment::factory()->for($invoice)->voided()->create();
        expect($invoice->fresh()->canBeCancelled())->toBeTrue();
    });

    it('shouldBeOverdue reflects due_date and balance_due', function () {
        $overdue = Invoice::factory()->issued()->create([
            'due_date' => now()->subDay()->toDateString(),
            'balance_due' => 100,
        ]);
        expect($overdue->shouldBeOverdue(now()))->toBeTrue();

        $notOverdue = Invoice::factory()->issued()->create([
            'due_date' => now()->addDay()->toDateString(),
            'balance_due' => 100,
        ]);
        expect($notOverdue->shouldBeOverdue(now()))->toBeFalse();
    });
});

describe('Invoice authorization', function () {
    it('blocks users without invoices.view permission', function () {
        $user = userWithPermissions([]);
        $this->actingAs($user);
        $this->get(route('invoices.index'))->assertForbidden();
    });

    it('blocks users without invoices.create permission', function () {
        $user = userWithPermissions(['invoices.view']);
        $this->actingAs($user);
        $this->get(route('invoices.create'))->assertForbidden();
    });
});
