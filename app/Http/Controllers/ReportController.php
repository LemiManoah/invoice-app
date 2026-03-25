<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Expense;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function sales(Request $request)
    {
        $start_date = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $end_date = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $invoices = Invoice::with('customer')
            ->whereBetween('invoice_date', [$start_date, $end_date])
            ->get();

        $summary = [
            'total_invoiced' => $invoices->sum('total_amount'),
            'total_paid' => $invoices->sum('amount_paid'),
            'total_balance' => $invoices->sum('balance_due'),
            'invoice_count' => $invoices->count(),
        ];

        return view('reports.sales', compact('invoices', 'summary', 'start_date', 'end_date'));
    }

    public function expenses(Request $request)
    {
        $start_date = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $end_date = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $expenses = Expense::with('category')
            ->where('status', 'valid')
            ->whereBetween('expense_date', [$start_date, $end_date])
            ->get();

        $by_category = $expenses->groupBy('expense_category_id')->map(function ($group) {
            return [
                'name' => $group->first()->category->name,
                'total' => $group->sum('amount'),
            ];
        });

        return view('reports.expenses', compact('expenses', 'by_category', 'start_date', 'end_date'));
    }

    public function profitLoss(Request $request)
    {
        $start_date = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $end_date = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $revenue = Payment::where('status', 'valid')
            ->whereBetween('payment_date', [$start_date, $end_date])
            ->sum('amount');

        $total_expenses = Expense::where('status', 'valid')
            ->whereBetween('expense_date', [$start_date, $end_date])
            ->sum('amount');

        return view('reports.profit_loss', compact('revenue', 'total_expenses', 'start_date', 'end_date'));
    }
}
