<?php

declare(strict_types=1);

namespace App\Actions\Invoice;

use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

final readonly class SyncInvoiceStatusesAction
{
    public function handle(): void
    {
        Invoice::query()
            ->whereIn('status', ['issued', 'partially_paid', 'overdue'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->where('balance_due', '>', 0)
            ->update(['status' => 'overdue']);

        Invoice::query()
            ->where('status', 'overdue')
            ->where(function ($query): void {
                $query->whereNull('due_date')
                    ->orWhereDate('due_date', '>=', now()->toDateString());
            })
            ->where('balance_due', '>', 0)
            ->update([
                'status' => DB::raw("CASE WHEN amount_paid > 0 THEN 'partially_paid' ELSE 'issued' END"),
            ]);
    }
}
