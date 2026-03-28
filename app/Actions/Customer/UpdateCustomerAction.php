<?php

declare(strict_types=1);

namespace App\Actions\Customer;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\Customer;

final readonly class UpdateCustomerAction
{
    public function __construct(
        private CreateAuditLogAction $createAuditLog,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Customer $customer, array $data): Customer
    {
        $before = $customer->toArray();
        $customer->update($data);

        $this->createAuditLog->handle('customer.updated', $customer, $before, $customer->fresh()->toArray());

        return $customer;
    }
}
