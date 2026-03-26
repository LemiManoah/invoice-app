<?php

declare(strict_types=1);

namespace App\Actions\Expense;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\Expense;
use Illuminate\Validation\ValidationException;

final readonly class UpdateExpenseAction
{
    public function __construct(
        private CreateAuditLogAction $createAuditLog,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Expense $expense, array $data): Expense
    {
        if ($expense->isVoided()) {
            throw ValidationException::withMessages([
                'expense' => 'Voided expenses cannot be edited.',
            ]);
        }

        $before = $expense->toArray();
        $expense->update($data);

        $this->createAuditLog->handle('expense.updated', $expense, $before, $expense->fresh()->toArray());

        return $expense;
    }
}
