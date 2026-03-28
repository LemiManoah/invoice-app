<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Expense;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Expense|null $expense */
        $expense = $this->route('expense');

        return $expense !== null && ($this->user()?->can('update', $expense) ?? false);
    }

    public function rules(): array
    {
        /** @var Expense $expense */
        $expense = $this->route('expense');

        return [
            'expense_category_id' => 'required|exists:expense_categories,id',
            'currency_id' => 'required|exists:currencies,id',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'payment_method_id' => [
                'required',
                'integer',
                Rule::exists('payment_methods', 'id')->where(
                    static fn ($query) => $query
                        ->where('is_active', true)
                        ->orWhere('id', $expense->payment_method_id),
                ),
            ],
            'vendor_name' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'expense_category_id.required' => 'Select an expense category.',
            'expense_category_id.exists' => 'Select a valid expense category.',
            'expense_date.required' => 'Select the expense date.',
            'amount.required' => 'Enter the expense amount.',
            'amount.numeric' => 'The expense amount must be a valid number.',
            'amount.min' => 'The expense amount must be at least 0.01.',
            'payment_method_id.required' => 'Select a payment method.',
            'payment_method_id.exists' => 'Select a valid active payment method.',
            'description.required' => 'Enter an expense description.',
        ];
    }
}
