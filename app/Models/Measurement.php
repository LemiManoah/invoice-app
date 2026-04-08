<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Measurement extends Model
{
    protected $fillable = [
        'customer_id',
        'neck',
        'chest',
        'waist',
        'hips',
        'shoulder',
        'sleeve_length',
        'jacket_length',
        'trouser_waist',
        'trouser_length',
        'inseam',
        'thigh',
        'knee',
        'cuff',
        'height',
        'weight',
        'posture_notes',
        'fitting_notes',
        'is_current',
        'measured_by',
        'measurement_date',
        // Jacket piece
        'jacket_shoulder',
        'jacket_chest',
        'jacket_stomach_waist',
        'jacket_sleeve',
        'jacket_biceps',
        'jacket_wrist',
        'jacket_lower_arm',
        'jacket_hip_line',
        // Trouser piece
        'trouser_thigh_cuff',
        'trouser_length_fit',
        'trouser_ankle_fit',
        'trouser_knee_fit',
        'trouser_fly_fit',
        'trouser_hips',
        // Waistcoat piece
        'waistcoat_chest',
        'waistcoat_waist',
        'waistcoat_length',
        // Skirt piece
        'skirt_waist',
        'skirt_hip_line',
        'skirt_full_length',
        // Shirt piece
        'shirt_chest',
        'shirt_waist',
        'shirt_shoulder',
        'shirt_full_length',
        'shirt_bottom_cut',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'measurement_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function measurer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'measured_by');
    }

    public function hasJacket(): bool
    {
        return (bool) array_filter([
            $this->jacket_length, $this->jacket_shoulder, $this->jacket_chest,
            $this->jacket_stomach_waist, $this->jacket_sleeve, $this->jacket_biceps,
            $this->jacket_wrist, $this->jacket_lower_arm, $this->jacket_hip_line,
        ]);
    }

    public function hasTrouser(): bool
    {
        return (bool) array_filter([
            $this->trouser_waist, $this->trouser_thigh_cuff, $this->trouser_length_fit,
            $this->trouser_ankle_fit, $this->trouser_knee_fit, $this->trouser_fly_fit,
            $this->trouser_hips,
        ]);
    }

    public function hasWaistcoat(): bool
    {
        return (bool) array_filter([
            $this->waistcoat_chest, $this->waistcoat_waist, $this->waistcoat_length,
        ]);
    }

    public function hasSkirt(): bool
    {
        return (bool) array_filter([
            $this->skirt_waist, $this->skirt_hip_line, $this->skirt_full_length,
        ]);
    }

    public function hasShirt(): bool
    {
        return (bool) array_filter([
            $this->shirt_chest, $this->shirt_waist, $this->shirt_shoulder,
            $this->shirt_full_length, $this->shirt_bottom_cut,
        ]);
    }

    /** @return array<string> */
    public function pieces(): array
    {
        $pieces = [];
        if ($this->hasJacket()) $pieces[] = 'Jacket';
        if ($this->hasTrouser()) $pieces[] = 'Trouser';
        if ($this->hasWaistcoat()) $pieces[] = 'Waistcoat';
        if ($this->hasSkirt()) $pieces[] = 'Skirt';
        if ($this->hasShirt()) $pieces[] = 'Shirt';
        return $pieces;
    }
}
