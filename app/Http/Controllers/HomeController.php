<?php

namespace App\Http\Controllers;

use App\Models\DocumentTag;
use App\Models\Inventory;
use App\Models\Lead;
use App\Models\Customer;
use App\Models\LeadEmail;
use App\Models\LeadEnquiry;
use App\Models\LeadPhoneNumber;
use App\Models\Transaction;
use App\Models\LeadTag;
use App\Models\LeadStatus;
use App\Models\LeadNote;
use App\Models\LeadInterest;
use App\Models\InventoryType;
use App\Models\Location;
use App\Models\Document;
use App\User;
use App\Models\SystemLog;
use App\Models\Currency;
use DB;
use Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // dd(\App::getLocale());
        $color = ['#29B6F6', '#66BB6A', '#EF5350'];
        $page_title = 'Dashboard';
        $leads_by_tag = array();
        $leads_by_status = array();
        $inventory_by_type = array();
        $inventory_by_make = array();
        $user_id = Auth::user()->id;
        $locations = Auth::user()->location;
        $locations = explode(',', $locations);
        $total_vehicle = 0;
        if (Auth::user()->type == 'Dealer') {
            $inventories = Inventory::where('user_id', Auth::user()->id)->get();
            $total_inventories = $inventories->count();
            $leads = Lead::where('user_id', Auth::user()->id)->where('is_converted', 0)->where('is_deleted', '!=', 1)->get();
            $total_leads = $leads->count();
            $total_customers = Customer::where('user_id', Auth::user()->id)->count();
        }
        else {
            $inventories = Inventory::whereIn('location', $locations)->get();
            $total_inventories = $inventories->count();
            $leads = Lead::raw(function($collection) use($locations, $user_id)
            {
                return $collection->aggregate([
                    ['$addFields' => [
                        'location' => [ '$split' => [ '$assign_locations', ',' ] ] 
                    ]],
                    ['$unwind' => '$location'],
                    ['$project' => [
                        'in_location' => [
                        '$in' => ['$location', $locations ]
                        ],
                        "first_name" => 1,
                        "last_name" => 1,
                        "middle_name" => 1,
                        "assign_users" => 1,
                    ]],
                    ['$match' => [
                        '$or' => [
                            [ 'assign_users' => [ '$regex' => '.*' . $user_id . '.*'] ],
                            [ 'in_location' => true ]
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$_id',
                        'first_name' => ['$first' => '$first_name'],
                        'last_name' => ['$first' => '$last_name'],
                        'middle_name' => ['$first' => '$middle_name']
                    ]]
                ]);
            });
            $total_leads = $leads->count();
            
            $customers = Customer::raw(function($collection) use($locations, $user_id)
            {
                return $collection->aggregate([
                    ['$addFields' => [
                        'location' => [ '$split' => [ '$assign_locations', ',' ] ] 
                    ]],
                    ['$unwind' => '$location'],
                    ['$project' => [
                        'in_location' => [
                        '$in' => ['$location', $locations ]
                        ],
                        "first_name" => 1,
                        "last_name" => 1,
                        "middle_name" => 1,
                        "assign_users" => 1,
                        "tags" => 1,
                        "profile_image_src" => 1,
                        "profile_image" => 1,
                    ]],
                    ['$match' => [
                        '$or' => [
                            [ 'assign_users' => [ '$regex' => '.*' . $user_id . '.*'] ],
                            [ 'in_location' => true ]
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$_id',
                        'first_name' => ['$first' => '$first_name'],
                        'last_name' => ['$first' => '$last_name'],
                        'middle_name' => ['$first' => '$middle_name'],
                        'tags' => ['$first' => '$tags'],
                        'profile_image_src' => ['$first' => '$profile_image_src'],
                        "profile_image" => ['$first' => '$profile_image'],
                    ]]
                ]);
            });

            $total_customers = $customers->count();

        }

        
        $total_transactions = Transaction::where('user_id', Auth::user()->id)->count();
        $all_lead_tags = LeadTag::get();
        $all_lead_status = LeadStatus::get();
        $all_inventory_type = InventoryType::where('user_id', Auth::user()->id)->get();
        $all_vehicle_makes = Inventory::where('user_id', Auth::user()->id)->where('is_deleted', '!=', 1)->groupBy('make')->get();
        $total_price_inventory = Inventory::where('user_id', Auth::user()->id)->get();
        $cur_symbol = Currency::select('symbol')->where('iso_code', config('app.currency'))->first();

        $tt = 0;
        foreach ($inventories as $in) {
            $tt += $in->price;
        }
        $avg_price_inventory = 0;
        if ($tt != 0) $avg_price_inventory = round($tt / $total_inventories, 2);

        $last_documents = Document::orderBy('created_at')->where('user_id', Auth::user()->id)->take(8)->get();
        $num_documents = Document::where('user_id', Auth::user()->id)->count();

        $logs = SystemLog::with(['inventory_details', 'lead_details', 'customer_details', 'user_details'])->where('user_id', Auth::user()->id)
                ->whereIn('category', array('leads', 'customers', 'inventory', 'location'))->orderBy('created_at', 'desc')->take(10)->get();

        
        foreach($all_vehicle_makes as $make) {
            $cnt = Inventory::where('user_id', Auth::user()->id)->where('is_deleted', '!=', 1)->where('make', $make->make)->count();
            array_push($inventory_by_make, array(
                'make_name' => $make->make_details ? $make->make_details->name : '',
                'value' => $cnt
            ));
        }

        foreach ($all_inventory_type as $index => $type) {
            $cnt = Lead::where("type", "=", $type->_ID)->where('is_converted', 0)->where('is_deleted', '!=', 1)->count();
            array_push($inventory_by_type, array(
                'status' => $type->type_name,
                'value' => $cnt,
                'color' => $color[$index % 3]
            ));
        }
        foreach ($all_lead_tags as $index => $tag) {
            $cnt = Lead::where("tags", "LIKE", "%" . $tag->_ID . "%")->where('is_converted', 0)->where('is_deleted', '!=', 1)->count();
            array_push($leads_by_tag, array(
                'status' => $tag->tag_name,
                'value' => $cnt,
                'color' => $color[$index % 3]
            ));
        }

        foreach ($all_lead_status as $index => $status) {
            $cnt = Lead::where("status", "=", $status->_ID)->where('is_converted', 0)->where('is_deleted', '!=', 1)->count();
            array_push($leads_by_status, array(
                'status' => $status->status_name,
                'value' => $cnt,
                'color' => $color[$index % 3]
            ));
        }
        return view('home', [
            'total_inventories' => $total_inventories,
            'total_leads' => $total_leads,
            'total_customers' => $total_customers,
            'total_transactions' => $total_transactions,
            'leads_by_tag' => $leads_by_tag,
            'leads_by_status' => $leads_by_status,
            'inventory_by_type' => $inventory_by_type,
            'inventory_by_make' => $inventory_by_make,
            'total_price_inventory' => number_format($tt),
            'avg_price_inventory' => number_format($avg_price_inventory, 2),
            'last_documents' => $last_documents,
            'num_documents' => $num_documents,
            'logs' => $logs,
            'page_title' => $page_title,
            'cur_symbol' => $cur_symbol->symbol
        ]);
    }

    public function search(Request $request) {
        $page_title = "Search";
        $search_param = $request->input('q');
        $user_id = Auth::user()->id;
        $leads = Lead::raw(function($collection) use($search_param, $user_id)
        {
            return $collection->aggregate([
                ['$project' => [
                    'name' => [
                        '$concat' => ['$first_name', ' ', '$middle_name', ' ', '$last_name']
                    ],
                    "first_name" => 1,
                    "last_name" => 1,
                    "middle_name" => 1,
                    "user_id" => 1,
                    "profile_image" => 1
                ]],
                ['$match' => [
                    'name' => ['$regex' => '.*' . $search_param . '.*'],
                    'user_id' => $user_id
                ]],
            ]);
        });
        
        $customers = Customer::raw(function($collection) use($search_param, $user_id)
        {
            return $collection->aggregate([
                ['$project' => [
                    'name' => [
                        '$concat' => ['$first_name', ' ', '$middle_name', ' ', '$last_name']
                    ],
                    "first_name" => 1,
                    "last_name" => 1,
                    "middle_name" => 1,
                    "user_id" => 1,
                    "profile_image" => 1
                ]],
                ['$match' => [
                    'name' => ['$regex' => '.*' . $search_param . '.*'],
                    'user_id' => $user_id
                ]],
        ]);
        });
        $inventories = Inventory::where('user_id', Auth::user()->id)->where("full_name", "LIKE", "%" . $search_param . "%")->get();
        $locations = Location::where('user_id', Auth::user()->id)->where("name", "LIKE", "%" . $search_param . "%")->where('is_deleted', '!=', 1)->get();
        $users = User::with(['phone_number_details'])
            ->where('dealer_id', Auth::user()->id)->where("name", "LIKE", "%" . $search_param . "%")->get();

        $documents = Document::where('user_id', Auth::user()->id)->where("original_name", "LIKE", "%" . $search_param . "%")->where('is_deleted', '!=', 1)->get();
        return view('search', [
            'leads' => $leads,
            'customers' => $customers,
            'locations' => $locations,
            'inventories' => $inventories,
            'page_title' => $page_title,
            'users' => $users,
            'documents' => $documents,
            'search_param' => $search_param
        ]);
    }
}