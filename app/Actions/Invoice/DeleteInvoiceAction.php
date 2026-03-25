<?php

namespace App\Actions\Invoice;

use App\Models\Invoice;

class DeleteInvoiceAction
{
    public function __invoke(Invoice $invoice): void
    {
        $invoice->delete();
    }
}
