<?php

namespace App\Http\Controllers;

use App\Events\InventoryImportEvent;
use App\Imports\InventoryImport;
use App\Jobs\ImportInventories;
use App\Models\CarEquipment;
use App\Models\CarGeneration;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\CarSeries;
use App\Models\CarSpecification;
use App\Models\CarSpecificationValue;
use App\Models\CarTrim;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Inventory;
use App\Models\InventoryOption;
use App\Models\InventoryPhoto;
use App\Models\InventoryStatus;
use App\Models\InventoryTag;
use App\Models\InventoryType;
use App\Models\Location;
use App\Models\SystemLog;
use App\Models\Document;
use App\Models\InventoryInformationField;
use App\Models\LeadInterest;
use App\Models\Transmission;
use App\Models\VehicleType;
use App\Models\UserSetting;
use App\Models\Transaction;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Excel;
use Illuminate\Support\Facades\DB;
use PragmaRX\Countries\Package\Countries;
use Carbon\Carbon;
use DataTables;
use Pimlie\DataTables\MongodbDataTable;
use Image;
use Cloudder;
use Illuminate\Support\Facades\Log;
// use romanzipp\QueueMonitor\Traits\QueueMonitor; // <---
use romanzipp\QueueMonitor\Models\Monitor;

class InventoryController extends Controller
{
    //
    public function index(Request $request)
    {
        $page_title = "All Listings";
        $r_permission = Auth::user()->hasPermission('inventory', 'read');
        $w_permission = Auth::user()->hasPermission('inventory', 'write');
        $d_permission = Auth::user()->hasPermission('inventory', 'delete');
        $json_data = json_encode(array(
            'permission' => array('read' => $r_permission, 'write' => $w_permission, 'delete' => $d_permission)
        ));
        return view('inventories.index', compact('page_title', 'json_data'));
    }

    public function draftlistings()
    {
        $page_title = "Inventory Draft";
        return view('inventories.draft', compact('page_title'));
    }

    public function deletedlistings()
    {
        $page_title = "Deleted Listings";
        
        $inventory_query = $this->get_inventory_query();
        $inventory_query->where('is_deleted', 1);
        $data = [];

        $inventories = $inventory_query->get();
        foreach ($inventories as $inventory) {

            // ***************************************** //
            $state = '<span class="py-1 px-2 rounded-full text-xs bg-theme-11 text-white font-medium">Deleted</span>';

            // ***************************************** //

            $brand_logo = isset($inventory->make_details->name) ? asset('images/car_brand_logos/' . strtolower($inventory->make_details->name) . '.jpg') : asset('/global_assets/images/placeholders/placeholder.jpg');
            $car_photo = isset($inventory->photo_details) && $inventory->photo_details->first() ?   $inventory->photo_details->first()->image_src  : asset('/global_assets/images/placeholders/placeholder.jpg');
            $transmission = isset($inventory->transmission_details->transmission) ? $inventory->transmission_details->transmission : '';
            $name = $inventory->inventory_name;
            $generation = isset($inventory->generation_details->name) ? $inventory->generation_details->name : "";
            $serie = isset($inventory->serie_details->name) ? $inventory->serie_details->name : "";
            $trim = isset($inventory->trim_details->name) ? $inventory->trim_details->name : "";
            $equipment = isset($inventory->equipment_details->name) ? $inventory->equipment_details->name : "";
            if ($generation && $serie && $trim && $equipment) {
                $version = "$generation  -  $serie  -  $trim  -  $equipment";
            } else {
                $version = "";
            }
            if (isset($inventory->location_details)) {
                $location = $inventory->location_details->name;
            } else {
                $location = '';
            }

            $name_field = "<div class=\"flex items-center\">
                    <div class=\"flex mr-3\">
                        <a href=\"#\" class=\"intro-x w-20 h-20 image-fit\">
                            <img src=\"$brand_logo\" class=\"rounded-circle\" width=\"60\" height=\"60\" alt=\"\">
                        </a>
                        <a href=\"javascript:;\" class=\"intro-x w-20 h-20 image-fit\">
                            <img src=\"$car_photo\" class=\"car_photo\" id=\"$inventory->id\"  width=\"100\" height=\"60\" alt=\"\"  onclick=\"openGallery($inventory->id)\">
                        </a>
                    </div>
                    <div>
                        <a href=\"#\" class=\"text-theme-7 truncate font-semibold\">" . ($name != "" && $version != "" ? "$name / $version" : $name . $version) . "</a>
                        <div class=\"text-theme-7 text-sm\">
                            "
                        . ($transmission != "" && $inventory->fuel_type && $inventory->mileage ? "$transmission / $inventory->fuel_type / $inventory->mileage" : "") .
                        "

                        </div>
                        <div class=\"text-theme-7 text-sm\">
                        "
                        . ($location != "" && $inventory->country && $inventory->city ? "$location / $inventory->country - $inventory->city" : "") .
                        "

                        </div>
                    </div>
                </div>";
            
            // ***************************************** //

            $price_field = "<h6 class=\"font-semibold mb-0\">" . $inventory->price_with_currency . "</h6>";

            // ***************************************** //

            $tags = $inventory->tags;
            $tag_str = '';
            if ($tags) {
                $tags = explode(',', $tags);
                foreach ($tags as $tag_id) {
                    $tag = InventoryTag::find($tag_id);
                    if ($tag != null)
                        $tag_str .= "<span class=\"truncate py-1 px-2 rounded-full text-xs text-white font-medium bg-$tag->color\">$tag->tag_name</span>";
                }
            }
            
            // ***************************************** //

            $status = isset($inventory->status_details) ? "<span class='truncate py-1 px-2 rounded-full text-xs text-white font-medium bg-" . $inventory->status_details->color . "'>" . $inventory->status_details->status_name . "</span>" : '---';

            // ***************************************** //

            $leads_count = $inventory->leads_count;

            // ***************************************** //

            $action_str = '
                <div class="list-icons list-icons-extended">
                    <a href="' . route('inventories.edit', $inventory->id) . '">
                        <i class="icon-pencil"></i>
                    </a>
                    <a href="' . route('inventories.view', $inventory->id) . '">
                        <i class="icon-eye"></i>
                    </a>
                </div>';

            $data[] = array(
                "state" => $state,
                "name_field" => $name_field,
                "price_field" => $price_field,
                "tag_str" => $tag_str,
                "status" => $status,
                "leads_count" => $leads_count,
                "action_str" => $action_str
            );
        }
        // print_r($data);exit;
        // foreach ($data as $item) {
        //     print_r($item['state']);
        //     echo '===================';
        // }
        // exit;
        return view('inventories.deleted', compact('page_title', 'data'));
    }

    /* -------- Begin Inventory Status Section -------- */

    public function status()
    {
        $page_title = "Inventory Status";
        $statuses = InventoryStatus::get();
        return view('inventories.status', [
            'page_title' => $page_title,
            'statuses' => $statuses
        ]);
    }

    public function statuscreate()
    {
        $page_title = "Status Create";
        return view('inventories.createstatus', compact('page_title'));
    }

    public function editstatus($id)
    {
        $page_title = "Edit Status";
        $status = InventoryStatus::find($id);
        return view('inventories.editstatus', compact('status', 'page_title'));
    }

    public function deletestatus($id)
    {
        $status = InventoryStatus::find($id);
        if ($status) {
            $status->delete();
            return redirect()->back()->with('success', "You removed a inventory status successfully!");
        } else {
            abort(404);
        }
    }

    public function statussave(Request $request)
    {
        if (isset($request->id)) {
            $status = InventoryStatus::find($request->id);
        } else {
            $status = new InventoryStatus;
            $status->user_id = Auth::user()->id;
        }
        $status->status_name = $request->status_name;
        $status->color = $request->color;
        $status->save();
        if (isset($request->id)) {
            return redirect(route('inventories.status'))->with('success', "You updated a inventory status successfully!");
        }
        return redirect()->back()->with('success', "You saved a inventory status successfully!");
    }

    /* -------- End Inventory Status Section -------- */

    public function ajaxLoad()
    {
        $inventory_query = $this->get_inventory_query();
        
        $inventories = $inventory_query->get();
        foreach ($inventories as $inventory) {

            // ***************************************** //
            $state = '';
            if ($inventory->is_deleted == 0 && $inventory->is_draft == 0) {
                $status_str = '<span class="text-xs text-theme-9 font-medium flex items-center"><i class="icon-checkmark2 mr-2"></i>Published</span>';
            } else if ($inventory->is_deleted == 1) {
                $status_str = '<span class="text-xs text-theme-6 font-medium flex items-center"><i class="icon-cross mr-2"></i>Deleted</span>';
            } else if ($inventory->is_draft == 1) {
                $status_str = '<span class="text-xs text-theme-4 font-medium flex items-center"><i class="icon-database mr-2"></i>Draft</span>';
            }
            $state = $status_str;

            // ***************************************** //

            $brand_logo = isset($inventory->make_details->name) ? asset('images/car_brand_logos/' . strtolower($inventory->make_details->name) . '.jpg') : asset('/global_assets/images/placeholders/placeholder.jpg');
            $car_photo = isset($inventory->photo_details) && $inventory->photo_details->first() ?   $inventory->photo_details->first()->image_src  : asset('/global_assets/images/placeholders/placeholder.jpg');
            $transmission = isset($inventory->transmission_details->transmission) ? $inventory->transmission_details->transmission : '';
            $name = $inventory->inventory_name;
            $generation = isset($inventory->generation_details->name) ? $inventory->generation_details->name : "";
            $serie = isset($inventory->serie_details->name) ? $inventory->serie_details->name : "";
            $trim = isset($inventory->trim_details->name) ? $inventory->trim_details->name : "";
            $equipment = isset($inventory->equipment_details->name) ? $inventory->equipment_details->name : "";
            if ($generation && $serie && $trim && $equipment) {
                $version = "$generation  -  $serie  -  $trim  -  $equipment";
            } else {
                $version = "";
            }
            if (isset($inventory->location_details)) {
                $location = $inventory->location_details->name;
            } else {
                $location = '';
            }

            $name_field = "<div class=\"flex items-center\">
                    <div class=\"flex items-center justify-center mr-3\">
                        <a href=\"#\">
                            <img src=\"$brand_logo\" class=\"rounded-full\" width=\"60\" height=\"60\" alt=\"\">
                        </a>
                        <a href=\"javascript:;\">
                            <img src=\"$car_photo\" class=\"rounded-full\" id=\"$inventory->id\"  width=\"100\" height=\"60\" alt=\"\"  onclick=\"openGallery($inventory->id)\">
                        </a>
                    </div>
                    <div>
                        <a href=\"#\" class=\"truncate text-theme-4 font-semibold\">" . ($name != "" && $version != "" ? "$name / $version" : $name . $version) . "</a>
                        <div class=\"text-theme-7 text-sm\">
                            "
                        . ($transmission != "" && $inventory->fuel_type && $inventory->mileage ? "$transmission / $inventory->fuel_type / $inventory->mileage" : "") .
                        "

                        </div>
                        <div class=\"text-theme-7 text-sm\">
                        "
                        . ($location != "" && $inventory->country && $inventory->city ? "$location / $inventory->country - $inventory->city" : "") .
                        "

                        </div>
                    </div>
                </div>";
            
            // ***************************************** //

            $price_field = "<h6 class=\"font-semibold mb-0\">" . $inventory->price_with_currency . "</h6>";

            // ***************************************** //

            $tags = $inventory->tags;
            $tag_str = '';
            if ($tags) {
                $tags = explode(',', $tags);
                foreach ($tags as $tag_id) {
                    $tag = InventoryTag::find($tag_id);
                    if ($tag)
                        $tag_str .= "<span class=\"truncate py-1 px-2 rounded-full text-xs text-white font-medium bg-$tag->color\">$tag->tag_name</span>";
                }
            }
            
            // ***************************************** //

            $status = isset($inventory->status_details) ? "<span class='truncate py-1 px-2 rounded-full text-xs text-white font-medium bg-" . $inventory->status_details->color . "'>" . $inventory->status_details->status_name . "</span>" : '---';

            // ***************************************** //

            $leads_count = $inventory->leads_count;

            // ***************************************** //

            $action_str = '
                <div class="flex items-center justify-center">
                    <a href="' . route('inventories.edit', $inventory->id) . '" class="p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md mr-2">
                        <i class="icon-pencil"></i>
                    </a>
                    <a href="' . route('inventories.view', $inventory->id) . '" class="p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md">
                        <i class="icon-eye"></i>
                    </a>
                </div>';

            $data[] = array(
                $state,
                $name_field,
                $price_field,
                $tag_str,
                $status,
                $leads_count,
                $action_str
            );
        }
        return $data;
    }

    public function ajaxLoadDetail($id)
    {
        $inventory = Inventory::with([
            'vehicle_details',
            'location_details',
            'user_details',
            'make_details',
            'model_details',
            'generation_details',
            'serie_details',
            'location_details',
            'user_details',
            'photo_details',

        ])->find($id);
        // return $inventory;
        $selected_tags = array();
        if ($inventory->tags) {
            foreach (explode(",", $inventory->tags) as $tag) {
                $tag = InventoryTag::find($tag);
                $selected_tags[] = array(
                    'label' => $tag->tag_name, 'value' => (int) $tag->id
                );
            }
        } else {
            $selected_tags = null;
        }
        $cur_symbol = Currency::where('iso_code', config('app.currency'))->first();
        $cur_symbol = array('value' =>$cur_symbol->id, 'label' => $cur_symbol->currency . '(' . $cur_symbol->symbol . ')');

        $data = array(
            'id' => $inventory->id,
            'photos' => $inventory->photo_details,
            'vehicle_type' => $inventory->vehicle_type,
            'latitude' => $inventory->latitude,
            'longitude' => $inventory->longitude,
            'make' => $inventory->make,
            'model' => $inventory->model,
            'generation' => $inventory->generation,
            'serie' => $inventory->serie,
            'trim' => $inventory->trim,
            'equipment' => $inventory->equipment,
            'year' => $inventory->year,
            'price' => $inventory->price_original,
            'currency' => $inventory->currency,
            'negotiable' => $inventory->negotiable,
            'country' => $inventory->country,
            'city' => $inventory->city,
            'color' => $inventory->color,
            'transmission' => $inventory->transmission,
            'engine' => $inventory->engine,
            'mileage' => $inventory->mileage,
            'fuel_type' => $inventory->fuel_type,
            'body_type' => $inventory->body_type,
            'location' => $inventory->location,
            'mileage_unit' => $inventory->mileage_unit,
            'steering_wheel' => $inventory->steering_wheel,
            'vin' => $inventory->vin,
            'plate_number' => $inventory->plate_number,
            'chassis_number' => $inventory->chassis_number,
            'reference' => $inventory->reference,
            'date_of_purchase' => $inventory->date_of_purchase,
            'price_of_purchase' => $inventory->price_of_purchase,
            'currency_of_purchase' => $inventory->currency_of_purchase,
            'number_of_seats' => $inventory->number_of_seats,
            'number_of_doors' => $inventory->number_of_doors,
            'cylinder' => $inventory->cylinder,
            'mechanical_condition' => $inventory->mechanical_condition,
            'body_condition' => $inventory->body_condition,
            'cur_currency' => $cur_symbol,
            'status' => $inventory->status ? array(
                'value' => $inventory->status, 'label' => $inventory->status_details->status_name
            ) : null,
            'finance' => $inventory->finance,
            'description' => $inventory->description ? $inventory->description : '',
            'options' => $inventory->options ? array_map('intval', explode(',', $inventory->options)) : [],
            'tags' => $inventory->tags ? $selected_tags : [],
            'select_vehicle_type'  => $inventory->vehicle_type ? array(
                'value' => $inventory->vehicle_type, 'label' => $inventory->vehicle_details->name
            ) : null,
            'select_mileage_unit'  => $inventory->mileage_unit ? array(
                'value' => $inventory->mileage_unit, 'label' => $inventory->mileage_unit == 'km' ? 'Kilometers' : 'Miles'
            ) : null,
            'select_steering_wheel'  => $inventory->steering_wheel ? array(
                'value' => $inventory->steering_wheel, 'label' => $inventory->steering_wheel == 'left' ? 'Left' : 'Right'
            ) : null,
            'select_make' => $inventory->make ? array(
                'value' => $inventory->make, 'label' => $inventory->make_details->name
            ) : null,
            'select_model' => $inventory->model ? array(
                'value' => $inventory->model, 'label' => $inventory->model_details->name
            ) : null,
            'select_generation' => $inventory->generation ? array(
                'value' => $inventory->generation, 'label' => $inventory->generation_details->name
            ) : null,
            'select_serie' => $inventory->serie ? array(
                'value' => $inventory->serie, 'label' => $inventory->serie_details->name
            ) : null,
            'select_trim' => $inventory->trim ? array(
                'value' => $inventory->trim, 'label' => $inventory->trim_details->name
            ) : null,
            'select_equipment' => $inventory->equipment ? array(
                'value' => $inventory->equipment, 'label' => $inventory->equipment_details->name
            ) : null,
            'select_year' => $inventory->year ? array(
                'value' => $inventory->year, 'label' => $inventory->year
            ) : null,
            'select_currency' => isset($inventory->currency) && $inventory->currency_details ? array(
                'value' => $inventory->currency, 'label' => $inventory->currency_details->currency . "(" . ($inventory->currency_details->symbol) . ")"
            ) : null,
            'select_currency_of_purchase' => isset($inventory->currency_of_purchase) ? array(
                'value' => $inventory->currency_of_purchase, 'label' => $inventory->currency_of_purchase_details->currency . "(" . ($inventory->currency_of_purchase_details->symbol) . ")"
            ) : null,
            'select_country' => isset($inventory->country) ? array(
                'value' => $inventory->country, 'label' => $inventory->country
            ) : null,
            'select_transmission' => isset($inventory->transmission) && $inventory->transmission_details ? array(
                'value' => $inventory->transmission, 'label' => $inventory->transmission_details->transmission
            ) : null,
            'select_fuel_type' => $inventory->fuel_type ? array(
                'value' => $inventory->fuel_type, 'label' => $inventory->fuel_type
            ) : null,
            'select_body_type' => $inventory->body_type ? array(
                'value' => $inventory->body_type, 'label' => $inventory->body_type
            ) : null,
            'select_location' => $inventory->location ? array(
                'value' => $inventory->location, 'label' => $inventory->location_details->name
            ) : null,
            'select_location' => $inventory->location ? array(
                'value' => $inventory->location, 'label' => $inventory->location_details->name
            ) : null,
            'select_status' => $inventory->status ? array(
                'value' => $inventory->status, 'label' => $inventory->status_details->status_name
            ) : null,
            'select_tags' => $selected_tags,
            'makes' => $inventory->vehicle_type ? CarMake::where('id_car_type', $inventory->vehicle_type)->get() : [],
            'models' => $inventory->make ? CarModel::where('id_car_type', $inventory->vehicle_type)
                ->where('id_car_make', $inventory->make)
                ->get() : [],
            'generations' => $inventory->model ? CarGeneration::where('id_car_type', $inventory->vehicle_type)
                ->where('id_car_model', $inventory->model)
                ->get() : [],
            'series' => $inventory->generation ? CarSeries::where('id_car_type', $inventory->vehicle_type)
                ->where('id_car_generation', $inventory->generation)
                ->get() : [],
            'trims' => $inventory->serie ? CarTrim::where('id_car_type', $inventory->vehicle_type)
                ->where('id_car_serie', $inventory->serie)
                ->get() : [],
            'equipments' => $inventory->trim ? CarEquipment::where('id_car_type', $inventory->vehicle_type)
                ->where('id_car_trim', $inventory->trim)
                ->get() : [],
        );
        return $data;
        # code...
    }

    public function dashboard(Request $request)
    {
        $page_title = "Inventory Dashboard";
        $end_date = date('Y-m-d');
        $start_date =  date('Y-m-d', strtotime('-2 week', strtotime($end_date)));
        
        if (isset($request->start)) {
            $start_date = $request->start;
        }
        if (isset($request->end)) {
            $end_date = $request->end;
        }
        
        $inventories_query = $this->get_inventory_query();

        $inventories_query->whereBetween('created_at', array(
                Carbon::parse($start_date), Carbon::parse($end_date)
            ));

        $price_updated_inventories_since_30_days = $this->get_price_updated_inventories_since_30_days();
        $inventories_no_prices = $this->get_inventories_no_price();
        $inventories_without_photos = $this->get_inventories_without_photos();
        $avg_price = $this->get_main_prices($start_date, $end_date)['avg_price'];
        $max_price = $this->get_main_prices($start_date, $end_date)['max_price'];
        $min_price = $this->get_main_prices($start_date, $end_date)['min_price'];
        $median_price = $this->get_main_prices($start_date, $end_date)['median_price'];
        $today = $end_date;
        $ts = strtotime($today);
        // find the year (ISO-8601 year number) and the current week
        $year = date('o', $ts);
        $week = date('W', $ts);
        // print week for the current date
        $dates = array();
        $weeks = array();
        for ($i = 1; $i <= 7; $i++) {
            // timestamp from ISO week date format
            $ts = strtotime($year . 'W' . $week . $i);
            $dates[] = date("Y-m-d", $ts);
            $weeks[] = date("l", $ts);
        }

        // get all dates between start date and end date
        $dates = array();
        $realEnd = new \DateTime($end_date);
        $period = new \DatePeriod(
            new \DateTime($start_date),
            new \DateInterval('P1D'),
            $realEnd->add(new \DateInterval('P1D'))
        );
        $weeks = array();
        if ($start_date == $end_date) {
            $dates[] = $start_date->format('Y-m-d');
            $weeks[] = $start_date->format('l');
        } else {
            foreach ($period as $key => $value) {
                $dates[] = $value->format('Y-m-d');
                $weeks[] = $value->format('l');
            }
        }
        $stack_chart_data = $this->get_stack_chart_data($dates);
        $vehicle_type_chat_data = array(
            'series' => $stack_chart_data,
            'legend' => VehicleType::pluck('name'),
            'weeks' => $weeks,
            'dates' => $dates
        );

        $total_counts = $this->get_inventory_query()->count();

        $countries_series = $this->get_countries_and_cities_series($start_date, $end_date)['countries_series'];
        $cities_series = $this->get_countries_and_cities_series($start_date, $end_date)['cities_series'];
        $location_chart_data = $this->get_countries_and_cities_series($start_date, $end_date)['location_chart_data'];
        $brand_chart_details = $this->get_brand_chart_details($start_date, $end_date, $total_counts);
        $currency_symbol = Currency::where('iso_code', config('app.currency'))->first() ? Currency::where('iso_code', config('app.currency'))->first()->symbol : '$';
        $inventories_with_photos_chart = $this->get_inventories_with_photos_chart()['inventories_with_photos_chart'];
        $inventories_less_than_5_photos = $this->get_inventories_with_photos_chart()['inventories_with_photos_5'];
        $inventories_with_missing_important_fields =$this->get_inventories_with_missing_important_fields();
        $inventories_more_than_60_days = $this->get_inventories_more_than_60_days();
        $body_condition_chart_data = $this->get_chart_data_by_condition()['body_condition_chart_data'];
        $mechanical_condition_chart_data = $this->get_chart_data_by_condition()['mechanical_condition_chart_data'];
        $stock_age_chart_data = $this->get_stock_age_chart_data();
        $char_data_by_status = $this->get_inventories_chart_data_by_status();
        
        
        
        $mechanical_condition_chart_data = json_encode($mechanical_condition_chart_data);
        $body_condition_chart_data = json_encode($body_condition_chart_data);
        $char_data_by_status = json_encode($char_data_by_status);
        
        $total_diff_days = 0;
        $total_mileage = 0;
        $transaction_cnt = 0;
        $diff_price = 0;
        $cur_currency = Currency::where('iso_code', config('app.currency'))->first();
        $currency_symbol = Currency::where('iso_code', config('app.currency'))->first() ? Currency::where('iso_code', config('app.currency'))->first()->symbol : '$';

        $inventory_query = $this->get_inventory_query();

        $inventories = $inventory_query->get();
        foreach ($inventories as $inventory) {
            $purchase_date = $inventory->date_of_purchase;
            $purchase_price = $inventory->price_of_purchase;
            $purchase_price = $purchase_price == null ? 0 : $purchase_price;

            $purchase_currency = $inventory->currency_of_purchase;

            if (Transaction::where('inventory_id', $inventory->id)->count() != 0) {
                $transaction = Transaction::where('inventory_id', $inventory->id)->first();
                $sale_price = $transaction->price;
                $sale_price_currency = $transaction->currency;
                                    
                $purchase_currency = isset($purchase_currency) ? $purchase_currency : Currency::where('iso_code', config('app.currency'))->first()->id;
                $sale_price_currency = isset($sale_price_currency) ? $sale_price_currency : Currency::where('iso_code', config('app.currency'))->first()->id;

                $model_purchase_currency = Currency::find($purchase_currency);
                $model_sale_currency = Currency::find($sale_price_currency);

                $purchase_price_with_rate = ((float) $purchase_price * $model_purchase_currency->currency_rate) / (float) $cur_currency->currency_rate;
                $sale_price_with_rate = ((float) $sale_price * $model_sale_currency->currency_rate) / (float) $cur_currency->currency_rate;

                $transaction_cnt ++;

                $diff_price += abs((float)$purchase_price_with_rate - (float)$sale_price_with_rate);
            }

            $diff_days = Carbon::parse($purchase_date)->diffInDays();
            $total_diff_days += (int)$diff_days;
            $total_mileage += (int)$inventory->mileage;
        }

        $average_time_in_stock = (int)$total_diff_days / $inventories->count() . ' days';
        $average_mileage_of_cars = (int)$total_mileage / $inventories->count() . ' ' . UserSetting::where('user_id', Auth::user()->id)->first()->mesure;
        if ($transaction_cnt == 0)
            $average_marge_per_car = 0;
        else
            $average_marge_per_car = number_format((float)$diff_price / $transaction_cnt, 0, '.', ',') . ' ' . $cur_currency->symbol;
        $profit_potential = number_format((float)$diff_price, 0, '.', ',') . ' ' . $cur_currency->symbol;

        $table_data = $this->ajaxLoad();

        return view('inventories.dashboard', [
            'currency_symbol' => $currency_symbol,
            'price_updated_inventories_since_30_days' => $price_updated_inventories_since_30_days,
            'inventories_no_prices' => $inventories_no_prices,
            'inventories_without_photos' => $inventories_without_photos,
            'inventories_less_than_5_photos' => $inventories_less_than_5_photos,
            'inventories_with_missing_important_fields' => $inventories_with_missing_important_fields,
            'inventories_more_than_60_days' => $inventories_more_than_60_days,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'avg_price' => (float) $avg_price,
            'max_price' => (float) $max_price,
            'min_price' => (float) $min_price,
            'median_price' => (float) $median_price,
            'vehicle_type_chat_data' => json_encode($vehicle_type_chat_data),
            'location_chart_data' => json_encode($location_chart_data),
            'brand_chart_details' => json_encode($brand_chart_details),
            'inventories_with_photos_chart' => json_encode($inventories_with_photos_chart),
            'stock_age_chart_data' => json_encode($stock_age_chart_data),
            'page_title' => $page_title,
            'total_counts' =>  $total_counts,
            'mechanical_condition' => $mechanical_condition_chart_data,
            'body_condition' => $body_condition_chart_data,
            'inventory_by_status' => $char_data_by_status,
            'average_time_in_stock' => $average_time_in_stock,
            'average_mileage_of_cars' => $average_mileage_of_cars,
            'average_marge_per_car' => $average_marge_per_car,
            'profit_potential' => $profit_potential,
            'table_data' => $table_data
        ]);
    }


    public function create()
    {
        $page_title = "Create Inventory";
        $inventory_types = InventoryType::all();
        $currencies = Currency::all();

        $statuses = InventoryStatus::get();
        $tags = InventoryTag::get();
        $vehicle_types = VehicleType::all();
        $fuel_types = CarSpecification::where('name', 'Fuel')->count() ? CarSpecificationValue::where('id_car_specification', CarSpecification::where('name', 'Fuel')->first()->id_car_specification)->groupBy('value')->orderBy('value')->get() : [];
        $body_types = CarSpecification::where('name', 'Body Type')->count() ? CarSpecificationValue::where('id_car_specification', CarSpecification::where('name', 'Body Type')->first()->id_car_specification)->groupBy('value')->orderBy('value')->get() : [];
        $options = InventoryOption::whereIn('user_id', [0, Auth::user()->id])->get();
        $locations = Location::where('user_id', Auth::user()->id)->get();
        if (Auth::user()->type != 'Dealer') {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $locations = Location::whereIn('_id', $locations)->get();
        }
        $transmissions = Transmission::all();
        $cur_symbol = Currency::where('iso_code', config('app.currency'))->first();
        $cur_symbol = array('value' =>$cur_symbol->id, 'label' => $cur_symbol->currency . '(' . $cur_symbol->symbol . ')');
        $cur_mileage = UserSetting::where('user_id', Auth::user()->id)->first()->mesure;
        $json_data = json_encode([
            'transmissions' => $transmissions,
            'inventory_types' => $inventory_types,
            'vehicle_types' => $vehicle_types,
            'currencies' => $currencies,
            'status_list' => $statuses,
            'fuel_types' => $fuel_types,
            'body_types' => $body_types,
            'option_list' => $options,
            'locations' => $locations,
            'tag_list' => $tags,
            'cur_currency' => $cur_symbol,
            'cur_mileage' => $cur_mileage,
        ]);
        return view('inventories.create', [
            'json_data' => $json_data,
            'page_title' => $page_title
        ]);
    }

    public function edit($id)
    {
        # code...
        $page_title = "Inventory Edit";
        $inventory = Inventory::find($id);
        if (!$inventory) {
            abort(404);
        }
        $inventory_types = InventoryType::all();
        $currencies = Currency::all();
        $statuses = InventoryStatus::get();
        $tags = InventoryTag::get();
        $vehicle_types = VehicleType::all();
        $fuel_types = CarSpecification::where('name', 'Fuel')->count() ? CarSpecificationValue::where('id_car_specification', CarSpecification::where('name', 'Fuel')->first()->id_car_specification)->groupBy('value')->orderBy('value')->get() : [];
        $body_types = CarSpecification::where('name', 'Body Type')->count() ? CarSpecificationValue::where('id_car_specification', CarSpecification::where('name', 'Body Type')->first()->id_car_specification)->groupBy('value')->orderBy('value')->get() : [];
        $options = InventoryOption::whereIn('user_id', [0, Auth::user()->id])->get();
        $locations = Location::where('user_id', Auth::user()->id)->get();
        
        if (Auth::user()->type != 'Dealer') {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $locations = Location::whereIn('_id', $locations)->get();
        }

        $transmissions = Transmission::all();
        $cur_symbol = Currency::where('iso_code', config('app.currency'))->first();
        $cur_symbol = array('value' =>$cur_symbol->id, 'label' => $cur_symbol->currency . '(' . $cur_symbol->symbol . ')');
        $json_data = json_encode([
            'transmissions' => $transmissions,
            'inventory_types' => $inventory_types,
            'vehicle_types' => $vehicle_types,
            'currencies' => $currencies,
            'status_list' => $statuses,
            'fuel_types' => $fuel_types,
            'body_types' => $body_types,
            'option_list' => $options,
            'locations' => $locations,
            'tag_list' => $tags,
            'cur_curreny' => $cur_symbol
        ]);
        return view('inventories.edit', compact('json_data', 'inventory', 'page_title'));
    }

    public function delete($id)
    {
        $inventory = Inventory::find($id);
        if ($inventory) {
            $inventory->is_deleted = 1;
            $log = new SystemLog;
            $log->user_id = Auth::user()->id;
            $log->action = 'deleted';
            $log->category = 'inventory';
            $log->model = Inventory::class;
            $log->model_id = $inventory->id;
            $log->save();
            $inventory->save();
            return redirect()->back()->with('success', 'You removed a listing successfully!');
        } else {
            abort(404);
        }
    }

    public function draft($id)
    {
        $inventory = Inventory::find($id);
        if ($inventory) {
            $inventory->is_draft = 1;
            $inventory->save();

            $log = new SystemLog;
            $log->user_id = Auth::user()->id;
            $log->action = 'drafted';
            $log->category = 'inventory';
            $log->model = InventoryOption::class;
            $log->model_id = $inventory->id;
            $log->save();


            return redirect()->back()->with('success', 'You removed a listing successfully!');
        } else {
            abort(404);
        }
    }

    public function publish($id)
    {
        $inventory = Inventory::find($id);
        if ($inventory) {
            $inventory->is_draft = 0;
            $inventory->is_deleted = 0;
            $log = new SystemLog;
            $log->user_id = Auth::user()->id;
            $log->action = 'published';
            $log->category = 'inventory';
            $log->model = Inventory::class;
            $log->model_id = $inventory->id;
            $log->save();
            $inventory->save();
            return redirect()->back()->with('success', 'You published a listing successfully!');
        } else {
            abort(404);
        }
    }
    
    /* -------- Begin Create Inventory Section -------- */

    public function save(Request $request)
    {
        $isCreate = true;
        if (isset($request->id) && $request->id) {
            $inventory = Inventory::find($request->id);
            $isCreate = false;
        } else {
            $inventory = new Inventory();
            $inventory->user_id = Auth::user()->id;
        }
        if (Auth::user()->type != 'Dealer')
            $inventory->dealer_id = Auth::user()->dealer_id;
        $inventory->vehicle_type = $request->vehicle_type;
        $inventory->make = $request->make;
        $inventory->model = $request->model;
        $inventory->latitude = $request->latitude;
        $inventory->longitude = $request->longitude;
        $inventory->generation = $request->generation;
        $inventory->serie = $request->serie;
        $inventory->trim = $request->trim;
        $inventory->equipment = $request->equipment;
        $inventory->year = $request->year;
        $inventory->price = $request->price;
        $inventory->currency = $request->currency;
        $inventory->negotiable = $request->negotiable;
        $inventory->country = $request->country;
        $inventory->city = $request->city;
        $inventory->color = $request->color;
        $inventory->transmission = $request->transmission;
        $inventory->engine = $request->engine;
        $inventory->mileage = $request->mileage;
        $inventory->vin = $request->vin;
        $inventory->plate_number = $request->plate_number;
        $inventory->chassis_number = $request->chassis_number;
        $inventory->reference = $request->reference;
        $inventory->date_of_purchase = $request->date_of_purchase;
        $inventory->price_of_purchase = $request->price_of_purchase;
        $inventory->currency_of_purchase = $request->currency_of_purchase;
        $inventory->fuel_type = $request->fuel_type;
        $inventory->body_type = $request->body_type;
        $inventory->is_draft = $request->draft;
        $inventory->number_of_seats = $request->number_of_seats;
        $inventory->number_of_doors = $request->number_of_doors;
        $inventory->cylinder = $request->cylinder;
        $inventory->mechanical_condition = $request->mechanical_condition;
        $inventory->body_condition = $request->body_condition;
        $options = [];
        foreach ($request->options as $option) {
            $options[] = $option;
        }
        $inventory->options = implode(',', $options);
        $inventory->location = $request->location;
        $inventory->description = $request->description;
        $tags  = [];
        foreach ($request->tags as $request_tags) {
            $tags[] = $request_tags['value'];
        }
        $inventory->tags = implode(',', $tags);
        $inventory->status = isset($request->status['value']) ? $request->status['value'] : '';
        $inventory->finance = $request->finance;
        if ($inventory->save()) {
            $new_inv = Inventory::find($inventory->id);
            $new_inv->full_name = $new_inv->inventory_name;
            $new_inv->save();
        }

        $log = new SystemLog;
        $log->user_id = Auth::user()->id;
        if ($isCreate) {
            $log->action = 'created';
            $log->changes = array();
        } else {
            $log->action = 'updated';
            $log->changes = $inventory->getChanges();
        }
        $log->category = 'inventory';
        $log->model = Inventory::class;
        $log->model_id = $inventory->id;

        $log->save();
        $inventory_id = $inventory->id;
        foreach ($request->photos as $photo) {
            $photo_model = InventoryPhoto::find($photo['id']);
            $photo_model->inventory_id = $inventory_id;
            $photo_model->save();
        }

        return array(
            'status' => 'success',
            'option' => $isCreate ? 'created' : 'updated',
            'data' => $inventory->toArray()
        );
    }

    /* -------- End Create Inventory Section -------- */

    /* -------- Begin Inventory Photo Section -------- */

    public function photoedit($id)
    {
        $page_title = "Inventory Photo Edit";
        $photos = InventoryPhoto::where('inventory_id', $id)
            //    ->orderBy('name', 'desc')
            ->get();
        return view('inventories.photoedit', [
            'inventoryId' => $id,
            'photos' => $photos,
            'page_title' => $page_title
        ]);
    }

    public function photosave(Request $request, $id)
    {
        $photo = new InventoryPhoto();
        $photo->inventory_id = $id;
        if ($file = $request->file('photo')) {
            $path = 'images/inventories/' . $id;

            $ext = strtolower($file->getClientOriginalExtension());
            $upload_file_name = (time() . '-' . uniqid()) . '.' . $file->getClientOriginalExtension();
            $photo->upload_path =   $path . '/' . $upload_file_name;
            $photo->file_name =   $file->getClientOriginalName();
            $photo->file_size =   $file->getSize();
            $file->move(public_path($path), $upload_file_name);
            $photo->user_id = Auth::user()->id;
            $photo->save();
            return $photo;
        }
    }

    public function uploadphoto(Request $request)
    {
        $photo = new InventoryPhoto();
        if ($file = $request->file('file')) {
            $image_name = $request->file('file')->getRealPath();;
            $option1 = array(
                "folder" => "dealers/" . Auth::user()->id . '/inventories', //"/images/rooms/$photo->room_id",
                "public_id" => (time() . '-' . uniqid()),
                "quality" => "auto:low",
                "flags" => "lossy",
                "resource_type" => "image"
            );
            Cloudder::upload($image_name, null, $option1);
            $resposne = Cloudder::getResult();
            $photo->upload_path =   $resposne['public_id'];
            $photo->original_path =   $resposne['public_id'];
            $photo->file_name =   $file->getClientOriginalName();
            $photo->file_size =   $file->getSize();
            $photo->source =   'cloudinary';
            // $file->move(public_path($path), $upload_file_name);
            $photo->user_id = Auth::user()->id;
            // $settings = Auth::user()->settings;
            // if (isset($settings->company_logo)) {
            //     $company_logo = $settings->company_logo;
            //     $water_mark_place = isset($settings->water_mark_place) ? $settings->water_mark_place : 'center';
            //     $watermark_transparence = isset($settings->watermark_transparence) ? $settings->watermark_transparence : 50;
            //     $img = Image::make(public_path($path . '/' . $upload_file_name));
            //     $img->insert(public_path($company_logo), $water_mark_place, 10, 10)->opacity($watermark_transparence);
            //     $img->save(public_path($path . '/inventory_photo_' . $upload_file_name));
            //     $photo->upload_path = $path . '/inventory_photo_' . $upload_file_name;
            // } else {
            //     $photo->upload_path =   $path . '/' . $upload_file_name;
            // }

            $photo->save();
            $uploadFile = $image_name;
            if (function_exists('curl_file_create')) { // php 5.5+
                $cFile = curl_file_create($uploadFile, $file->getClientMimeType(), $file->getClientOriginalName());
            } else {
                $cFile = '@' . realpath($uploadFile);
            }

            //ADD PARAMETER IN REQUEST LIKE regions
            $data = array(
                'upload' => $cFile,
                'mmc' => true
                //'regions' => 'fr' // Optional
            );

            // Prepare new cURL resource
            $ch = curl_init('https://api.platerecognizer.com/v1/plate-reader/');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            // Set HTTP Header for POST request
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                array(
                    "Authorization: Token " . env('PLATE_RECOGNIZE_API_KEY')  //API KEY
                )
            );

            // Submit the POST request and close cURL session handle
            $result = curl_exec($ch);
            curl_close($ch);
            $api_result = json_decode($result, true);
            if (isset($api_result['results']) && count($api_result['results'])) {
                $countries = recorgnizeCountriesFromPlateNumber($api_result['results'][0]['plate'])['result'];
                $extra_info = recorgnizeCountriesFromPlateNumber($api_result['results'][0]['plate'])['extra'];
            } else {
                $countries = null;
                $extra_info = null;
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.carnet.ai/mmg/detect?features=mmg,color');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $post = array(
                'file' =>   $cFile
            );
            curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($uploadFile));
            curl_setopt($ch, CURLOPT_POST, true);
            $headers = array();
            $headers[] = 'Api-Key: ' . env('CAR_NET_API_KEY');
            $headers[] = 'Content-Type: application/octet-stream';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $recorgnized_result = curl_exec($ch);
            if (curl_errno($ch)) { }
            curl_close($ch);
            $recorgnized_result = json_decode($recorgnized_result, true);
            return  array(
                'status' => 'success',
                'file' => $photo,
                'api_result' => $api_result,
                'country' => $countries,
                'extra_info' => $extra_info,
                'recorgnized_result' => $recorgnized_result,
                'aaa' => realpath($photo->upload_path)
            );
        }
    }

    public function removephoto(Request $request)
    {
        $photo = InventoryPhoto::find($request->id);
        $photo->delete();
    }

    /* -------- End Inventory Photo Section -------- */

    /* -------- Begin Inventory Tag Section -------- */

    public function tags()
    {
        $page_title = "Inventory Tags";
        $tags = InventoryTag::get();
        return view('inventories.tags', [
            'tags' => $tags,
            'page_title' => $page_title
        ]);
    }

    public function createtags()
    {
        $page_title = "Create Tags";
        return view('inventories.createtags', compact('page_title'));
    }

    public function savetags(Request $request)
    {
        if (isset($request->id)) {
            $tag = InventoryTag::find($request->id);
            $isCreate = false;
        } else {
            $tag = new InventoryTag;
            $tag->user_id = Auth::user()->id;
            $isCreate = true;
        }

        $tag->tag_name = $request->tag_name;
        $tag->color = $request->color;

        $tag->save();
        $log = new SystemLog;
        $log->user_id = Auth::user()->id;
        if ($isCreate) {
            $log->action = 'created';
        } else {
            $log->action = 'updated';
        }
        $log->category = 'inventory_tag';
        $log->model = InventoryTag::class;
        $log->model_id = $tag->id;
        $log->save();
        if (isset($request->id)) {
            return redirect(route('inventories.tags'))->with('success', "You updated a inventory tag successfully!");
        }
        return redirect()->back()->with('success', "You saved a inventory tag successfully!");
    }

    public function edittags($id)
    {
        $page_title = 'Edit Tag';
        $tag = InventoryTag::find($id);
        return view('inventories.edittag', compact('tag', 'page_title'));
    }

    public function deletetags($id)
    {
        $page_title = 'Delete Tag';
        $tag = InventoryTag::find($id);
        if ($tag) {
            $log = new SystemLog;
            $log->user_id = Auth::user()->id;
            $log->action = 'deleted';
            $log->category = 'inventory_tag';
            $log->model = InventoryTag::class;
            $log->model_id = $tag->id;
            $log->save();
            $tag->delete();

            return redirect()->back()->with('success', 'You removed a tag successfully');
        } else {
            return abort(404);
        }
        return view('inventories.edittag', compact('tag', 'page_title'));
    }

    /* -------- End Inventory Tag Section -------- */

    public function options()
    {
        $page_title = "Inventory Options";
        $options = InventoryOption::where('user_id', Auth::user()->id)->get();
        return view('inventories.options', [
            'options' => $options,
            'page_title' => $page_title
        ]);
    }

    public function createoptions()
    {
        $page_title = 'Create Option';
        return view('inventories.createoptions', compact('page_title'));
    }

    public function editoptions($id)
    {
        $page_title = 'Edit Option';
        $option = InventoryOption::find($id);
        return view('inventories.editoptions', compact('option', 'page_title'));
    }

    public function saveoptions(Request $request)
    {
        $log = new SystemLog;
        $log->user_id = Auth::user()->id;

        $log->category = 'inventory_option';
        $log->model = InventoryOption::class;
        if (isset($request->id)) {
            $option = InventoryOption::find($request->id);
            $log->action = 'updated';
        } else {
            $option = new InventoryOption;
            $option->user_id = Auth::user()->id;
            $log->action = 'created';
        }

        $log->model_id = $option->id;
        $log->save();
        $option->option_name = $request->option_name;
        $option->color = 'red';

        $option->save();
        if (isset($request->id)) {
            return redirect(route('inventories.options'))->with('success', "You updated a inventory option successfully!");
        }
        return redirect()->back()->with('success', "You saved a inventory option successfully!");
    }

    public function deleteoptions($id)
    {
        $option = InventoryOption::find($id);
        if ($option) {
            $log = new SystemLog;
            $log->user_id = Auth::user()->id;
            $log->action = 'deleted';
            $log->category = 'inventory_option';
            $log->model = InventoryOption::class;
            $log->model_id = $option->id;
            $log->save();
            $option->delete();
            return redirect()->back()->with('success', 'You removed a option successfully');
        } else {
            return abort(404);
        }
    }

    public function logs()
    {
        $logs = SystemLog::with(['inventory_details', 'user_details'])
            ->where('model', Inventory::class)
            ->where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')->get();
        $page_title = "Inventory Logs";

        return view('inventories.logs', compact('logs', 'page_title'));
    }

    public function getimages($id)
    {
        $inventory = Inventory::find($id);
        $data = array();
        foreach ($inventory->photo_details as $photo) {
            $data[] = array(
                'src' => $photo->image_src
            );
        }
        return $data;
    }

    public function view($id)
    {
        $page_title = "Inventory View";
        $inventory = Inventory::find($id);
        $documents = Document::where('parent_inventories', "LIKE", "%" . $id . "%")->get();
        
        $inventory_query  = Inventory::with([
            'vehicle_details',
            'location_details',
            'equipment_details',
            'trim_details',
            'serie_details',
            'generation_details',
            'user_details',
            'make_details',
            'model_details',
            'location_details',
            'user_details',
            'photo_details'
        ])->where('user_id', Auth::user()->id);

        $customers = Customer::where('user_id', Auth::user()->id)->where('is_deleted', '!=', 1)->get();
        $leads = Lead::where('user_id', Auth::user()->id)->where('is_converted', 0)->where('is_deleted', '!=', 1)->get();
        $locations = Location::where('user_id', Auth::user()->id)->where('is_deleted', '!=', 1)->get();
        $inventories = $inventory_query->get();
        $users = User::where('dealer_id', Auth::user()->id)->where('is_deleted', '!=', 1)->get();
        $json_data = json_encode(array(
            'users' => $users,
        ));
        return view('inventories.view', compact('inventory', 'json_data', 'page_title', 'inventories', 'locations', 'leads', 'users', 'customers'));
    }

    public function documentupload(Request $request, $id)
    {
        if ($file = $request->file('file')) {

            $path = "documents/inventories/$id";
            $document = new Document();
            $document->original_name = $file->getClientOriginalName();

            $leads = $request->get('leads');
            $customers = $request->get('customers');
            $inventories = $request->get('inventories');
            $locations = $request->get('locations');
            $users = $request->get('users');
            $description = $request->get('description');

            
            $document = new Document();

            $ext = strtolower($file->getClientOriginalExtension());
            $size = $file->getSize();
            $upload_file_name = (time() . '-' . uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($path), $upload_file_name);
            
            $document->original_name = $file->getClientOriginalName();
            $document->upload_path = $path . '/' . $upload_file_name;
            $document->kinds = 'inventory';
            $document->parent_inventories = isset($inventories)? $inventories : null;
            $document->parent_customers = isset($customers)? $customers : null;
            $document->parent_users = isset($users)? $users : null;
            $document->parent_leads = isset($leads)? $leads . ',' . $id : null;
            $document->parent_locations = isset($locations)? $locations : null;
            $document->description = isset($description)? $description : null;
            $document->user_id = Auth::user()->id;
            $document->tags = '';
            $document->type = $ext;
            $document->size = $size;
            $document->save();

            $log = new SystemLog();
            $log->user_id = Auth::user()->id;
            $log->action =  'document_created';
            $log->category = 'inventory';
            $log->model = Inventory::class;
            $log->model_id = $id;
            $log->save();
        }
    }
    public function documentload($id)
    {

        $documents = Document::where('parent_inventories', "LIKE", "%" . $id . "%")->get();
        $html_str = "";

        foreach ($documents as $document) {

            $html_str .= "<li class=\"flex flex-wrap items-center mt-5\">
                            <div class=\"mr-3\">
                                <i class=\"{$document->icon} icon-2x text-{$document->icon_color}-300 top-0\"></i>
                            </div>

                            <div class=\"\">
                                <div class=\"font-medium\"> $document->original_name </div>
                                <ul
                                    class=\"\">
                                    <li class=\"text-theme-15\">Size: " . formatSizeUnits($document->size) . "</li>
                                    <li class=\"\">By <a href=\"javascript:;\" class=\"text-theme-1\"> {$document->user_details->full_name}</a></li>
                                </ul>
                            </div>

                            <div class=\"ml-5\">
                                <div class=\"list-icons\">
                                    <a href=\"" . asset($document->upload_path) . "\" download class=\"text-theme-4\"><i class=\"icon-download\"></i></a>
                                </div>
                            </div>
                        </li>";
        }
        return $html_str;
    }

    public function valuation()
    {
        $page_title = "Inventory Valuation";
        $car_makes = CarMake::all();
        $countries = Inventory::whereNotNull('country')->groupBy('country')->pluck('country');
        // dd($countries);
        $country_array = array();
        foreach ($countries as $country) {
            $country_array[] = array(
                'value' => $country,
                'label' => Countries::where('cca2', $country)->first()->name->official
            );
        }
        $colors = Inventory::whereNotNull('color')->groupBy('color')->pluck('color');
        $fuel_types = Inventory::whereNotNull('fuel_type')->groupBy('fuel_type')->pluck('fuel_type');
        $body_types = Inventory::whereNotNull('body_type')->groupBy('body_type')->pluck('body_type');
        $transmissions = Transmission::all();
        return view('inventories.valuation', [
            'car_makes' => $car_makes,
            'countries' => $country_array,
            'colors' => $colors,
            'fuel_types' => $fuel_types,
            'body_types' => $body_types,
            'transmissions' => $transmissions,
            'page_title' => $page_title
        ]);
    }

    public function getCitiesByCountry($country)
    {
        return $cities = Inventory::whereNotNull('city')->where('country', $country)->groupBy('city')->pluck('city');
    }

    public function import(Request $request)
    {
        $page_title = "Inventory Import Listings";
        $running_import_job = Monitor::ordered()->where('queue', 'inventories_import')
            ->whereNotNull('progress')
            ->whereNull('finished_at')->first();
        // dd($running_import_job);
        // $host = $request->getHttpHost();



        // dd($host);
        return view('inventories.import', [
            'running_import_job' => $running_import_job,
            'page_title' => $page_title,
        ]);
    }
    public function uploadImportFile(Request $request)
    {
        $rows = [];
        $preview_rows = array();
        if ($file = $request->file('file')) {
            $path = "inventories/imports/excel";
            $upload_file_name = (time() . '-' . uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($path), $upload_file_name);
            $rows = Excel::toArray(new InventoryImport, $path . '/' . $upload_file_name);

            for ($i = 0; $i < 30; $i++) {
                if (isset($rows[0][$i])) {
                    $tempRow = [];
                    foreach ($rows[0][$i] as $key => $value) {
                        $tempRow[] = array('value' => $value);
                    }
                    $preview_rows[] = $tempRow;
                }
            }
        }
        return array('file_path' =>  $path . '/' . $upload_file_name, 'preview_rows' => $preview_rows);
        // return $request->all();
    }

    public function startImport(Request $request)
    {
        $userId = Auth::user()->id;

        ImportInventories::dispatch($request->all(), Auth::user()) //->delay(now()->addSeconds(1))
            ->onQueue('inventories_import');
    }

    public function demandRequests()
    {
        $page_title = "Inventory Demand Requests";
        return view('inventories.demand_requests', compact('page_title'));
    }

    public function ajaxLoadDemandRequests(Request $request)
    {
        $draw = $request->get('draw');
        $interests_model = LeadInterest::whereNull('inventory_id');

        if (isset($request->order[0]['column'])) {
            if ($request->order[0]['column'] == 2) {
                if ($request->order[0]['dir'] == 'desc') {
                    // $interests_model->sortBy('price', 'DESC');
                } else {
                    // $interests_model->sortBy('price');
                }
            }
        }
        return DataTables::of($interests_model)
            ->addColumn('name', function (LeadInterest $interest) {
                $brand_logo = isset($interest->make_details->name) ? asset('images/car_brand_logos/' . strtolower($interest->make_details->name) . '.jpg') : asset('/global_assets/images/placeholders/placeholder.jpg');
                $car_photo = isset($interest->photo_details) && $interest->photo_details->first() ?   $interest->photo_details->first()->image_src  : asset('/global_assets/images/placeholders/placeholder.jpg');
                $transmission = isset($interest->transmission_details->transmission) ? $interest->transmission_details->transmission : '';
                $name = $interest->inventory_name;
                $generation = isset($interest->generation_details->name) ? $interest->generation_details->name : "";
                $serie = isset($interest->serie_details->name) ? $interest->serie_details->name : "";
                $trim = isset($interest->trim_details->name) ? $interest->trim_details->name : "";
                $equipment = isset($interest->equipment_details->name) ? $interest->equipment_details->name : "";
                if ($generation && $serie && $trim && $equipment) {
                    $version = "$generation  -  $serie  -  $trim  -  $equipment";
                } else {
                    $version = "";
                }
                if (isset($interest->location_details)) {
                    $location = $interest->location_details->name;
                } else {
                    $location = '';
                }
                return $name_field = "<div class=\"d-flex align-items-center\">
                <div class=\"mr-3\">
                    <a href=\"#\">
                        <img src=\"$brand_logo\" class=\"rounded-circle\" width=\"60\" height=\"60\" alt=\"\">
                    </a>
                    <a href=\"javascript:;\">
                        <img src=\"$car_photo\" class=\"car_photo\" id=\"$interest->id\"  width=\"100\" height=\"60\" alt=\"\"  onclick=\"openGallery($interest->id)\">
                    </a>
                </div>
                <div>
                    <a href=\"#\" class=\"text-default font-weight-semibold\">" . ($name != "" && $version != "" ? "$name / $version" : $name . $version) . "</a>
                    <div class=\"text-muted font-size-sm\">
                        "
                    . ($transmission != "" && $interest->fuel_type && $interest->mileage ? "$transmission / $interest->fuel_type / $interest->mileage" : "") .
                    "

                    </div>
                    <div class=\"text-muted font-size-sm\">
                    "
                    . ($location != "" && $interest->country && $interest->city ? "$location / $interest->country - $interest->city" : "") .
                    "

                    </div>
                </div>
            </div>";
            })
            ->editColumn('price', function (LeadInterest $interest) {
                return $price_field = "<h6 class=\"font-weight-semibold mb-0\">" . $interest->price_with_currency . "</h6>";
            })
            ->addColumn('tags', function (LeadInterest $interest) {
                $tags = $interest->tags;
                $tag_str = '';
                if ($tags) {
                    $tags = explode(',', $tags);
                    foreach ($tags as $tag_id) {
                        $tag = InventoryTag::find($tag_id);
                        $tag_str .= "<span class=\"badge bg-$tag->color\">$tag->tag_name</span>";
                    }
                }
                return $tag_str;
            })

            ->rawColumns(['name', 'price'])
            // ->orderColumn('price_with_currency', 'price_with_currency $1')
            ->orderColumn('price', '-price $1')
            ->make(true);
    }

    public function export()
    {
        $csvExporter = new \Laracsv\Export();
        $csvExporter->build(Inventory::where('user_id', Auth::user()->id)->get(), ['inventory_name', 'price_with_currency', 'created_at']);
        $csvExporter->download('inventories.csv');
    }

    public function filter(Request $request)
    {
        $perPage = 10;
        $inventories_query = Inventory::query();
        $page_number = $request->page;
        if (Auth::user()->type == 'Dealer'){
            $inventories_query = Inventory::where('user_id', Auth::user()->id)->orWhere('dealer_id', Auth::user()->id);
        }
        else {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            if (empty($locations))
                $inventories_query = Inventory::where('user_id', Auth::user()->id)->orWhereNull('location');
            else
                $inventories_query = Inventory::whereIn('location', $locations)->orWhere('user_id', Auth::user()->id)->orWhereNull('location');
        }

        if ($request->vehicle_type) {
            $inventories_query->where('vehicle_type', $request->vehicle_type);
        }
        if ($request->make) {
            $inventories_query->where('make', $request->make);
        }
        if ($request->model) {
            $inventories_query->where('model', $request->model);
        }
        if ($request->generation) {
            $inventories_query->where('generation', $request->generation);
        }
        if ($request->serie) {
            $inventories_query->where('serie', $request->serie);
        }
        if ($request->trim) {
            $inventories_query->where('trim', $request->trim);
        }
        if ($request->transmission) {
            $inventories_query->where('transmission', $request->transmission);
        }
        if ($request->color) {
            // $inventories_query->whereRaw(['color' => array('$regex' => new MongoRegex)]);
            $inventories_query->where('color', 'regex', "/.*" . $request->color . "/i");
        }
        if ($request->country) {
            // $inventories_query->whereRaw(['color' => array('$regex' => new MongoRegex)]);
            $inventories_query->where('country',   $request->country);
        }
        if ($request->city) {
            // $inventories_query->whereRaw(['color' => array('$regex' => new MongoRegex)]);
            $inventories_query->where('city',   $request->city);
        }
        if ($request->from_year) {
            // $inventories_query->whereRaw(['color' => array('$regex' => new MongoRegex)]);
            $inventories_query->whereRaw(['year' => array('$gt' => (int) $request->from_year, '$lt' => (int) $request->to_year)]); //
            //where('year', '>=',   $request->from_year);
        }
        if ($request->to_year) {
            // $inventories_query->whereRaw(['color' => array('$regex' => new MongoRegex)]);
            // $inventories_query->whereRaw(['year' => array('$lt' => $request->to_year)]); //
            // $inventories_query->where('year', '<=',   $request->to_year);
        }
        return $inventories_query->paginate($perPage, ['*'], 'page', $page_number);
    }

    public function params(Request $request)
    {
        $price_min = 0;
        
        $price_max = (float)Inventory::where('user_id', Auth::user()->id)->get()->max('price');
        $price_min = (float)Inventory::where('user_id', Auth::user()->id)->get()->min('price');
        if ($price_max <= $price_min)
            $price_max = $price_min + 1;
        $mileage_min = 0;
        $mileage_max = 100;
        // $mileage_max = Inventory::where('user_id', Auth::user()->id)->max('mileage');

        return array(
            'price_min' => $price_min,
            'price_max' => ceil($price_max),
            'price_range' => array('min' => $price_min, 'max' => ceil($price_max)),
        );
    }

    public function search(Request $request)
    {
        Log::info('Start Search Inventories');
        Log::info(date("Y-m-d H:i:s"));
        $time_start = microtime_float();
        $searchKey = $request->searchKey;
        $inventoryQuery = DB::collection('inventories')->raw(function ($collection) use ($searchKey) {
            return $collection->aggregate(
                array(
                    array('$lookup' => array(
                        'from' => 'car_make',
                        'localField' => 'make',
                        'foreignField' => 'id_car_make',
                        'as' => 'make_details'
                    )),
                    array(
                        '$unwind' => array(
                            'path' => '$make_details'
                        )
                    ),
                    array('$lookup' => array(
                        'from' => 'car_model',
                        'localField' => 'model',
                        'foreignField' => 'id_car_model',
                        'as' => 'model_details'
                    )),
                    array(
                        '$unwind' => array(
                            'path' => '$model_details'
                        )
                    ),
                    array(
                        '$project' => array(

                            'id' => array(
                                '$toString' => '$_id'
                            ),
                            'make_details' => 1,
                            'model_details' => 1,
                            'inventory_name' => array(
                                '$concat' => array('$make_details.name', ' ', '$model_details.name', ' ', array(
                                    '$substr' => array(
                                        '$year', 0, -1
                                    )
                                ))
                            )
                        )
                    ),
                    array(
                        '$match' => array(
                            'inventory_name' => array(
                                '$regex' => $searchKey //"/.*Toyota/i"
                            )
                        )
                    )
                )
            );
        }); //->project(['make', 'model']);
        Log::info('End Search Inventories');
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        Log::info(date("Y-m-d H:i:s"));
        Log::info("$time seconds");

        // Log::info(print_r($elapsedTime, true));
        return $inventoryQuery->toArray(); //->where('inventory_name', 'like', "%$request->searchKey%");
    }


    public function get_price_updated_inventories_since_30_days() {

        if (Auth::user()->type == 'Dealer') {
            $price_updated_inventories_since_30_days_ids = SystemLog::where('model', '=', Inventory::class)->where('user_id', Auth::user()->id)
            ->whereBetween('updated_at', array(
                Carbon::now()->subDays(30), Carbon::now()
            ))
            ->where('changes.price', 'exists', true)
            ->pluck('model_id');
            $price_updated_inventories_since_30_days = Inventory::where(function($q){
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
                })->whereNotIn('_id', $price_updated_inventories_since_30_days_ids)->count();
        }
        else {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $price_updated_inventories_since_30_days_ids = SystemLog::where('model', '=', Inventory::class)->where('user_id', Auth::user()->id)
                ->whereBetween('updated_at', array(
                    Carbon::now()->subDays(30), Carbon::now()
                ))
                ->where('changes.price', 'exists', true)
                ->pluck('model_id');
            $price_updated_inventories_since_30_days = Inventory::where(function($q) use($locations) {
                $q->where('user_id', Auth::user()->id)
                ->orWhereNull('location')->orWhereIn('location', $locations);
                })->whereNotIn('_id', $price_updated_inventories_since_30_days_ids)->count();
        }
        return $price_updated_inventories_since_30_days;
    }

    public function get_inventories_no_price() {
        if (Auth::user()->type == 'Dealer') {
            $inventories_no_prices = Inventory::whereNull('price')->where(function($q){
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
                })->count();
        }
        else {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $inventories_no_prices = Inventory::whereNull('price')->where(function($q) use($locations) {
                $q->where('user_id', Auth::user()->id)
                ->orWhereNull('location')->orWhereIn('location', $locations);
                })->count();
        }

        return $inventories_no_prices;
    }

    public function get_inventories_without_photos() {
        
        if (Auth::user()->type == 'Dealer') {
            $inventories_without_photos = Inventory::doesnthave('photo_details')->where(function($q){
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
                })->count();
        }
        else {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $inventories_without_photos = Inventory::doesnthave('photo_details')->where(function($q) use($locations) {
                $q->where('user_id', Auth::user()->id)
                ->orWhereNull('location')->orWhereIn('location', $locations);
                })->count();
        }

        return $inventories_without_photos;
    }

    public function get_main_prices($start_date, $end_date) {
        
        $inventory_query = $this->get_inventory_query();
        if (Auth::user()->type == 'Dealer') {
            $inventory_price_query = $inventory_query->whereBetween('created_at', array(
                    Carbon::parse($start_date), Carbon::parse($end_date)
                ))->pluck('price');
        }
        else {
            $inventory_query = $this->get_inventory_query();
            $inventory_price_query = $inventory_query->whereBetween('created_at', array(
                    Carbon::parse($start_date), Carbon::parse($end_date)
                ))->pluck('price');
        }

        return array(
            'avg_price' => $inventory_price_query->avg(),
            'max_price' => $inventory_price_query->max(),
            'min_price' => $inventory_price_query->min(),
            'median_price' => $inventory_price_query->median(),
        );
    }

    public function get_stack_chart_data($dates) {
        
        $vehicle_types = VehicleType::all();
        $stack_chart_data = array();

        foreach ($vehicle_types as $type) {

            $counts_by_date = array();
            foreach ($dates as $index=>$date) {
                
                if (Auth::user()->type == 'Dealer') {

                    $inventory_query = Inventory::where(function($q){
                        $q->where('user_id', Auth::user()->id)
                        ->orWhere('dealer_id', Auth::user()->id);
                    });
                }
                else {
                    $locations = Auth::user()->locations;
                    $locations = explode(',', $locations);
                    $inventory_query = Inventory::where(function($q) use($locations) {
                        $q->where('user_id', Auth::user()->id)
                        ->orWhereNull('location')->orWhereIn('location', $locations);
                    });
                }
                
                $start_day = Carbon::parse($date)->copy()->startOfDay();
                $end_day = Carbon::parse($date)->copy()->endOfDay();

                $counts_by_date[] = $inventory_query->whereBetween('created_at', array($start_day , $end_day))->count();

            }
            $stack_chart_data[] = array(
                'name' => ucfirst($type->name),
                'type' => 'bar',
                'data' => $counts_by_date
            );
        }

        return $stack_chart_data;
    }

    public function get_countries_and_cities_series($start_date, $end_date) {
        
        $inventory_query = $this->get_inventory_query();
        $countries = $inventory_query->whereBetween('created_at', array(
                Carbon::parse($start_date), Carbon::parse($end_date)
            ))->whereNotNull('country')->groupBy('country')->pluck('country');
        // dd($countries);
        $countries_series = array();
        $cities_series = array();
        foreach ($countries as $country) {

            $inventory_query = $this->get_inventory_query();
            $countries_series[] = array(
                'value' => $inventory_query->whereBetween('created_at', array(
                    Carbon::parse($start_date), Carbon::parse($end_date)
                ))->where('country', $country)->count(),
                'name' => isset(Countries::where('cca2', $country)->first()->name->official) ? Countries::where('cca2', $country)->first()->name->official : ""
            );
            
            $inventory_query = $this->get_inventory_query();
            $cities = $inventory_query->whereBetween('created_at', array(
                    Carbon::parse($start_date), Carbon::parse($end_date)
                ))->where('country', $country)->groupBy('city')->pluck('city');
            
            foreach ($cities as $city) {
                $inventory_query = $this->get_inventory_query();
                $cities_series[] = array(
                    'value' => $inventory_query->where('created_at', '>=', $start_date)
                        ->where('created_at', '<=', $end_date)->where('country', $country)->where('city', $city)->count(),
                    'name' => $city
                );
            }
        }
        

        $location_chart_data = array(
            'legen' => $countries,
            'countries_series' => $countries_series,
            'cities_series' => $cities_series,
        );

        return array(
            'countries_series' => $countries_series,
            'cities_series' => $cities_series,
            'location_chart_data' => $location_chart_data
        );
    }

    public function get_brand_chart_details($start_date, $end_date, $total_counts) {

        $inventory_query = $this->get_inventory_query();
        $brands = $inventory_query->whereNotNull('make')->where('make', '<>', '')
                            ->whereBetween('created_at', array(
                                Carbon::parse($start_date), Carbon::parse($end_date)
                            ))->groupBy('make')->pluck('make');
                            
        $brand_array = array();
        $brand_series = array();
        foreach ($brands as $make) {

            $inventory_query = $this->get_inventory_query();
            
            $brand_array[$make] = $inventory_query->where('make', $make)
                ->whereBetween('created_at', array(
                    Carbon::parse($start_date), Carbon::parse($end_date)
                ))->count();
            
        }
        if (!empty($brand_array)) arsort($brand_array);
        $brand_count = 0;
        foreach ($brand_array as $make => $count_by_brand) {

            // $count_by_brand = $inventories_query->where('make', $make)->count();
            if ($brand_count < 10) {
                $brand_series[] = array(
                    'count' => $count_by_brand,
                    'name' => isset(CarMake::where('id_car_make', "$make")->first()->name) ?  ucfirst(CarMake::where('id_car_make', "$make")->first()->name) : '',
                    'percent' => $total_counts ? round(($count_by_brand / $total_counts) * 100, 2) : 0
                );
            } else {
                break;
            }
            $brand_count++;
        }
        $brand_chart_details = array(
            'total_count' => $total_counts,
            'counts_by_brand' => $brand_series
        );
        return $brand_chart_details;
    }

    public function get_inventories_with_photos_chart() {

        
        $inventory_query = $this->get_inventory_query();
        $inventories_with_photos_0 = $inventory_query->has('photo_details',  '<', 1)->count();
        $inventory_query = $this->get_inventory_query();
        $inventories_with_photos_5 = $inventory_query->has('photo_details',  '<=', 5)->count() - $inventories_with_photos_0;
        $inventory_query = $this->get_inventory_query();
        $inventories_with_photos_10 = $inventory_query->has('photo_details',  '<=', 10)->count() - $inventories_with_photos_5;
        $inventory_query = $this->get_inventory_query();
        $inventories_with_photos_10_more = $inventory_query->has('photo_details',  '>', 10)->count();

        $inventories_with_photos_chart = [];

        if ($inventories_with_photos_0) {
            $inventories_with_photos_chart[] = array(
                "value" => $inventories_with_photos_0,
                'name' => "0 Photos"
            );
        }
        if ($inventories_with_photos_5) {
            $inventories_with_photos_chart[] = array(
                "value" => $inventories_with_photos_5,
                'name' => "Less than 5 Photos"
            );
        }
        if ($inventories_with_photos_10) {
            $inventories_with_photos_chart[] = array(
                "value" => $inventories_with_photos_10,
                'name' => "Less than 10 Photos"
            );
        }
        if ($inventories_with_photos_10_more) {
            $inventories_with_photos_chart[] = array(
                "value" => $inventories_with_photos_10_more,
                'name' => "More than 10 Photos"
            );
        }
        return  array(
            'inventories_with_photos_5' => $inventories_with_photos_5,
            'inventories_with_photos_chart' => $inventories_with_photos_chart
        );
    }

    public function get_stock_age_chart_data() {
        
        $inventory_query = $this->get_inventory_query();
        
        $min_date = $inventory_query->first();

        $stock_age_chart_data = [];

        if ($min_date) {
            $min_date = $min_date->created_at;

            $inventory_query = $this->get_inventory_query();
            $inventories_less_than_7_days = $inventory_query->whereBetween('created_at', array(
                    Carbon::now()->subDays(7), Carbon::now()
                ))->count();

            $inventory_query = $this->get_inventory_query();
            $inventories_less_than_30_days = $inventory_query->whereBetween('created_at', array(
                    Carbon::now()->subDays(30), Carbon::now()->subDays(7)
                ))->count();

            $inventory_query = $this->get_inventory_query();
            $inventories_less_than_60_days = $inventory_query->whereBetween('created_at', array(
                    Carbon::now()->subDays(60), Carbon::now()->subDays(30)
                ))->count();

            $inventory_query = $this->get_inventory_query();
            $inventories_less_than_90_days = $inventory_query->whereBetween('created_at', array(
                    Carbon::now()->subDays(90), Carbon::now()->subDays(60)
                ))->count();

            $inventory_query = $this->get_inventory_query();
            $inventories_less_than_120_days = $inventory_query->whereBetween('created_at', array(
                    Carbon::now()->subDays(120), Carbon::now()->subDays(90)
                ))->count();

            $inventory_query = $this->get_inventory_query();
            $inventories_more_than_120_days = $inventory_query->whereBetween('created_at', array(
                    $min_date, Carbon::now()->subDays(120)
                ))->count();
                
            if ($inventories_less_than_7_days) {
                $stock_age_chart_data[] = array('name' => 'Less than 7 Days', 'value' => $inventories_less_than_7_days);
            }
            if ($inventories_less_than_30_days) {
                $stock_age_chart_data[] = array('name' => '7 Days - 30 Days', 'value' => $inventories_less_than_30_days);
            }
            if ($inventories_less_than_60_days) {
                $stock_age_chart_data[] = array('name' => '30 Days - 60 Days', 'value' => $inventories_less_than_60_days);
            }
            if ($inventories_less_than_90_days) {
                $stock_age_chart_data[] = array('name' => '60 Days - 90 Days', 'value' => $inventories_less_than_90_days);
            }
            if ($inventories_less_than_120_days) {
                $stock_age_chart_data[] = array('name' => '90 Days - 120 Days', 'value' => $inventories_less_than_120_days);
            }
            if ($inventories_more_than_120_days) {
                $stock_age_chart_data[] = array('name' => 'More than 120 Days', 'value' => $inventories_more_than_120_days);
            }
        }
        else {
            $stock_age_chart_data = array();
        }

        return $stock_age_chart_data;
    }

    public function get_inventories_with_missing_important_fields() {
        
        $inventory_query = $this->get_inventory_query();
        $important_fields = InventoryInformationField::all();
        $inventories_missing_fields_query = $inventory_query->where(function ($query) use ($important_fields) {
            foreach ($important_fields as $field) {
                $query->orWhereNull($field->field);
            }
        });
        return $inventories_missing_fields_query->count();
    }

    public function get_inventories_more_than_60_days() {
        
        $inventory_query = $this->get_inventory_query();
        $min_date = $inventory_query->first()->created_at;

        $inventory_query = $this->get_inventory_query();
        return $inventory_query->whereBetween('created_at', array(
            $min_date, Carbon::now()->subDays(60)
        ))->count();
    }

    public function get_chart_data_by_condition() {
        
        $conditions = array(
            ["label" => "Very Good", "value" => "very_good", "color"=> "#29B6F6"],
            ["label" => "Good", "value" => "good", "color"=> "#6E1A16"],
            ["label" => "Average", "value" => "average", "color"=> "#A9B1F6"],
            ["label" => "Bad", "value" => "bad", "color"=> "#EF5350"],
            ["label" => "Very Bad", "value" => "very_bad", "color"=> "#66BB6A"]
        );

        $inventory_query = $this->get_inventory_query();
        
        $body_condition = array();
        $mechanical_condition = array();
        foreach ($conditions as $condition) {
            $inventory_query = $this->get_inventory_query();
            $cnt_mechanical = $inventory_query->where('mechanical_condition', $condition['value'])->count();

            $inventory_query = $this->get_inventory_query();
            $cnt_body = $inventory_query->where('body_condition', $condition['value'])->count();
                
            $body_condition[] = array(
                "label" => $condition['label'],
                "value" => $cnt_body,
                "color" => $condition['color']
            );
            $mechanical_condition[] = array(
                "label" => $condition['label'],
                "value" => $cnt_mechanical,
                "color" => $condition['color']
            );
        }
        return array(
            'body_condition_chart_data' => $body_condition,
            'mechanical_condition_chart_data' => $mechanical_condition
        );
    }

    public function get_inventories_chart_data_by_status() {
        
        $all_status = InventoryStatus::get();
        $inventory_by_status = array();

        foreach ($all_status as $status) {
            
            $inventory_query = $this->get_inventory_query();
            $cnt_status = $inventory_query->where('status', $status->id)->count();
            $inventory_by_status[] = array(
                "label" => $status->status_name,
                "value" => $cnt_status,
                "color" => $status->color
            );
        }

        return $inventory_by_status;
    }

    public function get_inventory_query() {

        if (Auth::user()->type == 'Dealer') {
            $inventory_query = Inventory::where(function($q){
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            });
        }
        else {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $inventory_query = Inventory::where(function($q) use($locations) {
                $q->where('user_id', Auth::user()->id)
                ->orWhereNull('location')->orWhereIn('location', $locations);
            });
        }
        return $inventory_query;
    }
}
