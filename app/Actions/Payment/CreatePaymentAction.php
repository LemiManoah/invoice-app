<?php

declare(strict_types=1);

namespace App\Actions\Payment;

use App\Actions\Audit\CreateAuditLogAction;
use App\Actions\Invoice\RefreshInvoiceStatusAction;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class CreatePaymentAction
{
    public function __construct(
        private GenerateReceiptAction $generateReceipt,
        private RefreshInvoiceStatusAction $refreshInvoiceStatus,
        private CreateAuditLogAction $createAuditLog,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data, Invoice $invoice): Payment
    {
        if (! $invoice->canAcceptPayments()) {
            throw ValidationException::withMessages([
                'amount' => 'Payments can only be recorded for issued, overdue, or partially paid invoices with a balance due.',
            ]);
        }

        $paymentAmount = (float) $data['amount'];
        if ($paymentAmount > (float) $invoice->balance_due) {
            $data['amount'] = (float) $invoice->balance_due;
        }

        return DB::transaction(function () use ($data, $invoice): Payment {
            $paymentMethod = PaymentMethod::query()
                ->active()
                ->findOrFail($data['payment_method_id']);

            $payment = $invoice->payments()->create([
                ...$data,
                'payment_method' => $paymentMethod->name,
                'received_by' => Auth::id(),
                'status' => 'valid',
            ]);

            $payment->load('invoice');
            $this->generateReceipt->handle($payment);
            $this->refreshInvoiceStatus->handle($invoice);
            $this->createAuditLog->handle(
                'payment.recorded',
                $payment,
                null,
                $payment->fresh()->load('receipt')->toArray(),
            );

            return $payment->fresh(['receipt', 'invoice']);
        });
    }
}
