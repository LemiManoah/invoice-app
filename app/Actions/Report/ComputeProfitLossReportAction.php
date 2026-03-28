<?php

declare(strict_types=1);

namespace App\Actions\Report;

use App\Models\Expense;
use App\Models\Payment;
use App\Support\CurrencyManager;
use Carbon\Carbon;

final readonly class ComputeProfitLossReportAction
{
    /**
     * @return array<string, mixed>
     */
    public function handle(?string $startDate, ?string $endDate): array
    {
        $start = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfMonth();

        $revenue = Payment::query()->with('currency')->where('status', 'valid')->whereBetween('payment_date', [$start->toDateString(), $end->toDateString()])->get()
            ->sum(fn (Payment $p) => app(CurrencyManager::class)->convertValue($p->amount, $p->currency));

        $expenses = Expense::query()->with('currency')->where('status', 'valid')->whereBetween('expense_date', [$start->toDateString(), $end->toDateString()])->get()
            ->sum(fn (Expense $e) => app(CurrencyManager::class)->convertValue($e->amount, $e->currency));

        return [
            'revenue' => $revenue,
            'total_expenses' => $expenses,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
        ];
    }
}
