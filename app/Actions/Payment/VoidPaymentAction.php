<?php

declare(strict_types=1);

namespace App\Actions\Payment;

use App\Actions\Audit\CreateAuditLogAction;
use App\Actions\Invoice\RefreshInvoiceStatusAction;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class VoidPaymentAction
{
    public function __construct(
        private RefreshInvoiceStatusAction $refreshInvoiceStatus,
        private CreateAuditLogAction $createAuditLog,
    ) {}

    public function handle(Payment $payment, string $reason): Payment
    {
        if ($payment->isVoided()) {
            throw ValidationException::withMessages([
                'payment' => 'This payment has already been voided.',
            ]);
        }

        return DB::transaction(function () use ($payment, $reason): Payment {
            $before = $payment->only(['status', 'voided_at', 'voided_by', 'void_reason']);

            $payment->forceFill([
                'status' => 'voided',
                'voided_at' => now(),
                'voided_by' => Auth::id(),
                'void_reason' => $reason,
            ])->save();

            $this->refreshInvoiceStatus->handle($payment->invoice);
            $this->createAuditLog->handle(
                'payment.voided',
                $payment,
                $before,
                $payment->fresh()->only(['status', 'voided_at', 'voided_by', 'void_reason']),
                $reason,
            );

            return $payment->refresh();
        });
    }
}
