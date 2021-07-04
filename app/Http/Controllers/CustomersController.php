<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Currency;
use App\Models\Inventory;
use App\Models\Location;
use App\Models\SystemLog;
use App\Models\VehicleType;
use App\Models\Transmission;
use App\Models\CarSpecification;
use App\Models\CarSpecificationValue;
use App\Models\Customer;
use App\Models\CustomerTag;
use App\Models\CustomerStatus;
use App\Models\CustomerPhoneNumber;
use App\Models\CustomerEmail;
use App\Models\Transaction;
use PragmaRX\Countries\Package\Countries;
use App\User;
use Auth;
use Carbon\Carbon;
use MongoDB\BSON\ObjectId;

class CustomersController extends Controller
{
    //
    public function dashboard()
    {
        $page_title = "Customers Dashboard";
        if (Auth::user()->type == 'Dealer')
            $total_customers = Customer::where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->count();
        else {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $user_id = Auth::user()->id;

            $total_customers = Customer::raw(function($collection) use($locations, $user_id)
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
                        'middle_name' => 1,
                        'assign_locations' => 1,
                        'user_id' => 1,
                        'is_deleted' => 1,
                    ]],
                    ['$match' => [
                        '$or' => [
                            [ 'assign_users' => [ '$regex' => '.*' . $user_id . '.*'] ],
                            [ 'in_location' => true ],
                            [ 'assign_locations' => '' ],
                            [ 'user_id' => $user_id ]
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$_id',
                        'first_name' => ['$first' => '$first_name'],
                        'last_name' => ['$first' => '$last_name'],
                        'middle_name' => ['$first' => '$middle_name'],
                        'assign_locations' => ['$first' => '$assign_locations'],
                        'in_location' => ['$first' => '$in_location'],
                        'is_deleted' => ['$first' => '$is_deleted'],
                        'tags' => ['$first' => '$tags'],
                        'profile_image_src' => ['$first' => '$profile_image_src'],
                        "profile_image" => ['$first' => '$profile_image'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_deleted' => 0]
                        ]
                    ]],
                ]);
            })->count();
        }

        $phone_number_customers = CustomerPhoneNumber::groupBy('customer_id')->pluck('o_cusomter_id')->toArray();

        $no_phone_number_customers = Customer::whereNotIn('_id', $phone_number_customers)
            ->where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->where('is_deleted', '!=', 1)->count();
        
        if (Auth::user()->type != 'Dealer') {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $user_id = Auth::user()->id;
            $no_phone_number_customers = Customer::raw(function($collection) use($locations, $user_id, $phone_number_customers)
            {
                return $collection->aggregate([
                    ['$addFields' => [
                        'location' => [ '$split' => [ '$assign_locations', ',' ] ] ,
                    ]],
                    ['$unwind' => '$location'],
                    ['$project' => [
                        'in_location' => [
                        '$in' => ['$location', $locations]
                        ],
                        'first_name' => 1,
                        'last_name' => 1,
                        'middle_name' => 1,
                        'assign_users' => 1,
                        'assign_locations' => 1,
                        'user_id' => 1,
                        'is_deleted' => 1,
                        '_id' => 1
                    ]],
                    ['$match' => [
                        '$or' => [
                            [ 'assign_users' => [ '$regex' => '.*' . $user_id . '.*'] ],
                            [ 'in_location' => true ],
                            [ 'assign_locations' => '' ],
                            [ 'user_id' => $user_id ]
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$_id',
                        'first_name' => ['$first' => '$first_name'],
                        'last_name' => ['$first' => '$last_name'],
                        'middle_name' => ['$first' => '$middle_name'],
                        'assign_locations' => ['$first' => '$assign_locations'],
                        'in_location' => ['$first' => '$in_location'],
                        'is_deleted' => ['$first' => '$is_deleted'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_deleted' => 0],
                            ['_id' => [ '$nin' => $phone_number_customers ]],
                        ]
                    ]],
                ]);
            })->count();
        }

        $email_customers = CustomerEmail::groupBy('customer_id')->pluck('o_cusomter_id')->toArray();
        $no_email_customers = Customer::whereNotIn('_id', $email_customers)
            ->where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })
            ->where('is_deleted', '!=', 1)->count();
        
        if (Auth::user()->type != 'Dealer') {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $user_id = Auth::user()->id;
            $no_email_customers = Customer::raw(function($collection) use($locations, $user_id, $email_customers)
            {
                return $collection->aggregate([
                    ['$addFields' => [
                        'location' => [ '$split' => [ '$assign_locations', ',' ] ] ,
                    ]],
                    ['$unwind' => '$location'],
                    ['$project' => [
                        'in_location' => [
                        '$in' => ['$location', $locations]
                        ],
                        'first_name' => 1,
                        'last_name' => 1,
                        'middle_name' => 1,
                        'assign_users' => 1,
                        'assign_locations' => 1,
                        'user_id' => 1,
                        'is_deleted' => 1,
                        '_id' => 1
                    ]],
                    ['$match' => [
                        '$or' => [
                            [ 'assign_users' => [ '$regex' => '.*' . $user_id . '.*'] ],
                            [ 'in_location' => true ],
                            [ 'assign_locations' => '' ],
                            [ 'user_id' => $user_id ]
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$_id',
                        'first_name' => ['$first' => '$first_name'],
                        'last_name' => ['$first' => '$last_name'],
                        'middle_name' => ['$first' => '$middle_name'],
                        'assign_locations' => ['$first' => '$assign_locations'],
                        'in_location' => ['$first' => '$in_location'],
                        'is_deleted' => ['$first' => '$is_deleted'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_deleted' => 0],
                            ['_id' => [ '$nin' => $email_customers ]],
                        ]
                    ]],
                ]);
            })->count();
        }

        $no_country_customers = Customer::whereNull('country_base_residence')
            ->where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->where('is_deleted', '!=', 1)->count();
        
         
        if (Auth::user()->type != 'Dealer') {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $user_id = Auth::user()->id;
            $no_country_customers = Customer::raw(function($collection) use($locations, $user_id)
            {
                return $collection->aggregate([
                    ['$addFields' => [
                        'location' => [ '$split' => [ '$assign_locations', ',' ] ] ,
                    ]],
                    ['$unwind' => '$location'],
                    ['$project' => [
                        'in_location' => [
                        '$in' => ['$location', $locations]
                        ],
                        'first_name' => 1,
                        'last_name' => 1,
                        'middle_name' => 1,
                        'assign_users' => 1,
                        'assign_locations' => 1,
                        'user_id' => 1,
                        'is_deleted' => 1,
                        'tags' => 1,
                        'country_base_residence' => 1
                    ]],
                    ['$match' => [
                        '$or' => [
                            [ 'assign_users' => [ '$regex' => '.*' . $user_id . '.*'] ],
                            [ 'in_location' => true ],
                            [ 'assign_locations' => '' ],
                            [ 'user_id' => $user_id ]
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$_id',
                        'first_name' => ['$first' => '$first_name'],
                        'last_name' => ['$first' => '$last_name'],
                        'middle_name' => ['$first' => '$middle_name'],
                        'assign_locations' => ['$first' => '$assign_locations'],
                        'in_location' => ['$first' => '$in_location'],
                        'is_converted' => ['$first' => '$is_converted'],
                        'is_deleted' => ['$first' => '$is_deleted'],
                        'tags' => ['$first' => '$tags'],
                        'country_base_residence' => ['$first' => '$country_base_residence'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_deleted' => 0],
                            ['country_base_residence' => null],
                        ]
                    ]],
                ]);
            })->count();
        }

        $logs = SystemLog::where('model', Customer::class)->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->take(10)->get();



        $countries_series = array();
        $cities_series = array();

        $tag_chart_data = array();
        $tags = CustomerTag::get();

        $statusList = CustomerStatus::get();
        $status_chart_data = array();

        if (Auth::user()->type == 'Dealer') {

            $total_counts_by_countries = Customer::where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->where('is_deleted', '!=', 1)->groupBy('country_base_residence')->count();

            $total_counts_by_cities = Customer::where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->whereNotNull('city')->groupBy('city')->count();
            
            $countries = Customer::where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->groupBy('country_base_residence')->take(5)->pluck('country_base_residence');

            $cities = Customer::where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->whereNotNull('city')->groupBy('city')->take(5)->pluck('city');

            foreach ($countries as $country) {
                $count_by_country = Customer::where(function($q) {
                    $q->where('user_id', Auth::user()->id)
                    ->orWhere('dealer_id', Auth::user()->id);
                })->where('country_base_residence', $country)->count();
                if (!isset($country))
                    $country_name = 'Unknown';
                else {
                    $country_name = ucfirst(Countries::where('cca2', $country)->first()->name->official);
                }
                $countries_series[] = array(
                    'count' => $count_by_country,
                    'name' => $country_name,
                    'percent' => $total_customers ? round(($count_by_country / $total_customers) * 100, 2) : 0
                );
            }
            foreach ($cities as $city) {
                $count_by_city = Customer::where(function($q) {
                    $q->where('user_id', Auth::user()->id)
                    ->orWhere('dealer_id', Auth::user()->id);
                })->where('city', $city)->count();
                $cities_series[] = array(
                    'count' => $count_by_city,
                    'name' => $city ?  ucfirst($city) : 'Unknown',
                    'percent' => $total_customers ? round(($count_by_city / $total_customers) * 100, 2) : 0
                );
            }
            
            $maleCustomers = Customer::where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->where('gender', 'Male')->count();
            $femaleCustomers = Customer::where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->where('gender', 'Female')->count();
            
            foreach ($tags as $tag) {
                $customers_count = Customer::where(function($q) {
                    $q->where('user_id', Auth::user()->id)
                    ->orWhere('dealer_id', Auth::user()->id);
                })->where("tags", "LIKE", "%" . $tag->_ID . "%")->where('is_deleted', '!=', 1)->count();

                $tag_chart_data[] = array(
                    'label' => $tag->tag_name, 'value' =>
                    $customers_count, 'color' => $tag->color
                );
            }
            foreach ($statusList as $status) {
                $status_chart_data[] = array(
                    'label' => $status->status_name,
                    'color' => $status->color,
                    'value' => Customer::where(function($q) {
                        $q->where('user_id', Auth::user()->id)
                        ->orWhere('dealer_id', Auth::user()->id);
                    })->where('is_deleted', '!=', 1)->where('status', $status->id)->count()
                );
            }
        }
        else {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $user_id = Auth::user()->id;
            $total_counts_by_countries = Customer::raw(function($collection) use($locations, $user_id)
            {
                return $collection->aggregate([
                    ['$addFields' => [
                        'location' => [ '$split' => [ '$assign_locations', ',' ] ] ,
                    ]],
                    ['$unwind' => '$location'],
                    ['$project' => [
                        'in_location' => [
                        '$in' => ['$location', $locations]
                        ],
                        'first_name' => 1,
                        'last_name' => 1,
                        'middle_name' => 1,
                        'assign_users' => 1,
                        'assign_locations' => 1,
                        'user_id' => 1,
                        'is_deleted' => 1,
                        'tags' => 1,
                        'country_base_residence' => 1
                    ]],
                    ['$match' => [
                        '$or' => [
                            [ 'assign_users' => [ '$regex' => '.*' . $user_id . '.*'] ],
                            [ 'in_location' => true ],
                            [ 'assign_locations' => '' ],
                            [ 'user_id' => $user_id ]
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$_id',
                        'first_name' => ['$first' => '$first_name'],
                        'last_name' => ['$first' => '$last_name'],
                        'middle_name' => ['$first' => '$middle_name'],
                        'assign_locations' => ['$first' => '$assign_locations'],
                        'in_location' => ['$first' => '$in_location'],
                        'is_converted' => ['$first' => '$is_converted'],
                        'is_deleted' => ['$first' => '$is_deleted'],
                        'tags' => ['$first' => '$tags'],
                        'country_base_residence' => ['$first' => '$country_base_residence'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_deleted' => 0],
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$country_base_residence'
                    ]]
                ]);
            })->count();
            
            $total_counts_by_cities = Customer::raw(function($collection) use($locations, $user_id)
            {
                return $collection->aggregate([
                    ['$addFields' => [
                        'location' => [ '$split' => [ '$assign_locations', ',' ] ] ,
                    ]],
                    ['$unwind' => '$location'],
                    ['$project' => [
                        'in_location' => [
                        '$in' => ['$location', $locations]
                        ],
                        'first_name' => 1,
                        'last_name' => 1,
                        'middle_name' => 1,
                        'assign_users' => 1,
                        'assign_locations' => 1,
                        'user_id' => 1,
                        'is_deleted' => 1,
                        'tags' => 1,
                        'country_base_residence' => 1,
                        'city' => 1,
                    ]],
                    ['$match' => [
                        '$or' => [
                            [ 'assign_users' => [ '$regex' => '.*' . $user_id . '.*'] ],
                            [ 'in_location' => true ],
                            [ 'assign_locations' => '' ],
                            [ 'user_id' => $user_id ]
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$_id',
                        'first_name' => ['$first' => '$first_name'],
                        'last_name' => ['$first' => '$last_name'],
                        'middle_name' => ['$first' => '$middle_name'],
                        'assign_locations' => ['$first' => '$assign_locations'],
                        'in_location' => ['$first' => '$in_location'],
                        'is_converted' => ['$first' => '$is_converted'],
                        'is_deleted' => ['$first' => '$is_deleted'],
                        'tags' => ['$first' => '$tags'],
                        'country_base_residence' => ['$first' => '$country_base_residence'],
                        'city' => ['$first' => '$city'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_deleted' => 0],
                            ['city' => [
                                '$exists' => true, 
                                '$ne' => null 
                            ]]
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$city'
                    ]]
                ]);
            })->count();

            
            $countries = Customer::raw(function($collection) use($locations, $user_id)
            {
                return $collection->aggregate([
                    ['$addFields' => [
                        'location' => [ '$split' => [ '$assign_locations', ',' ] ] ,
                    ]],
                    ['$unwind' => '$location'],
                    ['$project' => [
                        'in_location' => [
                        '$in' => ['$location', $locations]
                        ],
                        'first_name' => 1,
                        'last_name' => 1,
                        'middle_name' => 1,
                        'assign_users' => 1,
                        'assign_locations' => 1,
                        'user_id' => 1,
                        'is_deleted' => 1,
                        'tags' => 1,
                        'country_base_residence' => 1,
                        'city' => 1,
                    ]],
                    ['$match' => [
                        '$or' => [
                            [ 'assign_users' => [ '$regex' => '.*' . $user_id . '.*'] ],
                            [ 'in_location' => true ],
                            [ 'assign_locations' => '' ],
                            [ 'user_id' => $user_id ]
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$_id',
                        'first_name' => ['$first' => '$first_name'],
                        'last_name' => ['$first' => '$last_name'],
                        'middle_name' => ['$first' => '$middle_name'],
                        'assign_locations' => ['$first' => '$assign_locations'],
                        'in_location' => ['$first' => '$in_location'],
                        'is_converted' => ['$first' => '$is_converted'],
                        'is_deleted' => ['$first' => '$is_deleted'],
                        'tags' => ['$first' => '$tags'],
                        'country_base_residence' => ['$first' => '$country_base_residence'],
                        'city' => ['$first' => '$city'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_deleted' => 0],
                            ['country_base_residence' => [
                                '$exists' => true, 
                                '$ne' => null 
                            ]]
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$country_base_residence'
                    ]],
                    ['$limit' => 5],
                ]);
            });


            
            $cities = Customer::raw(function($collection) use($locations, $user_id)
            {
                return $collection->aggregate([
                    ['$addFields' => [
                        'location' => [ '$split' => [ '$assign_locations', ',' ] ] ,
                    ]],
                    ['$unwind' => '$location'],
                    ['$project' => [
                        'in_location' => [
                        '$in' => ['$location', $locations]
                        ],
                        'first_name' => 1,
                        'last_name' => 1,
                        'middle_name' => 1,
                        'assign_users' => 1,
                        'assign_locations' => 1,
                        'user_id' => 1,
                        'is_deleted' => 1,
                        'tags' => 1,
                        'country_base_residence' => 1,
                        'city' => 1,
                    ]],
                    ['$match' => [
                        '$or' => [
                            [ 'assign_users' => [ '$regex' => '.*' . $user_id . '.*'] ],
                            [ 'in_location' => true ],
                            [ 'assign_locations' => '' ],
                            [ 'user_id' => $user_id ]
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$_id',
                        'first_name' => ['$first' => '$first_name'],
                        'last_name' => ['$first' => '$last_name'],
                        'middle_name' => ['$first' => '$middle_name'],
                        'assign_locations' => ['$first' => '$assign_locations'],
                        'in_location' => ['$first' => '$in_location'],
                        'is_converted' => ['$first' => '$is_converted'],
                        'is_deleted' => ['$first' => '$is_deleted'],
                        'tags' => ['$first' => '$tags'],
                        'country_base_residence' => ['$first' => '$country_base_residence'],
                        'city' => ['$first' => '$city'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_deleted' => 0],
                            ['city' => [
                                '$exists' => true, 
                                '$ne' => null 
                            ]]
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$city'
                    ]],
                    ['$limit' => 5],
                ]);
            });

            foreach ($countries as $ct) {
                $country = $ct['_id'];
                $count_by_country = Customer::raw(function($collection) use($locations, $user_id, $country)
                {
                    return $collection->aggregate([
                        ['$addFields' => [
                            'location' => [ '$split' => [ '$assign_locations', ',' ] ] ,
                        ]],
                        ['$unwind' => '$location'],
                        ['$project' => [
                            'in_location' => [
                            '$in' => ['$location', $locations]
                            ],
                            'first_name' => 1,
                            'last_name' => 1,
                            'middle_name' => 1,
                            'assign_users' => 1,
                            'assign_locations' => 1,
                            'user_id' => 1,
                            'is_deleted' => 1,
                            'tags' => 1,
                            'country_base_residence' => 1,
                            'city' => 1,
                        ]],
                        ['$match' => [
                            '$or' => [
                                [ 'assign_users' => [ '$regex' => '.*' . $user_id . '.*'] ],
                                [ 'in_location' => true ],
                                [ 'assign_locations' => '' ],
                                [ 'user_id' => $user_id ]
                            ]
                        ]],
                        ['$group' => [
                            '_id' => '$_id',
                            'first_name' => ['$first' => '$first_name'],
                            'last_name' => ['$first' => '$last_name'],
                            'middle_name' => ['$first' => '$middle_name'],
                            'assign_locations' => ['$first' => '$assign_locations'],
                            'in_location' => ['$first' => '$in_location'],
                            'is_converted' => ['$first' => '$is_converted'],
                            'is_deleted' => ['$first' => '$is_deleted'],
                            'tags' => ['$first' => '$tags'],
                            'country_base_residence' => ['$first' => '$country_base_residence'],
                            'city' => ['$first' => '$city'],
                        ]],
                        ['$match' => [
                            '$and' => [
                                ['is_deleted' => 0],
                                ['country_base_residence' => $country],
                            ]
                        ]],
                    ]);
                })->count();
                if (!isset($country))
                    $country_name = 'Unknown';
                else {
                    $country_name = ucfirst(Countries::where('cca2', $country)->first()->name->official);
                }
                $countries_series[] = array(
                    'count' => $count_by_country,
                    'name' => $country_name,
                    'percent' => $total_customers ? round(($count_by_country / $total_customers) * 100, 2) : 0
                );
            }
            foreach ($cities as $ct) {
                $city = $ct['_id'];
                
                $count_by_city = Customer::raw(function($collection) use($locations, $user_id, $city)
                {
                    return $collection->aggregate([
                        ['$addFields' => [
                            'location' => [ '$split' => [ '$assign_locations', ',' ] ] ,
                        ]],
                        ['$unwind' => '$location'],
                        ['$project' => [
                            'in_location' => [
                            '$in' => ['$location', $locations]
                            ],
                            'first_name' => 1,
                            'last_name' => 1,
                            'middle_name' => 1,
                            'assign_users' => 1,
                            'assign_locations' => 1,
                            'user_id' => 1,
                            'is_deleted' => 1,
                            'tags' => 1,
                            'country_base_residence' => 1,
                            'city' => 1,
                        ]],
                        ['$match' => [
                            '$or' => [
                                [ 'assign_users' => [ '$regex' => '.*' . $user_id . '.*'] ],
                                [ 'in_location' => true ],
                                [ 'assign_locations' => '' ],
                                [ 'user_id' => $user_id ]
                            ]
                        ]],
                        ['$group' => [
                            '_id' => '$_id',
                            'first_name' => ['$first' => '$first_name'],
                            'last_name' => ['$first' => '$last_name'],
                            'middle_name' => ['$first' => '$middle_name'],
                            'assign_locations' => ['$first' => '$assign_locations'],
                            'in_location' => ['$first' => '$in_location'],
                            'is_converted' => ['$first' => '$is_converted'],
                            'is_deleted' => ['$first' => '$is_deleted'],
                            'tags' => ['$first' => '$tags'],
                            'country_base_residence' => ['$first' => '$country_base_residence'],
                            'city' => ['$first' => '$city'],
                        ]],
                        ['$match' => [
                            '$and' => [
                                ['is_deleted' => 0],
                                ['city' => $city],
                            ]
                        ]],
                    ]);
                })->count();
                
                $cities_series[] = array(
                    'count' => $count_by_city,
                    'name' => $city ?  ucfirst($city) : 'Unknown',
                    'percent' => $total_customers ? round(($count_by_city / $total_customers) * 100, 2) : 0
                );
            }
            
            
            $maleCustomers = Customer::raw(function($collection) use($locations, $user_id)
            {
                return $collection->aggregate([
                    ['$addFields' => [
                        'location' => [ '$split' => [ '$assign_locations', ',' ] ] ,
                    ]],
                    ['$unwind' => '$location'],
                    ['$project' => [
                        'in_location' => [
                        '$in' => ['$location', $locations]
                        ],
                        'first_name' => 1,
                        'last_name' => 1,
                        'middle_name' => 1,
                        'assign_users' => 1,
                        'assign_locations' => 1,
                        'user_id' => 1,
                        'is_deleted' => 1,
                        'tags' => 1,
                        'gender' => 1
                    ]],
                    ['$match' => [
                        '$or' => [
                            [ 'assign_users' => [ '$regex' => '.*' . $user_id . '.*'] ],
                            [ 'in_location' => true ],
                            [ 'assign_locations' => '' ],
                            [ 'user_id' => $user_id ]
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$_id',
                        'first_name' => ['$first' => '$first_name'],
                        'last_name' => ['$first' => '$last_name'],
                        'middle_name' => ['$first' => '$middle_name'],
                        'assign_locations' => ['$first' => '$assign_locations'],
                        'in_location' => ['$first' => '$in_location'],
                        'is_deleted' => ['$first' => '$is_deleted'],
                        'tags' => ['$first' => '$tags'],
                        'gender' => ['$first' => '$gender'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_deleted' => 0],
                            ['gender' => 'Male']
                        ]
                    ]]
                ]);
            })->count();

            $femaleCustomers = Customer::raw(function($collection) use($locations, $user_id)
            {
                return $collection->aggregate([
                    ['$addFields' => [
                        'location' => [ '$split' => [ '$assign_locations', ',' ] ] ,
                    ]],
                    ['$unwind' => '$location'],
                    ['$project' => [
                        'in_location' => [
                        '$in' => ['$location', $locations]
                        ],
                        'first_name' => 1,
                        'last_name' => 1,
                        'middle_name' => 1,
                        'assign_users' => 1,
                        'assign_locations' => 1,
                        'user_id' => 1,
                        'is_deleted' => 1,
                        'tags' => 1,
                        'gender' => 1
                    ]],
                    ['$match' => [
                        '$or' => [
                            [ 'assign_users' => [ '$regex' => '.*' . $user_id . '.*'] ],
                            [ 'in_location' => true ],
                            [ 'assign_locations' => '' ],
                            [ 'user_id' => $user_id ]
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$_id',
                        'first_name' => ['$first' => '$first_name'],
                        'last_name' => ['$first' => '$last_name'],
                        'middle_name' => ['$first' => '$middle_name'],
                        'assign_locations' => ['$first' => '$assign_locations'],
                        'in_location' => ['$first' => '$in_location'],
                        'is_deleted' => ['$first' => '$is_deleted'],
                        'tags' => ['$first' => '$tags'],
                        'gender' => ['$first' => '$gender'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_deleted' => 0],
                            ['gender' => 'Female']
                        ]
                    ]]
                ]);
            })->count();

            
            foreach ($tags as $tag) {
                $tag_id = $tag->id;
                $customers_count = Customer::raw(function($collection) use($locations, $user_id, $tag_id)
                {
                    return $collection->aggregate([
                        ['$addFields' => [
                            'location' => [ '$split' => [ '$assign_locations', ',' ] ] ,
                        ]],
                        ['$unwind' => '$location'],
                        ['$project' => [
                            'in_location' => [
                            '$in' => ['$location', $locations]
                            ],
                            'first_name' => 1,
                            'last_name' => 1,
                            'middle_name' => 1,
                            'assign_users' => 1,
                            'assign_locations' => 1,
                            'user_id' => 1,
                            'is_deleted' => 1,
                            'tags' => 1,
                        ]],
                        ['$match' => [
                            '$or' => [
                                [ 'assign_users' => [ '$regex' => '.*' . $user_id . '.*'] ],
                                [ 'in_location' => true ],
                                [ 'assign_locations' => '' ],
                                [ 'user_id' => $user_id ]
                            ]
                        ]],
                        ['$group' => [
                            '_id' => '$_id',
                            'first_name' => ['$first' => '$first_name'],
                            'last_name' => ['$first' => '$last_name'],
                            'middle_name' => ['$first' => '$middle_name'],
                            'assign_locations' => ['$first' => '$assign_locations'],
                            'in_location' => ['$first' => '$in_location'],
                            'is_converted' => ['$first' => '$is_converted'],
                            'is_deleted' => ['$first' => '$is_deleted'],
                            'tags' => ['$first' => '$tags'],
                            'gender' => ['$first' => '$gender'],
                        ]],
                        ['$match' => [
                            '$and' => [
                                ['is_deleted' => 0],
                                [ 'tags' => [ '$regex' => '.*' . $tag_id . '.*'] ],
                            ]
                        ]]
                    ]);
                })->count();
                $tag_chart_data[] = array(
                    'label' => $tag->tag_name, 'value' =>
                    $customers_count, 'color' => $tag->color
                );
            }

            foreach ($statusList as $status) {
                $status_id = $status->id;
                $value = Customer::raw(function($collection) use($locations, $user_id, $status_id)
                {
                    return $collection->aggregate([
                        ['$addFields' => [
                            'location' => [ '$split' => [ '$assign_locations', ',' ] ] ,
                        ]],
                        ['$unwind' => '$location'],
                        ['$project' => [
                            'in_location' => [
                            '$in' => ['$location', $locations]
                            ],
                            'first_name' => 1,
                            'last_name' => 1,
                            'middle_name' => 1,
                            'assign_users' => 1,
                            'assign_locations' => 1,
                            'user_id' => 1,
                            'is_deleted' => 1,
                            'status' => 1,
                        ]],
                        ['$match' => [
                            '$or' => [
                                [ 'assign_users' => [ '$regex' => '.*' . $user_id . '.*'] ],
                                [ 'in_location' => true ],
                                [ 'assign_locations' => '' ],
                                [ 'user_id' => $user_id ]
                            ]
                        ]],
                        ['$group' => [
                            '_id' => '$_id',
                            'first_name' => ['$first' => '$first_name'],
                            'last_name' => ['$first' => '$last_name'],
                            'middle_name' => ['$first' => '$middle_name'],
                            'assign_locations' => ['$first' => '$assign_locations'],
                            'in_location' => ['$first' => '$in_location'],
                            'is_converted' => ['$first' => '$is_converted'],
                            'is_deleted' => ['$first' => '$is_deleted'],
                            'status' => ['$first' => '$status'],
                            'gender' => ['$first' => '$gender'],
                        ]],
                        ['$match' => [
                            '$and' => [
                                ['is_deleted' => 0],
                                ['status' => $status_id],
                            ]
                        ]]
                    ]);
                })->count();

                $status_chart_data[] = array(
                    'label' => $status->status_name,
                    'color' => $status->color,
                    'value' => $value
                );
            }
        }

        $countries_chart_details = json_encode(
            array(
                'total_count' => $total_counts_by_countries,
                'counts_by_country' => $countries_series
            )
        );
        $cities_chart_details = json_encode(
            array(
                'total_count' => $total_counts_by_cities,
                'counts_by_city' => $cities_series
            )
        );

        $gender_chart_data = json_encode(
            array(
                array('label' => 'Male', 'value' => $maleCustomers, 'color' => '#03A9F4'), array('label' => 'Female', 'value' => $femaleCustomers, 'color' => '#EC407A')
            )
        );

        $tag_chart_data = json_encode(($tag_chart_data));

        $status_chart_data = json_encode($status_chart_data);
        $table_data = $this->getTableData();
        return view('customers.dashboard', compact(
            'page_title',
            'total_customers',
            'countries_chart_details',
            'cities_chart_details',
            'gender_chart_data',
            'tag_chart_data',
            'status_chart_data',
            'no_phone_number_customers',
            'no_email_customers',
            'no_country_customers',
            'table_data',
            'logs'));
    }

    public function index(Request $request)
    {
        $page_title = 'Customers';
        $data = $this->getTableData();
        
        return view('customers.index', compact('page_title', 'data'));
    }

    public function delete($id) {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->is_deleted = 1;
            $log = new SystemLog;
            $log->user_id = Auth::user()->id;
            $log->action = 'deleted';
            $log->category = 'customer';
            $log->model = Customer::class;
            $log->model_id = $customer->id;
            $log->save();
            $customer->save();
            return redirect()->back()->with('success', 'You removed a listing successfully!');
        } else {
            abort(404);
        }
    }

    public function edit($id)
    {
        $page_title = 'Edit Customer';
        $tags = CustomerTag::get();
        $locations = Location::where('user_id', Auth::user()->id)->where('is_deleted', '!=', 1)->get();
        if (Auth::user()->type != 'Dealer') {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $locations = Location::whereIn('_id', $locations)->where('is_deleted', '!=', 1)->get();
        }
        $json_data = json_encode(array(
            'tags' => $tags,
            'users' => User::where('dealer_id', Auth::user()->id)->get(),
            'currencies' => Currency::all(),
            'locations' => $locations
        ));
        $customer = Customer::find($id);
        return view('customers.edit', [
            'customer' => $customer,
            'json_data' => $json_data,
            'page_title' => $page_title
        ]);
    }

    public function ajaxLoad()
    {
        $option = 'all';
        $data = array();
        $customers = Customer::with(['phone_number_details', 'email_details'])
            ->where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->where('is_deleted', '!=', 1)->get();
        
        if (Auth::user()->type != "Dealer") {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $user_id = Auth::user()->id;
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
                        'middle_name' => 1,
                        'assign_locations' => 1,
                        'user_id' => 1,
                        'is_deleted' => 1,
                    ]],
                    ['$match' => [
                        '$or' => [
                            [ 'assign_users' => [ '$regex' => '.*' . $user_id . '.*'] ],
                            [ 'in_location' => true ],
                            [ 'assign_locations' => '' ],
                            [ 'user_id' => $user_id ]
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$_id',
                        'first_name' => ['$first' => '$first_name'],
                        'last_name' => ['$first' => '$last_name'],
                        'middle_name' => ['$first' => '$middle_name'],
                        'assign_locations' => ['$first' => '$assign_locations'],
                        'in_location' => ['$first' => '$in_location'],
                        'is_deleted' => ['$first' => '$is_deleted'],
                        'tags' => ['$first' => '$tags'],
                        'profile_image_src' => ['$first' => '$profile_image_src'],
                        "profile_image" => ['$first' => '$profile_image'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_deleted' => 0]
                        ]
                    ]],
                ]);
            });
            
        }
        foreach ($customers as $customer) {
            $phone_numbers = '';
            foreach ($customer->phone_number_details as $phone_number) {
                $phone_numbers .= $phone_number->intl_formmated_number . '<br/>';
            }
            $emails = '';
            foreach ($customer->email_details as $email) {
                $emails .= $email->email . '<br/>';
            }
            $tag_str = '';
            if ($customer->tags) {
                $tags = explode(',', $customer->tags);
                foreach ($tags as $tag_id) {
                    $tag = CustomerTag::find($tag_id);
                    if ($tag) {
                        $tag_str .= "<span class=\"badge bg-$tag->color\">$tag->tag_name</span>";
                    }
                }
            }
            $action_str =     '<div class="list-icons">
                <div class="list-icons-item dropdown">
                    <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu7"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">';
            if (Auth::user()->hasPermission('customer', 'write'))
                $action_str .='<a href="' . route('customers.edit', $customer->id) . '" class="dropdown-item"><i class="icon-pencil"></i> Edit Customer</a>';
            if (Auth::user()->hasPermission('customer', 'read'))
                $action_str .='<a href="' . route('customers.view', $customer->id) . '" class="dropdown-item"><i class="icon-eye"></i> View Customer</a>';
            if (Auth::user()->hasPermission('customer', 'delete'))
                $action_str .= '<a class="dropdown-item delete-customer" onclick="confirmRemove(\''. $customer->profile_image_src .'\', \'' . $customer->name . '\', \'' .$customer->id . '\')"><i class="icon-bin"></i> Delete Customer</a>';
            $action_str .='</div>
                </div>
            </div>
            ';
            $data[] = array(
                "<img
                    class=\"rounded-circle\" width=\"60\" height=\"60\"
                    src='" . $customer->profile_image_src . "'/>",
                $customer->name,
                $phone_numbers,
                $emails,
                $tag_str, $action_str
            );
        }
        return array(
            'data' => $data,
        );
    }

    public function ajaxLoadDetail($id)
    {
        $lead = Customer::with(['phone_number_details', 'email_details'])->find($id);
        $tag_array  = array();
        if ($lead->tags) {
            $tags = explode(",", $lead->tags);
            foreach ($tags as $tag) {
                $tag_array[] = array(
                    'label' => CustomerTag::find($tag) ? CustomerTag::find($tag)->tag_name : '', 'value' => $tag
                );
            }
        }
        return array(
            'id' => $lead->id,
            'first_name' => $lead->first_name,
            'last_name' => $lead->last_name,
            'date_of_birth' => $lead->date_of_birth,
            'facebook_url' => $lead->facebook_url,
            'middle_name' => $lead->middle_name,
            'address' => $lead->address,
            'city' => $lead->city,
            'postal_code' => $lead->postal_code,
            'looking_to' => $lead->looking_to ? explode(',', $lead->looking_to) : array(),
            'gender' => $lead->gender,
            'looking_to_price' => $lead->looking_to_price,
            'timeframe_to_sell' => $lead->timeframe_to_sell,
            'timeframe_to_buy' => $lead->timeframe_to_buy,
            'timeframe_to_buy_duration' => $lead->timeframe_to_buy_duration,
            'timeframe_to_sell_duration' => $lead->timeframe_to_sell_duration,
            'looking_to_price_currency' => $lead->looking_to_price_currency,
            'selected_looking_to_price_currency' => $lead->looking_to_price_currency ? array('label' => Currency::find($lead->looking_to_price_currency)->currency . "(" . Currency::find($lead->looking_to_price_currency)->symbol . ")", 'value' => $lead->looking_to_price_currency) : null,
            'civility' => $lead->civility,
            'country_base_residence' => $lead->country_base_residence,
            'select_country_base_residence' => $lead->country_base_residence ? array('label' => $lead->country_base_residence, 'value' => $lead->country_base_residence) : null,
            'tag' => $lead->tags ? $tag_array : null,
            'profile_image_src' => $lead->profile_image_src,
            'profile_image' => $lead->profile_image,
            'phone_numbers' => $lead->phone_number_details,
            'emails' => $lead->email_details,

        );
    }

    public function view($id)
    {
        $page_title = 'View Customer';
        $customer = Customer::find($id);
        $logs = SystemLog::where('model', Customer::class)->where('model_id', $id)->orderBy('created_at', 'desc')->get();

        $tags = CustomerTag::get();
        
        $locations = Location::where('user_id', Auth::user()->id)->where('is_deleted', '!=', 1)->get();
        
        if (Auth::user()->type != 'Dealer') {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $locations = Location::whereIn('_id', $locations)->where('is_deleted', '!=', 1)->get();
        }

        $users = User::where('dealer_id', Auth::user()->id)->where('is_deleted', '!=', 1)->get();

        $json_data = json_encode(array(
            'tags' => $tags,
            'customer' => $customer,
            'users' => User::where('dealer_id', Auth::user()->id)->get(),
            'currencies' => Currency::all(),
            'locations' => $locations,
            'vehicle_types' => VehicleType::all(),
            'transmissions' => Transmission::all(),
            'fuel_types' => CarSpecification::where('name', 'Fuel')->count() ? CarSpecificationValue::where('id_car_specification', CarSpecification::where('name', 'Fuel')->first()->id_car_specification)->groupBy('value')->orderBy('value')->get() : [],
            'body_types' => CarSpecification::where('name', 'Body Type')->count() ? CarSpecificationValue::where('id_car_specification', CarSpecification::where('name', 'Body Type')->first()->id_car_specification)->groupBy('value')->orderBy('value')->get() : [],
        ));

        $transaction_model = Transaction::where('customer_id', $id);
        $transactions = [];
        foreach($transaction_model as $transaction) {
            $name_field = 'No Inventory';
            $customer_name = 'No Customer';
            $user_name = 'No assigned users';
            $location_name = 'No assigned locations';

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
                $name_field = "<div class=\"d-flex align-items-center\">
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
                    $user_name = $user_str;
                } else {
                    $user_name = "No assigned users";
                }
            }
            $locations = $transaction->location;
            if ($locations && count($locations)) {
                $location_str = '';
                foreach ($locations as $location) {
                    $location_str .= '<p>' . $location['label'] . '</p>';
                }
                $location_name = $location_str;
            }
            $price = $transaction->price_with_currency;
            $date = Carbon::parse($transaction->date_of_sale)->diffForHumans();
            $action_str = `<div class="list-icons">
                <div class="list-icons-item dropdown">
                    <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu7"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                        <a href="' . route('transactions.edit', $transaction->id) . '" class="dropdown-item"><i class="icon-pencil"></i> Edit Listing</a>
                    </div>
                </div>
            </div>`;
            array_push($transactions, array(
                'name_field' => $name_field,
                'customer_name' =>  $customer_name,
                'user_name' => $user_name,
                'location_name' => $location_name,
                'price' => $price,
                'date' => $date,
                'action'=> $action_str
            ));
        }

        return view('customers.view', compact('customer', 'logs', 'json_data', 'page_title', 'users', 'locations', 'transactions'));
    }

    public function create()
    {
        # code...
        $page_title = 'Create Customer';
        
        $locations = Location::where('user_id', Auth::user()->id)->where('is_deleted', '!=', 1)->get();
        
        if (Auth::user()->type != 'Dealer') {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $locations = Location::whereIn('_id', $locations)->where('is_deleted', '!=', 1)->get();
        }

        $json_data = json_encode(array(
            'tags' => CustomerTag::get(),
            'users' => User::where('dealer_id', Auth::user()->id)->get(),
            'currencies' => Currency::all(),
            'locations' => $locations
        ));
        return view('customers.create', [
            'json_data' => $json_data,
            'page_title' => $page_title
        ]);
    }


    public function save(Request $request)
    {
        $isNew = false;
        $post_data = $request->post_data;
        $post_data = json_decode($post_data, true);
        if (isset($post_data['id']) && $post_data['id']) {
            $customer = Customer::find($post_data['id']);
        } else {
            $customer = new Customer();
            $customer->user_id = Auth::user()->id;
            $customer->is_converted = 0;
            $isNew = true;
        }
        if ($file = $request->file('profile_image')) {
            $path = 'images/customers/profile';

            $ext = strtolower($file->getClientOriginalExtension());
            $upload_file_name = (time() . '-' . uniqid()) . '.' . $file->getClientOriginalExtension();


            $file->move(public_path($path), $upload_file_name);
            $customer->profile_image =  $path . '/' . $upload_file_name;
        }


        if (Auth::user()->type != 'Dealer') {
            $customer->dealer_id = Auth::user()->dealer_id;
            $customer->assign_users = Auth::user()->id;
        }
        else 
            $customer->assign_users = "";
        $customer->assign_locations = "";
        $customer->first_name = isset($post_data['first_name']) ?  $post_data['first_name'] : null;
        $customer->last_name = isset($post_data['last_name']) ?  $post_data['last_name'] : null;
        $customer->date_of_birth = isset($post_data['date_of_birth']) ?  $post_data['date_of_birth'] : null;
        $customer->looking_to_price_currency = isset($post_data['looking_to_price_currency']) ?  $post_data['looking_to_price_currency'] : null;
        $customer->facebook_url = isset($post_data['facebook_url']) ?  $post_data['facebook_url'] : null;
        $customer->middle_name = isset($post_data['middle_name']) ?  $post_data['middle_name'] : "";
        $customer->gender = isset($post_data['gender']) ?  $post_data['gender'] : null;
        $customer->looking_to_price = isset($post_data['looking_to_price']) ?  floatval($post_data['looking_to_price']) : null;
        $customer->timeframe_to_sell = isset($post_data['timeframe_to_sell']) ?  floatval($post_data['timeframe_to_sell']) : null;
        $customer->timeframe_to_buy = isset($post_data['timeframe_to_buy']) ?  floatval($post_data['timeframe_to_buy']) : null;
        $customer->timeframe_to_buy_duration = isset($post_data['timeframe_to_buy_duration']) ?  $post_data['timeframe_to_buy_duration'] : null;
        $customer->timeframe_to_sell_duration = isset($post_data['timeframe_to_sell_duration']) ?  $post_data['timeframe_to_sell_duration'] : null;
        $customer->address = isset($post_data['address']) ?  $post_data['address'] : null;
        $customer->city = isset($post_data['city']) ?  $post_data['city'] : null;
        $customer->postal_code = isset($post_data['postal_code']) ?  $post_data['postal_code'] : null;
        $customer->civility = isset($post_data['civility']) ?  $post_data['civility'] : null;
        $customer->country_base_residence = isset($post_data['country_base_residence']) ?  $post_data['country_base_residence'] : null;
        $customer->looking_to = isset($post_data['looking_to']) ?  implode(',', $post_data['looking_to']) : null;
        $customer->is_deleted = 0;
        $tags =  $post_data['tag'] ?  $post_data['tag'] : array();
        $save_tags = array();
        if (isset($tags) && $tags != null) {
            foreach ($tags as $tag) {
                $save_tags[] = $tag['value'];
            }
            $customer->tags = implode(',', $save_tags);
        }
        else {
            $customer->tags = '';
        }



        $customer->save();

        $log = new SystemLog();
        $log->user_id = Auth::user()->id;
        $log->action = $isNew ? 'created' : 'updated';
        $log->category = 'customers';
        $log->model = Customer::class;
        $log->model_id = $customer->id;
        $log->save();

        $customer_id = $customer->id;
        $phone_numbers = isset($post_data['phone_numbers']) ? $post_data['phone_numbers'] : array();
        CustomerPhoneNumber::where('customer_id', $customer_id)->delete();
        foreach ($phone_numbers as $phone_number) {
            $customer_phone_number = new CustomerPhoneNumber();
            $customer_phone_number->customer_id = $customer_id;
            $customer_phone_number->o_customer_id = new ObjectId($customer_id);
            $customer_phone_number->valid = $phone_number['valid'];
            $customer_phone_number->number = $phone_number['number'];
            $customer_phone_number->mobile_no = $phone_number['mobile_no'];
            $customer_phone_number->local_format = $phone_number['local_format'];
            $customer_phone_number->international_format = $phone_number['international_format'];
            $customer_phone_number->country_prefix = $phone_number['country_prefix'];
            $customer_phone_number->country_code = $phone_number['country_code'];
            $customer_phone_number->country_name = $phone_number['country_name'];
            $customer_phone_number->location = $phone_number['location'];
            $customer_phone_number->carrier = $phone_number['carrier'];
            $customer_phone_number->line_type = $phone_number['line_type'];
            $customer_phone_number->messaging_apps = implode(",", $phone_number['messaging_apps']);
            $customer_phone_number->intl_formmated_number = $phone_number['intl_formmated_number'];
            $customer_phone_number->save();
        }
        // if (isset($post_data['removed_phone_numbers']) && count($post_data['removed_phone_numbers'])) {
        //     foreach ($post_data['removed_phone_numbers'] as $removed_phone) {
        //         CustomerPhoneNumber::find($removed_phone)->delete();
        //     }
        // }
        $emails = isset($post_data['emails']) ? $post_data['emails'] : array();
        CustomerEmail::where('customer_id', $customer_id)->delete();
        foreach ($emails as $email) {
            $customer_email =  new CustomerEmail();
            $customer_email->customer_id = $customer_id;
            $customer_email->o_customer_id = new ObjectId($customer_id);
            $customer_email->valid = $email['valid'];
            $customer_email->email = $email['email'];
            $customer_email->save();
        }
        // if (isset($post_data['removed_emails']) && count($post_data['removed_emails'])) {
        //     foreach ($post_data['removed_emails'] as $removed_email) {
        //         CustomerEmail::find($removed_email)->delete();
        //     }
        // }
        return array(
            'status' => 'success',
            'customer_id' => $customer_id,
            'option' => $isNew ? 'created' : 'updated'
        );
    }

    public function filter(Request $request)
    {
        $customers = Customer::query();
        $transactionQuery = Transaction::query();
        if (isset($request->inventory['value']) && $request->inventory['value']) {
            $purchased_inventory  = $request->inventory['value'];
            $transactionQuery->where('inventory_id', $purchased_inventory);
        }
        if (isset($request->date_range) && count($request->date_range)) {
            $purchased_timeline = $request->date_range;
            $transactionQuery->whereBetween('date_of_sale', array(
                Carbon::parse($purchased_timeline[0]), Carbon::parse($purchased_timeline[1])
            ));
        }
        $customerIds = $transactionQuery->groupBy('customer_id')->pluck('customer_id');
        return $customers->whereIn('_id', $customerIds)->get();
    }

    public function export()
    {
        $csvExporter = new \Laracsv\Export();
        $csvExporter->build(Customer::where('user_id', Auth::user()->id)->where('is_deleted', '!=', 1)->get(), ['name', 'gender', 'address', 'city', 'country_base_residence', 'created_at']);
        $csvExporter->download('customers.csv');
    }

    public function assign(Request $request, $id) {        
        $locations = $request->locations;
        $users = $request->users;
        $customer = Customer::find($id);
        if ($locations != null) {
            $locations = implode(',', $locations);
            $customer->assign_locations = $locations;
        }
        else {
            $customer->assign_locations = "";
        }
        if ($users != null) {
            $users = implode(',', $users);
            $customer->assign_users = $users;
        }
        else {
            $customer->assign_users = "";
        }
        $customer->save();

        return redirect()->back()->with('success', "Assigned Successfully!");
    }

    public function getTableData() {
        $data = array();
        $customers = Customer::with(['phone_number_details', 'email_details'])
            ->where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->where('is_deleted', '!=', 1)->get();
        
        if (Auth::user()->type != "Dealer") {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $user_id = Auth::user()->id;
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
                        'middle_name' => 1,
                        'assign_locations' => 1,
                        'user_id' => 1,
                        'is_deleted' => 1,
                    ]],
                    ['$match' => [
                        '$or' => [
                            [ 'assign_users' => [ '$regex' => '.*' . $user_id . '.*'] ],
                            [ 'in_location' => true ],
                            [ 'assign_locations' => '' ],
                            [ 'user_id' => $user_id ]
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$_id',
                        'first_name' => ['$first' => '$first_name'],
                        'last_name' => ['$first' => '$last_name'],
                        'middle_name' => ['$first' => '$middle_name'],
                        'assign_locations' => ['$first' => '$assign_locations'],
                        'in_location' => ['$first' => '$in_location'],
                        'is_deleted' => ['$first' => '$is_deleted'],
                        'tags' => ['$first' => '$tags'],
                        'profile_image_src' => ['$first' => '$profile_image_src'],
                        "profile_image" => ['$first' => '$profile_image'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_deleted' => 0]
                        ]
                    ]],
                ]);
            });
            
        }
        foreach ($customers as $customer) {
            $phone_numbers = '';
            foreach ($customer->phone_number_details as $phone_number) {
                $phone_numbers .= $phone_number->intl_formmated_number . '<br/>';
            }
            $emails = '';
            foreach ($customer->email_details as $email) {
                $emails .= $email->email . '<br/>';
            }
            $tag_str = '';
            if ($customer->tags) {
                $tags = explode(',', $customer->tags);
                foreach ($tags as $tag_id) {
                    $tag = CustomerTag::find($tag_id);
                    if ($tag) {
                        $tag_str .= "<span class=\"truncate py-1 px-2 rounded-full text-xs text-white font-medium bg-$tag->color\">$tag->tag_name</span>";
                    }
                }
            }
            $action_str =     '<div class="flex sm:justify-center items-center">';
            if (Auth::user()->hasPermission('customer', 'write'))
                $action_str .='<a href="' . route('customers.edit', $customer->id) . '" class="p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 mr-2"><i class="icon-pencil"></i></a>';
            if (Auth::user()->hasPermission('customer', 'read'))
                $action_str .='<a href="' . route('customers.view', $customer->id) . '" class="p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 mr-2"><i class="icon-eye"></i></a>';
            if (Auth::user()->hasPermission('customer', 'delete'))
                $action_str .= '<a href="javascript:;" class="delete-customer p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 mr-2" onclick="confirmRemove(\''. $customer->profile_image_src .'\', \'' . $customer->name . '\', \'' .$customer->id . '\')"><i class="icon-bin"></i></a>';
            $action_str .='</div>';
            $data[] = array(
                "<img
                    class=\"rounded-full m-auto\" width=\"60\" height=\"60\"
                    src='" . $customer->profile_image_src . "'/>",
                $customer->name,
                $phone_numbers,
                $emails,
                $tag_str, $action_str
            );
        }
        return $data;
    }
}