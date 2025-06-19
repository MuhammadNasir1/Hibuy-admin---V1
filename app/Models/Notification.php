<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications'; // optional if the table name matches the plural of the model

    protected $primaryKey = 'notification_id'; // optional, default is 'id'

    public $timestamps = true; // enables created_at and updated_at auto handling (default true)
}
