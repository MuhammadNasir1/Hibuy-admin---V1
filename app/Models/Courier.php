<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;

    protected $table = 'couriers'; // optional if the table name matches the plural of the model

    protected $primaryKey = 'courier_id'; // optional, default is 'id'

    public $timestamps = true; // enables created_at and updated_at auto handling (default true)

    protected $fillable = [
        'courier_name',
        'courier_tracking_url',
        'courier_contact_number',
    ];
}
