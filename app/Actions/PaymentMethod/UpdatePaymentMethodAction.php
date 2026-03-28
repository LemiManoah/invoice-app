<?php

declare(strict_types=1);

namespace App\Actions\PaymentMethod;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final readonly class UpdatePaymentMethodAction
{
    public function __construct(
        private CreateAuditLogAction $createAuditLog,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(PaymentMethod $paymentMethod, array $data): PaymentMethod
    {
        return DB::transaction(function () use ($paymentMethod, $data): PaymentMethod {
            $before = $paymentMethod->toArray();

            $paymentMethod->update([
                'name' => $data['name'],
                'slug' => $this->generateUniqueSlug((string) $data['name'], $paymentMethod),
                'is_active' => (bool) $data['is_active'],
                'sort_order' => (int) $data['sort_order'],
                'notes' => $data['notes'] ?? null,
            ]);

            $this->createAuditLog->handle('payment_method.updated', $paymentMethod, $before, $paymentMethod->fresh()->toArray());

            return $paymentMethod->refresh();
        });
    }

    private function generateUniqueSlug(string $name, PaymentMethod $ignore): string
    {
        $base = Str::slug($name) ?: 'payment-method';
        $slug = $base;
        $suffix = 2;

        while (
            PaymentMethod::query()
                ->whereKeyNot($ignore->getKey())
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
