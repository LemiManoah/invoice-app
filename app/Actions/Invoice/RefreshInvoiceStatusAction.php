<?php

declare(strict_types=1);

namespace App\Actions\Invoice;

use App\Models\Invoice;

final readonly class RefreshInvoiceStatusAction
{
    public function handle(Invoice $invoice): Invoice
    {
        if ($invoice->status === 'cancelled') {
            return $invoice;
        }

        $today = today();
        $amountPaid = (float) $invoice->validPayments()->sum('amount');
        $balanceDue = max((float) $invoice->total_amount - $amountPaid, 0);

        if ($invoice->issued_at === null && $invoice->status === 'draft') {
            $status = 'draft';
        } elseif ($balanceDue <= 0) {
            $status = 'paid';
        } elseif ($amountPaid > 0) {
            $status = $invoice->due_date && $invoice->due_date->lt($today) ? 'overdue' : 'partially_paid';
        } else {
            $status = $invoice->due_date && $invoice->due_date->lt($today) ? 'overdue' : 'issued';
        }

        $invoice->forceFill([
            'amount_paid' => $amountPaid,
            'balance_due' => $balanceDue,
            'status' => $status,
        ])->save();

        return $invoice->refresh();
    }
}
