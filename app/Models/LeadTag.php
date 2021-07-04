<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class LeadTag extends Model
{
    //
    protected $table = 'lead_tags';
    protected $appends = ['id'];
}
