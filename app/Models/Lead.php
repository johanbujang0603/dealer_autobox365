<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lead extends Model
{
    //

    protected $appends = ['name', 'profile_image_src', 'id', 'tags_array'];

    public function getTagsArrayAttribute()
    {
        if (isset($this->attributes['tags']) && $this->attributes['tags']) {
            $tags = explode(",", $this->attributes['tags']);
            $tag_array = array();
            foreach ($tags as $tag) {
                if (LeadTag::find($tag)) {
                    $tag_array[] = LeadTag::find($tag);
                }
            }
            return $tag_array;
        } else {
            return array();
        }
    }
    public function getNameAttribute()
    {
        $array = [];
        if (isset($this->attributes['first_name']) && $this->attributes['first_name']) {
            $array[] = $this->attributes['first_name'];
        }
        if (isset($this->attributes['middle_name']) && $this->attributes['middle_name']) {
            $array[] = $this->attributes['middle_name'];
        }
        if (isset($this->attributes['last_name']) && $this->attributes['last_name']) {
            $array[] = $this->attributes['last_name'];
        }
        return implode(' ', $array);
    }
    public function getProfileImageSrcAttribute()
    {
        if(isset($this->attributes['profile_image_source'])){
            if($this->attributes['profile_image_source'] == 'facebook'){
                return $this->attributes['profile_image'];
            }
        }
        if (isset($this->attributes['profile_image'])) {
            return $this->attributes['profile_image'] ? asset($this->attributes['profile_image']) : asset('/global_assets/images/placeholders/placeholder.jpg');
        }
        return "https://ui-avatars.com/api/?bold=true&color=ffffff&background=139ff0&name=" . $this->attributes['first_name'] . '+' . $this->attributes['last_name'];
    }

    public function phone_number_details()
    {
        # code...
        return $this->hasMany('App\Models\LeadPhoneNumber', 'lead_id', '_id');
    }
    public function email_details()
    {
        # code...
        return $this->hasMany('App\Models\LeadEmail', 'lead_id', '_id');
    }

    public function currency_details()
    {
        # code...
        return $this->hasOne('App\Models\Currency', '_id', 'looking_to_price_currency');
    }
    public function avgLookingPrice()
    {
        $avgPrice = LeadInterest::where('lead_id', $this->id)
            ->leftJoin('currencies', 'lead_interests.price_currency', 'currencies.id')
            ->avg(DB::raw('(lead_interests.price_from*currencies.currency_rate + lead_interests.price_to*currencies.currency_rate)/2'));
        return number_format($avgPrice, 0, '.', ',');
    }
    public function minLookingPrice()
    {
        $minPrice = LeadInterest::where('lead_id', $this->id)
            ->leftJoin('currencies', 'lead_interests.price_currency', 'currencies.id')
            ->min(DB::raw('(lead_interests.price_from*currencies.currency_rate)'));
        return number_format($minPrice, 0, '.', ',');
    }
    public function maxLookingPrice()
    {
        $maxPrice = LeadInterest::where('lead_id', $this->id)
            ->leftJoin('currencies', 'lead_interests.price_currency', 'currencies.id')
            ->max(DB::raw('(lead_interests.price_to*currencies.currency_rate)'));
        return number_format($maxPrice, 0, '.', ',');
    }

    public function isHot()
    {
        $interests = LeadInterest::where('lead_id', $this->id)->get();
        $isHot = false;
        foreach ($interests as $interest) {
            if ($interest->looking_to && ($interest->price_from || $interest->price_to)) {
                $isHot = true;
            }
        }
        return $isHot;
    }

    public function convert()
    { }
}
