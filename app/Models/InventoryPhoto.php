<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Cloudder;
use App\User;

class InventoryPhoto extends Model
{
    //
    protected $appends = ['image_src', 'land_image_src', 'id'];


    public function getImageSrcAttribute()
    {
        if ($this->attributes['source'] == 'local') {
            return asset($this->attributes['upload_path']);
        } else if ($this->attributes['source'] == 'cloudinary') {
            $user_id = $this->attributes['user_id'];
            $user = User::find($user_id);
            $settings = $user->settings;
            $options = array();
            $options['width'] = '480';
            $options['height'] = '360';
            $options['crop'] = 'fill';
            $options['flags'] = 'lossy';
            $options['quality'] = 'auto:low';
            if (isset($settings->company_logo)) {
                if ($settings->company_logo_source == 'cloudinary') {
                    $watermark_position = isset($settings->watermark_place) ? $settings->watermark_place : 'center';
                    $options['transformation'] = array(
                        'overlay' => $settings->company_logo,
                        "width" => 200,
                        // "height" => 0.3,
                        // 'width' => 100,
                        "color" => "white",
                        "flags" => "relative",
                        "crop" => "scale",
                        "gravity" => $watermark_position,
                        'x' => 50, 'y' => 100,
                        "opacity" => isset($settings->watermark_transparence) ? $settings->watermark_transparence : 50
                    );
                }
            }

            return $image = \Cloudder::show($this->attributes['upload_path'], $options);
        } else {
            return $this->attributes['upload_path'];
        }
    }
    public function getLandImageSrcAttribute()
    {
        if ($this->attributes['source'] == 'local') {
            return asset($this->attributes['upload_path']);
        } else if ($this->attributes['source'] == 'cloudinary') {
            $user_id = $this->attributes['user_id'];
            $user = User::find($user_id);
            $settings = $user->settings;
            $options = array();
            $options['width'] = '1920';
            $options['height'] = '1080';
            $options['crop'] = 'fill';
            $options['flags'] = 'lossy';
            $options['quality'] = 'auto:low';
            if (isset($settings->company_logo)) {
                if ($settings->company_logo_source == 'cloudinary') {
                    $watermark_position = isset($settings->watermark_place) ? $settings->watermark_place : 'center';
                    $options['transformation'] = array(
                        'overlay' => $settings->company_logo,
                        "width" => 200,
                        // "height" => 0.3,
                        // 'width' => 100,
                        "color" => "white",
                        "flags" => "relative",
                        "crop" => "scale",
                        "gravity" => $watermark_position,
                        'x' => $watermark_position == 'center' ? 0 : 50, 'y' => $watermark_position == 'center' ? 0 : 100,
                        "opacity" => isset($settings->watermark_transparence) ? $settings->watermark_transparence : 50
                    );
                }
            }

            return $image = \Cloudder::show($this->attributes['upload_path'], $options);
        } else {
            return $this->attributes['upload_path'];
        }
    }
}
