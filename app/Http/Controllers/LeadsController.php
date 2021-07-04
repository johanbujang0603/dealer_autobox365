<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Customer;
use App\Models\CustomerDeal;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\LeadTag;
use App\Models\Lead;
use App\Models\LeadEmail;
use App\Models\LeadPhoneNumber;
use App\Models\Location;
use App\Models\SystemLog;
use App\Models\CarSpecification;
use App\Models\CarSpecificationValue;
use App\Models\CustomerEmail;
use App\Models\CustomerPhoneNumber;
use App\Models\LeadInterest;
use App\Models\LeadStatus;
use App\Models\VehicleType;
use App\Models\Transmission;
use App\Models\Transaction;
use App\Models\Document;
use App\User;
use PragmaRX\Countries\Package\Countries;
use Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use MongoDB\BSON\ObjectId;

class LeadsController extends Controller
{
    //
    public function dashboard()
    {

        $color = ['#29B6F6', '#66BB6A', '#EF5350', '#51FA32', '#A0C15F'];
        $page_title = "Leads Dashboard";
        $phone_number_leads = LeadPhoneNumber::groupBy('lead_id')->pluck('o_lead_id')->toArray();

        $no_phone_number_leads = Lead::whereNotIn('_id', $phone_number_leads)
            ->where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })
            ->where('is_converted', 0)->where('is_deleted', '!=', 1)->count();
        
        if (Auth::user()->type != 'Dealer') {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $user_id = Auth::user()->id;
            $no_phone_number_leads = Lead::raw(function($collection) use($locations, $user_id, $phone_number_leads)
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
                        'is_converted' => 1,
                        'is_deleted' => 1,
                        'tags' => 1,
                        'profile_image' => 1,
                        'profile_image_source' => 1,
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
                        'is_converted' => ['$first' => '$is_converted'],
                        'is_deleted' => ['$first' => '$is_deleted'],
                        'tags' => ['$first' => '$tags'],
                        'profile_image' => ['$first' => '$profile_image'],
                        'profile_image_source' => ['$first' => '$profile_image_source'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_converted' => 0],
                            ['is_deleted' => 0],
                            ['_id' => [ '$nin' => $phone_number_leads ]],
                        ]
                    ]],
                ]);
            })->count();
        }

        $email_leads = LeadEmail::groupBy('lead_id')->pluck('o_lead_id')->toArray();
        
        $no_email_leads = Lead::whereNotIn('_id', $email_leads)
            ->where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })
            ->where('is_converted', 0)->where('is_deleted', '!=', 1)->count();

        if (Auth::user()->type != 'Dealer') {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $user_id = Auth::user()->id;
            $no_email_leads = Lead::raw(function($collection) use($locations, $user_id, $email_leads)
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
                        'is_converted' => 1,
                        'is_deleted' => 1,
                        'tags' => 1,
                        'profile_image' => 1,
                        'profile_image_source' => 1,
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
                        'is_converted' => ['$first' => '$is_converted'],
                        'is_deleted' => ['$first' => '$is_deleted'],
                        'tags' => ['$first' => '$tags'],
                        'profile_image' => ['$first' => '$profile_image'],
                        'profile_image_source' => ['$first' => '$profile_image_source'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_converted' => 0],
                            ['is_deleted' => 0],
                            ['_id' => [ '$nin' => $email_leads ]],
                        ]
                    ]],
                ]);
            })->count();
        }

        
        $no_country_leads = Lead::whereNull('country_base_residence')
            ->where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })
            ->where('is_converted', 0)->where('is_deleted', '!=', 1)->count();
        
         
        if (Auth::user()->type != 'Dealer') {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $user_id = Auth::user()->id;
            $no_country_leads = Lead::raw(function($collection) use($locations, $user_id)
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
                        'is_converted' => 1,
                        'is_deleted' => 1,
                        'tags' => 1,
                        'profile_image' => 1,
                        'profile_image_source' => 1,
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
                        'profile_image' => ['$first' => '$profile_image'],
                        'profile_image_source' => ['$first' => '$profile_image_source'],
                        'country_base_residence' => ['$first' => '$country_base_residence'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_converted' => 0],
                            ['is_deleted' => 0],
                            ['country_base_residence' => null],
                        ]
                    ]],
                ]);
            })->count();
        }

        $logs = SystemLog::where('model', Lead::class)->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->take(10)->get();

        $total_leads = Lead::where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->where('is_converted', 0)->where('is_deleted', '!=', 1)->count();
        if (Auth::user()->type != 'Dealer') {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $total_leads = $this->total_leads_counts($locations);
        }

        $countries_chart_details = array();
        $cities_series = array();

        if (Auth::user()->type == 'Dealer') {
            $countries = Lead::where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->where('is_converted', 0)->where('is_deleted', '!=', 1)->groupBy('country_base_residence')->take(10)->pluck('country_base_residence');
    
            $cities = Lead::where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->where('is_converted', 0)->where('is_deleted', '!=', 1)->groupBy('city')->take(10)->pluck('city');
            

            foreach ($countries as $index => $country) {
                $count_by_country = Lead::where(function($q) {
                    $q->where('user_id', Auth::user()->id)
                    ->orWhere('dealer_id', Auth::user()->id);
                })->where('is_converted', 0)->where('is_deleted', '!=', 1)->where('country_base_residence', $country)->count();

                $countries_chart_details[] = array(
                    'label' => isset(Countries::where('cca2', $country)->first()->name) ? ucfirst(Countries::where('cca2', $country)->first()->name->official) : 'Unknown',
                    'value' => $count_by_country,
                    'color' => $color[$index % 3],
                );
            }
            $countries_chart_details = json_encode($countries_chart_details);
            
            foreach ($cities as $index => $city) {
                $count_by_city = Lead::where(function($q) {
                    $q->where('user_id', Auth::user()->id)
                    ->orWhere('dealer_id', Auth::user()->id);
                })->where('is_converted', 0)->where('city', $city)->where('is_deleted', '!=', 1)->count();
                $cities_series[] = array(
                    'value' => $count_by_city,
                    'label' => $city ?  ucfirst($city) : 'Unknown',
                    'color' => $color[$index % 3]
                );
            }
            $cities_chart_details = json_encode($cities_series);

            $maleLeads = Lead::where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->where('is_converted', 0)->where('is_deleted', '!=', 1)->where('gender', 'Male')->count();
            $femaleLeads = Lead::where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->where('is_converted', 0)->where('is_deleted', '!=', 1)->where('gender', 'Female')->count();
            $unknowLeads = Lead::where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->where('is_converted', 0)->where('is_deleted', '!=', 1)->where('gender', '!=', 'Female')->where('gender', '!=', 'Male')->count();

            $tag_chart_data = array();
            $tags = LeadTag::get();
            foreach ($tags as $tag) {
                $leads_count = Lead::where(function($q) {
                    $q->where('user_id', Auth::user()->id)
                    ->orWhere('dealer_id', Auth::user()->id);
                })->where("tags", "LIKE", "%" . $tag->_ID . "%")->where('is_converted', 0)->where('is_deleted', '!=', 1)->count();
                $tag_chart_data[] = array(
                    'label' => $tag->tag_name, 'value' =>
                    $leads_count, 'color' => $tag->color
                );
            }
            $unknown_tags = Lead::where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->where('is_converted', 0)->where('is_deleted', '!=', 1)->whereNull('tags')->count();
            if ($unknown_tags != 0) {
                $tag_chart_data[] = array(
                    'label' => 'Unknown', 'value' => $unknown_tags, 'color' => '#0F2A3A'
                );
            }
            $tag_chart_data = json_encode(($tag_chart_data));
            

            $statusList = LeadStatus::get();
            $status_chart_data = array();
            foreach ($statusList as $status) {
                $status_chart_data[] = array(
                    'label' => $status->status_name,
                    'color' => $status->color,
                    'value' => Lead::where(function($q) {
                        $q->where('user_id', Auth::user()->id)
                        ->orWhere('dealer_id', Auth::user()->id);
                    })->where('is_converted', 0)->where('is_deleted', '!=', 1)->where('status', $status->id)->count()
                );
            }
            $unknow_status = Lead::where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->where('is_converted', 0)->where('is_deleted', '!=', 1)->whereNull('status')->count();
            if ($unknow_status != 0 ){
                $status_chart_data[] = array(
                    'label' => 'Unknown',
                    'color' => '#0F2A3A',
                    'value' => $unknow_status
                );
            }
            $status_chart_data = json_encode($status_chart_data);
            
        }
        else {
            $user_id = Auth::user()->id;
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            
            $countries = Lead::raw(function($collection) use($locations, $user_id)
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
                        'is_converted' => 1,
                        'is_deleted' => 1,
                        'tags' => 1,
                        'profile_image' => 1,
                        'profile_image_source' => 1,
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
                        'profile_image' => ['$first' => '$profile_image'],
                        'profile_image_source' => ['$first' => '$profile_image_source'],
                        'country_base_residence' => ['$first' => '$country_base_residence'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_converted' => 0],
                            ['is_deleted' => 0],
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$country_base_residence',
                    ]]
                ]);
            });

            $cities = Lead::raw(function($collection) use($locations, $user_id)
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
                        'is_converted' => 1,
                        'is_deleted' => 1,
                        'tags' => 1,
                        'profile_image' => 1,
                        'profile_image_source' => 1,
                        'city' => 1
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
                        'profile_image' => ['$first' => '$profile_image'],
                        'profile_image_source' => ['$first' => '$profile_image_source'],
                        'city' => ['$first' => '$city'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_converted' => 0],
                            ['is_deleted' => 0],
                        ]
                    ]],
                    ['$group' => [
                        '_id' => '$city',
                    ]]
                ]);
            });

            foreach ($countries as $index => $ct) {
                $country = $ct['_id'];
                
                $count_by_country = Lead::raw(function($collection) use($locations, $user_id, $country)
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
                            'is_converted' => 1,
                            'is_deleted' => 1,
                            'tags' => 1,
                            'profile_image' => 1,
                            'profile_image_source' => 1,
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
                            'profile_image' => ['$first' => '$profile_image'],
                            'profile_image_source' => ['$first' => '$profile_image_source'],
                            'country_base_residence' => ['$first' => '$country_base_residence'],
                        ]],
                        ['$match' => [
                            '$and' => [
                                ['is_converted' => 0],
                                ['is_deleted' => 0],
                                ['country_base_residence' => $country]
                            ]
                        ]]
                    ]);
                })->count();
                $countries_chart_details[] = array(
                    'label' => isset(Countries::where('cca2', $country)->first()->name) ? ucfirst(Countries::where('cca2', $country)->first()->name->official) : 'Unknown',
                    'value' => $count_by_country,
                    'color' => $color[$index % 3],
                );
            }
            $countries_chart_details = json_encode($countries_chart_details);
            
            foreach ($cities as $index => $ct) {
                $city = $ct['_id'];
                $count_by_city = Lead::raw(function($collection) use($locations, $user_id, $city)
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
                            'is_converted' => 1,
                            'is_deleted' => 1,
                            'tags' => 1,
                            'profile_image' => 1,
                            'profile_image_source' => 1,
                            'city' => 1
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
                            'profile_image' => ['$first' => '$profile_image'],
                            'profile_image_source' => ['$first' => '$profile_image_source'],
                            'city' => ['$first' => '$city'],
                        ]],
                        ['$match' => [
                            '$and' => [
                                ['is_converted' => 0],
                                ['is_deleted' => 0],
                                ['city' => $city]
                            ]
                        ]]
                    ]);
                })->count();
                $cities_series[] = array(
                    'value' => $count_by_city,
                    'label' => $city ?  ucfirst($city) : 'Unknown',
                    'color' => $color[$index % 3]
                );
            }
            $cities_chart_details = json_encode($cities_series);

            
            $maleLeads = Lead::raw(function($collection) use($locations, $user_id)
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
                        'is_converted' => 1,
                        'is_deleted' => 1,
                        'tags' => 1,
                        'profile_image' => 1,
                        'profile_image_source' => 1,
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
                        'is_converted' => ['$first' => '$is_converted'],
                        'is_deleted' => ['$first' => '$is_deleted'],
                        'tags' => ['$first' => '$tags'],
                        'profile_image' => ['$first' => '$profile_image'],
                        'profile_image_source' => ['$first' => '$profile_image_source'],
                        'gender' => ['$first' => '$gender'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_converted' => 0],
                            ['is_deleted' => 0],
                            ['gender' => 'Male']
                        ]
                    ]]
                ]);
            })->count();

            $femaleLeads = Lead::raw(function($collection) use($locations, $user_id)
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
                        'is_converted' => 1,
                        'is_deleted' => 1,
                        'tags' => 1,
                        'profile_image' => 1,
                        'profile_image_source' => 1,
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
                        'is_converted' => ['$first' => '$is_converted'],
                        'is_deleted' => ['$first' => '$is_deleted'],
                        'tags' => ['$first' => '$tags'],
                        'profile_image' => ['$first' => '$profile_image'],
                        'profile_image_source' => ['$first' => '$profile_image_source'],
                        'gender' => ['$first' => '$gender'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_converted' => 0],
                            ['is_deleted' => 0],
                            ['gender' => 'Female']
                        ]
                    ]]
                ]);
            })->count();
            

            $unknowLeads = Lead::raw(function($collection) use($locations, $user_id)
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
                        'is_converted' => 1,
                        'is_deleted' => 1,
                        'tags' => 1,
                        'profile_image' => 1,
                        'profile_image_source' => 1,
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
                        'is_converted' => ['$first' => '$is_converted'],
                        'is_deleted' => ['$first' => '$is_deleted'],
                        'tags' => ['$first' => '$tags'],
                        'profile_image' => ['$first' => '$profile_image'],
                        'profile_image_source' => ['$first' => '$profile_image_source'],
                        'gender' => ['$first' => '$gender'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_converted' => 0],
                            ['is_deleted' => 0],
                            ['gender' => [ '$ne' => 'Female']],
                            ['gender' => [ '$ne' => 'Male']]
                        ]
                    ]]
                ]);
            })->count();

            
            $tag_chart_data = array();
            $tags = LeadTag::get();
            foreach ($tags as $tag) {
                $tag_id = $tag->_ID;
                $leads_count = Lead::raw(function($collection) use($locations, $user_id, $tag_id)
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
                            'is_converted' => 1,
                            'is_deleted' => 1,
                            'tags' => 1,
                            'profile_image' => 1,
                            'profile_image_source' => 1,
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
                            'profile_image' => ['$first' => '$profile_image'],
                            'profile_image_source' => ['$first' => '$profile_image_source'],
                            'gender' => ['$first' => '$gender'],
                        ]],
                        ['$match' => [
                            '$and' => [
                                ['is_converted' => 0],
                                ['is_deleted' => 0],
                                [ 'tags' => [ '$regex' => '.*' . $tag_id . '.*'] ],
                            ]
                        ]]
                    ]);
                })->count();

                $tag_chart_data[] = array(
                    'label' => $tag->tag_name, 'value' =>
                    $leads_count, 'color' => $tag->color
                );
            }
            
            $unknown_tags = Lead::raw(function($collection) use($locations, $user_id, $tag_id)
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
                        'is_converted' => 1,
                        'is_deleted' => 1,
                        'tags' => 1,
                        'profile_image' => 1,
                        'profile_image_source' => 1,
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
                        'profile_image' => ['$first' => '$profile_image'],
                        'profile_image_source' => ['$first' => '$profile_image_source'],
                        'gender' => ['$first' => '$gender'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_converted' => 0],
                            ['is_deleted' => 0],
                            ['tags' => [ '$type' => 10 ]],
                        ]
                    ]]
                ]);
            })->count();

            if ($unknown_tags != 0) {
                $tag_chart_data[] = array(
                    'label' => 'Unknown', 'value' => $unknown_tags, 'color' => '#0F2A3A'
                );
            }
            $tag_chart_data = json_encode(($tag_chart_data));

            
            $statusList = LeadStatus::get();
            $status_chart_data = array();
            foreach ($statusList as $status) {
                $status_id = $status->id;
                $value = Lead::raw(function($collection) use($locations, $user_id, $status_id)
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
                            'is_converted' => 1,
                            'is_deleted' => 1,
                            'status' => 1,
                            'profile_image' => 1,
                            'profile_image_source' => 1,
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
                            'profile_image' => ['$first' => '$profile_image'],
                            'profile_image_source' => ['$first' => '$profile_image_source'],
                            'gender' => ['$first' => '$gender'],
                        ]],
                        ['$match' => [
                            '$and' => [
                                ['is_converted' => 0],
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

            
            $unknow_status = Lead::raw(function($collection) use($locations, $user_id, $tag_id)
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
                        'is_converted' => 1,
                        'is_deleted' => 1,
                        'status' => 1,
                        'profile_image' => 1,
                        'profile_image_source' => 1,
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
                        'profile_image' => ['$first' => '$profile_image'],
                        'profile_image_source' => ['$first' => '$profile_image_source'],
                        'gender' => ['$first' => '$gender'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_converted' => 0],
                            ['is_deleted' => 0],
                            ['status' => [ '$type' => 10 ]],
                        ]
                    ]]
                ]);
            })->count();

            if ($unknow_status != 0 ){
                $status_chart_data[] = array(
                    'label' => 'Unknown',
                    'color' => '#0F2A3A',
                    'value' => $unknow_status
                );
            }
            $status_chart_data = json_encode($status_chart_data);

        }

        $gender_chart_data = json_encode(
            array(
                array('label' => 'Male', 'value' => $maleLeads, 'color' => '#03A9F4'), array('label' => 'Female', 'value' => $femaleLeads, 'color' => '#EC407A')
            )
        );
        if ($unknowLeads != 0) {
            $gender_chart_data = json_encode(
                array(
                    array('label' => 'Male', 'value' => $maleLeads, 'color' => '#03A9F4'), array('label' => 'Female', 'value' => $femaleLeads, 'color' => '#EC407A'),
                    array('label' => 'Unknown', 'value' => $unknowLeads, 'color' => '#7F1AF1')
                )
            );
        }
        $table_data = $this->ajax_load();

        
        return view(
            'leads.dashboard',
            compact(
                'logs',
                'no_phone_number_leads',
                'no_email_leads',
                'no_country_leads',
                'total_leads',
                'countries_chart_details',
                'cities_chart_details',
                'gender_chart_data',
                'tag_chart_data',
                'status_chart_data',
                'page_title',
                'table_data'));
    }

    public function index()
    {
        $page_title = 'Leads';
        $r_permission = Auth::user()->hasPermission('lead', 'read');
        $w_permission = Auth::user()->hasPermission('lead', 'write');
        $d_permission = Auth::user()->hasPermission('lead', 'delete');
        $json_data = json_encode(array(
            'permission' => array('read' => $r_permission, 'write' => $w_permission, 'delete' => $d_permission)
        ));
        return view('leads.index', compact('page_title', 'json_data'));
    }

    public function delete($id) {
        $lead = Lead::find($id);
        if ($lead) {
            $lead->is_deleted = 1;
            $log = new SystemLog;
            $log->user_id = Auth::user()->id;
            $log->action = 'deleted';
            $log->category = 'leads';
            $log->model = Lead::class;
            $log->model_id = $lead->id;
            $log->save();
            $lead->save();
            return redirect()->back()->with('success', 'You removed a listing successfully!');
        } else {
            abort(404);
        }
    }

    public function create()
    {
        $page_title = "Add Leads";
        
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
            ])->where('user_id', Auth::user()->id)->orWhere('dealer_id', Auth::user()->id);
        }
        else {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            if (empty($locations))
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
                ])->where('user_id', Auth::user()->id)->orWhereNull('location');
            else
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
                ])->whereIn('location', $locations)->orWhere('user_id', Auth::user()->id)->orWhereNull('location');
        }

        $inventories = $inventory_query->get();
        $tags = LeadTag::get();
        $status = LeadStatus::get();
        $cur_symbol = Currency::where('iso_code', config('app.currency'))->first();
        $cur_symbol = array('value' =>$cur_symbol->id, 'label' => $cur_symbol->currency . '(' . $cur_symbol->symbol . ')');
        $locations = Location::where('user_id', Auth::user()->id)->get();
        if (Auth::user()->type != 'Dealer') {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $locations = Location::whereIn('_id', $locations)->get();
        }
        $json_data = json_encode(array(
            'inventories' => $inventories,
            'tags' => $tags,
            'status' => $status,
            'users' => User::where('dealer_id', Auth::user()->id)->get(),
            'currencies' => Currency::all(),
            'locations' => $locations,
            'cur_currency' => $cur_symbol,
            'looking_to_price_currency' => $cur_symbol['value'],
        ));
        return view('leads.create', [
            'json_data' => $json_data,
            'page_title' => $page_title
        ]);
    }

    public function edit($id)
    {
        $page_title = 'Edit Lead';
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
            ])->where('user_id', Auth::user()->id)->orWhere('dealer_id', Auth::user()->id);
        }
        else {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            if (empty($locations))
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
                ])->where('user_id', Auth::user()->id)->orWhereNull('location');
            else
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
                ])->whereIn('location', $locations)->orWhere('user_id', Auth::user()->id)->orWhereNull('location');
        }

        $inventories = $inventory_query->get();
        $tags = LeadTag::get();
        $status = LeadStatus::get();

        $cur_symbol = Currency::where('iso_code', config('app.currency'))->first();
        $cur_symbol = array('value' =>$cur_symbol->id, 'label' => $cur_symbol->currency . '(' . $cur_symbol->symbol . ')');
        $locations = Location::where('user_id', Auth::user()->id)->get();
        if (Auth::user()->type != 'Dealer') {
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $locations = Location::whereIn('_id', $locations)->get();
        }
        $json_data = json_encode(array(
            'inventories' => $inventories,
            'tags' => $tags,
            'status' => $status,
            'users' => User::where('dealer_id', Auth::user()->id)->get(),
            'currencies' => Currency::all(),
            'locations' => $locations,
            'cur_currency' => $cur_symbol
        ));
        $lead = Lead::find($id);
        return view('leads.edit', [
            'lead' => $lead,
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
            $lead = Lead::find($post_data['id']);
        } else {
            $lead = new Lead();
            $lead->user_id = Auth::user()->id;
            $lead->is_converted = 0;
            $isNew = true;
        }
        if ($file = $request->file('profile_image')) {
            $path = 'images/leads/profile';

            $ext = strtolower($file->getClientOriginalExtension());
            $upload_file_name = (time() . '-' . uniqid()) . '.' . $file->getClientOriginalExtension();


            $file->move(public_path($path), $upload_file_name);
            $lead->profile_image =  $path . '/' . $upload_file_name;
        }


        if (Auth::user()->type != 'Dealer') {
            $lead->dealer_id = Auth::user()->dealer_id;
            $lead->assign_users = Auth::user()->id;
        }
        else 
            $lead->assign_users = "";
        $lead->first_name = isset($post_data['first_name']) ?  $post_data['first_name'] : null;
        $lead->last_name = isset($post_data['last_name']) ?  $post_data['last_name'] : null;
        $lead->date_of_birth = isset($post_data['date_of_birth']) ?  $post_data['date_of_birth'] : null;
        $lead->looking_to_price_currency = isset($post_data['looking_to_price_currency']) ?  $post_data['looking_to_price_currency'] : null;
        $lead->facebook_url = isset($post_data['facebook_url']) ?  $post_data['facebook_url'] : null;
        $lead->middle_name = isset($post_data['middle_name']) ?  $post_data['middle_name'] : "";
        $lead->gender = isset($post_data['gender']) ?  $post_data['gender'] : null;
        $lead->looking_to_price_from = isset($post_data['looking_to_price_from']) ?  floatval($post_data['looking_to_price_from']) : null;
        $lead->looking_to_price_to = isset($post_data['looking_to_price_to']) ?  floatval($post_data['looking_to_price_to']) : null;
        $lead->timeframe_to_sell = isset($post_data['timeframe_to_sell']) ?  floatval($post_data['timeframe_to_sell']) : null;
        $lead->timeframe_to_buy = isset($post_data['timeframe_to_buy']) ?  floatval($post_data['timeframe_to_buy']) : null;
        $lead->timeframe_to_buy_duration = isset($post_data['timeframe_to_buy_duration']) ?  $post_data['timeframe_to_buy_duration'] : null;
        $lead->timeframe_to_sell_duration = isset($post_data['timeframe_to_sell_duration']) ?  $post_data['timeframe_to_sell_duration'] : null;
        $lead->address = isset($post_data['address']) ?  $post_data['address'] : null;
        $lead->city = isset($post_data['city']) ?  $post_data['city'] : null;
        $lead->postal_code = isset($post_data['postal_code']) ?  $post_data['postal_code'] : null;
        $lead->civility = isset($post_data['civility']) ?  $post_data['civility'] : null;
        $lead->country_base_residence = isset($post_data['country_base_residence']) ?  $post_data['country_base_residence'] : null;
        $lead->looking_to = isset($post_data['looking_to']) ?  implode(',', $post_data['looking_to']) : null;
        $lead->status = isset($post_data['status']) ?  $post_data['status']['value'] : null;
        $lead->assign_locations = "";
        $lead->is_deleted = 0;
        $lead->is_converted = 0;
        $tags =  $post_data['tags'] ?  $post_data['tags'] : array();
        $save_tags = array();
        if (isset($tags) && $tags) {
            foreach ($tags as $tag) {
                $save_tags[] = $tag['value'];
            }
        }

        $lead->tags = implode(',', $save_tags);
        $lead->save();

        $log = new SystemLog();
        $log->user_id = Auth::user()->id;
        $log->action = $isNew ? 'created' : 'updated';
        $log->category = 'leads';
        $log->model = Lead::class;
        $log->model_id = $lead->id;
        $log->save();

        $lead_id = $lead->id;
        $phone_numbers = isset($post_data['phone_numbers']) ? $post_data['phone_numbers'] : array();
        LeadPhoneNumber::where('lead_id', $lead_id)->delete();
        foreach ($phone_numbers as $phone_number) {
            if ($phone_number['valid'] == true) {
                $lead_phone_number = new LeadPhoneNumber();
                $lead_phone_number->lead_id = $lead_id;
                $lead_phone_number->o_lead_id = new ObjectId($lead_id);
                $lead_phone_number->valid = $phone_number['valid'];
                $lead_phone_number->number = $phone_number['number'];
                $lead_phone_number->mobile_no = $phone_number['mobile_no'];
                $lead_phone_number->local_format = $phone_number['local_format'];
                $lead_phone_number->international_format = $phone_number['international_format'];
                $lead_phone_number->country_prefix = $phone_number['country_prefix'];
                $lead_phone_number->country_code = $phone_number['country_code'];
                $lead_phone_number->country_name = $phone_number['country_name'];
                $lead_phone_number->location = $phone_number['location'];
                $lead_phone_number->carrier = $phone_number['carrier'];
                $lead_phone_number->line_type = $phone_number['line_type'];
                $lead_phone_number->intl_formmated_number = $phone_number['intl_formmated_number'];
                $lead_phone_number->messaging_apps = implode(",", $phone_number['messaging_apps']);
                $lead_phone_number->save();
            }
        }
        $emails = isset($post_data['emails']) ? $post_data['emails'] : array();
        LeadEmail::where('lead_id', $lead_id)->delete();
        foreach ($emails as $email) {
            if ($email['email'] != '') {
                $lead_email =  new LeadEmail();
                $lead_email->o_lead_id = new ObjectId($lead_id);
                $lead_email->lead_id = $lead_id;
                $lead_email->valid = $email['valid'];
                $lead_email->email = $email['email'];
                $lead_email->save();
            }
        }
        return array(
            'status' => 'success',
            'lead_id' => $lead_id,
            'looking_to' => $post_data['looking_to'],
            'option' => $isNew ? 'created' : 'updated'
        );
    }

    public function ajax_phone_exist(Request $request) {
//        $post_data = $request->post_data;
//        $post_data = json_decode($post_data, true);
//        $international_number = $post_data['phone_number']['international_format'];
//        $leads = Lead::with(['phone_number_details'])->where('is_deleted', '!=', 1)
//            ->where('is_converted', 0)->where('user_id', Auth::user()->id)->get();
//        $cnt = 0;
//        foreach ($leads as $lead) {
//            foreach ($lead->phone_number_details as $num) {
//                if ($num->international_format == $international_number) {
//                    $cnt = $lead->id;
//                    break;
//                }
//            }
//            if ($cnt = 1) {
//                break;
//            }
//        }
//        $is_exist = $cnt == 0 ? false : true;
//        $lead_id = $is_exist == 0 ? null : LeadPhoneNumber::where('international_format', $international_number)->first()->lead_id;
//        return array(
//            'status' => 'success',
//            'is_exist' => $is_exist,
//            'lead_id' => $lead_id
//        );
    }

    public function ajax_load()
    {
        $option = 'all';
        $data = array();
        if (Auth::user()->type == 'Dealer') {
            $leads = Lead::with(['phone_number_details', 'email_details'])->where(function($q) {
                $q->where('user_id', Auth::user()->id)
                ->orWhere('dealer_id', Auth::user()->id);
            })->where('is_converted', 0)->where('is_deleted', '!=', 1)->get();
        }
        else {
            $user_id = Auth::user()->id;
            $locations = Auth::user()->locations;
            $locations = explode(',', $locations);
            $leads = Lead::raw(function($collection) use($locations, $user_id)
            {
                return $collection->aggregate([
                    ['$addFields' => [
                        'location' => [ '$split' => [ '$assign_locations', ',' ] ] 
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
                        'is_converted' => 1,
                        'is_deleted' => 1,
                        "tags" => 1,
                        "profile_image" => 1,
                        "profile_image_source" => 1,
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
                        'profile_image' => ['$first' => '$profile_image'],
                        'profile_image_source' => ['$first' => '$profile_image_source'],
                    ]],
                    ['$match' => [
                        '$and' => [
                            ['is_converted' => 0],
                            ['is_deleted' => 0]
                        ]
                    ]],
                    ['$lookup' => [
                        'from' => 'lead_emails',
                        'foreignField' => 'o_lead_id',
                        'localField' => '_id',
                        'as' => 'email_details',
                    ]],
                    ['$lookup' => [
                        'from' => 'lead_phone_numbers',
                        'foreignField' => 'o_lead_id',
                        'localField' => '_id',
                        'as' => 'phone_number_details',
                    ]],
                ]);
            });
        }
        foreach ($leads as $lead) {
            $phone_numbers = '';
            foreach ($lead->phone_number_details as $phone_number) {
                $phone_numbers .= $phone_number->intl_formmated_number . '<br/>';
            }
            $emails = '';
            foreach ($lead->email_details as $email) {
                $emails .= $email->email . '<br/>';
            }
            $tag_str = '';
            if ($lead->tags) {
                $tags = explode(',', $lead->tags);
                foreach ($tags as $tag_id) {
                    $tag = LeadTag::find($tag_id);
                    if ($tag) {
                        $tag_str .= "<span class=\"truncate py-1 px-2 rounded-full text-xs text-white font-medium bg-$tag->color\">$tag->tag_name</span>";
                    }
                }
            }

            $action_str =     '<div class="dropdown relative">
                <button class="dropdown-toggle button inline-block"><i class="icon-menu7"></i></button>
                <div class="dropdown-box mt-10 absolute w-48 top-0 right-0 z-20"><div class="dropdown-box__content box p-2">';
            
            if (Auth::user()->hasPermission('lead', 'write'))
                $action_str .='<a href="' . route('leads.edit', $lead->id) . '" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md cursor-pointer"><i class="icon-pencil mr-2"></i> Edit</a>';
            if (Auth::user()->hasPermission('lead', 'read'))
                $action_str .='<a href="' . route('leads.view', $lead->id) . '" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md cursor-pointer"><i class="icon-eye mr-2"></i> View</a>';
            if (Auth::user()->hasPermission('lead', 'delete'))
                $action_str .='<a class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md cursor-pointer delete-customer" onclick="confirmRemove(\''. $lead->profile_image_src .'\', \'' . $lead->name . '\', \'' .$lead->id . '\')"><i class="icon-bin mr-2"></i> Delete </a>';
            $action_str .= '
                         </div>
                </div>
            </div>
            ';
            $data[] = array(
                "<img
                    class=\"rounded-full ml-auto\" width=\"60\" height=\"60\"
                    src='" . $lead->profile_image_src . "'/>",
                $lead->name,
                $phone_numbers,
                $emails,
                $tag_str, $action_str
            );
        }
        return $data;
    }

    public function ajax_load_detail($id)
    {
        $lead = Lead::with(['phone_number_details', 'email_details'])->find($id);
        $tag_array  = array();
        if ($lead->tags) {
            $tags = explode(",", $lead->tags);
            foreach ($tags as $tag) {
                $tag_array[] = array(
                    'label' => LeadTag::find($tag) ? LeadTag::find($tag)->tag_name : '', 'value' => $tag
                );
            }
        }
        $c_status = null;
        if ($lead->status)
            $c_status = array('label' => LeadStatus::find($lead->status)->status_name, 'value' => $lead->status);

        $cur_symbol = Currency::where('iso_code', config('app.currency'))->first();
        $cur_symbol = array('value' =>$cur_symbol->id, 'label' => $cur_symbol->currency . '(' . $cur_symbol->symbol . ')');
        $selected_looking_to_price_currency = $lead->looking_to_price_currency ? array('label' => Currency::find($lead->looking_to_price_currency)->currency . "(" . Currency::find($lead->looking_to_price_currency)->symbol . ")", 'value' => $lead->looking_to_price_currency) : $cur_symbol;
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
            'looking_to_price_from' => $lead->looking_to_price_from,
            'looking_to_price_to' => $lead->looking_to_price_to,
            'timeframe_to_sell' => $lead->timeframe_to_sell,
            'timeframe_to_buy' => $lead->timeframe_to_buy,
            'timeframe_to_buy_duration' => $lead->timeframe_to_buy_duration,
            'timeframe_to_sell_duration' => $lead->timeframe_to_sell_duration,
            'looking_to_price_currency' => $lead->looking_to_price_currency,
            'selected_looking_to_price_currency' => $selected_looking_to_price_currency,
            'civility' => $lead->civility,
            'country_base_residence' => $lead->country_base_residence,
            'select_country_base_residence' => $lead->country_base_residence ? array('label' => $lead->country_base_residence, 'value' => $lead->country_base_residence) : null,
            'new_tags' => $lead->tags ? $tag_array : [],
            'status' => $lead->status ? $c_status : null,
            'profile_image_src' => $lead->profile_image_src,
            'profile_image' => $lead->profile_image,
            'phone_numbers' => $lead->phone_number_details,
            'emails' => $lead->email_details,
        );
    }

    public function convert(Request $request)
    {
        $lead = Lead::find($request->id);
        $deals = $request->deals;
        $lead->is_converted = 1;
        $lead->converted_at = date('Y-m-d H:i:s');
        $lead->save();

        $customer = new Customer();
        // $customer->fill($lead->toArray());
        foreach ($lead->toArray() as $key => $value) {
            if ($key != '_id' || $key != 'id') {
                $customer->$key = $value;
            }
        }
        $customer->previous_lead_id = $lead->id;
        $customer->save();


        $phone_numbers = $lead->phone_number_details;
        foreach ($phone_numbers as $phone_number) {
            $customer_phone_number = new CustomerPhoneNumber();
            foreach ($phone_number->toArray() as $key => $value) {
                if ($key != '_id' || $key != 'id') {
                    $customer_phone_number->$key = $value;
                }
            }
            $customer_phone_number->customer_id = $customer->id;
            $customer_phone_number->save();
        }
        $emails = $lead->email_details;
        foreach ($emails as $email) {
            $customer_email = new CustomerEmail();
            foreach ($email->toArray() as $key => $value) {
                if ($key != '_id' || $key != 'id') {
                    $customer_email->$key = $value;
                }
            }
            $customer_email->customer_id = $customer->id;
            $customer_email->save();
        }
        foreach ($deals as $deal) {
            $deal_model = new CustomerDeal();
            $deal_model->lead_id = $lead->id;
            $deal_model->customer_id = $customer->id;
            $deal_model->conversion_date = $deal['date'];
            $deal_model->inventory_purchased = $deal['inventory'];
            $deal_model->purchased_price = $deal['price'];
            $deal_model->purchased_currency = $deal['currency'];
            $deal_model->sales_user = isset($deal['user']) ? $deal['user'] : null;
            $deal_model->sales_location = $deal['location'];
            $deal_model->notes = $deal['notes'];
            $deal_model->save();
            echo 'xxx';
            print_r($deal['attach_file']);exit;
            if ($file = $deal['attach_file']) {
                print_r($file);exit;
                $path = "documents/leads/$lead->id";
                $document = new Document();
                $document->original_name = $file->getClientOriginalName();

                $ext = strtolower($file->getClientOriginalExtension());
                $size = $file->getSize();
                $upload_file_name = (time() . '-' . uniqid()) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path($path), $upload_file_name);
                $document->upload_path = $path . '/' . $upload_file_name;
                $document->kinds = 'lead';
                $document->parent_leads = $lead->id;
                $document->parent_customers = $customer->id;
                $document->user_id = Auth::user()->id;
                $document->tags = '';
                $document->type = $ext;
                $document->size = $size;
                $document->save();
            }

            if (isset($request->create_transaction) && $request->create_transaction) {
                $transaction_model = new Transaction();
                $transaction_model->user_id = Auth::user()->id;
                $transaction_model->inventory_id = $deal['inventory'];
                $transaction_model->date_of_sale = Carbon::parse($deal['date']);
                $transaction_model->lead = $lead->id;
                $transaction_model->customer_id = $customer->id;
                $transaction_model->date_of_estimate_delivery = null;
                $transaction_model->price = $deal['price'] ? (float) $deal['price'] : 0;
                $transaction_model->down_payment_price = isset($deal['down_payment_price']) ? (float) $deal['down_payment_price'] : 0;
                $transaction_model->financial_institution_name = null;
                $transaction_model->user = $deal['user'];
                $transaction_model->location = $deal['location'];
                $transaction_model->currency = $deal['currency'];
                $transaction_model->notes = $deal['notes'];
                $transaction_model->finance = 'No';
                $transaction_model->save();
            }
        }
        return array(
            'status' => 'success'
        );
    }

    public function view($id)
    {
        $page_title = 'View Lead';
        $lead_id = $id;
        $lead = Lead::with(['currency_details', 'email_details'])->find($id);
        $email_cnt = LeadEmail::where('lead_id', $id)->where('email', '!=', '')->count();
        $logs = SystemLog::where('model', Lead::class)->where('model_id', $id)->get();
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
        $inventories = $inventory_query->where('is_deleted', '!=', 1)->get();
        $users = User::where('dealer_id', Auth::user()->id)->where('is_deleted', '!=', 1)->get();

        $tags = LeadTag::whereIn('user_id', [0, Auth::user()->id])->get();
        $users = User::where('dealer_id', Auth::user()->id)->get();

        $cur_symbol = Currency::where('iso_code', config('app.currency'))->first();
        $cur_symbol = array('value' =>$cur_symbol->id, 'label' => $cur_symbol->currency . '(' . $cur_symbol->symbol . ')');
        $json_data = json_encode(array(
            'inventories' => $inventories,
            'tags' => $tags,
            'lead' => $lead,
            'users' => $users,
            'currencies' => Currency::all(),
            'locations' => Location::where('user_id', Auth::user()->id)->get(),
            'vehicle_types' => VehicleType::all(),
            'transmissions' => Transmission::all(),
            'fuel_types' => CarSpecification::where('name', 'Fuel')->count() ? CarSpecificationValue::where('id_car_specification', CarSpecification::where('name', 'Fuel')->first()->id_car_specification)->groupBy('value')->orderBy('value')->get() : [],
            'body_types' => CarSpecification::where('name', 'Body Type')->count() ? CarSpecificationValue::where('id_car_specification', CarSpecification::where('name', 'Body Type')->first()->id_car_specification)->groupBy('value')->orderBy('value')->get() : [],
            'cur_currency' => $cur_symbol
        ));

        return view('leads.view', compact('lead', 'logs', 'json_data', 'users', 'page_title', 'email_cnt', 'customers', 'leads', 'locations', 'users', 'inventories', 'lead_id'));
    }


    public function getbyinterested(Request $request)
    {
        $query = LeadInterest::query();
        if (isset($request->vehicle_type) && $request->vehicle_type) {
            $query->where('vehicle_type', $request->vehicle_type);
        }
        if (isset($request->make) && $request->make) {
            $query->where('make', $request->make);
        }
        if (isset($request->model) && $request->model) {
            $query->where('model', $request->model);
        }
        if (isset($request->generation) && $request->generation) {
            $query->where('generation', $request->generation);
        }
        if (isset($request->serie) && $request->serie) {
            $query->where('serie', $request->serie);
        }
        if (isset($request->trim) && $request->trim) {
            $query->where('trim', $request->trim);
        }
        if (isset($request->equipment) && $request->equipment) {
            $query->where('equipment', $request->equipment);
        }
        if (isset($request->transmission) && $request->transmission) {
            $query->where('transmission', $request->transmission);
        }
        if (isset($request->color) && $request->color) {
            $query->where('color', $request->color);
        }


        $leadIds = $query->pluck('lead_id');
        Log::info($leadIds);
        return Lead::whereIn('_id', $leadIds)->get();
    }

    public function export()
    {
        $csvExporter = new \Laracsv\Export();
        $csvExporter->build(Lead::where('user_id', Auth::user()->id)->where('is_converted', 0)->get(), ['name', 'gender', 'address', 'city', 'country_base_residence', 'created_at']);
        $csvExporter->download('leads.csv');
    }

    public function filter(Request $request)
    {
        $page_number = $request->page_number;
        $perPage = 10;
        if (Auth::user()->type == 'Dealer') {
            $query = Lead::with(['phone_number_details', 'email_details'])
                    ->where(function($q) {
                        $q->where('user_id', Auth::user()->id)
                        ->orWhere('dealer_id', Auth::user()->id);
                    })->where('is_converted', 0)->where('is_deleted', '!=', 1)->orderBy('created_at', 'desc');
                
            return $query->paginate($perPage, ['*'], 'page', $page_number);
        }

        $locations = Auth::user()->locations;
        $locations = explode(',', $locations);
        $user_id = Auth::user()->id;

        $total_counts = $this->total_leads_counts($locations);

        $leads = Lead::raw(function($collection) use($locations, $user_id, $page_number, $perPage)
        {
            return $collection->aggregate([
                ['$addFields' => [
                    'location' => [ '$split' => [ '$assign_locations', ',' ] ] 
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
                    'is_converted' => 1,
                    'is_deleted' => 1,
                    "tags" => 1,
                    "profile_image" => 1,
                    "profile_image_source" => 1,
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
                    'profile_image' => ['$first' => '$profile_image'],
                    'profile_image_source' => ['$first' => '$profile_image_source'],
                ]],
                ['$match' => [
                    '$and' => [
                        ['is_converted' => 0],
                        ['is_deleted' => 0]
                    ]
                ]],
                ['$lookup' => [
                    'from' => 'lead_emails',
                    'foreignField' => 'o_lead_id',
                    'localField' => '_id',
                    'as' => 'email_details',
                ]],
                ['$lookup' => [
                    'from' => 'lead_phone_numbers',
                    'foreignField' => 'o_lead_id',
                    'localField' => '_id',
                    'as' => 'phone_number_details',
                ]],
                ['$skip' => ($page_number - 1) * $perPage],
                ['$limit' => $perPage],
            ]);
        });

        $res['data'] = $leads;
        $res['last_page'] = ceil($total_counts/$perPage);
        return $res;
    }

    public function add_email(Request $request) {
        $lead_id = $request->get('lead_id');
        $email = $request->get('email');
        $valid = $request->get('valid');
        $new_lead_email = new LeadEmail();
        $new_lead_email->lead_id = $lead_id;
        $new_lead_email->email = $email;
        $new_lead_email->valid = $valid;
        $new_lead_email->save();
        return redirect()->back()->with('success', 'You saved the email successfully!');
    }
    
    public function verify_email(Request $request) {
        $email = $request->post('email');
        $key = "pnP4WnQjPKE55cMulbHI9";
        $url = "https://apps.emaillistverify.com/api/verifyEmail?secret=".$key."&email=".urlencode($email)."&timeout=15";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );

        $response = curl_exec($ch);
        echo $response;
        curl_close($ch);
    }

    public function assign(Request $request, $id) {
        $lead_id = $id;
        // dd($request->all());exit;
        $locations = $request->locations;
        $users = $request->users;
        $lead = Lead::find($id);
        if ($locations != null) {
            $locations = implode(',', $locations);
            $lead->assign_locations = $locations;
        }
        else {
            $lead->assign_locations = "";
        }
        if ($users != null) {
            $users = implode(',', $users);
            $lead->assign_users = $users;
        }
        else {
            $lead->assign_users = "";
        }
        $lead->save();
        return redirect()->back()->with('success', "Assigned Successfully!");
    }

    public function total_leads_counts($locations) {
        
        $user_id = Auth::user()->id;
        
        $leads = Lead::raw(function($collection) use($locations, $user_id)
        {
            return $collection->aggregate([
                ['$addFields' => [
                    'location' => [ '$split' => [ '$assign_locations', ',' ] ] 
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
                    'is_converted' => 1,
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
                    'is_converted' => ['$first' => '$is_converted'],
                    'is_deleted' => ['$first' => '$is_deleted'],
                ]],
                ['$match' => [
                    '$and' => [
                        ['is_converted' => 0],
                        ['is_deleted' => 0]
                    ]
                ]],
            ]);
        });
        $leads = $leads->toArray();
        return count($leads);
    }
}
