<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use App\User;

class InventoryTag extends Model
{
    //
    protected $appends = ['created_by', 'id'];

    public function getCreatedByAttribute()
    {
        if ($this->attributes['user_id'] == 0) {
            return 'Admin';
        } else {
            return User::find($this->attributes['user_id'])->name;
        }
    }
}
