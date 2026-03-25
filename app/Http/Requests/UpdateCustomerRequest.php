<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $customer = $this->route('customer');
        $id = $customer ? $customer->id : null;

        return [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|unique:customers,phone'.($id ? ','.$id : ''),
            'alternative_phone' => 'nullable|string',
            'email' => 'nullable|email|unique:customers,email'.($id ? ','.$id : ''),
            'address' => 'nullable|string',
            'gender' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'notes' => 'nullable|string',
        ];
    }
}
