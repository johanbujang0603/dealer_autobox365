<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Calendar extends Model
{
    //
    protected $table = 'calendar';
    protected $appends = ['formated_date', 'id', 'date'];

    public function getFormatedDateAttribute()
    {
        return date('c', strtotime($this->attributes['datetime']));
    }
    public function getDateAttribute()
    {
        return date('c', strtotime($this->attributes['datetime']));
        // return $this->attributes['datetime'];
    }
}
