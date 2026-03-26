<?php

namespace App\Http\Controllers;

use App\Actions\Dashboard\ComputeDashboardDataAction;
use App\Actions\Invoice\SyncInvoiceStatusesAction;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly ComputeDashboardDataAction $computeDashboardData,
        private readonly SyncInvoiceStatusesAction $syncInvoiceStatuses,
    ) {
    }

    public function index(): View
    {
        ($this->syncInvoiceStatuses)();
        $data = ($this->computeDashboardData)();

        return view('dashboard', $data);
    }
}
