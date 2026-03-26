<?php

declare(strict_types=1);

namespace App\Actions\Report;

use App\Models\Invoice;
use Carbon\Carbon;

final readonly class ComputeSalesReportAction
{
    /**
     * @return array<string, mixed>
     */
    public function handle(?string $startDate, ?string $endDate): array
    {
        $start = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfMonth();

        $invoices = Invoice::query()
            ->with('customer')
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->whereBetween('invoice_date', [$start->toDateString(), $end->toDateString()])
            ->get();

        return [
            'invoices' => $invoices,
            'summary' => [
                'total_invoiced' => $invoices->sum('total_amount'),
                'total_paid' => $invoices->sum('amount_paid'),
                'total_balance' => $invoices->sum('balance_due'),
                'invoice_count' => $invoices->count(),
            ],
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
        ];
    }
}
