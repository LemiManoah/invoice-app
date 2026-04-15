<?php

declare(strict_types=1);

use App\Actions\Payment\CreatePaymentAction;
use App\Actions\Payment\VoidPaymentAction;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Receipt;

beforeEach(function () {
    $this->currencies = seedBaselineCurrencies();
    $this->method = PaymentMethod::query()->updateOrCreate(
        ['name' => 'Cash'],
        [
            'slug' => 'cash',
            'is_active' => true,
            'sort_order' => 1,
            'notes' => null,
        ],
    );
    $this->admin = adminActor();
    $this->actingAs($this->admin);
});

function freshIssuedInvoice(int $currencyId, float $total = 1000): Invoice
{
    $invoice = Invoice::factory()->issued()->create([
        'currency_id' => $currencyId,
        'subtotal_amount' => $total,
        'total_amount' => $total,
        'amount_paid' => 0,
        'balance_due' => $total,
    ]);
    $invoice->items()->create([
        'item_name' => 'Item', 'quantity' => 1, 'unit_price' => $total, 'line_total' => $total,
    ]);

    return $invoice;
}

describe('PaymentController@store', function () {
    it('records a valid payment against an issued invoice, generates a receipt, and refreshes the invoice status', function () {
        $invoice = freshIssuedInvoice($this->currencies['ugx']->id, 1000);

        $response = $this->post(route('payments.store', $invoice), [
            'currency_id' => $this->currencies['ugx']->id,
            'amount' => 400,
            'payment_date' => now()->toDateString(),
            'payment_method_id' => $this->method->id,
            'reference_number' => 'REF-001',
        ]);

        $response->assertRedirect();
        $invoice->refresh();
        expect((float) $invoice->amount_paid)->toBe(400.0)
            ->and((float) $invoice->balance_due)->toBe(600.0)
            ->and($invoice->status)->toBe('partially_paid');

        $payment = $invoice->payments()->first();
        expect($payment->status)->toBe('valid')
            ->and($payment->payment_method)->toBe('Cash')
            ->and($payment->received_by)->toBe($this->admin->id)
            ->and(Receipt::where('payment_id', $payment->id)->exists())->toBeTrue();
    });

    it('marks the invoice paid once the balance reaches zero', function () {
        $invoice = freshIssuedInvoice($this->currencies['ugx']->id, 1000);

        $this->post(route('payments.store', $invoice), [
            'currency_id' => $this->currencies['ugx']->id,
            'amount' => 1000,
            'payment_date' => now()->toDateString(),
            'payment_method_id' => $this->method->id,
        ])->assertRedirect();

        expect($invoice->refresh()->status)->toBe('paid')
            ->and((float) $invoice->balance_due)->toBe(0.0);
    });

    it('caps an over-payment to the invoice balance_due', function () {
        $invoice = freshIssuedInvoice($this->currencies['ugx']->id, 500);

        $this->post(route('payments.store', $invoice), [
            'currency_id' => $this->currencies['ugx']->id,
            'amount' => 9999,
            'payment_date' => now()->toDateString(),
            'payment_method_id' => $this->method->id,
        ]);

        $payment = $invoice->payments()->first();
        expect((float) $payment->amount)->toBe(500.0)
            ->and($invoice->refresh()->status)->toBe('paid');
    });

    it('caps an over-payment using the payment currency equivalent when currencies differ', function () {
        $invoice = freshIssuedInvoice($this->currencies['ugx']->id, 3800);

        $this->post(route('payments.store', $invoice), [
            'currency_id' => $this->currencies['usd']->id,
            'amount' => 2,
            'payment_date' => now()->toDateString(),
            'payment_method_id' => $this->method->id,
        ])->assertRedirect();

        $payment = $invoice->payments()->first();
        $invoice->refresh();

        expect((float) $payment->amount)->toBe(1.0)
            ->and($payment->currency_id)->toBe($this->currencies['usd']->id)
            ->and((float) $invoice->amount_paid)->toBe(3800.0)
            ->and((float) $invoice->balance_due)->toBe(0.0);
    });

    it('rejects payments on a draft invoice', function () {
        $invoice = Invoice::factory()->draft()->create(['currency_id' => $this->currencies['ugx']->id]);

        /** @var CreatePaymentAction $action */
        $action = app(CreatePaymentAction::class);
        expect(fn () => $action->handle([
            'currency_id' => $this->currencies['ugx']->id,
            'amount' => 10,
            'payment_date' => now()->toDateString(),
            'payment_method_id' => $this->method->id,
        ], $invoice))->toThrow(\Illuminate\Validation\ValidationException::class);
    });

    it('rejects payments on a cancelled invoice', function () {
        $invoice = Invoice::factory()->cancelled()->create(['currency_id' => $this->currencies['ugx']->id]);

        /** @var CreatePaymentAction $action */
        $action = app(CreatePaymentAction::class);
        expect(fn () => $action->handle([
            'currency_id' => $this->currencies['ugx']->id,
            'amount' => 10,
            'payment_date' => now()->toDateString(),
            'payment_method_id' => $this->method->id,
        ], $invoice))->toThrow(\Illuminate\Validation\ValidationException::class);
    });

    it('validates required fields', function () {
        $invoice = freshIssuedInvoice($this->currencies['ugx']->id);
        $this->post(route('payments.store', $invoice), [])
            ->assertSessionHasErrors(['currency_id', 'amount', 'payment_date', 'payment_method_id']);
    });

    it('rejects amount less than 0.01', function () {
        $invoice = freshIssuedInvoice($this->currencies['ugx']->id);
        $this->post(route('payments.store', $invoice), [
            'currency_id' => $this->currencies['ugx']->id,
            'amount' => 0,
            'payment_date' => now()->toDateString(),
            'payment_method_id' => $this->method->id,
        ])->assertSessionHasErrors('amount');
    });

    it('rejects an inactive payment method', function () {
        $invoice = freshIssuedInvoice($this->currencies['ugx']->id);
        $inactive = PaymentMethod::factory()->inactive()->create();

        $this->post(route('payments.store', $invoice), [
            'currency_id' => $this->currencies['ugx']->id,
            'amount' => 100,
            'payment_date' => now()->toDateString(),
            'payment_method_id' => $inactive->id,
        ])->assertSessionHasErrors('payment_method_id');
    });
});

describe('Multi-currency payment handling', function () {
    it('converts a foreign-currency payment into the invoice currency before applying it', function () {
        // Invoice in UGX for 3800 UGX. Payment of $1 USD (worth 3800 UGX) should fully pay it.
        $invoice = freshIssuedInvoice($this->currencies['ugx']->id, 3800);

        $this->post(route('payments.store', $invoice), [
            'currency_id' => $this->currencies['usd']->id,
            'amount' => 1,
            'payment_date' => now()->toDateString(),
            'payment_method_id' => $this->method->id,
        ]);

        $invoice->refresh();
        expect((float) $invoice->amount_paid)
            ->toBe(3800.0);
    });

    it('flags payments in a different currency from the invoice', function () {
        $invoice = freshIssuedInvoice($this->currencies['ugx']->id, 1000);
        $this->post(route('payments.store', $invoice), [
            'currency_id' => $this->currencies['usd']->id,
            'amount' => 10,
            'payment_date' => now()->toDateString(),
            'payment_method_id' => $this->method->id,
        ]);

        $payment = $invoice->payments()->first();
        expect($payment->currency_id)->toBe($this->currencies['usd']->id)
            ->and($invoice->currency_id)->toBe($this->currencies['ugx']->id)
            ->and($payment->currency_id)->not->toBe($invoice->currency_id);
    });
});

describe('PaymentController@void / VoidPaymentAction', function () {
    it('voids a valid payment and reverses the invoice amount_paid', function () {
        $invoice = freshIssuedInvoice($this->currencies['ugx']->id, 1000);
        $this->post(route('payments.store', $invoice), [
            'currency_id' => $this->currencies['ugx']->id,
            'amount' => 500,
            'payment_date' => now()->toDateString(),
            'payment_method_id' => $this->method->id,
        ]);

        $payment = $invoice->payments()->first();

        $this->post(route('payments.void', $payment), ['void_reason' => 'Bounced cheque'])
            ->assertRedirect();

        $payment->refresh();
        $invoice->refresh();
        expect($payment->status)->toBe('voided')
            ->and($payment->void_reason)->toBe('Bounced cheque')
            ->and($payment->voided_by)->toBe($this->admin->id)
            ->and((float) $invoice->amount_paid)->toBe(0.0)
            ->and((float) $invoice->balance_due)->toBe(1000.0)
            ->and($invoice->status)->toBe('issued');
    });

    it('refuses to void an already voided payment', function () {
        $payment = Payment::factory()->voided()->create();

        /** @var VoidPaymentAction $action */
        $action = app(VoidPaymentAction::class);
        expect(fn () => $action->handle($payment, 'duplicate'))
            ->toThrow(\Illuminate\Validation\ValidationException::class);
    });

    it('excludes voided payments from amount_paid calculations', function () {
        $invoice = freshIssuedInvoice($this->currencies['ugx']->id, 1000);
        Payment::factory()->for($invoice)->create(['amount' => 400, 'status' => 'valid']);
        Payment::factory()->for($invoice)->voided()->create(['amount' => 200]);

        app(\App\Actions\Invoice\RefreshInvoiceStatusAction::class)->handle($invoice);

        expect((float) $invoice->fresh()->amount_paid)->toBe(400.0);
    });
});

describe('PaymentController@index / @show', function () {
    it('lists payments for authorised users', function () {
        Payment::factory()->count(2)->create();
        $this->get(route('payments.index'))->assertOk();
    });

    it('shows a payment detail page', function () {
        $payment = Payment::factory()->create();
        $this->get(route('payments.show', $payment))->assertOk();
    });
});

describe('Payment authorization', function () {
    it('blocks users without payments.create permission from posting a payment', function () {
        $invoice = freshIssuedInvoice($this->currencies['ugx']->id);
        $user = userWithPermissions(['invoices.view']);
        $this->actingAs($user);

        $this->post(route('payments.store', $invoice), [
            'currency_id' => $this->currencies['ugx']->id,
            'amount' => 100,
            'payment_date' => now()->toDateString(),
            'payment_method_id' => $this->method->id,
        ])->assertForbidden();
    });
});
