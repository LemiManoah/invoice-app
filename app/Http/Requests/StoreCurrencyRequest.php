<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Currency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreCurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Currency::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('currencies', 'name')],
            'code' => ['required', 'string', 'size:3', Rule::unique('currencies', 'code')],
            'symbol' => ['required', 'string', 'max:20'],
            'decimal_places' => ['required', 'integer', 'min:0', 'max:4'],
            'exchange_rate' => ['required', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'is_default' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Enter the currency name.',
            'name.unique' => 'That currency name already exists.',
            'code.required' => 'Enter the currency code.',
            'code.size' => 'The currency code must be exactly 3 letters.',
            'code.unique' => 'That currency code already exists.',
            'symbol.required' => 'Enter the currency symbol or label.',
            'decimal_places.required' => 'Enter how many decimal places to display.',
            'decimal_places.integer' => 'The decimal places must be a whole number.',
            'decimal_places.min' => 'The decimal places cannot be negative.',
            'sort_order.required' => 'Enter the display order for this currency.',
        ];
    }
}
