<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdatePaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var PaymentMethod|null $paymentMethod */
        $paymentMethod = $this->route('paymentMethod');

        return $paymentMethod !== null && ($this->user()?->can('update', $paymentMethod) ?? false);
    }

    public function rules(): array
    {
        /** @var PaymentMethod $paymentMethod */
        $paymentMethod = $this->route('paymentMethod');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('payment_methods', 'name')->ignore($paymentMethod)],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Enter a payment method name.',
            'name.unique' => 'That payment method already exists.',
            'is_active.required' => 'Specify whether this payment method is active.',
            'sort_order.required' => 'Provide a sort order for this payment method.',
        ];
    }
}
