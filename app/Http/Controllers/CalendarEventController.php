<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use App\Models\CalendarEventType;
use App\Models\Inventory;
use App\Models\Location;
use App\Models\Lead;
use App\Models\Customer;
use App\User;
use Illuminate\Http\Request;
use Auth;

class CalendarEventController extends Controller
{
    //

    public function save(Request $request)
    {
        if (isset($request->event_id) && $request->event_id) {
            $calendar = Calendar::find($request->event_id);
        } else {
            $calendar = new Calendar();


            $calendar->create_user_id = Auth::user()->id;
        }
        // $request->date = substr($request->date, 0, strpos($request->date, '('));
        // $calendar->datetime = date('Y-m-d H:i:s', strtotime($request->date));
        $calendar->datetime = $request->date;
        $calendar->notes = $request->notes;
        $calendar->kinds = $request->kinds;
        $calendar->type = $request->type;
        $calendar->title = $request->title;
        $modelclass = '';
        if ($request->kinds == 'lead') {
            $modelclass = Lead::class;
            $calendar->user_id = $request->users ? implode(',', $request->users) : '';
        } else if ($request->kinds == 'inventory') {
            $modelclass = Inventory::class;
        }
        $calendar->model_class = $modelclass;
        $calendar->model_id = $request->model_id;

        $calendar->users = $request->users;
        $calendar->created_by = Auth::user()->id;
        $calendar->locations = $request->locations;
        $calendar->leads = $request->leads;
        $calendar->customers = $request->customers;
        $calendar->save();
        return $calendar;
    }

    public function load(Request $request)
    {
        return Calendar::where('kinds', $request->kinds)->where('model_id', $request->model_id)->get();
    }

    public function detail(Request $request)
    {
        $calendar = Calendar::find($request->id);
        $users = User::whereIn('id', explode(',', $calendar->user_id))->get();
        return array(
            'calendar' => $calendar,
            'users' => $users
        );
    }

    public function delete(Request $request)
    {
        $id = $request->event_id;
        $calendar = Calendar::find($id);
        $calendar->delete();
    }

    public function index()
    {
        $page_title = 'Calendar Event';
        return view('calendar.index', compact('page_title'));
    }

    public function load_basic_data()
    {
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
        $inventories = $inventory_query->where('is_deleted', 1)->get()->toArray();
        $types = CalendarEventType::where('user_id', Auth::user()->id)->get();
        return array(
            'type_list' => $types,
            'inventories' => $inventories,
            'users' => User::where('dealer_id', Auth::user()->id)->get(),
            'events' => Calendar::where('created_by', Auth::user()->id)->get(),
            'locations' => Location::where('user_id', Auth::user()->id)->get(),
            'leads' => Lead::where('user_id', Auth::user()->id)->where('is_deleted', '!=', 1)->where('is_converted', 0)->get(),
            'customers' => Customer::where('user_id', Auth::user()->id)->get(),
        );
    }
}
