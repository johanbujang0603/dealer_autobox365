<?php

namespace App\Models;

use App\User;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Support\Facades\DB;
// use Pimlie\DataTables\Traits\MongodbDataTableTrait;

class Inventory extends Model
{
    //
    // use MongodbDataTableTrait;
    protected $appends = ['brand_name', 'state', 'inventory_name', 'brand_logo', 'id', 'price', 'price_original', 'price_with_currency', 'featured_photo', 'transmission_name', 'leads_count'];

    public function getLeadsCountAttribute()
    {
        $inventory_id = $this->attributes['_id'];
        $leadIds = LeadInterest::where('inventory_id', $inventory_id . '')->pluck('lead_id');;
        return Lead::whereIn('_id', $leadIds)->count();
    }
    public function getStateAttribute()
    {
        if (isset($this->attributes['is_draft']) && isset($this->attributes['is_deleted']) && $this->attributes['is_deleted'] == 0 && $this->attributes['is_draft'] == 0) {
            return 'Published';
        } else if (isset($this->attributes['is_deleted']) && $this->attributes['is_deleted'] == 1) {
            return 'Deleted';
        } else if (isset($this->attributes['is_draft']) && $this->attributes['is_draft'] == 1) {
            return 'Draft';
        }
    }
    public function getPriceOriginalAttribute()
    {
        if (isset($this->attributes['price'])) {
            return $this->attributes['price'];
        } else {
            return 0;
        }
    }
    public function getFeaturedPhotoAttribute()
    {
        if ($this->photo_details->count()) {
            return $this->photo_details->first()->image_src;
        } else {
            return null;
        }
    }
    public function getTransmissionNameAttribute()
    {
        if ($this->transmission_details() && isset($this->transmission_details->transmission)) {
            return $this->transmission_details->transmission;
        } else {
            return null;
        }
    }
    public function getPriceAttribute()
    {
        if (isset($this->attributes['price'])) {
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
        }
        return null;

        // return (float) $this->attributes['price']; //120;
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

    public function vehicle_details()
    {
        return $this->hasOne('App\Models\VehicleType', 'id_car_type', 'vehicle_type');
    }
    public function location_details()
    {
        return $this->hasOne('App\Models\Location', '_id', 'location');
    }
    public function make_details()
    {
        return $this->hasOne('App\Models\CarMake', 'id_car_make', 'make');
    }
    public function photo_details()
    {
        return $this->hasMany('App\Models\InventoryPhoto', 'inventory_id', '_id');
    }
    public function model_details()
    {
        return $this->hasOne('App\Models\CarModel', 'id_car_model', 'model');
    }
    public function generation_details()
    {
        return $this->hasOne('App\Models\CarGeneration', 'id_car_generation', 'generation');
    }
    public function serie_details()
    {
        return $this->hasOne('App\Models\CarSeries', 'id_car_serie', 'serie');
    }
    public function trim_details()
    {
        return $this->hasOne('App\Models\CarTrim', 'id_car_trim', 'trim');
    }
    public function equipment_details()
    {
        return $this->hasOne('App\Models\CarEquipment', 'id_car_equipment', 'equipment');
    }
    public function currency_details()
    {
        return $this->hasOne('App\Models\Currency', '_id', 'currency');
    }
    public function currency_of_purchase_details()
    {
        return $this->hasOne('App\Models\Currency', '_id', 'currency_of_purchase');
    }
    public function transmission_details()
    {
        return $this->hasOne('App\Models\Transmission', '_id', 'transmission');
    }
    public function status_details()
    {
        return $this->hasOne('App\Models\InventoryStatus', '_id', 'status');
    }

    public function user_details()
    {
        return $this->hasOne('App\User', '_id', 'user_id');
    }

    public function getBrandNameAttribute()
    {
        return "Hello";
    }
    public function getBrandLogoAttribute()
    {
        if ($this->make_details() && isset($this->make_details->name)) {
            return  asset('images/car_brand_logos/' . strtolower($this->make_details->name) . '.jpg');
        } else {
            return asset('global_assets/images/placeholders/placeholder.jpg');
        }
    }
    public function getInventoryNameAttribute()
    {
        $inventory_name = '';
        $make_name = '';
        $model_name = '';
        $year = '';
        if ($this->make_details() && isset($this->make_details->name)) {
            $make_name .= $this->make_details->name;
        }
        if ($this->model_details() && isset($this->model_details->name)) {
            $model_name .= $this->model_details->name;
        }
        if (isset($this->attributes['year'])) {
            $year = $this->attributes['year'];
        }
        return $make_name . ' ' . $model_name . ' ' . $year;
    }
    public function getVersionAttribute()
    {
        $generation = isset($this->generation_details->name) ? $this->generation_details->name : "";
        $serie = isset($this->serie_details->name) ? $this->serie_details->name : "";
        $trim = isset($this->trim_details->name) ? $this->trim_details->name : "";
        $equipment = isset($this->equipment_details->name) ? $this->equipment_details->name : "";
        if ($generation && $serie && $trim && $equipment) {
            $version = "$generation  -  $serie  -  $trim  -  $equipment";
        } else {
            $version = "";
        }
        return $version;
    }
    public function leadsCount()
    {
        $inventory_id = $this->id;
        return LeadInterest::where('item_option', 'inventory')->where('inventory_id', $inventory_id)->groupBy('lead_id')->count();
    }
    public function leadsCountOfWeek()
    {
        $inventory_id = $this->id;
        $end_date = date('Y-m-d H:i:s');
        $start_date =  date('Y-m-d H:i:s', strtotime('-1 week', strtotime($end_date)));
        return LeadInterest::where('item_option', 'inventory')
            ->where('inventory_id', $inventory_id)
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->groupBy('lead_id')->count();
    }
    public function leadsCountOfMonth()
    {
        $inventory_id = $this->id;
        $end_date = date('Y-m-d H:i:s');
        $start_date =  date('Y-m-d H:i:s', strtotime('-1 month', strtotime($end_date)));
        return LeadInterest::where('item_option', 'inventory')
            ->where('inventory_id', $inventory_id)
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->groupBy('lead_id')->count();
    }
    public function leads()
    {
        $inventory_id = $this->id;
        $lead_ids = LeadInterest::where('item_option', 'inventory')->where('inventory_id', $inventory_id)->groupBy('lead_id')->pluck('lead_id')->toArray();
        return Lead::whereIn('_id', $lead_ids)->get();
    }

    public function totalInventoriesLeads()
    {
        return LeadInterest::where('item_option', 'inventory')->groupBy('lead_id')->count();
    }

    public function totalInventoriesLeadsOfWeek()
    {
        $inventory_id = $this->id;
        $end_date = date('Y-m-d H:i:s');
        $start_date =  date('Y-m-d H:i:s', strtotime('-1 week', strtotime($end_date)));
        return LeadInterest::where('item_option', 'inventory')
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)->groupBy('lead_id')->count();
    }
    public function totalInventoriesLeadsOfMonth()
    {
        $end_date = date('Y-m-d H:i:s');
        $start_date =  date('Y-m-d H:i:s', strtotime('-1 month', strtotime($end_date)));
        return LeadInterest::where('item_option', 'inventory')
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)->groupBy('lead_id')->count();
    }
    public function getIncreasementPercentage($option)
    {
        if ($option == 'week') {
            $leads_of_this_inventory = $this->leadsCountOfWeek();
            $leads_of_whole_inventories = $this->totalInventoriesLeadsOfWeek();
        } else if ($option == 'month') {
            $leads_of_this_inventory = $this->leadsCountOfMonth();
            $leads_of_whole_inventories = $this->totalInventoriesLeadsOfMonth();
        } else {
            $leads_of_this_inventory = $this->leadsCount();
            $leads_of_whole_inventories = $this->totalInventoriesLeads();
        }

        $leads_per_inventory = self::where('is_deleted', 0)->count() ? $leads_of_whole_inventories / (self::where('is_deleted', 0)->where('is_draft', 0)->count()) : 0;
        return $leads_per_inventory ? (($leads_of_this_inventory - $leads_per_inventory) / $leads_per_inventory) * 100 : 'N/A';
    }

    public function getChannels()
    {
        $inventory_id = $this->id;

        return LeadInterest::where('item_option', 'inventory')->where('inventory_id', $inventory_id)->groupBy('channel')->pluck('channel');;
    }

    public function totalCounts()
    {
        return self::where('is_draft', 0)->where('is_deleted', 0)->count();
    }
    public function draftCounts()
    {
        return self::where('is_draft', 1)->where('is_deleted', 0)->count();
    }

    public function avgPrice($currency_id)
    {
        $currency_details = !$currency_id ? Currency::first() : Currency::find($currency_id);
        $rate = $currency_details->currency_rate;

        $avgPriceWithUSD = self::where('is_draft', 0)->where('is_deleted', 0)->leftJoin('currencies', 'inventories.currency', 'currencies.id')->avg(DB::raw('inventories.price*currencies.currency_rate'));
        $avgPriceWithCurrency = $rate != 0 ? $avgPriceWithUSD / $rate : null;
        return $avgPriceWithCurrency ? number_format($avgPriceWithCurrency, 0, '.', ',') . $currency_details->symbol : "N/A";
    }
    public function medianPrice($currency_id)
    {
        $currency_details = !$currency_id ? Currency::first() : Currency::find($currency_id);
        $rate = $currency_details->currency_rate;

        $medianPriceWithUSD = self::where('is_draft', 0)
            ->where('is_deleted', 0)->leftJoin('currencies', 'inventories.currency', 'currencies.id')
            // ->select(DB::raw('inventories.price*currencies.currency_rate as inventory_price'))
            ->pluck('inventory_price')->median();
        $medianPriceWithCurrency = $rate != 0 ? $medianPriceWithUSD / $rate : null;
        return $medianPriceWithCurrency ? number_format($medianPriceWithCurrency, 0, '.', ',') . $currency_details->symbol : "N/A";
    }
    public function minimumPrice($currency_id)
    {
        $currency_details = !$currency_id ? Currency::first() : Currency::find($currency_id);
        $rate = $currency_details->currency_rate;

        $minPriceWithUSD = self::where('is_draft', 0)->where('is_deleted', 0)->leftJoin('currencies', 'inventories.currency', 'currencies.id')->min(DB::raw('inventories.price*currencies.currency_rate'));
        $minPriceWithCurrency = $rate != 0 ? $minPriceWithUSD / $rate : null;
        return $minPriceWithCurrency ? number_format($minPriceWithCurrency, 0, '.', ',') . $currency_details->symbol : "N/A";
    }
    public function maximumPrice($currency_id)
    {
        $currency_details = !$currency_id ? Currency::first() : Currency::find($currency_id);
        $rate = $currency_details->currency_rate;

        $maxPriceWithUSD = self::where('is_draft', 0)->where('is_deleted', 0)->leftJoin('currencies', 'inventories.currency', 'currencies.id')->max(DB::raw('inventories.price*currencies.currency_rate'));
        $maxPriceWithCurrency = $rate != 0 ? $maxPriceWithUSD / $rate : null;
        return $maxPriceWithCurrency ? number_format($maxPriceWithCurrency, 0, '.', ',') . $currency_details->symbol : "N/A";
    }
    public function other_details()
    {
        return $this->hasMany('App\Models\CarSpecificationValue', 'id_car_trim', 'trim');
    }

    public function log_details()
    {
        $logs = SystemLog::where('model', Inventory::class)
            ->where('model_id', $this->id)
            ->orderBy('created_at', 'desc')->get();
        return $logs;
    }

    public function getCompanyLogoAttribute()
    {
        $user_id = $this->attributes['user_id'];
        $user = User::find($user_id);
        if ($user->settings) {
            if ($user->settings->company_logo_source) {
                return $user->settings->company_logo_src;
            }
        } else { }
    }
}
