<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Complaints extends Model
{
    use HasFactory;

    // Table name (optional if Laravel can infer it)
    protected $table = 'complaints';

    // Primary key
    protected $primaryKey = 'complaint_id';

    // Fillable fields
    protected $fillable = [
        'user_id',
        'user_email',
        'complaintAbout',
        'messageNote',
        'status',
    ];
}
