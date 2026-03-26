<?php

declare(strict_types=1);

namespace App\Actions\Report;

use App\Models\Expense;
use Carbon\Carbon;

final readonly class ComputeExpensesReportAction
{
    /**
     * @return array<string, mixed>
     */
    public function handle(?string $startDate, ?string $endDate): array
    {
        $start = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfMonth();

        $expenses = Expense::query()
            ->with('category')
            ->where('status', 'valid')
            ->whereBetween('expense_date', [$start->toDateString(), $end->toDateString()])
            ->get();

        $byCategory = $expenses->groupBy('expense_category_id')->map(function ($group): array {
            return [
                'name' => $group->first()?->category?->name ?? 'Unknown',
                'total' => $group->sum('amount'),
            ];
        });

        return [
            'expenses' => $expenses,
            'by_category' => $byCategory,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
        ];
    }
}
