<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Illuminate\View\View;

class ReceiptController extends Controller
{
    public function show(Receipt $receipt): View
    {
        $receipt->load(['payment.invoice.customer', 'payment.receiver']);

        return view('receipts.show', compact('receipt'));
    }

    public function print(Receipt $receipt): View
    {
        $receipt->load(['payment.invoice.customer', 'payment.receiver']);

        return view('receipts.print', compact('receipt'));
    }
}
