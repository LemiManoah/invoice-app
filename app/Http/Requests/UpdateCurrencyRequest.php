<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Currency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateCurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Currency|null $currency */
        $currency = $this->route('currency');

        return $currency !== null && ($this->user()?->can('update', $currency) ?? false);
    }

    public function rules(): array
    {
        /** @var Currency $currency */
        $currency = $this->route('currency');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('currencies', 'name')->ignore($currency)],
            'code' => ['required', 'string', 'size:3', Rule::unique('currencies', 'code')->ignore($currency)],
            'symbol' => ['required', 'string', 'max:20'],
            'decimal_places' => ['required', 'integer', 'min:0', 'max:4'],
            'exchange_rate' => ['required', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'is_default' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator): void {
                /** @var Currency $currency */
                $currency = $this->route('currency');

                if ($currency->is_default && ! $this->boolean('is_default')) {
                    $validator->errors()->add('is_default', 'The current default currency must remain default until another currency is set as default.');
                }
            },
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
