<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Role extends Model
{
    //

    protected $appends = ['id'];

    public function permission_details()
    {
        # code...
        return $this->hasMany('App\Models\RolePermission', 'role_id', '_id');
    }
}
