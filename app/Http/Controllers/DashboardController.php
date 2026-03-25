<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        $stats = [
            'new_customers_today' => Customer::whereDate('created_at', $today)->count(),
            'invoices_created_today' => Invoice::whereDate('created_at', $today)->count(),
            'collected_today' => Payment::where('status', 'valid')->whereDate('payment_date', $today)->sum('amount'),
            'outstanding_balance' => Invoice::whereNotIn('status', ['cancelled', 'paid'])->sum('balance_due'),
            'active_orders' => Order::whereIn('status', ['confirmed', 'in_cutting', 'in_stitching', 'in_finishing'])->count(),
            'ready_orders' => Order::where('status', 'ready_for_delivery')->count(),
        ];

        $recent_orders = Order::with('customer')->latest()->take(5)->get();
        $recent_invoices = Invoice::with('customer')->latest()->take(5)->get();
        $recent_payments = Payment::with('invoice.customer')->where('status', 'valid')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recent_orders', 'recent_invoices', 'recent_payments'));
    }
}
