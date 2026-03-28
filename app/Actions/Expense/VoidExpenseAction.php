<?php

declare(strict_types=1);

namespace App\Actions\Expense;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final readonly class VoidExpenseAction
{
    public function __construct(
        private CreateAuditLogAction $createAuditLog,
    ) {}

    public function handle(Expense $expense, string $reason): Expense
    {
        if ($expense->isVoided()) {
            throw ValidationException::withMessages([
                'expense' => 'This expense has already been voided.',
            ]);
        }

        $before = $expense->only(['status', 'voided_at', 'voided_by', 'void_reason']);

        $expense->forceFill([
            'status' => 'voided',
            'voided_at' => now(),
            'voided_by' => Auth::id(),
            'void_reason' => $reason,
        ])->save();

        $this->createAuditLog->handle(
            'expense.voided',
            $expense,
            $before,
            $expense->fresh()->only(['status', 'voided_at', 'voided_by', 'void_reason']),
            $reason,
        );

        return $expense->refresh();
    }
}
