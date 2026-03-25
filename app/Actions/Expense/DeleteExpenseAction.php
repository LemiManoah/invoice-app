<?php

namespace App\Actions\Expense;

use App\Models\Expense;

class DeleteExpenseAction
{
    public function __invoke(Expense $expense): void
    {
        $expense->delete();
    }
}
