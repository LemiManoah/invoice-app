<?php

namespace App\Actions\Customer;

use App\Models\Customer;

class CreateCustomerAction
{
    public function __invoke(array $data): Customer
    {
        return Customer::create($data);
    }
}
