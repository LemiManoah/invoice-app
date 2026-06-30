<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

final readonly class ReceiptController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:receipts.view', only: ['index', 'show']),
            new Middleware('permission:receipts.print', only: ['print', 'printThermal', 'downloadPdf']),
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

    public function printThermal(Request $request, Receipt $receipt): View
    {
        $this->authorize('print', $receipt);

        $receipt->load(['payment.invoice.customer', 'payment.receiver']);

        $paperWidth = (int) $request->query('size', 80);
        $paperWidth = in_array($paperWidth, [58, 80], true) ? $paperWidth : 80;

        return view('receipts.print-thermal', compact('receipt', 'paperWidth'));
    }

    public function downloadPdf(Receipt $receipt): Response
    {
        $this->authorize('print', $receipt);

        $receipt->load(['payment.invoice.customer', 'payment.receiver']);

        $pdf = Pdf::loadView('receipts.print', compact('receipt'));

        return $pdf->download('receipt-' . $receipt->number . '.pdf');
    }
}
