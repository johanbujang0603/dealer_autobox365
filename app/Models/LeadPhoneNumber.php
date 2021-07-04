<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Propaganistas\LaravelPhone\PhoneNumber;

class LeadPhoneNumber extends Model
{
    //
    protected $appends = ['id'];

    public function getFormattedNumberAttribute()
    {
        if ($this->attributes['country_code'] == '')
            return '';
        $localformat_number = PhoneNumber::make($this->attributes['local_format']);
        return $localformat_number;
    }
}
