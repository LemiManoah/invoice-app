<?php

namespace Database\Seeders;

use App\Models\Measurement;
use Illuminate\Database\Seeder;

class MeasurementSeeder extends Seeder
{
    public function run(): void
    {
        $measurements = [
            // John Anderson (CUST001) - Male measurements
            [
                'customer_id' => 1,
                'neck' => 15.5,
                'chest' => 40.0,
                'waist' => 34.0,
                'hips' => 38.0,
                'shoulder' => 18.0,
                'sleeve_length' => 34.0,
                'jacket_length' => 29.5,
                'trouser_waist' => 34.0,
                'trouser_length' => 42.0,
                'inseam' => 32.0,
                'thigh' => 24.0,
                'knee' => 18.0,
                'cuff' => 9.5,
                'height' => 72.0,
                'weight' => 180.0,
                'posture_notes' => 'Standard posture',
                'fitting_notes' => 'Athletic build, prefers slim fit',
                'is_current' => true,
                'measured_by' => 1,
                'measurement_date' => now()->subDays(30),
            ],
            // Sarah Mitchell (CUST002) - Female measurements
            [
                'customer_id' => 2,
                'neck' => 13.5,
                'chest' => 36.0,
                'waist' => 28.0,
                'hips' => 39.0,
                'shoulder' => 15.5,
                'sleeve_length' => 31.0,
                'jacket_length' => 26.5,
                'trouser_waist' => 28.0,
                'trouser_length' => 38.0,
                'inseam' => 30.0,
                'thigh' => 22.0,
                'knee' => 16.0,
                'cuff' => 8.5,
                'height' => 65.0,
                'weight' => 135.0,
                'posture_notes' => 'Slight forward lean',
                'fitting_notes' => 'Pear shape, needs adjustments for hip area',
                'is_current' => true,
                'measured_by' => 1,
                'measurement_date' => now()->subDays(15),
            ],
            // Michael Chen (CUST003) - Male measurements
            [
                'customer_id' => 3,
                'neck' => 16.0,
                'chest' => 42.0,
                'waist' => 36.0,
                'hips' => 40.0,
                'shoulder' => 19.0,
                'sleeve_length' => 35.0,
                'jacket_length' => 30.0,
                'trouser_waist' => 36.0,
                'trouser_length' => 44.0,
                'inseam' => 33.0,
                'thigh' => 25.0,
                'knee' => 19.0,
                'cuff' => 10.0,
                'height' => 74.0,
                'weight' => 195.0,
                'posture_notes' => 'Broad shoulders',
                'fitting_notes' => 'Larger frame, needs extra room in shoulders',
                'is_current' => true,
                'measured_by' => 1,
                'measurement_date' => now()->subDays(45),
            ],
            // Emily Rodriguez (CUST004) - Female measurements
            [
                'customer_id' => 4,
                'neck' => 13.0,
                'chest' => 34.0,
                'waist' => 26.0,
                'hips' => 37.0,
                'shoulder' => 15.0,
                'sleeve_length' => 30.0,
                'jacket_length' => 25.5,
                'trouser_waist' => 26.0,
                'trouser_length' => 36.0,
                'inseam' => 29.0,
                'thigh' => 21.0,
                'knee' => 15.0,
                'cuff' => 8.0,
                'height' => 62.0,
                'weight' => 120.0,
                'posture_notes' => 'Upright posture',
                'fitting_notes' => 'Petite frame, prefers fitted styles',
                'is_current' => true,
                'measured_by' => 1,
                'measurement_date' => now()->subDays(20),
            ],
            // David Thompson (CUST005) - Male measurements
            [
                'customer_id' => 5,
                'neck' => 15.0,
                'chest' => 38.0,
                'waist' => 32.0,
                'hips' => 37.0,
                'shoulder' => 17.5,
                'sleeve_length' => 33.0,
                'jacket_length' => 28.5,
                'trouser_waist' => 32.0,
                'trouser_length' => 40.0,
                'inseam' => 31.0,
                'thigh' => 23.0,
                'knee' => 17.0,
                'cuff' => 9.0,
                'height' => 70.0,
                'weight' => 165.0,
                'posture_notes' => 'Balanced posture',
                'fitting_notes' => 'Average build, classic proportions',
                'is_current' => true,
                'measured_by' => 1,
                'measurement_date' => now()->subDays(10),
            ],
        ];

        Measurement::insert($measurements);
    }
}
