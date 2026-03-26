<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'expense_category_id' => 'required|exists:expense_categories,id',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => ['required', 'string', Rule::in(['Cash', 'Bank Transfer', 'Mobile Money', 'Card', 'Other'])],
            'vendor_name' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'description' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ];
    }
}
