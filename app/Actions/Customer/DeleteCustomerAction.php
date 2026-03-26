<?php

declare(strict_types=1);

namespace App\Actions\Customer;

use App\Models\Customer;
use Illuminate\Validation\ValidationException;

final readonly class DeleteCustomerAction
{
    public function handle(Customer $customer): void
    {
        if (
            $customer->orders()->exists()
            || $customer->invoices()->exists()
            || $customer->measurements()->exists()
        ) {
            throw ValidationException::withMessages([
                'customer' => 'Customers with measurements, orders, or invoices cannot be deleted.',
            ]);
        }

        $customer->delete();
    }
}
