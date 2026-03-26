<?php

namespace App\Actions\Report;

use App\Models\Invoice;
use Carbon\Carbon;

class ComputeOutstandingBalancesReportAction
{
    public function __invoke(?string $startDate, ?string $endDate): array
    {
        $start = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfMonth();

        $invoices = Invoice::with('customer')
            ->whereNotIn('status', ['draft', 'cancelled', 'paid'])
            ->whereBetween('invoice_date', [$start->toDateString(), $end->toDateString()])
            ->orderByDesc('balance_due')
            ->get();

        return [
            'invoices' => $invoices,
            'summary' => [
                'customers_with_balances' => $invoices->pluck('customer_id')->unique()->count(),
                'total_outstanding' => $invoices->sum('balance_due'),
                'overdue_total' => $invoices->where('status', 'overdue')->sum('balance_due'),
            ],
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
        ];
    }
}
