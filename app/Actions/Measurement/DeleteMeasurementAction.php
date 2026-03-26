<?php

declare(strict_types=1);

namespace App\Actions\Measurement;

use App\Models\Measurement;

final readonly class DeleteMeasurementAction
{
    public function handle(Measurement $measurement): void
    {
        $measurement->delete();
    }
}
