<?php

declare(strict_types=1);

namespace App\Actions\Report;

use App\Models\Invoice;
use App\Support\CurrencyManager;
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
            ->with(['customer', 'currency'])
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->whereBetween('invoice_date', [$start->toDateString(), $end->toDateString()])
            ->get();

        $currencyManager = app(CurrencyManager::class);

        return [
            'invoices' => $invoices,
            'summary' => [
                'total_invoiced' => $invoices->sum(fn (Invoice $i) => $currencyManager->convertValue($i->total_amount, $i->currency)),
                'total_paid' => $invoices->sum(fn (Invoice $i) => $currencyManager->convertValue($i->amount_paid, $i->currency)),
                'total_balance' => $invoices->sum(fn (Invoice $i) => $currencyManager->convertValue($i->balance_due, $i->currency)),
                'invoice_count' => $invoices->count(),
            ],
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
        ];
    }
}
