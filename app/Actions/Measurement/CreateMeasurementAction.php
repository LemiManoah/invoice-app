<?php

declare(strict_types=1);

namespace App\Actions\Measurement;

use App\Models\Customer;
use App\Models\Measurement;
use Illuminate\Support\Facades\Auth;

final readonly class CreateMeasurementAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Customer $customer, array $data): Measurement
    {
        if (! empty($data['is_current'])) {
            $customer->measurements()->update(['is_current' => false]);
        }

        return $customer->measurements()->create([
            ...$data,
            'measured_by' => Auth::id(),
        ]);
    }
}
