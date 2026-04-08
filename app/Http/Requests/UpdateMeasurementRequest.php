<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeasurementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'neck' => 'nullable|numeric|min:0',
            'chest' => 'nullable|numeric|min:0',
            'waist' => 'nullable|numeric|min:0',
            'hips' => 'nullable|numeric|min:0',
            'shoulder' => 'nullable|numeric|min:0',
            'sleeve_length' => 'nullable|numeric|min:0',
            'jacket_length' => 'nullable|numeric|min:0',
            'trouser_waist' => 'nullable|numeric|min:0',
            'trouser_length' => 'nullable|numeric|min:0',
            'inseam' => 'nullable|numeric|min:0',
            'thigh' => 'nullable|numeric|min:0',
            'knee' => 'nullable|numeric|min:0',
            'cuff' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'posture_notes' => 'nullable|string',
            'fitting_notes' => 'nullable|string',
            'measurement_date' => 'required|date',
            'is_current' => 'boolean',
            // Jacket piece
            'jacket_shoulder' => 'nullable|numeric|min:0',
            'jacket_chest' => 'nullable|numeric|min:0',
            'jacket_stomach_waist' => 'nullable|numeric|min:0',
            'jacket_sleeve' => 'nullable|numeric|min:0',
            'jacket_biceps' => 'nullable|numeric|min:0',
            'jacket_wrist' => 'nullable|numeric|min:0',
            'jacket_lower_arm' => 'nullable|numeric|min:0',
            'jacket_hip_line' => 'nullable|numeric|min:0',
            // Trouser piece
            'trouser_thigh_cuff' => 'nullable|numeric|min:0',
            'trouser_length_fit' => 'nullable|numeric|min:0',
            'trouser_ankle_fit' => 'nullable|numeric|min:0',
            'trouser_knee_fit' => 'nullable|numeric|min:0',
            'trouser_fly_fit' => 'nullable|numeric|min:0',
            'trouser_hips' => 'nullable|numeric|min:0',
            // Waistcoat piece
            'waistcoat_chest' => 'nullable|numeric|min:0',
            'waistcoat_waist' => 'nullable|numeric|min:0',
            'waistcoat_length' => 'nullable|numeric|min:0',
            // Skirt piece
            'skirt_waist' => 'nullable|numeric|min:0',
            'skirt_hip_line' => 'nullable|numeric|min:0',
            'skirt_full_length' => 'nullable|numeric|min:0',
            // Shirt piece
            'shirt_chest' => 'nullable|numeric|min:0',
            'shirt_waist' => 'nullable|numeric|min:0',
            'shirt_shoulder' => 'nullable|numeric|min:0',
            'shirt_full_length' => 'nullable|numeric|min:0',
            'shirt_bottom_cut' => 'nullable|numeric|min:0',
        ];
    }
}
