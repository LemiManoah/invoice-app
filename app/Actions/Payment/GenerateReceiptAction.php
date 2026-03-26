<?php

declare(strict_types=1);

namespace App\Actions\Payment;

use App\Models\Payment;
use App\Models\Receipt;

final readonly class GenerateReceiptAction
{
    public function handle(Payment $payment): Receipt
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
