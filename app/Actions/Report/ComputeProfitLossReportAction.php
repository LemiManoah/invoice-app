<?php

namespace App\Actions\Report;

use App\Models\Expense;
use App\Models\Payment;
use Carbon\Carbon;

class ComputeProfitLossReportAction
{
    public function __invoke(?string $startDate, ?string $endDate): array
    {
        $start = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfMonth();

        $revenue = Payment::where('status', 'valid')->whereBetween('payment_date', [$start->toDateString(), $end->toDateString()])->sum('amount');
        $totalExpenses = Expense::where('status', 'valid')->whereBetween('expense_date', [$start->toDateString(), $end->toDateString()])->sum('amount');

        return [
            'revenue' => $revenue,
            'expenses' => $totalExpenses,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
        ];
    }
}
