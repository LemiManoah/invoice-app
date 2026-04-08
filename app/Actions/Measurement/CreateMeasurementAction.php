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
            // Detect which pieces the new measurement covers
            $newMeasurement = new Measurement($data);
            $newPieces = $newMeasurement->pieces();

            $customer->measurements()->where('is_current', true)->get()
                ->each(function (Measurement $existing) use ($newPieces): void {
                    // Only deactivate if it shares at least one piece with the new measurement
                    if (! empty($newPieces) && array_intersect($existing->pieces(), $newPieces)) {
                        $existing->update(['is_current' => false]);
                    } elseif (empty($newPieces)) {
                        // No specific pieces detected — fall back to deactivating all
                        $existing->update(['is_current' => false]);
                    }
                });
        }

        return $customer->measurements()->create([
            ...$data,
            'measured_by' => Auth::id(),
        ]);
    }
}
