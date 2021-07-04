<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\ValuationData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValuationController extends Controller
{
    //
    public function search(Request $request)
    {
        $make = $request->make;
        $model = $request->model;
        $country = $request->country;
        $city = $request->city;
        $color = $request->color;
        $year = $request->year;
        $fuel_type = $request->fuel_type;
        $mileage_min = $request->mileage_min;
        $mileage_max = $request->mileage_max;
        $transmission = $request->transmission;
        $valuation_data_query = ValuationData::whereNotNull('_id');
        $page_lengh = 10;

        Log::info($make);

        if ($make != 'null') {

            $valuation_data_query->where('make', $make);
            if ($model != 'null') {
                $valuation_data_query->where('model', $model);
            }
        }
        if ($country != 'null') {
            $valuation_data_query->where('country', $country);
            if ($city != 'null') {
                $valuation_data_query->where('city', $city);
            }
        }
        if ($color != 'null') {
            $valuation_data_query->where('color', $color);
        }
        if ($year != 'null') {
            $valuation_data_query->where('year', $year);
        }
        if ($fuel_type != 'null') {
            $valuation_data_query->where('fuel_type', $fuel_type);
        }
        if ($transmission != 'null') {
            $valuation_data_query->where('transmission', $transmission);
        }
        if ($mileage_max != 'null') {

            Log::info($mileage_max);

            $valuation_data_query->where('mileage', '<=', (float) $mileage_max);
        }
        if ($mileage_min != 'null') {
            Log::info($mileage_min);
            $valuation_data_query->where('mileage', '>=', (float) $mileage_min);
        }
        $temp_query = $valuation_data_query;
        $search_result =  $valuation_data_query->paginate($page_lengh)->toArray();
        $search_result['avg_price'] = $temp_query->get()->avg('price');
        $search_result['median_price'] = $temp_query->get()->median('price');
        $search_result['minimum_price'] = $temp_query->get()->min('price');
        $search_result['maximum_price'] = $temp_query->get()->max('price');
        $search_result['currency_symbol'] = Currency::where('iso_code', config('app.currency'))->first() ? Currency::where('iso_code', config('app.currency'))->first()->symbol : '$';
        return $search_result;
    }

    public function makes()
    {
        return ValuationData::groupBy('make')->pluck('make');
    }

    public function models($make)
    {
        return ValuationData::where('make', $make)->groupBy('model')->pluck('model');
    }
    public function cities($country)
    {
        return ValuationData::where('country', $country)->groupBy('city')->pluck('city');
    }
    public function countries()
    {
        return ValuationData::groupBy('country')->pluck('country');
    }
    public function colors()
    {
        return ValuationData::groupBy('color')->pluck('color');
    }
    public function years()
    {
        return ValuationData::groupBy('year')->pluck('year');
    }
    public function fule_types()
    {
        return ValuationData::groupBy('fuel_type')->pluck('fuel_type');
    }
    public function body_types()
    {
        return ValuationData::groupBy('body_type')->pluck('body_type');
    }
    public function transmissions()
    {
        return ValuationData::groupBy('transmission')->pluck('transmission');
    }
    public function filter_params()
    {
        $makes = $this->makes();
        $models = array();
        return array(
            'makes' => $makes,
            'models' => $models,
            'countries' => $this->countries(),
            'cities' => array(),
            'colors' => $this->colors(),
            'years' => $this->years(),
            'fuel_types' => $this->fule_types(),
            'body_types' => $this->body_types(),
            'transmissions' => $this->transmissions(),
            'mileage_min' => (float) ValuationData::min('mileage'),
            'mileage_max' =>  ValuationData::max('mileage'),
        );
    }
}
