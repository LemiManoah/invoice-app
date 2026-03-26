<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

final readonly class AuditLogController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:audit-logs.view'),
        ];
    }

    public function index(): View
    {
        $this->authorize('viewAny', AuditLog::class);

        $auditLogs = AuditLog::query()->with('user')->latest()->paginate(20);

        return view('audit_logs.index', compact('auditLogs'));
    }
}
