<?php

namespace App\Actions\Payment;

use App\Actions\Audit\CreateAuditLogAction;
use App\Actions\Invoice\RefreshInvoiceStatusAction;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CreatePaymentAction
{
    public function __construct(
        private readonly GenerateReceiptAction $generateReceipt,
        private readonly RefreshInvoiceStatusAction $refreshInvoiceStatus,
        private readonly CreateAuditLogAction $createAuditLog,
    ) {
    }

    public function __invoke(array $data, Invoice $invoice): Payment
    {
        if (! $invoice->canAcceptPayments()) {
            throw ValidationException::withMessages([
                'amount' => 'Payments can only be recorded for issued, overdue, or partially paid invoices with a balance due.',
            ]);
        }

        if ((float) $data['amount'] > (float) $invoice->balance_due) {
            throw ValidationException::withMessages([
                'amount' => 'Payment amount cannot exceed the remaining invoice balance.',
            ]);
        }

        return DB::transaction(function () use ($data, $invoice) {
            $payment = $invoice->payments()->create([
                ...$data,
                'received_by' => Auth::id(),
                'status' => 'valid',
            ]);

            $payment->load('invoice');
            ($this->generateReceipt)($payment);
            ($this->refreshInvoiceStatus)($invoice);

            ($this->createAuditLog)(
                'payment.recorded',
                $payment,
                null,
                $payment->fresh()->load('receipt')->toArray()
            );

            return $payment->fresh(['receipt', 'invoice']);
        });
    }
}
