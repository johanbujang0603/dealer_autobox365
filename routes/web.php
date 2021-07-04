<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Events\InventoryImportEvent;


Route::get('/inventory/share/{id}', 'OtherController@inventoryShare')->name('inventory.share');
Route::get('/thank_you', 'OtherController@thankyou')->name('thank_you');
Route::post('/inventory/leads/generate', 'OtherController@leadGenerate')->name('inventory.lead_generate');
Route::get('/facebook/webhooks/{category}', 'FacebookWebhookController@webhook');
Route::post('/facebook/webhooks/{category}', 'FacebookWebhookController@webhook');
Route::get('/remove_error_inventories', function () {
    foreach (App\Models\Inventory::all() as $inventory) {
        echo $inventory->price;
        echo '<br/>';
        if (!is_numeric($inventory->price)) {
            dd($inventory);
            $inventory->delete();
        }
    }
});
Route::get('/language/{lang}', function ($lang) {
    // return view('welcome');
    session(['my_locale' => $lang]);
    // dd(session('my_locale'));
    return redirect(route('home'));
})->name('language');
Route::get('/lang_currency', 'Controller@setLangCurrency')->name('lang_currency');


Route::group(['prefix' => 'valuation'], function () {
    Route::get('/search', 'ValuationController@search');
    Route::get('/makes', 'ValuationController@makes');
    Route::get('/filter_params', 'ValuationController@filter_params');
    Route::get('/{make}/models', 'ValuationController@models');
    Route::get('/{country}/cities', 'ValuationController@cities');
});
// makes

Route::group(['prefix' => 'car2db'], function () {
    //
    Route::get('/vehicle_types', 'Car2DBController@getVehicleTypes');
    Route::get('/transmissions', 'Car2DBController@transmissions');
    Route::get('/{type}/makes', 'Car2DBController@getMakes');
    Route::get('/{make}/models', 'Car2DBController@getModelsByMake');
    Route::get('/{type}/{make}/models', 'Car2DBController@getModels');
    Route::get('/{type}/{model}/generations', 'Car2DBController@getGenerations');
    Route::get('/{type}/{model}/{generation}/series', 'Car2DBController@getSeries');
    Route::get('/{type}/{model}/{serie}/trims', 'Car2DBController@getTrims');
    Route::get('/{type}/{trim}/equipments', 'Car2DBController@getEquipments');
    Route::get('/{type}/{trim}/specifications', 'Car2DBController@getSpecifications');
});
