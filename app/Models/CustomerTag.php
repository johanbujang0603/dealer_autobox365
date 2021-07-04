<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class CustomerTag extends Model
{
    //
    protected $table = 'customer_tags';
    protected $appends = ['id'];
}
