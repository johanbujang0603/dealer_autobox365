<?php

namespace App\Jobs;

use App\Events\InventoryImportEvent;
use Exception;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Excel;
use Illuminate\Support\Facades\Log;
use App\Imports\InventoryImport;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\Currency;
use App\Models\Inventory;
use App\Models\InventoryPhoto;
use App\Models\Transmission;
use App\User;
use PragmaRX\Countries\Package\Countries;
use romanzipp\QueueMonitor\Traits\QueueMonitor; // <---


class ImportInventories implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, QueueMonitor;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $config = array();
    public $user = null;
    protected $pusher;
    public function __construct(array $config, User $user)
    {
        //
        Log::debug('constructor job');
        $this->config = $config;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        Log::debug('Job Running');

        if ($this->attempts() < 5) {

            $rows = Excel::toArray(new InventoryImport,   public_path($this->config['file_path']));
            if (isset($rows[0])) {
                $count = 0;
                foreach ($rows[0] as $key => $row) {
                    if ($key == 0 && $this->config['exclude_first_row_option']) {
                        continue;
                    }


                    Log::info($row[$this->config['city_column']]);


                    $progress = (++$count / ($this->config['exclude_first_row_option'] ? count($rows[0]) - 1 : count($rows[0]))) * 100;
                    $progress = round($progress, 2);
                    $city_column = $row[$this->config['city_column']];
                    $color_column = $row[$this->config['color_column']];
                    $country_column = isset($this->config['country_column']) ? $row[$this->config['country_column']] : null;
                    $currency_column = $row[$this->config['currency_column']];
                    $fuel_column = $row[$this->config['fuel_column']];
                    $make_column = $row[$this->config['make_column']];
                    $mileage_column = $row[$this->config['mileage_column']];
                    $model_column = $row[$this->config['model_column']];
                    $photo_column = $row[$this->config['photo_column']];
                    $price_column = (float) $row[$this->config['price_column']];
                    $transmission_column = $row[$this->config['transmission_column']];
                    $unique_column = $row[$this->config['unique_column']];
                    $year_column = $row[$this->config['year_column']];

                    if (Inventory::where('unique_field', $unique_column)->first()) {
                        $inventory = Inventory::where('unique_field', $unique_column)->first();
                    } else {
                        $inventory = new Inventory();
                    }

                    // $car_make = CarMake::whereRaw(['LOWER(`name`) LIKE "' => strtolower($make_column)])->first();
                    $car_make = CarMake::where('name', $make_column)->first();

                    $id_car_make = $car_make ? $car_make->id_car_make : null;
                    $inventory->vehicle_type = $car_make ? $car_make->id_car_type : null;
                    $inventory->make = $id_car_make;
                    $car_model = CarModel::where('id_car_make', $id_car_make)->where('name', $model_column)->first();
                    $inventory->model = $car_model ? $car_model->id_car_model : null;
                    $inventory->city = $city_column;
                    $inventory->color = $color_column;
                    if ($country_column) {
                        $country_code =  Countries::where('name.common', $country_column)->first();
                        $country_code = isset($country_code->cca2) ? $country_code->cca2 : null;
                        $inventory->country = $country_code;
                    } else {
                        $inventory->country = $this->config['country_default'];
                    }

                    $inventory->price = $price_column;
                    $transmission = Transmission::where('transmission', $transmission_column)->first();
                    if ($transmission) {
                        $inventory->transmission = $transmission->transmission;
                    }
                    $inventory->mileage = $mileage_column ? $mileage_column : 0;
                    $inventory->fuel_type = $fuel_column;
                    $inventory->year = $year_column;
                    $inventory->is_draft   = 0;
                    $inventory->is_deleted   = 0;
                    $inventory->unique_field = $unique_column;
                    $inventory->user_id = $this->user->id;
                    // $currency_symbol = getCurren
                    $currency_symbol = getCurrencySymbol($currency_column);
                    $currency_model = Currency::where('currency', $currency_symbol)->first();
                    if (!$currency_model) {

                        $currency_model = new Currency();
                        $currency_model->currency = $currency_symbol;
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
                    $inventory->currency = $currency_model->id;

                    $inventory->save();
                    $inventory_photo = InventoryPhoto::where('inventory_id', $inventory->id)->where('upload_path', $photo_column)->first();
                    if (!$inventory_photo && $photo_column) {
                        $inventory_photo = new InventoryPhoto();
                        $inventory_photo->source = 'online';
                        $inventory_photo->inventory_id = $inventory->id;
                        $inventory_photo->upload_path = $photo_column;
                        $inventory_photo->user_id = $this->user->id;
                        $inventory_photo->save();
                    }
                    event(new InventoryImportEvent(
                        $this->user,
                        array('progress' => $progress, 'log' => 'Imported ' . $inventory->inventory_name)
                    ));
                }
            }
        } else { }
    }

    public function failed(Exception $exception)
    {
        // Send user notification of failure, etc...

        Log::error($exception);
    }
}
