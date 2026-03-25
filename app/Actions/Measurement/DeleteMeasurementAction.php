<?php

namespace App\Actions\Measurement;

use App\Models\Measurement;

class DeleteMeasurementAction
{
    public function __invoke(Measurement $measurement): void
    {
        $measurement->delete();
    }
}
