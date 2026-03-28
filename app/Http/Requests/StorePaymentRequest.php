<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Invoice|null $invoice */
        $invoice = $this->route('invoice');

        return $invoice !== null && ($this->user()?->can('create', [Payment::class, $invoice]) ?? false);
    }

    public function rules(): array
    {
        return [
            'currency_id' => 'required|exists:currencies,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method_id' => [
                'required',
                'integer',
                Rule::exists('payment_methods', 'id')->where(static fn ($query) => $query->where('is_active', true)),
            ],
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Enter the amount received.',
            'amount.numeric' => 'The payment amount must be a valid number.',
            'amount.min' => 'The payment amount must be at least 0.01.',
            'payment_date.required' => 'Select the payment date.',
            'payment_date.date' => 'The payment date must be a valid date.',
            'payment_method_id.required' => 'Select a payment method.',
            'payment_method_id.exists' => 'Select a valid active payment method.',
            'reference_number.max' => 'The payment reference may not be greater than 255 characters.',
        ];
    }
}
