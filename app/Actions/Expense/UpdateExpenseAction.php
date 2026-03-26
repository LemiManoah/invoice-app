<?php

namespace App\Actions\Expense;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\Expense;
use Illuminate\Validation\ValidationException;

class UpdateExpenseAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLog,
    ) {
    }

    public function __invoke(Expense $expense, array $data): Expense
    {
        if ($expense->isVoided()) {
            throw ValidationException::withMessages([
                'expense' => 'Voided expenses cannot be edited.',
            ]);
        }

        $before = $expense->toArray();
        $expense->update($data);

        ($this->createAuditLog)('expense.updated', $expense, $before, $expense->fresh()->toArray());

        return $expense;
    }
}
