<?php

namespace App\Actions\Payment;

use App\Models\Payment;
use App\Models\Receipt;

class GenerateReceiptAction
{
    public function __invoke(Payment $payment): Receipt
    {
        if ($payment->receipt !== null) {
            return $payment->receipt;
        }

        return $payment->receipt()->create([
            'receipt_number' => 'RCT-'.strtoupper(uniqid()),
            'issued_date' => $payment->payment_date,
        ]);
    }
}
