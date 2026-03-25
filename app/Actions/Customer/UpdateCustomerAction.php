<?php

namespace App\Actions\Customer;

use App\Models\Customer;

class UpdateCustomerAction
{
    public function __invoke(Customer $customer, array $data): Customer
    {
        $customer->update($data);

        return $customer;
    }
}
