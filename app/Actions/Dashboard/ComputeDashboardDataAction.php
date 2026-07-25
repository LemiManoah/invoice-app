<?php

declare(strict_types=1);

namespace App\Actions\Dashboard;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Support\CurrencyManager;
use Carbon\Carbon;

final readonly class ComputeDashboardDataAction
{
    /**
     * @return array<string, mixed>
     */
    public function handle(): array
    {
        $today = Carbon::today();
        $user = auth()->user();

        $canViewInvoices = $user->can('invoices.view');
        $canViewExpenses = $user->can('expenses.view');
        $canViewPayments = $user->can('payments.view');
        $canViewOrders = $user->can('orders.view');
        $canViewCustomers = $user->can('customers.view');

        $stats = [
            'new_customers_today' => $canViewCustomers ? Customer::query()->whereDate('created_at', $today)->count() : null,
            'invoices_issued_today' => $canViewInvoices ? Invoice::query()->whereNotNull('issued_at')->whereDate('issued_at', $today)->count() : null,
            'collected_today' => $canViewPayments ? Payment::query()->with('currency')->where('status', 'valid')->whereDate('payment_date', $today)->get()
                ->sum(fn (Payment $p) => app(CurrencyManager::class)->convertValue($p->amount, $p->currency)) : null,
            'expenses_today' => $canViewExpenses ? Expense::query()->with('currency')->where('status', 'valid')->whereDate('expense_date', $today)->get()
                ->sum(fn (Expense $e) => app(CurrencyManager::class)->convertValue($e->amount, $e->currency)) : null,
            'unpaid_balances' => $canViewInvoices ? Invoice::query()->with('currency')->whereNotIn('status', ['cancelled', 'paid', 'draft'])->get()
                ->sum(fn (Invoice $i) => app(CurrencyManager::class)->convertValue($i->balance_due, $i->currency)) : null,
            'overdue_invoices' => $canViewInvoices ? Invoice::query()->where('status', 'overdue')->count() : null,
            'active_orders' => $canViewOrders ? Order::query()->whereIn('status', ['confirmed', 'in_cutting', 'in_stitching', 'in_finishing'])->count() : null,
            'ready_orders' => $canViewOrders ? Order::query()->where('status', 'ready_for_delivery')->count() : null,
        ];

        return [
            'stats' => $stats,
            'recent_orders' => $canViewOrders ? Order::query()->with('customer')->latest()->take(5)->get() : collect(),
            'recent_invoices' => $canViewInvoices ? Invoice::query()->with('customer')->latest()->take(5)->get() : collect(),
            'recent_payments' => $canViewPayments ? Payment::query()->with('invoice.customer')->where('status', 'valid')->latest()->take(5)->get() : collect(),
            'invoices_chart' => $canViewInvoices ? $this->invoicesIssuedLast30Days($today) : ['labels' => [], 'values' => []],
            'payments_chart' => $canViewPayments ? $this->paymentsCollectedLast30Days($today) : ['labels' => [], 'values' => []],
        ];
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, int>}
     */
    private function invoicesIssuedLast30Days(Carbon $today): array
    {
        $start = $today->copy()->subDays(29);

        $counts = Invoice::query()
            ->whereNotNull('issued_at')
            ->whereBetween('issued_at', [$start->copy()->startOfDay(), $today->copy()->endOfDay()])
            ->selectRaw('date(issued_at) as day, count(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $labels = [];
        $values = [];

        for ($date = $start->copy(); $date->lte($today); $date->addDay()) {
            $labels[] = $date->format('M d');
            $values[] = (int) ($counts[$date->format('Y-m-d')] ?? 0);
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, float>}
     */
    private function paymentsCollectedLast30Days(Carbon $today): array
    {
        $start = $today->copy()->subDays(29);
        $currencyManager = app(CurrencyManager::class);

        $totalsByDay = Payment::query()
            ->with('currency')
            ->where('status', 'valid')
            ->whereBetween('payment_date', [$start->copy()->startOfDay(), $today->copy()->endOfDay()])
            ->get()
            ->groupBy(fn (Payment $payment) => $payment->payment_date->format('Y-m-d'))
            ->map(fn ($payments) => $payments->sum(
                fn (Payment $payment) => $currencyManager->convertValue($payment->amount, $payment->currency)
            ));

        $labels = [];
        $values = [];

        for ($date = $start->copy(); $date->lte($today); $date->addDay()) {
            $labels[] = $date->format('M d');
            $values[] = (float) ($totalsByDay[$date->format('Y-m-d')] ?? 0);
        }

        return ['labels' => $labels, 'values' => $values];
    }
}
