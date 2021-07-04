<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Location extends Model
{
    //
    protected $appends = ['logo_url', 'id'];



    public function getLogoUrlAttribute()
    {
        if (isset($this->attributes['logo']) && $this->attributes['logo']) {
            return asset($this->attributes['logo']);
        } else {
            return asset("global_assets/images/placeholders/placeholder.jpg");
        }
    }

    public function type_details()
    {
        return $this->hasOne('App\Models\LocationType', '_id', 'type');
    }
    public function photo_details()
    {
        return $this->hasMany('App\Models\LocationPhoto', 'location_id', '_id');
    }
    public function social_medias()
    {
        return $this->hasMany('App\Models\LocationSocial', 'location_id', '_id');
    }
    public function phone_numbers()
    {
        return $this->hasMany('App\Models\LocationPhone', 'location_id', '_id');
    }
}
