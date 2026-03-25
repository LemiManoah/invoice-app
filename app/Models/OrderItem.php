<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'garment_type',
        'description',
        'quantity',
        'unit_price',
        'style_notes',
        'fabric_details',
        'color',
        'lining_details',
        'button_details',
        'monogram_text',
        'urgent_flag',
    ];

    protected $casts = [
        'urgent_flag' => 'boolean',
        'unit_price' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
