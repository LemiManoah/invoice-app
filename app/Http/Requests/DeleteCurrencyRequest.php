<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Currency;
use Illuminate\Foundation\Http\FormRequest;

final class DeleteCurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Currency|null $currency */
        $currency = $this->route('currency');

        return $currency !== null && ($this->user()?->can('delete', $currency) ?? false);
    }

    public function rules(): array
    {
        return [];
    }
}
