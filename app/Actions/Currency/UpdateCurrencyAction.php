<?php

declare(strict_types=1);

namespace App\Actions\Currency;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;

final readonly class UpdateCurrencyAction
{
    public function __construct(
        private CreateAuditLogAction $createAuditLog,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(Currency $currency, array $attributes): Currency
    {
        return DB::transaction(function () use ($currency, $attributes): Currency {
            $before = $currency->toArray();

            if (($attributes['is_default'] ?? false) === true) {
                Currency::query()
                    ->whereKeyNot($currency->id)
                    ->update(['is_default' => false]);

                $attributes['is_active'] = true;
            }

            $currency->update([
                ...$attributes,
                'code' => strtoupper((string) $attributes['code']),
            ]);

            $this->createAuditLog->handle('currency.updated', $currency, $before, $currency->fresh()->toArray());

            return $currency->fresh();
        });
    }
}
