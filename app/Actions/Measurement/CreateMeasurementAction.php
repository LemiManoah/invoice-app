<?php

namespace App\Actions\Measurement;

use App\Models\Measurement;
use Illuminate\Support\Facades\Auth;

class CreateMeasurementAction
{
    public function __invoke($customer, array $data): Measurement
    {
        if (! empty($data['is_current']) && $data['is_current']) {
            $customer->measurements()->update(['is_current' => false]);
        }
        $data['measured_by'] = Auth::id();

        return $customer->measurements()->create($data);
    }
}
