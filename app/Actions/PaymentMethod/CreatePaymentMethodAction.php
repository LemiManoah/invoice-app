<?php

declare(strict_types=1);

namespace App\Actions\PaymentMethod;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final readonly class CreatePaymentMethodAction
{
    public function __construct(
        private CreateAuditLogAction $createAuditLog,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): PaymentMethod
    {
        return DB::transaction(function () use ($data): PaymentMethod {
            $paymentMethod = PaymentMethod::query()->create([
                'name' => $data['name'],
                'slug' => $this->generateUniqueSlug((string) $data['name']),
                'is_active' => (bool) $data['is_active'],
                'sort_order' => (int) $data['sort_order'],
                'notes' => $data['notes'] ?? null,
            ]);

            $this->createAuditLog->handle('payment_method.created', $paymentMethod, null, $paymentMethod->toArray());

            return $paymentMethod;
        });
    }

    private function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'payment-method';
        $slug = $base;
        $suffix = 2;

        while (PaymentMethod::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
