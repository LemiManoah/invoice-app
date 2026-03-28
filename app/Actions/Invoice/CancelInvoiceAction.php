<?php

declare(strict_types=1);

namespace App\Actions\Invoice;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final readonly class CancelInvoiceAction
{
    public function __construct(
        private CreateAuditLogAction $createAuditLog,
    ) {}

    public function handle(Invoice $invoice, string $reason): Invoice
    {
        if (! $invoice->canBeCancelled()) {
            throw ValidationException::withMessages([
                'invoice' => 'This invoice cannot be cancelled once valid payments exist.',
            ]);
        }

        $before = $invoice->only(['status', 'cancelled_at', 'cancelled_by', 'cancellation_reason']);

        $invoice->forceFill([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancelled_by' => Auth::id(),
            'cancellation_reason' => $reason,
        ])->save();

        $this->createAuditLog->handle(
            'invoice.cancelled',
            $invoice,
            $before,
            $invoice->fresh()->only(['status', 'cancelled_at', 'cancelled_by', 'cancellation_reason']),
            $reason,
        );

        return $invoice->refresh();
    }
}
