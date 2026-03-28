<?php

declare(strict_types=1);

namespace App\Actions\Report;

use App\Models\Payment;
use App\Support\CurrencyManager;
use Carbon\Carbon;

final readonly class ComputePaymentsReportAction
{
    /**
     * @return array<string, mixed>
     */
    public function handle(?string $startDate, ?string $endDate): array
    {
        $start = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfMonth();

        $payments = Payment::query()
            ->with(['invoice.customer', 'receipt', 'currency'])
            ->where('status', 'valid')
            ->whereBetween('payment_date', [$start->toDateString(), $end->toDateString()])
            ->latest('payment_date')
            ->get();

        $currencyManager = app(CurrencyManager::class);

        return [
            'payments' => $payments,
            'summary' => [
                'total_collected' => $payments->sum(fn (Payment $p) => $currencyManager->convertValue($p->amount, $p->currency)),
                'payments_count' => $payments->count(),
            ],
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
        ];
    }
}
