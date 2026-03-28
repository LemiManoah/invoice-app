<?php

declare(strict_types=1);

namespace App\Actions\Currency;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;

final readonly class SetDefaultCurrencyAction
{
    public function __construct(
        private CreateAuditLogAction $createAuditLog,
    ) {}

    public function handle(Currency $currency): Currency
    {
        return DB::transaction(function () use ($currency): Currency {
            Currency::query()->update(['is_default' => false]);

            $before = $currency->toArray();
            $currency->update([
                'is_default' => true,
                'is_active' => true,
            ]);

            $this->createAuditLog->handle('currency.default_updated', $currency, $before, $currency->fresh()->toArray());

            return $currency->fresh();
        });
    }
}
