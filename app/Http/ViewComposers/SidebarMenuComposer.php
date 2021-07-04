<?php
namespace App\Http\ViewComposers;

use App\Models\Inventory;
use App\Models\InventoryOption;
use App\Models\InventoryStatus;
use App\Models\InventoryTag;
use App\Models\Customer;
use App\Models\CustomerTag;
use App\User;
use App\Models\Location;
use App\Models\LocationType;
use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\LeadTag;
use App\Models\Calendar;
use App\Models\CalendarEventType;
use App\Models\Document;
use App\Models\DocumentTag;
use App\Models\Transaction;
use App\Models\Role;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Menu;

class SidebarMenuComposer {


    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('currentUser', Auth::user());
        if(Auth::user()){
            if( Auth::guard('web')->check()){
                $user_id = Auth::guard('web')->user()->id;
                $user_type = Auth::guard('web')->user()->type;
                $locations = Auth::guard('web')->user()->locations;
                $array_of_locations = explode(',', $locations);

                view()->share('menu_key', '');
                $published_inventories_count = Inventory::where('user_id', $user_id)->where('is_draft', 0)->where('is_deleted', 0)->count();
                $inventories_query = Inventory::query();
                $leads_query = Lead::query();
                $customer_query = Customer::query();
                $docuemnts_query = Document::query();

                if ($user_type == 'Dealer') {
                    $inventories_query->where(function($q) {
                        $q->where('user_id', Auth::user()->id)
                        ->orWhere('dealer_id', Auth::user()->id);
                    });
                    $leads_query->where(function($q) {
                        $q->where('user_id', Auth::user()->id)
                        ->orWhere('dealer_id', Auth::user()->id);
                    });
                    $customer_query->where(function($q) {
                        $q->where('user_id', Auth::user()->id)
                        ->orWhere('dealer_id', Auth::user()->id);
                    });
                    $docuemnts_query->where('user_id', $user_id);

                    $all_inventories_count = $inventories_query->count();

                    $all_lead_count = $leads_query->where('is_converted', 0)->where('is_deleted', '!=', 1)->count();
                    $all_customer_count = $customer_query->where('is_deleted', '!=', 1)->count();
                }
                else {
                    if (empty($array_of_locations))
                        $inventories_query->where('user_id', Auth::user()->id)->orWhereNull('location');
                    else
                        $inventories_query->whereIn('location', $array_of_locations)->orWhere('user_id', $user_id)->orWhereNull('location');
                    
                    $all_inventories_count = $inventories_query->count();
                    
                    $all_lead_count = Lead::raw(function($collection) use($array_of_locations, $user_id)
                    {
                        return $collection->aggregate([
                            ['$addFields' => [
                                'location' => [ '$split' => [ '$assign_locations', ',' ] ] 
                            ]],
                            ['$unwind' => '$location'],
                            ['$project' => [
                                'in_location' => [
                                  '$in' => ['$location', $array_of_locations]
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
                    })->count();
                    
                    $all_customer_count = Customer::raw(function($collection) use($array_of_locations, $user_id)
                    {
                        return $collection->aggregate([
                            ['$addFields' => [
                                'location' => [ '$split' => [ '$assign_locations', ',' ] ] 
                            ]],
                            ['$unwind' => '$location'],
                            ['$project' => [
                                'in_location' => [
                                '$in' => ['$location', $array_of_locations ]
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
                    
                    $customer_query->whereIn('location', $array_of_locations);
                    $docuemnts_query->whereIn('location', $array_of_locations);
                }
                
                $inventory_status_count = InventoryStatus::count();
                $inventory_tags_count = InventoryTag::count();
                $deleted_inventories_count = $inventories_query->where('is_deleted', 1)->count();
                $inventory_options_count = InventoryOption::where('user_id', $user_id)->count();
                
                $lead_tags_count = LeadTag::count();
                $lead_status_count = LeadStatus::count();
                $customer_tags_count = CustomerTag::count();
                $all_users_count = User::where('dealer_id', $user_id)->count();

                $all_locations_count = Location::where('user_id', $user_id)->count();
                $location_types_count = LocationType::count();
                $all_events_count = Calendar::where('created_by', $user_id)->count();
                $event_types_count = CalendarEventType::where('user_id', $user_id)->count();
                $all_documents_count = $docuemnts_query->where('is_deleted', '!=', 1)->count();
                $document_tags_count = DocumentTag::where('user_id', $user_id)->count();
                $all_transactions = Transaction::where('user_id', $user_id)->count();
                $all_roles = Role::where('user_id', $user_id)->count();

                view()->share('published_inventories', $published_inventories_count);
                view()->share('all_inventories', $all_inventories_count);
                view()->share('deleted_inventories', $deleted_inventories_count);
                view()->share('inventory_tags', $inventory_tags_count);
                view()->share('inventory_options', $inventory_options_count);
                view()->share('inventory_status_count', $inventory_status_count);
                view()->share('all_leads', $all_lead_count);
                view()->share('lead_tags', $lead_tags_count);
                view()->share('lead_status', $lead_status_count);
                view()->share('customer_tags', $customer_tags_count);
                view()->share('all_customers', $all_customer_count);
                view()->share('all_users', $all_users_count);
                view()->share('all_locations', $all_locations_count);
                view()->share('location_types', $location_types_count);
                view()->share('all_events', $all_events_count);
                view()->share('event_types', $event_types_count);
                view()->share('all_documents', $all_documents_count);
                view()->share('document_tags', $document_tags_count);
                view()->share('all_transactions', $all_transactions);
                view()->share('all_roles', $all_roles);
            }
        }
    }


}
