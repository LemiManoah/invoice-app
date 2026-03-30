<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class BusinessProfile extends Model
{
    protected $fillable = [
        'name',
        'location',
        'email',
        'contacts',
        'address',
        'po_box',
        'logo_path',
        'signature_path',
    ];
}
