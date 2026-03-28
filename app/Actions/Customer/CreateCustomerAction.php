<?php

declare(strict_types=1);

namespace App\Actions\Customer;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

final readonly class CreateCustomerAction
{
    public function __construct(
        private CreateAuditLogAction $createAuditLog,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): Customer
    {
        $customer = Customer::create([
            ...$data,
            'created_by' => Auth::id(),
        ]);

        $this->createAuditLog->handle('customer.created', $customer, null, $customer->toArray());

        return $customer;
    }
}
