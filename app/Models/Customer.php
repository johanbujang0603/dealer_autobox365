<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Customer extends Model
{
    //
    protected $appends = ['name', 'profile_image_src', 'id'];

    public function getNameAttribute()
    {
        return $this->attributes['first_name'] . ' ' . $this->attributes['middle_name'] . ' ' . $this->attributes['last_name'];
    }
    public function getProfileImageSrcAttribute()
    {
        if (isset($this->attributes['profile_image'])) {
            return $this->attributes['profile_image'] ? asset($this->attributes['profile_image']) : asset('/global_assets/images/placeholders/placeholder.jpg');
        }
        return "https://ui-avatars.com/api/?bold=true&color=ffffff&background=139ff0&name=" . $this->attributes['first_name'] . '+' . $this->attributes['last_name'];
    }

    public function phone_number_details()
    {
        # code...
        return $this->hasMany('App\Models\CustomerPhoneNumber', 'customer_id', '_id');
    }
    public function email_details()
    {
        # code...
        return $this->hasMany('App\Models\CustomerEmail', 'customer_id', '_id');
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
}
