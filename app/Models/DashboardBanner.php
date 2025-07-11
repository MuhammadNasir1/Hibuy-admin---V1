<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashboardBanner extends Model
{
    protected $table = 'dashboardbanners';
    protected $primaryKey = 'banner_id';
    public $timestamps = true;
}
