<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use DB;

class CarSpecificationValue extends Model
{
    //
    protected $table = 'car_specification_value';

    public function specific_details()
    {

        return $this->hasOne('App\Models\CarSpecification', 'id_car_specification', 'id_car_specification');
    }
}
