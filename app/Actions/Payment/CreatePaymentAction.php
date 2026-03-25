<?php

namespace App\Actions\Payment;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class CreatePaymentAction
{
    public function __invoke(array $data, $invoice): Payment
    {
        $data['received_by'] = Auth::id();
        $data['status'] = 'valid';

        return $invoice->payments()->create($data);
    }
}
