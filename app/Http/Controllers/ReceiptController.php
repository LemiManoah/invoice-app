<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Receipt;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

final readonly class ReceiptController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:receipts.view'),
        ];
    }

    public function show(Receipt $receipt): View
    {
        $this->authorize('view', $receipt);

        $receipt->load(['payment.invoice.customer', 'payment.receiver']);

        return view('receipts.show', compact('receipt'));
    }

    public function print(Receipt $receipt): View
    {
        $this->authorize('print', $receipt);

        $receipt->load(['payment.invoice.customer', 'payment.receiver']);

        return view('receipts.print', compact('receipt'));
    }
}
