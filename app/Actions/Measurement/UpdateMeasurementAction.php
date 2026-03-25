<?php

namespace App\Actions\Measurement;

use App\Models\Measurement;

class UpdateMeasurementAction
{
    public function __invoke(Measurement $measurement, array $data): Measurement
    {
        if (! empty($data['is_current']) && $data['is_current']) {
            $measurement->customer->measurements()->where('id', '!=', $measurement->id)->update(['is_current' => false]);
        }
        $measurement->update($data);

        return $measurement;
    }
}
