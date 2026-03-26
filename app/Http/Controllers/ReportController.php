<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Invoice\SyncInvoiceStatusesAction;
use App\Actions\Report\ComputeCustomerStatementAction;
use App\Actions\Report\ComputeExpensesReportAction;
use App\Actions\Report\ComputeOutstandingBalancesReportAction;
use App\Actions\Report\ComputePaymentsReportAction;
use App\Actions\Report\ComputeProfitLossReportAction;
use App\Actions\Report\ComputeSalesReportAction;
use App\Http\Requests\ReportDateRangeRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

final readonly class ReportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:reports.view'),
        ];
    }

    public function index(): View
    {
        return view('reports.index');
    }

    public function sales(
        ReportDateRangeRequest $request,
        ComputeSalesReportAction $action,
        SyncInvoiceStatusesAction $syncInvoiceStatuses,
    ): View {
        $syncInvoiceStatuses->handle();

        return view('reports.sales', $action->handle(
            $request->input('start_date', now()->startOfMonth()->toDateString()),
            $request->input('end_date', now()->endOfMonth()->toDateString()),
        ));
    }

    public function salesPrint(
        ReportDateRangeRequest $request,
        ComputeSalesReportAction $action,
        SyncInvoiceStatusesAction $syncInvoiceStatuses,
    ): View {
        $syncInvoiceStatuses->handle();

        return view('reports.print.sales', $action->handle(
            $request->input('start_date', now()->startOfMonth()->toDateString()),
            $request->input('end_date', now()->endOfMonth()->toDateString()),
        ));
    }

    public function expenses(ReportDateRangeRequest $request, ComputeExpensesReportAction $action): View
    {
        return view('reports.expenses', $action->handle(
            $request->input('start_date', now()->startOfMonth()->toDateString()),
            $request->input('end_date', now()->endOfMonth()->toDateString()),
        ));
    }

    public function expensesPrint(ReportDateRangeRequest $request, ComputeExpensesReportAction $action): View
    {
        return view('reports.print.expenses', $action->handle(
            $request->input('start_date', now()->startOfMonth()->toDateString()),
            $request->input('end_date', now()->endOfMonth()->toDateString()),
        ));
    }

    public function payments(ReportDateRangeRequest $request, ComputePaymentsReportAction $action): View
    {
        return view('reports.payments', $action->handle(
            $request->input('start_date', now()->startOfMonth()->toDateString()),
            $request->input('end_date', now()->endOfMonth()->toDateString()),
        ));
    }

    public function paymentsPrint(ReportDateRangeRequest $request, ComputePaymentsReportAction $action): View
    {
        return view('reports.print.payments', $action->handle(
            $request->input('start_date', now()->startOfMonth()->toDateString()),
            $request->input('end_date', now()->endOfMonth()->toDateString()),
        ));
    }

    public function outstandingBalances(
        ReportDateRangeRequest $request,
        ComputeOutstandingBalancesReportAction $action,
        SyncInvoiceStatusesAction $syncInvoiceStatuses,
    ): View {
        $syncInvoiceStatuses->handle();

        return view('reports.outstanding_balances', $action->handle(
            $request->input('start_date', now()->startOfMonth()->toDateString()),
            $request->input('end_date', now()->endOfMonth()->toDateString()),
        ));
    }

    public function outstandingBalancesPrint(
        ReportDateRangeRequest $request,
        ComputeOutstandingBalancesReportAction $action,
        SyncInvoiceStatusesAction $syncInvoiceStatuses,
    ): View {
        $syncInvoiceStatuses->handle();

        return view('reports.print.outstanding_balances', $action->handle(
            $request->input('start_date', now()->startOfMonth()->toDateString()),
            $request->input('end_date', now()->endOfMonth()->toDateString()),
        ));
    }

    public function customerStatement(
        ReportDateRangeRequest $request,
        ComputeCustomerStatementAction $action,
        SyncInvoiceStatusesAction $syncInvoiceStatuses,
    ): View {
        $syncInvoiceStatuses->handle();

        return view('reports.customer_statement', $action->handle(
            $request->integer('customer_id') ?: null,
            $request->input('start_date', now()->startOfMonth()->toDateString()),
            $request->input('end_date', now()->endOfMonth()->toDateString()),
        ));
    }

    public function customerStatementPrint(
        ReportDateRangeRequest $request,
        ComputeCustomerStatementAction $action,
        SyncInvoiceStatusesAction $syncInvoiceStatuses,
    ): View {
        $syncInvoiceStatuses->handle();

        return view('reports.print.customer_statement', $action->handle(
            $request->integer('customer_id') ?: null,
            $request->input('start_date', now()->startOfMonth()->toDateString()),
            $request->input('end_date', now()->endOfMonth()->toDateString()),
        ));
    }

    public function profitLoss(ReportDateRangeRequest $request, ComputeProfitLossReportAction $action): View
    {
        return view('reports.profit_loss', $action->handle(
            $request->input('start_date', now()->startOfMonth()->toDateString()),
            $request->input('end_date', now()->endOfMonth()->toDateString()),
        ));
    }

    public function profitLossPrint(ReportDateRangeRequest $request, ComputeProfitLossReportAction $action): View
    {
        return view('reports.print.profit_loss', $action->handle(
            $request->input('start_date', now()->startOfMonth()->toDateString()),
            $request->input('end_date', now()->endOfMonth()->toDateString()),
        ));
    }
}
