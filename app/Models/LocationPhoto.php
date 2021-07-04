<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class LocationPhoto extends Model
{
    //
    protected $appends = ['image_src', 'id'];


    public function getImageSrcAttribute()
    {
        return asset($this->attributes['upload_path']);
    }
}
