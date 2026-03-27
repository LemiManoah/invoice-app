<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Currency;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;

final class CurrencyManager
{
    private ?Currency $currency = null;

    public function current(): Currency
    {
        if ($this->currency instanceof Currency) {
            return $this->currency;
        }

        try {
            if (! Schema::hasTable('currencies')) {
                return $this->currency = $this->fallbackCurrency();
            }

            $currency = Currency::query()
                ->default()
                ->active()
                ->first()
                ?? Currency::query()->active()->ordered()->first()
                ?? $this->fallbackCurrency();

            return $this->currency = $currency;
        } catch (QueryException) {
            return $this->currency = $this->fallbackCurrency();
        }
    }

    public function formatValue(mixed $amount, ?int $fallbackDecimalPlaces = null): string
    {
        $currency = $this->current();
        $decimals = $currency->decimal_places;

        if ($fallbackDecimalPlaces !== null && $currency->code === 'USD') {
            $decimals = $fallbackDecimalPlaces;
        }

        return number_format((float) $amount, $decimals);
    }

    /**
     * @return array{code: string, symbol: string, decimal_places: int}
     */
    public function javascriptConfig(): array
    {
        $currency = $this->current();

        return [
            'code' => $currency->code,
            'symbol' => $currency->symbol,
            'decimal_places' => $currency->decimal_places,
        ];
    }

    private function fallbackCurrency(): Currency
    {
        return new Currency([
            'name' => 'Ugandan Shilling',
            'code' => 'UGX',
            'symbol' => 'UGX',
            'decimal_places' => 0,
            'is_default' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }
}
