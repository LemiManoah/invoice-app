<?php

declare(strict_types=1);

namespace App\Actions\Currency;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class DeleteCurrencyAction
{
    public function __construct(
        private CreateAuditLogAction $createAuditLog,
    ) {}

    public function handle(Currency $currency): void
    {
        if ($currency->is_default) {
            throw ValidationException::withMessages([
                'currency' => 'The default currency cannot be deleted. Set another default currency first.',
            ]);
        }

        DB::transaction(function () use ($currency): void {
            $before = $currency->toArray();
            $currency->delete();

            $this->createAuditLog->handle('currency.deleted', $currency, $before, null);
        });
    }
}
