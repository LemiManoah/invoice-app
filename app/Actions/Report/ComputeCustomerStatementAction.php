<?php

namespace App\Actions\Report;

use App\Models\Customer;
use Carbon\Carbon;

class ComputeCustomerStatementAction
{
    public function __invoke(?int $customerId, ?string $startDate, ?string $endDate): array
    {
        $start = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfMonth();
        $customer = $customerId ? Customer::find($customerId) : null;

        $invoices = collect();
        $payments = collect();

        if ($customer !== null) {
            $invoices = $customer->invoices()
                ->whereBetween('invoice_date', [$start->toDateString(), $end->toDateString()])
                ->orderBy('invoice_date')
                ->get();

            $payments = $customer->payments()
                ->with(['invoice', 'receipt'])
                ->where('status', 'valid')
                ->whereBetween('payment_date', [$start->toDateString(), $end->toDateString()])
                ->orderBy('payment_date')
                ->get();
        }

        return [
            'customers' => Customer::orderBy('full_name')->get(),
            'customer' => $customer,
            'invoices' => $invoices,
            'payments' => $payments,
            'summary' => [
                'total_invoiced' => $invoices->sum('total_amount'),
                'total_paid' => $payments->sum('amount'),
                'balance_due' => $customer?->invoices()->whereNotIn('status', ['cancelled', 'paid'])->sum('balance_due') ?? 0,
            ],
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
        ];
    }
}
