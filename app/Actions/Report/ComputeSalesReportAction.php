<?php

namespace App\Actions\Report;

use App\Models\Invoice;
use Carbon\Carbon;

class ComputeSalesReportAction
{
    public function __invoke(?string $startDate, ?string $endDate): array
    {
        $start = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfMonth();

        $invoices = Invoice::with('customer')
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->whereBetween('invoice_date', [$start->toDateString(), $end->toDateString()])
            ->get();

        $summary = [
            'total_invoiced' => $invoices->sum('total_amount'),
            'total_paid' => $invoices->sum('amount_paid'),
            'total_balance' => $invoices->sum('balance_due'),
            'invoice_count' => $invoices->count(),
        ];

        return [
            'invoices' => $invoices,
            'summary' => $summary,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
        ];
    }
}
