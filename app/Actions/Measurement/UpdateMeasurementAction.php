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
            // Detect which pieces the updated measurement will cover
            $updatedMeasurement = new Measurement(array_merge($measurement->toArray(), $data));
            $updatedPieces = $updatedMeasurement->pieces();

            $measurement->customer->measurements()
                ->where('id', '!=', $measurement->id)
                ->where('is_current', true)
                ->get()
                ->each(function (Measurement $existing) use ($updatedPieces): void {
                    if (! empty($updatedPieces) && array_intersect($existing->pieces(), $updatedPieces)) {
                        $existing->update(['is_current' => false]);
                    } elseif (empty($updatedPieces)) {
                        $existing->update(['is_current' => false]);
                    }
                });
        }

        $measurement->update($data);

        return $measurement;
    }
}
