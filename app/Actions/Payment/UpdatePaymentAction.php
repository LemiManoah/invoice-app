<?php

namespace App\Actions\Payment;

use App\Models\Payment;

class UpdatePaymentAction
{
    public function __invoke(Payment $payment, array $data): Payment
    {
        $payment->update($data);

        return $payment;
    }
}
