<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiderModel extends Model
{
    protected $table = 'riders';
    protected $fillable = [
        'rider_name',
        'rider_email',
        'phone',
        'vehicle_type',
        'vehicle_number',
        'city',
    ];

    public $timestamps = true;

    // Additional methods or relationships can be defined here
}
