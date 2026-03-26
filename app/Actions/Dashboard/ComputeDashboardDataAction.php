<?php

namespace App\Actions\Dashboard;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;

class ComputeDashboardDataAction
{
    public function __invoke(): array
    {
        $today = Carbon::today();

        $stats = [
            'new_customers_today' => Customer::whereDate('created_at', $today)->count(),
            'invoices_issued_today' => Invoice::whereNotNull('issued_at')->whereDate('issued_at', $today)->count(),
            'collected_today' => Payment::where('status', 'valid')->whereDate('payment_date', $today)->sum('amount'),
            'expenses_today' => Expense::where('status', 'valid')->whereDate('expense_date', $today)->sum('amount'),
            'unpaid_balances' => Invoice::whereNotIn('status', ['cancelled', 'paid', 'draft'])->sum('balance_due'),
            'overdue_invoices' => Invoice::where('status', 'overdue')->count(),
            'active_orders' => Order::whereIn('status', ['confirmed', 'in_cutting', 'in_stitching', 'in_finishing'])->count(),
            'ready_orders' => Order::where('status', 'ready_for_delivery')->count(),
        ];

        $recent_orders = Order::with('customer')->latest()->take(5)->get();
        $recent_invoices = Invoice::with('customer')->latest()->take(5)->get();
        $recent_payments = Payment::with('invoice.customer')->where('status', 'valid')->latest()->take(5)->get();

        return [
            'stats' => $stats,
            'recent_orders' => $recent_orders,
            'recent_invoices' => $recent_invoices,
            'recent_payments' => $recent_payments,
        ];
    }
}
