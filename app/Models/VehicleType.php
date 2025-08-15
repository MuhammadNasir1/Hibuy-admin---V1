<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    protected $table = 'vehicle_types';
    protected $primaryKey = 'id';

    protected $fillable = [
        'vehicle_type',
        'delivery_charge', // ✅ match your DB column
        'min_weight',
        'max_weight',
        'max_length',
        'max_width',
        'max_height',
    ];

}

