<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class ValuationData extends Model
{
    //
    protected $appends = ['id', 'price', 'price_with_currency'];
    protected $casts = [
        'mileage' => 'integer'
    ];
    public function getPriceAttribute()
    {
        $system_currency = config('app.currency');
        $currency = Currency::where('iso_code', $system_currency)->first();
        $rate = 1;
        $model_currency_rate = 1;
        if ($currency) {
            $rate = $currency->currency_rate;
        }
        if (isset($this->attributes['currency']) && $this->attributes['currency']) {
            $model_currency = Currency::find($this->attributes['currency']);
            if ($model_currency) {
                $model_currency_rate = $model_currency->currency_rate;
            }
        }
        $price = ((float) $this->attributes['price'] * $model_currency_rate) / (float) $rate;
        return $price;

        // return (float) $this->attributes['price']; //120;
    }
    public function getPriceWithCurrencyAttribute()
    {
        $system_currency = config('app.currency');
        $currency = Currency::where('iso_code', $system_currency)->first();
        $rate = 1;
        $model_currency_rate = 1;
        $currency_symbol = '$';
        if ($currency) {
            $rate = $currency->currency_rate;
            $currency_symbol = $currency->symbol;
        }
        if (isset($this->attributes['currency']) && $this->attributes['currency']) {
            $model_currency = Currency::find($this->attributes['currency']);
            if ($model_currency) {
                $model_currency_rate = $model_currency->currency_rate;
            }
        }
        $price = ((float) $this->attributes['price'] * $model_currency_rate) / (float) $rate;
        return number_format($price, 0, '.', ',') . $currency_symbol;
    }
}
