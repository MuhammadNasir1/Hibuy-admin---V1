<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    protected $primaryKey = 'query_id';
    protected $fillable = [
        'status',
        'response',
        'query_id',
        'subject',
        'message',
        'email',
    ];
}
