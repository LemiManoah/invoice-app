<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

final class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Invoice::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'order_id' => ['nullable', 'integer', 'exists:orders,id'],
            'currency_id' => ['required', 'integer', 'exists:currencies,id'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:invoice_date'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_name' => ['required', 'string', 'max:255'],
            'items.*.description' => ['nullable', 'string'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator): void {
                $orderId = $this->integer('order_id');

                if ($orderId === 0) {
                    return;
                }

                $order = Order::query()->with('invoice')->find($orderId);

                if ($order === null) {
                    return;
                }

                if ((int) $order->customer_id !== $this->integer('customer_id')) {
                    $validator->errors()->add('order_id', 'The selected order does not belong to the selected customer.');
                }

                if ($order->invoice !== null) {
                    $validator->errors()->add('order_id', 'The selected order already has an invoice.');
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Select the customer for this invoice.',
            'customer_id.exists' => 'Select a valid customer.',
            'order_id.exists' => 'Select a valid order.',
            'invoice_date.required' => 'Select the invoice date.',
            'invoice_date.date' => 'The invoice date must be a valid date.',
            'due_date.date' => 'The due date must be a valid date.',
            'due_date.after_or_equal' => 'The due date must be on or after the invoice date.',
            'items.required' => 'Add at least one invoice item.',
            'items.array' => 'The invoice items are invalid.',
            'items.min' => 'Add at least one invoice item.',
            'items.*.item_name.required' => 'Each invoice item needs a name.',
            'items.*.quantity.required' => 'Each invoice item needs a quantity.',
            'items.*.quantity.integer' => 'Each invoice item quantity must be a whole number.',
            'items.*.quantity.min' => 'Each invoice item quantity must be at least 1.',
            'items.*.unit_price.required' => 'Each invoice item needs a unit price.',
            'items.*.unit_price.numeric' => 'Each invoice item unit price must be a valid number.',
            'items.*.unit_price.min' => 'Each invoice item unit price must be zero or more.',
            'discount_amount.numeric' => 'The discount amount must be a valid number.',
            'discount_amount.min' => 'The discount amount must be zero or more.',
            'tax_amount.numeric' => 'The tax amount must be a valid number.',
            'tax_amount.min' => 'The tax amount must be zero or more.',
        ];
    }
}
