<?php

namespace App\Actions\Expense;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class CreateExpenseAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLog,
    ) {
    }

    public function __invoke(array $data): Expense
    {
        $data['created_by'] = Auth::id();
        $data['status'] = 'valid';

        $expense = Expense::create($data);

        ($this->createAuditLog)('expense.created', $expense, null, $expense->toArray());

        return $expense;
    }
}
