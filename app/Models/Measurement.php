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
}
