<?php

namespace App\Actions\Customer;

use App\Models\Customer;

class DeleteCustomerAction
{
    public function __invoke(Customer $customer): void
    {
        $customer->delete();
    }
}
