<?php

namespace App\Http\Controllers;

use App\Actions\Payment\CreatePaymentAction;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request, Invoice $invoice)
    {
        $data = $request->validated();

        return DB::transaction(function () use ($data, $invoice) {
            $payment = (new CreatePaymentAction)($data, $invoice);

            // Update invoice totals
            $invoice->amount_paid += $payment->amount;
            $invoice->balance_due -= $payment->amount;

            if ($invoice->balance_due <= 0) {
                $invoice->status = 'paid';
            } else {
                $invoice->status = 'partially_paid';
            }

            $invoice->save();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Payment recorded successfully.');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        if ($payment->status === 'voided') {
            return back()->with('error', 'Payment is already voided.');
        }
        return DB::transaction(function () use ($payment) {
            $invoice = $payment->invoice;
            // Rollback invoice totals
            $invoice->amount_paid -= $payment->amount;
            $invoice->balance_due += $payment->amount;

            if ($invoice->amount_paid <= 0) {
                $invoice->status = 'issued';
            } else {
                $invoice->status = 'partially_paid';
            }

            $invoice->save();

            // Void the payment via action
            (new DeletePaymentAction)($payment);
            return back()->with('success', 'Payment voided successfully.');
    }
}
