<?php

namespace App\Actions\Customer;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\Customer;

class UpdateCustomerAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLog,
    ) {
    }

    public function __invoke(Customer $customer, array $data): Customer
    {
        $before = $customer->toArray();
        $customer->update($data);

        ($this->createAuditLog)('customer.updated', $customer, $before, $customer->fresh()->toArray());

        return $customer;
    }
}
