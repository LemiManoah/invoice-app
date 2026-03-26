<?php

namespace App\Actions\Customer;

use App\Actions\Audit\CreateAuditLogAction;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CreateCustomerAction
{
    public function __construct(
        private readonly CreateAuditLogAction $createAuditLog,
    ) {
    }

    public function __invoke(array $data): Customer
    {
        $customer = Customer::create([
            ...$data,
            'created_by' => Auth::id(),
        ]);

        ($this->createAuditLog)('customer.created', $customer, null, $customer->toArray());

        return $customer;
    }
}
