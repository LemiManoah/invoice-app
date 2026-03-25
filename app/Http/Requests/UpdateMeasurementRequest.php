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
        ];
    }
}
