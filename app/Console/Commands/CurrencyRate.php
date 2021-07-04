<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CurrencyRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Currency Rate Update';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $currencies = Currency::all();
        foreach ($currencies as $currency) {

            try {
                $rate = \Swap::latest('USD/' . $currency->iso_code);

                $currency->currency_rate = 1 / $rate->getValue();
                $currency->save();
            } catch (Exception $e) {
                Log::debug($e);
            }
            // CurrencyModel::where('id', 1)->update($arr_data);
            sleep(1);
        }
    }
}
