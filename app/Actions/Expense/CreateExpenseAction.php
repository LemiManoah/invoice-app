<?php

namespace App\Actions\Expense;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class CreateExpenseAction
{
    public function __invoke(array $data): Expense
    {
        $data['created_by'] = Auth::id();
        $data['status'] = 'valid';

        return Expense::create($data);
    }
}
