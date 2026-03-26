<?php

namespace App\Http\Controllers;

use App\Actions\Invoice\SyncInvoiceStatusesAction;
use App\Actions\Report\ComputeCustomerStatementAction;
use App\Actions\Report\ComputeExpensesReportAction;
use App\Actions\Report\ComputeOutstandingBalancesReportAction;
use App\Actions\Report\ComputePaymentsReportAction;
use App\Actions\Report\ComputeProfitLossReportAction;
use App\Actions\Report\ComputeSalesReportAction;
use App\Http\Requests\ReportDateRangeRequest;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        private readonly ComputeSalesReportAction $computeSalesReport,
        private readonly ComputeExpensesReportAction $computeExpensesReport,
        private readonly ComputeProfitLossReportAction $computeProfitLossReport,
        private readonly ComputePaymentsReportAction $computePaymentsReport,
        private readonly ComputeOutstandingBalancesReportAction $computeOutstandingBalancesReport,
        private readonly ComputeCustomerStatementAction $computeCustomerStatement,
        private readonly SyncInvoiceStatusesAction $syncInvoiceStatuses,
    ) {
    }

    public function index(): View
    {
        return view('reports.index');
    }

    public function sales(ReportDateRangeRequest $request): View
    {
        ($this->syncInvoiceStatuses)();
        $start_date = $request->input('start_date', now()->startOfMonth()->toDateString());
        $end_date = $request->input('end_date', now()->endOfMonth()->toDateString());
        $data = ($this->computeSalesReport)($start_date, $end_date);

        return view('reports.sales', $data);
    }

    public function expenses(ReportDateRangeRequest $request): View
    {
        $start_date = $request->input('start_date', now()->startOfMonth()->toDateString());
        $end_date = $request->input('end_date', now()->endOfMonth()->toDateString());
        $data = ($this->computeExpensesReport)($start_date, $end_date);

        return view('reports.expenses', $data);
    }

    public function profitLoss(ReportDateRangeRequest $request): View
    {
        $start_date = $request->input('start_date', now()->startOfMonth()->toDateString());
        $end_date = $request->input('end_date', now()->endOfMonth()->toDateString());
        $data = ($this->computeProfitLossReport)($start_date, $end_date);

        return view('reports.profit_loss', $data);
    }

    public function payments(ReportDateRangeRequest $request): View
    {
        $start_date = $request->input('start_date', now()->startOfMonth()->toDateString());
        $end_date = $request->input('end_date', now()->endOfMonth()->toDateString());
        $data = ($this->computePaymentsReport)($start_date, $end_date);

        return view('reports.payments', $data);
    }

    public function outstandingBalances(ReportDateRangeRequest $request): View
    {
        ($this->syncInvoiceStatuses)();
        $start_date = $request->input('start_date', now()->startOfMonth()->toDateString());
        $end_date = $request->input('end_date', now()->endOfMonth()->toDateString());
        $data = ($this->computeOutstandingBalancesReport)($start_date, $end_date);

        return view('reports.outstanding_balances', $data);
    }

    public function customerStatement(ReportDateRangeRequest $request): View
    {
        ($this->syncInvoiceStatuses)();
        $start_date = $request->input('start_date', now()->startOfMonth()->toDateString());
        $end_date = $request->input('end_date', now()->endOfMonth()->toDateString());
        $data = ($this->computeCustomerStatement)($request->integer('customer_id') ?: null, $start_date, $end_date);

        return view('reports.customer_statement', $data);
    }
}
