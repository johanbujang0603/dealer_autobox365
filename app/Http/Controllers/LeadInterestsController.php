<?php

namespace App\Http\Controllers;

use App\Models\CarEquipment;
use App\Models\CarGeneration;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\CarSeries;
use App\Models\CarTrim;
use App\Models\Inventory;
use App\Models\Lead;
use App\Models\LeadInterest;
use App\Models\SystemLog;
use App\Models\Transmission;
use Illuminate\Http\Request;
use Auth;

class LeadInterestsController extends Controller
{
    //
    public function add(Request $request, $lead_id)
    {
        if (isset($request->id) && $request->id) {
            $interest = LeadInterest::find($request->id);
        } else {
            $interest = new LeadInterest();
        }

        $interest->lead_id = $lead_id;
        $interest->item_option = $request->choose_item;
        $interest->inventory_id = $request->inventory;
        $interest->vehicle_type = $request->vehicle_type;
        $interest->make = $request->make;
        $interest->model = $request->model;
        $interest->generation = $request->generation;
        $interest->serie = $request->serie;
        $interest->trim = $request->trim;
        $interest->equipment = $request->equipment;
        $interest->transmission = $request->transmission;
        $interest->color = $request->color;
        $interest->engine = $request->engine;
        $interest->steering_whieel = $request->steering_wheel;
        $interest->channel = $request->channel;
        $interest->notes = $request->notes;
        $interest->mileage_from = $request->mileage_from;
        $interest->mileage_to = $request->mileage_to;
        $interest->mileage_unit = $request->mileage_unit;
        $interest->price_from = $request->price_from;
        $interest->price_to = $request->price_to;
        $interest->price_currency = $request->price_currency;
        $interest->looking_to = $request->looking_to;
        $interest->looking_to_option = $request->looking_to_option;
        $interest->save();

        $log = new SystemLog();
        $log->user_id = Auth::user()->id;
        $log->action =  'interest_created';
        $log->category = 'leads';
        $log->model = Lead::class;
        $log->model_id = $lead_id;
        $log->save();
        return $interest;
    }

    public function load($id)
    {
        $interests = LeadInterest::where('lead_id', $id)->orderBy('created_at', 'desc')->get();
        $channel_array = [
            'Web' => 'icon-chrome',
            'Mobile' => 'icon-mobile',
            'Sms' => 'icon-textsms',
            'Email' => 'icon-envelope2',
        ];
        $html_str = '';
        foreach ($interests as $interest) {
            $car_image =  'data:image/svg+xml;charset=UTF-8,<svg width="100" height="60" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1051 250" preserveAspectRatio="none"><defs><style type="text/css">%23holder_16d0e7a6a35 text %7B fill:rgba(255%2C255%2C255%2C.75)%3Bfont-weight:bolder%3Bfont-family:Helvetica%2C monospace%3Bletter-spacing:0.5em%3Bfont-size:53pt %7D </style></defs><g id="holder_16d0e7a6a35"><rect width="1051" height="250" fill="%23777"></rect><g><text x="200" y="148.7">NO PHOTO</text></g></g></svg>';
            if ($interest->item_option == 'inventory') {
                $inventory = $interest->inventory_id ? $inventory = Inventory::find($interest->inventory_id) : null;
                $make = $inventory ? $inventory->make_details : null;
                $model = $inventory ? $inventory->model_details : null;
                $generation = $inventory ? $inventory->generation_details : null;
                $serie = $inventory ? $inventory->serie_details : null;
                $trim = $inventory ? $inventory->trim_details : null;
                $equipment = $inventory ? $inventory->equipment_details : null;
                $transmission = $inventory ? $inventory->transmission_details : null;
                $car_image = $inventory && $inventory->photo_details->count() ? '' . $inventory->photo_details[0]->image_src . '' : $car_image;
                $year = $inventory ? $inventory->year : '';
            } else {
                $make =  $interest->make ?  CarMake::where('id_car_make', $interest->make)->first() : null;
                $model =  $interest->model ?  CarModel::where('id_car_model', $interest->model)->first() : null;
                $generation = $interest->generation ? CarGeneration::where('id_car_generation', $interest->generation)->first() : null;
                $serie = $interest->serie ? CarSeries::where('id_car_serie', $interest->serie)->first() : null;
                $trim = $interest->trim ? CarTrim::where('id_car_trim', $interest->trim)->first() : null;
                $equipment = $interest->equipment ? CarEquipment::where('id_car_equipment', $interest->equipment)->first() : null;
                $transmission = $interest->transmission ? Transmission::where('id', $interest->transmission)->first() : null;
                $year = '';
            }

            $brand_image = '';

            if ($make) {
                $brand_image = $make ? asset('images/car_brand_logos/' . strtolower($make->name) . '.jpg') : asset('/global_assets/images/placeholders/placeholder.jpg');
            }
            $make = $make ? $make->name : '';
            $model = $model ? $model->name : '';

            if ($generation || $serie || $trim || $equipment) {

                $version = '';
                if ($generation) {
                    $version .= $generation->name;
                }
                if ($serie) {
                    $version .= ' - ' . $serie->name;
                }
                if ($trim) {
                    $version .= ' - ' . $trim->name;
                }
                if ($equipment) {
                    $version .= ' - ' . $equipment->name;
                }
            } else {
                $version = "";
            }
            $price_range_str = '';
            if ($interest->price_from && $interest->price_to) {
                $price_range_str = "Price from $interest->price_from to $interest->price_to";
            }
            $html_str .= '<li class="mt-5 flex item-center justify-between">
										<div class="flex items-center">
                <div class="mr-3 flex items-center justify-center flex-wrap">
                    <a href="javascript:;">
                        <img src=\'' . $brand_image . '\' class="rounded-full" width="60" height="60" alt="">
                    </a>
                    <a href="javascript:;">
                    <img src=\'' . $car_image . '\' class="car_photo" width="100" height="60" alt="">

                    </a>
                </div>
                <div>
                    <a href="javascript:;" class="font-medium">' . $make   . ' ' . $model   . ' ' . $year .   '</a>
                    <div class="text-theme-7 text-sm">
                       ' . $version . '

                    </div>
                    <div class="text-theme-7 text-sm">
                    ' . $price_range_str . ' | Looking to buy in: ' . $interest->looking_to . ' ' . $interest->looking_to_option . '
                    </div>
                </div>

                </div>
                
                <div class="dropdown relative">
                    <button class="dropdown-toggle button inline-block bg-theme-1 text-white"><i class="icon-menu7"></i></button>
                    <div class="dropdown-box mt-10 absolute w-48 top-0 right-0 z-20">
                        <div class="dropdown-box__content box p-2">
                            <a href="#" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md" interest_item"  data-id = "' . $interest->id . '" onclick="openInterestDetail(\'' . $interest->id . '\')"><i class="icon-file-stats mr-2"></i> View interest</a>
                            <a href="#" class="flex items-center border-b-2 block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md"" onclick="openInterestEditModal(\'' . $interest->id . '\')"><i class="icon-file-text2 mr-2" ></i> Edit interest</a>
                            <a href="#" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md"" onclick="openInterestDeleteConfirm(\'' . $interest->id . '\')"><i class="icon-gear mr-2"></i> Delete</a>
                        </div>
                    </div>
                </div>
            </li>';
        }
        return $html_str;
    }

    public function detail($id)
    {
        $interest = LeadInterest::find($id);
        $car_image = '';
        if ($interest->item_option == 'inventory') {
            $inventory = $interest->inventory_id ? $inventory = Inventory::find($interest->inventory_id) : null;
            $make = $inventory ? $inventory->make_details : null;
            $model = $inventory ? $inventory->model_details : null;
            $generation = $inventory ? $inventory->generation_details : null;
            $serie = $inventory ? $inventory->serie_details : null;
            $trim = $inventory ? $inventory->trim_details : null;
            $equipment = $inventory ? $inventory->equipment_details : null;
            $transmission = $inventory ? $inventory->transmission_details->transmission : null;
            $car_image = $inventory && $inventory->photo_details->count() ? '' . $inventory->photo_details[0]->image_src . '' : $car_image;
            $year = $inventory->year;
            $color = $inventory ? $inventory->color : '';
        } else {
            $make =  $interest->make ?  CarMake::where('id_car_make', $interest->make)->first() : null;
            $model =  $interest->model ?  CarModel::where('id_car_model', $interest->model)->first() : null;
            $generation = $interest->generation ? CarGeneration::where('id_car_generation', $interest->generation)->first() : null;
            $serie = $interest->serie ? CarSeries::where('id_car_serie', $interest->serie)->first() : null;
            $trim = $interest->trim ? CarTrim::where('id_car_trim', $interest->trim)->first() : null;
            $equipment = $interest->equipment ? CarEquipment::where('id_car_equipment', $interest->equipment)->first() : null;
            $transmission = $interest->transmission ? Transmission::where('id', $interest->transmission)->first()->transmission : null;
            $year = '';
            $color = $interest->color;
        }

        $brand_image = '';

        if ($make) {
            $brand_image = $make ? asset('images/car_brand_logos/' . strtolower($make->name) . '.jpg') : asset('/global_assets/images/placeholders/placeholder.jpg');
        }
        $make = $make ? $make->name : '';
        $model = $model ? $model->name : '';
        $generation = $generation ? $generation->name : '';
        $serie = $serie ? $serie->name : '';
        $trim = $trim ? $trim->name : '';
        $equipment = $equipment ? $equipment->name : '';

        if ($generation || $serie || $trim || $equipment) {

            $version = '';
            if ($generation) {
                $version .= $generation;
            }
            if ($serie) {
                $version .= ' - ' . $serie;
            }
            if ($trim) {
                $version .= ' - ' . $trim;
            }
            if ($equipment) {
                $version .= ' - ' . $equipment;
            }
        } else {
            $version = "";
        }
        $price_range_str = '';
        if ($interest->price_from && $interest->price_to) {
            $price_range_str = "Price from $interest->price_from to $interest->price_to";
        }
        $html_str = '';


        if ($interest->item_option == 'inventory') {

            $car_html_str = '<div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="col-span-12 lg:col-span-12 xxl:col-span-12">
                        <ul class="">
                            <li class="flex items-center flex-wrap mt-3">
                                <span class="font-semibold">Interested in:</span>
                                <div class="ml-auto">
                                <h6 class="font-medium">' . $make   . ' ' . $model   . ' ' . $year .   '<a href="' . route('inventories.edit', $interest->inventory_id) . '" class="text-theme-4"><small>(View full details of this inventory.)</small></a></h6>
                                </div>
                            </li>


                        </ul>
                    </div>

                </div>';
        } else {
            $car_html_str = '
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
                        <ul class="">
                            <li class="flex items-center flex-wrap mt-3">
                                <span class="font-semibold">Vehicle Type:</span>
                                <div class="ml-auto">
                                <h6 class="font-medium">' . $interest->getVehicleName() . '</h6>
                                </div>
                            </li>
                            <li class="flex items-center flex-wrap mt-3">
                                <span class="font-semibold">Make:</span>
                                <div class="ml-auto">
                                <h6 class="font-medium">' . $interest->getmakeName() . '</h6>
                                </div>
                            </li>
                            <li class="flex items-center flex-wrap mt-3">
                                <span class="font-semibold">Model:</span>
                                <div class="ml-auto">
                                <h6 class="font-medium">' . $interest->getModelName() . '</h6>
                                </div>
                            </li>
                            <li class="flex items-center flex-wrap mt-3">
                                <span class="font-semibold">Generation:</span>
                                <div class="ml-auto">
                                <h6 class="font-medium">' . $interest->getGenerationName() . '</h6>
                                </div>
                            </li>

                            <li class="flex items-center flex-wrap mt-3">
                                <span class="font-semibold">Serie:</span>
                                <div class="ml-auto">
                                <h6 class="font-medium">' . $interest->getSerieName() . '</h6>
                                </div>
                            </li>
                            <li class="flex items-center flex-wrap mt-3">
                                <span class="font-semibold">Trim:</span>
                                <div class="ml-auto">
                                <h6 class="font-medium">' . $interest->getTrimName() . '</h6>
                                </div>
                            </li>
                            <li class="flex items-center flex-wrap mt-3">
                                <span class="font-semibold">Equipment:</span>
                                <div class="ml-auto">
                                <h6 class="font-medium">' . $interest->getEquipmentName() . '</h6>
                                </div>
                            </li>

                        </ul>
                    </div>
                    <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
                        <ul class="">
                            <li class="flex items-center flex-wrap mt-3">
                                <span class="font-semibold">Color:</span>
                                <div class="ml-auto">
                                <h6 class="font-medium">' . $interest->color . '.</h6>
                                </div>
                            </li>
                            <li class="flex items-center flex-wrap mt-3">
                                <span class="font-semibold">Transmission:</span>
                                <div class="ml-auto">
                                <h6 class="font-medium">' . $interest->getTransmissionName() . '</h6>
                                </div>
                            </li>
                            <li class="flex items-center flex-wrap mt-3">
                                <span class="font-semibold">Steering Wheel:</span>
                                <div class="ml-auto">
                                <h6 class="font-medium">' . $interest->steering_whieel . '</h6>
                                </div>
                            </li>
                            <li class="flex items-center flex-wrap mt-3">
                                <span class="font-semibold">Mileage:</span>
                                <div class="ml-auto">
                                <h6 class="font-medium">' . $interest->getMileageRange() . '</h6>
                                </div>
                            </li>

                            <li class="flex items-center flex-wrap mt-3">
                                <span class="font-semibold">Price:</span>
                                <div class="ml-auto">
                                <h6 class="font-medium">' . $interest->getPriceRange() . '</h6>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
        ';
        }
        $html_str = '
            <div class="grid grid-cols-12 gap-6 mt-5">
                <div class="col-span-12 lg:col-span-12 xxl:col-span-12">
                    <ul class="">
                        <li class="flex items-center flex-wrap mt-3">
                            <span class="font-semibold">Looking to buy in:</span>
                            <div class="ml-auto">
                                <h6 class="font-medium">' . $interest->looking_to . $interest->looking_to_option . '</h6>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        ';
        return $car_html_str . $html_str;
    }

    public function load_edit_detail($id)
    {
        $interest = LeadInterest::find($id);
        $data = array(
            'id' => $interest->id,
            'item_option' => $interest->item_option,
            'inventory_id' => $interest->inventory_id,
            'vehicle_type' => $interest->vehicle_type,
            'make' => $interest->make,
            'model' => $interest->model,
            'generation' => $interest->generation,
            'serie' => $interest->serie,
            'trim' => $interest->trim,
            'equipment' => $interest->equipment,
            'transmission' => $interest->transmission,
            'color' => $interest->color,
            'engine' => $interest->engine,
            'steering_whieel' => $interest->steering_whieel,
            'channel' => $interest->channel,
            'notes' => $interest->notes,
            'mileage_from' => $interest->mileage_from,
            'mileage_to' => $interest->mileage_to,
            'mileage_unit' => $interest->mileage_unit,
            'price_from' => $interest->price_from,
            'price_to' => $interest->price_to,
            'price_currency' => $interest->price_currency,
            'looking_to' => $interest->looking_to,
            'looking_to_option' => $interest->looking_to_option,
            'select_vehicle_type'  => $interest->vehicle_type ? array(
                'value' => $interest->vehicle_type, 'label' => $interest->vehicle_details->name
            ) : null,
            'select_mileage_unit'  => $interest->mileage_unit ? array(
                'value' => $interest->mileage_unit, 'label' => $interest->mileage_unit == 'km' ? 'Kilometers' : 'Miles'
            ) : null,
            'select_steering_wheel'  => $interest->steering_whieel ? array(
                'value' => $interest->steering_whieel, 'label' => $interest->steering_whieel == 'left' ? 'Left' : 'Right'
            ) : null,
            'select_make' => $interest->make ? array(
                'value' => $interest->make, 'label' => $interest->make_details->name
            ) : null,
            'select_model' => $interest->model ? array(
                'value' => $interest->model, 'label' => $interest->model_details->name
            ) : null,
            'select_generation' => $interest->generation ? array(
                'value' => $interest->generation, 'label' => $interest->generation_details->name
            ) : null,
            'select_serie' => $interest->serie ? array(
                'value' => $interest->serie, 'label' => $interest->serie_details->name
            ) : null,
            'select_trim' => $interest->trim ? array(
                'value' => $interest->trim, 'label' => $interest->trim_details->name
            ) : null,
            'select_equipment' => $interest->equipment ? array(
                'value' => $interest->equipment, 'label' => $interest->equipment_details->name
            ) : null,
            'select_currency' => $interest->price_currency ? array(
                'value' => $interest->price_currency, 'label' => $interest->currency_details->currency . "(" . ($interest->currency_details->symbol) . ")"
            ) : null,
            'select_country' => $interest->country ? array(
                'value' => $interest->country, 'label' => $interest->country
            ) : null,
            'select_transmission' => $interest->transmission && $interest->transmission_details ? array(
                'value' => $interest->transmission, 'label' => $interest->transmission_details->transmission
            ) : null,
            'select_fuel_type' => $interest->fuel_type ? array(
                'value' => $interest->fuel_type, 'label' => $interest->fuel_type
            ) : null,
            'select_body_type' => $interest->body_type ? array(
                'value' => $interest->body_type, 'label' => $interest->body_type
            ) : null,
            'select_location' => $interest->location ? array(
                'value' => $interest->location, 'label' => $interest->location_details->name
            ) : null,
            'select_location' => $interest->location ? array(
                'value' => $interest->location, 'label' => $interest->location_details->name
            ) : null,
            'select_status' => $interest->status ? array(
                'value' => $interest->status, 'label' => $interest->status_details->status_name
            ) : null,
            'select_inventory' => $interest->inventory_id ? array(
                'label' => Inventory::find($interest->inventory_id)->inventory_name,
                'value' => $interest->inventory_id
            ) : null,
            'makes' => $interest->vehicle_type ? CarMake::where('id_car_type', $interest->vehicle_type)->get() : [],
            'models' => $interest->make ? CarModel::where('id_car_type', $interest->vehicle_type)
                ->where('id_car_make', $interest->make)
                ->get() : [],
            'generations' => $interest->model ? CarGeneration::where('id_car_type', $interest->vehicle_type)
                ->where('id_car_model', $interest->model)
                ->get() : [],
            'series' => $interest->generation ? CarSeries::where('id_car_type', $interest->vehicle_type)
                ->where('id_car_generation', $interest->generation)
                ->get() : [],
            'trims' => $interest->serie ? CarTrim::where('id_car_type', $interest->vehicle_type)
                ->where('id_car_serie', $interest->serie)
                ->get() : [],
            'equipments' => $interest->trim ? CarEquipment::where('id_car_type', $interest->vehicle_type)
                ->where('id_car_trim', $interest->trim)
                ->get() : [],
        );
        return $data;
        return $interest;
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        if ($interest = LeadInterest::find($id)) {
            $interest->delete();
            return array(
                'status' => 'success'
            );
        } else {
            return array(
                'status' => 'error'
            );
        }
    }
}
