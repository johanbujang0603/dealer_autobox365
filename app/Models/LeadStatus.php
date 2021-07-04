<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class LeadStatus extends Model
{
    //
    protected $table = 'lead_status';
    protected $appends = ['id'];
}
