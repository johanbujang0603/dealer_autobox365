<?php

namespace App\Jobs;

use App\Events\ValuationDataImportEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use PragmaRX\Countries\Package\Countries;
use romanzipp\QueueMonitor\Traits\QueueMonitor; // <---
use Excel;
use Illuminate\Support\Facades\Log;
use App\Imports\InventoryImport;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Currency;
use App\Models\Inventory;
use App\Models\InventoryPhoto;
use App\Models\Transmission;
use App\Models\ValuationData;
use App\User;

class ImportValuationData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, QueueMonitor;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $config = array();
    protected $pusher;
    public function __construct(array $config)
    {
        //
        Log::debug('constructor job');
        $this->config = $config;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        Log::info($this->config);
        if ($this->attempts() < 5) {

            $rows = Excel::toArray(new InventoryImport,   public_path($this->config['file_path']));
            if (isset($rows[0])) {
                $count = 0;
                foreach ($rows[0] as $key => $row) {
                    if ($key == 0 && $this->config['exclude_first_row_option']) {
                        continue;
                    }

                    $progress = (++$count / ($this->config['exclude_first_row_option'] ? count($rows[0]) - 1 : count($rows[0]))) * 100;
                    $progress = round($progress, 2);
                    $city_column = trim($row[$this->config['city_column']]);
                    $name_column = trim($row[$this->config['name_column']]);
                    $color_column = trim($row[$this->config['color_column']]);
                    $country_column = trim($row[$this->config['country_column']]);
                    $currency_column = trim($row[$this->config['currency_column']]);
                    $fuel_column = trim($row[$this->config['fuel_column']]);
                    $make_column = trim($row[$this->config['make_column']]);
                    $mileage_column = trim($row[$this->config['mileage_column']]);
                    $model_column = trim($row[$this->config['model_column']]);
                    $photo_column = trim($row[$this->config['photo_column']]);
                    $price_column = trim($row[$this->config['price_column']]);
                    $transmission_column = trim($row[$this->config['transmission_column']]);
                    $unique_column = trim($row[$this->config['unique_column']]);
                    $year_column = trim($row[$this->config['year_column']]);
                    if (ValuationData::where('unique_field', $unique_column)->count()) {
                        $valuation_data =  ValuationData::where('unique_field', $unique_column)->first();
                    } else {
                        $valuation_data = new ValuationData();
                        $valuation_data->unique_field = $unique_column;
                    }
                    $valuation_data->make = $make_column;
                    $valuation_data->model = $model_column;
                    $valuation_data->year = $year_column;
                    $valuation_data->city = $city_column;
                    $valuation_data->name = $name_column;
                    $valuation_data->color = $color_column;
                    $country_code = Countries::where('name.common', $country_column)->first();
                    $country_code = isset($country_code->cca2) ? $country_code->cca2 : $country_column;
                    $valuation_data->country = $country_code;
                    $valuation_data->price = (float)$price_column;
                    $transmission = Transmission::where('transmission', $transmission_column)->first();
                    if ($transmission) {
                        $valuation_data->transmission = $transmission->transmission;
                    }
                    $valuation_data->mileage = $mileage_column ? (float) $mileage_column : 0;
                    $valuation_data->fuel_type = $fuel_column;
                    $valuation_data->year = $year_column;
                    $valuation_data->unique_field = $unique_column;
                    if ($currency_column) {
                        $currency_symbol = getCurrencySymbol($currency_column);

                        $currency_model = Currency::where('iso_code', $currency_symbol)->first();
                        if (!$currency_model) {

                            $currency_model = new Currency();
                            $currency_model->currency = $currency_symbol;
                            $currency_model->iso_code = $currency_symbol;
                            $currency_model->symbol = $currency_symbol;
                            $rate_api_endpoint = "http://data.fixer.io/api/latest?access_key=" . env('FIXER_API_KEY') . "&symbols=$currency_symbol,USD";
                            $rate_result = file_get_contents($rate_api_endpoint);

                            Log::info($rate_api_endpoint);
                            Log::info($rate_result);

                            $rate = json_decode($rate_result, true);
                            $currency_rate = $rate['rates']['USD'] / $rate['rates'][$currency_symbol];
                            $currency_model->currency_rate = $currency_rate;
                            $currency_model->save();
                        }
                    } else {
                        $currency_model = Currency::where('iso_code', config('app.currency'))->first();
                    }

                    $valuation_data->currency = $currency_model->id;
                    $valuation_data->photo = $photo_column;
                    $valuation_data->save();
                    event(new ValuationDataImportEvent(
                        array('progress' => $progress, 'log' => 'Imported ' . $name_column)
                    ));
                }
            }
        }
    }
}
