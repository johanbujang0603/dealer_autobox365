<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class CarMake extends Model
{
    //
    protected $table = 'car_make';
    protected $appends = ['brand_logo'];

    public function getBrandLogoAttribute()
    {
        return asset('images/car_brand_logos/' . strtolower($this->attributes['name']) . '.jpg');
    }
}
