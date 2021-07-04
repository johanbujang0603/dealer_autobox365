<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class UserSetting extends Model
{
    //
    protected $appends = ['id'];

    public function getCompanyLogoSrcAttribute()
    {
        if (isset($this->attributes['company_logo_source']) && $this->attributes['company_logo_source'] == 'cloudinary') {
            $options = array();
            $options['secure'] = TRUE;
            $options['width'] = 'auto';
            // $options['height'] = 250;
            $options['crop'] = 'fill';
            $options['flags'] = 'lossy';
            $options['quality'] = 'auto:low';
            $path_parts = pathinfo($this->attributes['company_logo']);
            return $src = \Cloudder::show($this->attributes['company_logo'], $options);
        } else {
            return asset($this->attributes['company_logo']);
        }
    }
}
