<?php

declare(strict_types=1);

namespace App\Actions\Report;

use App\Models\Expense;
use App\Models\Payment;
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

        return [
            'revenue' => Payment::query()->where('status', 'valid')->whereBetween('payment_date', [$start->toDateString(), $end->toDateString()])->sum('amount'),
            'total_expenses' => Expense::query()->where('status', 'valid')->whereBetween('expense_date', [$start->toDateString(), $end->toDateString()])->sum('amount'),
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
        ];
    }
}
