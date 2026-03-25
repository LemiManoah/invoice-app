<?php

namespace App\Actions\Expense;

use App\Models\Expense;

class UpdateExpenseAction
{
    public function __invoke(Expense $expense, array $data): Expense
    {
        $expense->update($data);

        return $expense;
    }
}
