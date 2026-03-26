<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(): View
    {
        $auditLogs = AuditLog::with('user')
            ->latest()
            ->paginate(20);

        return view('audit_logs.index', compact('auditLogs'));
    }
}
