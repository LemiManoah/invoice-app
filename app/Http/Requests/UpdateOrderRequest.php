<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'currency_id' => 'required|exists:currencies,id',
            'order_date' => 'required|date',
            'promised_delivery_date' => 'nullable|date|after_or_equal:order_date',
            'status' => 'required|string',
            'priority' => 'required|string',
            'notes' => 'nullable|string',
        ];
    }
}
