<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Dashboard\ComputeDashboardDataAction;
use App\Actions\Invoice\SyncInvoiceStatusesAction;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

final readonly class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:dashboard.view'),
        ];
    }

    public function index(
        ComputeDashboardDataAction $computeDashboardData,
        SyncInvoiceStatusesAction $syncInvoiceStatuses,
    ): View {
        $syncInvoiceStatuses->handle();

        return view('dashboard', $computeDashboardData->handle());
    }
}
