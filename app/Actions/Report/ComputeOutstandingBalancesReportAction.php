<?php

declare(strict_types=1);

namespace App\Actions\Report;

use App\Models\Invoice;
use App\Support\CurrencyManager;
use Carbon\Carbon;

final readonly class ComputeOutstandingBalancesReportAction
{
    /**
     * @return array<string, mixed>
     */
    public function handle(?string $startDate, ?string $endDate): array
    {
        $start = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfMonth();

        $invoices = Invoice::query()
            ->with(['customer', 'currency'])
            ->whereNotIn('status', ['draft', 'cancelled', 'paid'])
            ->whereBetween('invoice_date', [$start->toDateString(), $end->toDateString()])
            ->orderByDesc('balance_due')
            ->get();

        $currencyManager = app(CurrencyManager::class);

        return [
            'invoices' => $invoices,
            'summary' => [
                'customers_with_balances' => $invoices->pluck('customer_id')->unique()->count(),
                'total_outstanding' => $invoices->sum(fn (Invoice $i) => $currencyManager->convertValue($i->balance_due, $i->currency)),
                'overdue_total' => $invoices->where('status', 'overdue')->sum(fn (Invoice $i) => $currencyManager->convertValue($i->balance_due, $i->currency)),
            ],
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
        ];
    }
}
