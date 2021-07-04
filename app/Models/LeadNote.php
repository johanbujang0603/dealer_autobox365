<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class LeadNote extends Model
{
    //
    protected $appends = ['id'];

    public function user_details()
    {
        return $this->hasOne('App\User', '_id', 'user_id');
    }
}
