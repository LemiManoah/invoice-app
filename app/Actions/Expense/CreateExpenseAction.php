<?php

declare(strict_types=1);

namespace App\Actions\Expense;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\Expense;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;

final readonly class CreateExpenseAction
{
    public function __construct(
        private CreateAuditLogAction $createAuditLog,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): Expense
    {
        $paymentMethod = PaymentMethod::query()
            ->active()
            ->findOrFail($data['payment_method_id']);

        $expense = Expense::create([
            ...$data,
            'payment_method' => $paymentMethod->name,
            'created_by' => Auth::id(),
            'status' => 'valid',
        ]);

        $this->createAuditLog->handle('expense.created', $expense, null, $expense->toArray());

        return $expense;
    }
}
