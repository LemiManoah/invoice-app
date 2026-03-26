<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Payment\CreatePaymentAction;
use App\Actions\Payment\VoidPaymentAction;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\VoidPaymentRequest;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

final readonly class PaymentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:payments.view', only: ['index', 'show']),
            new Middleware('permission:payments.create', only: ['store']),
            new Middleware('permission:payments.void', only: ['void']),
        ];
    }

    public function index(): View
    {
        $this->authorize('viewAny', Payment::class);

        $payments = Payment::query()
            ->with(['invoice.customer', 'receipt', 'receiver', 'voider'])
            ->latest('payment_date')
            ->paginate(15);

        return view('payments.index', compact('payments'));
    }

    public function show(Payment $payment): View
    {
        $this->authorize('view', $payment);

        $payment->load(['invoice.customer', 'receipt', 'receiver', 'voider']);

        return view('payments.show', compact('payment'));
    }

    public function store(StorePaymentRequest $request, Invoice $invoice, CreatePaymentAction $action): RedirectResponse
    {
        $this->authorize('create', [Payment::class, $invoice]);

        $action->handle($request->validated(), $invoice);

        return to_route('invoices.show', $invoice)->with('success', 'Payment recorded successfully.');
    }

    public function void(VoidPaymentRequest $request, Payment $payment, VoidPaymentAction $action): RedirectResponse
    {
        $this->authorize('void', $payment);

        $action->handle($payment, $request->validated('void_reason'));

        return back()->with('success', 'Payment voided successfully.');
    }
}
