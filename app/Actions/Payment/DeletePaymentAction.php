<?php

namespace App\Actions\Payment;

use App\Models\Payment;

class DeletePaymentAction
{
    public function __invoke(Payment $payment): void
    {
        $payment->delete();
    }
}
