<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class CustomerStatus extends Model
{
    //
    protected $table = 'customer_status';
    protected $appends = ['id'];
}
