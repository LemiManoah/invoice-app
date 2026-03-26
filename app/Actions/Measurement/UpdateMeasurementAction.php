<?php

declare(strict_types=1);

namespace App\Actions\Measurement;

use App\Models\Measurement;

final readonly class UpdateMeasurementAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Measurement $measurement, array $data): Measurement
    {
        if (! empty($data['is_current'])) {
            $measurement->customer->measurements()
                ->where('id', '!=', $measurement->id)
                ->update(['is_current' => false]);
        }

        $measurement->update($data);

        return $measurement;
    }
}
