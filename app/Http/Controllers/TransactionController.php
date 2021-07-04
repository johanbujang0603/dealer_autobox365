<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Customer;
use App\Models\Document;
use App\Models\Inventory;
use App\Models\Lead;
use App\Models\Location;
use App\Models\Transaction;
use App\User;
use Illuminate\Http\Request;
use Auth;
use DataTables;
use Carbon\Carbon;

class TransactionController extends Controller
{
    //
    public function index()
    {
        # code...
        $page_title = 'Transactions';

        $data = [];
        $transactions = Transaction::where('user_id', Auth::user()->id)->get();
        foreach ($transactions as $transaction) {
            $inventory_name = "No Inventory";
            $customer_name = "No Customer";
            $assigned_users = "No assigned users";
            $assigned_locations = 'No assigned locations';
            $price = $transaction->price_with_currency;
            $date = Carbon::parse($transaction->date_of_sale)->diffForHumans();
            $action_str = '<div class="dropdown relative">
                    <button class="dropdown-toggle button inline-block"><i class="icon-menu7"></i></button>
                    <div class="dropdown-box mt-10 absolute w-40 top-0 right-0 z-20">
                        <div class="dropdown-box__content box p-2">
                            <a href="' . route('transactions.edit', $transaction->id) . '" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md"><i class="icon-pencil mr-2"></i> Edit Listing</a>
                        </div>
                    </div>
                </div>
                ';

            $inventory = $transaction->inventory_details;
            if ($inventory) {
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
                $inventory_name = "<div class=\"flex items-center\">
                    <div class=\"mr-3 flex items-center justify-center\">
                        <a href=\"#\">
                            <img src=\"$brand_logo\" class=\"rounded-full\" width=\"60\" height=\"60\" alt=\"\">
                        </a>
                        <a href=\"javascript:;\">
                            <img src=\"$car_photo\" class=\"car_photo\" id=\"$inventory->id\"  width=\"100\" height=\"60\" alt=\"\"  onclick=\"openGallery($inventory->id)\">
                        </a>
                    </div>
                    <div>
                        <a href=\"#\" class=\"text-theme-1 font-medium\">" . ($name != "" && $version != "" ? "$name / $version" : $name . $version) . "</a>
                        <div class=\"text-sm\">
                            "
                    . ($transmission != "" && $inventory->fuel_type && $inventory->mileage ? "$transmission / $inventory->fuel_type / $inventory->mileage" : "") .
                    "

                        </div>
                        <div class=\"text-sm\">
                        "
                    . ($location != "" && $inventory->country && $inventory->city ? "$location / $inventory->country - $inventory->city" : "") .
                    "

                        </div>
                    </div>
                </div>";
            }

            if ($transaction->customer_id) {
                $customer = Customer::find($transaction->customer_id);
                $customer_name = $customer->name;
            }
            $users = $transaction->user;
            if ($users) {
                if (count($users)) {
                    $user_str = '';
                    foreach ($users as $user) {
                        $user_str .= '<p>' . $user['label'] . '</p>';
                    }
                    $assigned_users = $user_str;
                }
            }
            $locations = $transaction->location;
            if ($locations) {
                if (count($locations)) {
                    $location_str = '';
                    foreach ($locations as $location) {
                        $location_str .= '<p>' . $location['label'] . '</p>';
                    }
                    $assigned_locations = $location_str;
                }   
            }
            array_push($data, array(
                'inventory_name' => $inventory_name,
                'customer_name' => $customer_name,
                'assigned_users' => $assigned_users,
                'assigned_locations' => $assigned_locations,
                'price' => $price,
                'date' => $date,
                'action_str' => $action_str
            ));
        }

        return view('transactions.index', compact('page_title', 'data'));
    }

    public function basicdata()
    {
        # code...
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
        ])->where('is_deleted', 0)->where('is_draft', 0)->where('user_id', Auth::user()->id);
        $inventories = $inventory_query->get()->toArray();
        $users = User::where('dealer_id', Auth::user()->id)->get()->toArray();
        $leads = Lead::where('user_id', Auth::user()->id)->get()->toArray();
        return $data = array(
            'inventories' => $inventories,
            'currencies' => Currency::all()->toArray(),
            'locations' => Location::where('user_id', Auth::user()->id)->get(),
            'users' => $users,
            'leads' => $leads,
        );
    }
    public function create()
    {
        # code...
        $page_title = 'Create Transaction';
        
        $inventory_query = Inventory::query();
        
        if (Auth::user()->type == 'Dealer'){
            $inventory_query = Inventory::with([
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
            ])->where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->where('is_deleted', '!=', 1)->where('is_draft', '!=', 1);
        }
        else {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $inventory_query = Inventory::with([
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
            ])->where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhereNull('location')->orWhereIn('location', $locations);
            })->where('is_deleted', 0)->where('is_draft', 0);
        }

        $inventories = $inventory_query->get()->toArray();
        $users = User::where('dealer_id', Auth::user()->id)->get()->toArray();
        $leads = Lead::where('user_id', Auth::user()->id)->get()->toArray();
        $user_currency_id = Currency::where('iso_code', config('app.currency'))->first()->id;
        $data = array(
            'inventories' => $inventories,
            'currencies' => Currency::all()->toArray(),
            'locations' => Location::where('user_id', Auth::user()->id)->get(),
            'users' => $users,
            'leads' => $leads,
            'user_currency_id' => $user_currency_id
        );
        $json_data = json_encode($data);
        return view('transactions.create', compact('json_data', 'page_title'));
    }
    public function edit($id)
    {
        # code...
        $page_title = 'Edit Transaction';
        
        $inventory_query = Inventory::query();
        
        if (Auth::user()->type == 'Dealer'){
            $inventory_query = Inventory::with([
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
            ])->where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->where('is_deleted', '!=', 1)->where('is_draft', '!=', 1);
        }
        else {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $inventory_query = Inventory::with([
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
            ])->where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhereNull('location')->orWhereIn('location', $locations);
            })->where('is_deleted', 0)->where('is_draft', 0);
        }

        $inventories = $inventory_query->get()->toArray();
        $users = User::where('dealer_id', Auth::user()->id)->get()->toArray();
        $leads = Lead::where('user_id', Auth::user()->id)->get()->toArray();
        $data = array(
            'inventories' => $inventories,
            'currencies' => Currency::all()->toArray(),
            'locations' => Location::where('user_id', Auth::user()->id)->get(),
            'users' => $users,
            'leads' => $leads,
        );
        $json_data = json_encode($data);
        $transactionId = $id;
        return view('transactions.edit', compact('json_data', 'transactionId', 'page_title'));
    }
    public function loadDetails($id)
    {
        $transaction = Transaction::find($id);
        return array(
            'transaction' => $transaction,
            'documents' => $transaction->documents()
        );
    }
    
    public function loadByCustomer(Request $request, $id)
    {
        $transaction_model = Transaction::where('customer_id', $id);
        return DataTables::of($transaction_model)
            ->addColumn('inventory', function (Transaction $transaction) {
                $inventory = $transaction->inventory_details;
                if ($inventory) {
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
                    return $name_field = "<div class=\"d-flex align-items-center\">
                        <div class=\"mr-3\">
                            <a href=\"#\">
                                <img src=\"$brand_logo\" class=\"rounded-circle\" width=\"60\" height=\"60\" alt=\"\">
                            </a>
                            <a href=\"javascript:;\">
                                <img src=\"$car_photo\" class=\"car_photo\" id=\"$inventory->id\"  width=\"100\" height=\"60\" alt=\"\"  onclick=\"openGallery($inventory->id)\">
                            </a>
                        </div>
                        <div>
                            <a href=\"#\" class=\"text-default font-weight-semibold\">" . ($name != "" && $version != "" ? "$name / $version" : $name . $version) . "</a>
                            <div class=\"text-muted font-size-sm\">
                                "
                        . ($transmission != "" && $inventory->fuel_type && $inventory->mileage ? "$transmission / $inventory->fuel_type / $inventory->mileage" : "") .
                        "

                            </div>
                            <div class=\"text-muted font-size-sm\">
                            "
                        . ($location != "" && $inventory->country && $inventory->city ? "$location / $inventory->country - $inventory->city" : "") .
                        "

                            </div>
                        </div>
                    </div>";
                } else {
                    return "No Inventory";
                }
            })
            ->addColumn('customer', function (Transaction $transaction) {
                if ($transaction->customer_id) {
                    $customer = Customer::find($transaction->customer_id);
                    return $customer->name;
                } else {
                    return "No Customer";
                }
            })
            ->addColumn('users', function (Transaction $transaction) {
                $users = $transaction->user;
                if ($users) {
                    if (count($users)) {
                        $user_str = '';
                        foreach ($users as $user) {
                            $user_str .= '<p>' . $user['label'] . '</p>';
                        }
                        return $user_str;
                    } else {
                        return "No assigned users";
                    }
                } else {
                    return "No assigned users";
                }
            })
            ->addColumn('locations', function (Transaction $transaction) {
                // return "locations";
                $locations = $transaction->location;
                if ($locations) {
                    if (count($locations)) {
                        $location_str = '';
                        foreach ($locations as $location) {
                            $location_str .= '<p>' . $location['label'] . '</p>';
                        }
                        return $location_str;
                    } else {
                        return "No assigned locations";
                    }
                } else {
                    return "No assigned locations";
                }
            })
            ->addColumn('price', function (Transaction $transaction) {
                return $transaction->price_with_currency;
            })
            ->addColumn('date', function (Transaction $transaction) {
                // return $transaction->date_of_sale;
                return Carbon::parse($transaction->date_of_sale)->diffForHumans();
            })
            ->addColumn('action', function (Transaction $transaction) {
                $action_str =     '<div class="list-icons">
                    <div class="list-icons-item dropdown">
                        <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu7"></i></a>
                        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                            <a href="' . route('transactions.edit', $transaction->id) . '" class="dropdown-item"><i class="icon-pencil"></i> Edit Listing</a>
                        </div>
                    </div>
                </div>
                ';
                return $action_str;
            })

            ->rawColumns(['inventory', 'customer', 'users', 'locations', 'price', 'date', 'action'])

            ->make(true);
    }

    public function save(Request $request)
    {
        if (isset($request->id) && $request->id) {
            $transaction_model = Transaction::find($request->id);
        } else {
            $transaction_model = new Transaction();
            $transaction_model->user_id = Auth::user()->id;
        }
        $transaction_model->inventory_id = $request->inventory;
        $transaction_model->date_of_sale = Carbon::parse($request->date_of_sale);
        $transaction_model->date_of_estimate_delivery = Carbon::parse($request->date_of_estimate_delivery);
        $transaction_model->price = $request->price ? (float) $request->price : 0;
        $transaction_model->down_payment_price = $request->down_payment_price ? (float) $request->down_payment_price : 0;
        if (isset($request->customer_id) && $request->customer_id) {
            $transaction_model->lead_id = Customer::find($request->customer_id)->previous_lead_id;
            $transaction_model->customer_id = $request->customer_id;
        } else if (isset($request->lead) && $request->lead) {
            $lead_detail = Lead::find($request->lead);
            if (isset($lead_detail->is_converted) && $lead_detail->is_converted) {
                $customer_id = Customer::where('previous_lead_id', $lead_detail->id)->first()->id;
            } else {
                $lead_detail->is_converted = 1;
                $lead_detail->converted_at = Carbon::now();
                $lead_detail->save();
                $customer = new Customer();
                // $customer->fill($lead->toArray());
                foreach ($lead_detail->toArray() as $key => $value) {
                    if ($key != '_id' || $key != 'id') {
                        $customer->$key = $value;
                    }
                }
                $customer->previous_lead_id = $lead_detail->id;
                $customer->save();
                $customer_id = $customer->id;
            }
            $transaction_model->lead_id = $request->lead;
            $transaction_model->customer_id = $customer_id;
        }

        $transaction_model->financial_institution_name = $request->financial_institution_name;
        $transaction_model->user = $request->user;
        $transaction_model->location = $request->location;
        $transaction_model->currency = $request->currency;
        $transaction_model->notes = $request->notes;
        $transaction_model->finance = $request->finance;
        $transaction_model->save();

        $documents = $request->documents;
        if ($documents) {
            foreach ($documents as $document) {
                $document_model = Document::find($document['id']);
                $document_model->model_id = $transaction_model->id;
                $document_model->save();
            }
        }
        return array(
            'status' => 'success',
            'transaction_details' => $transaction_model
        );
    }
}
