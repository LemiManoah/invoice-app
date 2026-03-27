<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;

final class DeletePaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var PaymentMethod|null $paymentMethod */
        $paymentMethod = $this->route('paymentMethod');

        return $paymentMethod !== null && ($this->user()?->can('delete', $paymentMethod) ?? false);
    }

    public function rules(): array
    {
        return [];
    }
}
