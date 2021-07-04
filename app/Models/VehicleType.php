<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class VehicleType extends Model
{
    //
    protected $table = 'car_type';
    protected $appends = ['id'];
}
