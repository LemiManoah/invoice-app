<?php

namespace App\Actions\Report;

use App\Models\Payment;
use Carbon\Carbon;

class ComputePaymentsReportAction
{
    public function __invoke(?string $startDate, ?string $endDate): array
    {
        $start = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfMonth();

        $payments = Payment::with(['invoice.customer', 'receipt'])
            ->where('status', 'valid')
            ->whereBetween('payment_date', [$start->toDateString(), $end->toDateString()])
            ->latest('payment_date')
            ->get();

        return [
            'payments' => $payments,
            'summary' => [
                'total_collected' => $payments->sum('amount'),
                'payments_count' => $payments->count(),
            ],
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
        ];
    }
}
