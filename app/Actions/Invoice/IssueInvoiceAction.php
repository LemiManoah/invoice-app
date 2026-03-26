<?php

namespace App\Actions\Invoice;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\Invoice;
use Illuminate\Validation\ValidationException;

class IssueInvoiceAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLog,
        private readonly RefreshInvoiceStatusAction $refreshInvoiceStatus,
    ) {
    }

    public function __invoke(Invoice $invoice): Invoice
    {
        if ($invoice->status !== 'draft') {
            throw ValidationException::withMessages([
                'invoice' => 'Only draft invoices can be issued.',
            ]);
        }

        if ($invoice->items()->count() === 0) {
            throw ValidationException::withMessages([
                'invoice' => 'An invoice must have at least one item before it can be issued.',
            ]);
        }

        $invoice->forceFill([
            'issued_at' => now(),
            'status' => 'issued',
        ])->save();

        ($this->refreshInvoiceStatus)($invoice);

        ($this->createAuditLog)(
            'invoice.issued',
            $invoice,
            null,
            $invoice->fresh()->only(['status', 'issued_at', 'amount_paid', 'balance_due'])
        );

        return $invoice->refresh();
    }
}
