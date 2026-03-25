<?php

namespace App\Http\Controllers;

use App\Actions\Report\ComputeExpensesReportAction;
use App\Actions\Report\ComputeProfitLossReportAction;
use App\Actions\Report\ComputeSalesReportAction;
use App\Http\Requests\ReportDateRangeRequest;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function sales(ReportDateRangeRequest $request)
    {
        $start_date = $request->input('start_date', now()->startOfMonth()->toDateString());
        $end_date = $request->input('end_date', now()->endOfMonth()->toDateString());
        $data = (new ComputeSalesReportAction)($start_date, $end_date);

        return view('reports.sales', $data);
    }

    public function expenses(ReportDateRangeRequest $request)
    {
        $start_date = $request->input('start_date', now()->startOfMonth()->toDateString());
        $end_date = $request->input('end_date', now()->endOfMonth()->toDateString());
        $data = (new ComputeExpensesReportAction)($start_date, $end_date);

        return view('reports.expenses', $data);
    }

    public function profitLoss(ReportDateRangeRequest $request)
    {
        $start_date = $request->input('start_date', now()->startOfMonth()->toDateString());
        $end_date = $request->input('end_date', now()->endOfMonth()->toDateString());
        $data = (new ComputeProfitLossReportAction)($start_date, $end_date);

        return view('reports.profit_loss', $data);
    }
}
