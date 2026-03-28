<?php

declare(strict_types=1);

namespace App\Actions\Currency;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;

final readonly class CreateCurrencyAction
{
    public function __construct(
        private CreateAuditLogAction $createAuditLog,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes): Currency
    {
        return DB::transaction(function () use ($attributes): Currency {
            if (($attributes['is_default'] ?? false) === true) {
                Currency::query()->update(['is_default' => false]);
                $attributes['is_active'] = true;
            }

            /** @var Currency $currency */
            $currency = Currency::query()->create([
                ...$attributes,
                'code' => strtoupper((string) $attributes['code']),
            ]);

            $this->createAuditLog->handle('currency.created', $currency, null, $currency->toArray());

            return $currency;
        });
    }
}
