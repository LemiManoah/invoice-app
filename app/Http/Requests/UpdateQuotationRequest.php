<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Quotation;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateQuotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('quotation')) ?? false;
    }

    public function rules(): array
    {
        return [
            'customer_id'     => ['required', 'integer', 'exists:customers,id'],
            'currency_id'     => ['required', 'integer', 'exists:currencies,id'],
            'quotation_date'  => ['required', 'date'],
            'valid_until'     => ['nullable', 'date', 'after_or_equal:quotation_date'],
            'notes'           => ['nullable', 'string'],
            'items'           => ['required', 'array', 'min:1'],
            'items.*.item_name'   => ['required', 'string', 'max:255'],
            'items.*.description' => ['nullable', 'string'],
            'items.*.quantity'    => ['required', 'integer', 'min:1'],
            'items.*.unit_price'  => ['required', 'numeric', 'min:0'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'tax_amount'      => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
