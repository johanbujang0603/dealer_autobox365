<?php

namespace App\Models;

use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $appends = ['id'];
    protected $dates = array('date_of_sale', 'date_of_estimate_delivery', 'created_at', 'updated_at');
    public function inventory_details()
    {
        return $this->hasOne('App\Models\Inventory', '_id', 'inventory_id');
    }
    public function diffForHumansForSalesDate()
    {
        return Carbon::parse($this->date_of_sale)->diffForHumans();
    }
    public function documents()
    {
        return Document::where('parent_model', Transaction::class)->where('model_id', $this->id)->get();
    }
    public function getPriceWithCurrencyAttribute()
    {
        if (isset($this->attributes['price'])) {
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
        } else {
            return null;
        }
    }
    public function getDownPriceWithCurrencyAttribute()
    {
        if (isset($this->attributes['down_payment_price'])) {
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
            $down_payment_price = ((float) $this->attributes['down_payment_price'] * $model_currency_rate) / (float) $rate;
            return number_format($down_payment_price, 0, '.', ',') . $currency_symbol;
        } else {
            return null;
        }
    }
}
