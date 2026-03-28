<?php

declare(strict_types=1);

namespace App\Actions\Report;

use App\Models\Customer;
use App\Support\CurrencyManager;
use Carbon\Carbon;
use Illuminate\Support\Collection;

final readonly class ComputeCustomerStatementAction
{
    /**
     * @return array<string, mixed>
     */
    public function handle(?int $customerId, ?string $startDate, ?string $endDate): array
    {
        $start = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
        $end = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfMonth();
        $customer = $customerId ? Customer::query()->find($customerId) : null;

        /** @var Collection<int, mixed> $invoices */
        $invoices = collect();
        /** @var Collection<int, mixed> $payments */
        $payments = collect();

        if ($customer !== null) {
            $invoices = $customer->invoices()
                ->with('currency')
                ->whereBetween('invoice_date', [$start->toDateString(), $end->toDateString()])
                ->orderBy('invoice_date')
                ->get();

            $payments = $customer->payments()
                ->with(['invoice', 'receipt', 'currency'])
                ->where('status', 'valid')
                ->whereBetween('payment_date', [$start->toDateString(), $end->toDateString()])
                ->orderBy('payment_date')
                ->get();
        }

        $currencyManager = app(CurrencyManager::class);

        return [
            'customers' => Customer::query()->orderBy('full_name')->get(),
            'customer' => $customer,
            'invoices' => $invoices,
            'payments' => $payments,
            'summary' => [
                'total_invoiced' => collect($invoices)->sum(fn ($i) => $currencyManager->convertValue($i->total_amount, $i->currency)),
                'total_paid' => collect($payments)->sum(fn ($p) => $currencyManager->convertValue($p->amount, $p->currency)),
                'balance_due' => $customer ? $customer->invoices()->with('currency')->whereNotIn('status', ['cancelled', 'paid'])->get()->sum(fn ($i) => $currencyManager->convertValue($i->balance_due, $i->currency)) : 0,
            ],
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
        ];
    }
}
