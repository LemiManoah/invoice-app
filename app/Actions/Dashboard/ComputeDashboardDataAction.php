<?php

declare(strict_types=1);

namespace App\Actions\Dashboard;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;

final readonly class ComputeDashboardDataAction
{
    /**
     * @return array<string, mixed>
     */
    public function handle(): array
    {
        $today = Carbon::today();

        $stats = [
            'new_customers_today' => Customer::query()->whereDate('created_at', $today)->count(),
            'invoices_issued_today' => Invoice::query()->whereNotNull('issued_at')->whereDate('issued_at', $today)->count(),
            'collected_today' => Payment::query()->where('status', 'valid')->whereDate('payment_date', $today)->sum('amount'),
            'expenses_today' => Expense::query()->where('status', 'valid')->whereDate('expense_date', $today)->sum('amount'),
            'unpaid_balances' => Invoice::query()->whereNotIn('status', ['cancelled', 'paid', 'draft'])->sum('balance_due'),
            'overdue_invoices' => Invoice::query()->where('status', 'overdue')->count(),
            'active_orders' => Order::query()->whereIn('status', ['confirmed', 'in_cutting', 'in_stitching', 'in_finishing'])->count(),
            'ready_orders' => Order::query()->where('status', 'ready_for_delivery')->count(),
        ];

        return [
            'stats' => $stats,
            'recent_orders' => Order::query()->with('customer')->latest()->take(5)->get(),
            'recent_invoices' => Invoice::query()->with('customer')->latest()->take(5)->get(),
            'recent_payments' => Payment::query()->with('invoice.customer')->where('status', 'valid')->latest()->take(5)->get(),
        ];
    }
}
