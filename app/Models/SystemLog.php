<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class SystemLog extends Model
{
    //

    protected $appends = ['id'];
    public function inventory_details()
    {
        return $this->hasOne('App\Models\Inventory', '_id', 'model_id');
    }

    public function lead_details()
    {
        return $this->hasOne('App\Models\Lead', '_id', 'model_id');
    }

    public function location_details()
    {
        return $this->hasOne('App\Models\Location', '_id', 'model_id');
    }
    
    public function customer_details()
    {
        return $this->hasOne('App\Models\Customer', '_id', 'model_id');
    }

    public function user_details()
    {
        return $this->hasOne('App\User', '_id', 'user_id');
    }
}
