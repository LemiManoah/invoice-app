<?php

declare(strict_types=1);

namespace App\Actions\Payment;

use App\Actions\Audit\CreateAuditLogAction;
use App\Actions\Invoice\RefreshInvoiceStatusAction;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Support\CurrencyManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class CreatePaymentAction
{
    public function __construct(
        private GenerateReceiptAction $generateReceipt,
        private RefreshInvoiceStatusAction $refreshInvoiceStatus,
        private CreateAuditLogAction $createAuditLog,
        private CurrencyManager $currencyManager,
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

        $invoice->loadMissing('currency');

        $paymentCurrency = Currency::query()->findOrFail($data['currency_id']);
        $paymentAmount = (float) $data['amount'];
        $invoiceEquivalent = $this->currencyManager->convertValue(
            $paymentAmount,
            $paymentCurrency,
            $invoice->currency,
        );

        if ($invoiceEquivalent > (float) $invoice->balance_due) {
            $maxReceivable = $this->currencyManager->convertValue(
                $invoice->balance_due,
                $invoice->currency,
                $paymentCurrency,
            );

            $data['amount'] = $this->truncateAmount(
                $maxReceivable,
                (int) $paymentCurrency->decimal_places,
            );
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

    private function truncateAmount(float $amount, int $decimals): float
    {
        if ($decimals <= 0) {
            return floor($amount);
        }

        $factor = 10 ** $decimals;

        return floor($amount * $factor) / $factor;
    }
}
