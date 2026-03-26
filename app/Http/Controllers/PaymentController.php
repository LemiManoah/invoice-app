<?php

namespace App\Http\Controllers;

use App\Actions\Payment\CreatePaymentAction;
use App\Actions\Payment\VoidPaymentAction;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\VoidPaymentRequest;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        private readonly CreatePaymentAction $createPayment,
        private readonly VoidPaymentAction $voidPayment,
    ) {
    }

    public function index(): View
    {
        $payments = Payment::with(['invoice.customer', 'receipt', 'receiver', 'voider'])
            ->latest('payment_date')
            ->paginate(15);

        return view('payments.index', compact('payments'));
    }

    public function show(Payment $payment): View
    {
        $payment->load(['invoice.customer', 'receipt', 'receiver', 'voider']);

        return view('payments.show', compact('payment'));
    }

    public function store(StorePaymentRequest $request, Invoice $invoice): RedirectResponse
    {
        ($this->createPayment)($request->validated(), $invoice);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Payment recorded successfully.');
    }

    public function void(VoidPaymentRequest $request, Payment $payment): RedirectResponse
    {
        ($this->voidPayment)($payment, $request->validated('void_reason'));

        return back()->with('success', 'Payment voided successfully.');
    }
}
