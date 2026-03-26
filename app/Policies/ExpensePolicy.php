<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;

final readonly class ExpensePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('expenses.view');
    }

    public function view(User $user, Expense $expense): bool
    {
        return $user->can('expenses.view');
    }

    public function create(User $user): bool
    {
        return $user->can('expenses.create');
    }

    public function update(User $user, Expense $expense): bool
    {
        return $user->can('expenses.update') && ! $expense->isVoided();
    }

    public function void(User $user, Expense $expense): bool
    {
        return $user->can('expenses.void') && ! $expense->isVoided();
    }
}
