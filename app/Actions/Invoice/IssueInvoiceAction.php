<?php

declare(strict_types=1);

namespace App\Actions\Invoice;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\Invoice;
use Illuminate\Validation\ValidationException;

final readonly class IssueInvoiceAction
{
    public function __construct(
        private CreateAuditLogAction $createAuditLog,
        private RefreshInvoiceStatusAction $refreshInvoiceStatus,
    ) {}

    public function handle(Invoice $invoice): Invoice
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

        $this->refreshInvoiceStatus->handle($invoice);
        $this->createAuditLog->handle(
            'invoice.issued',
            $invoice,
            null,
            $invoice->fresh()->only(['status', 'issued_at', 'amount_paid', 'balance_due']),
        );

        return $invoice->refresh();
    }
}
