<?php

namespace App\Http\Controllers;

use App\Models\CalendarEventType;
use Illuminate\Http\Request;
use Auth;

class CalendarEventTypeController extends Controller
{
    //
    public function index()
    {
        # code...
        $page_title = 'Calendar Event Types';
        $types = CalendarEventType::where('user_id', Auth::user()->id)->get();
        return view('calendar.types.index', compact('types', 'page_title'));
    }

    public function create()
    {
        # code...
        $page_title = 'Add Event Type';
        return view('calendar.types.create', compact('page_title'));
    }

    public function edit($id)
    {
        # code...
        $page_title = 'Edit Event Type';
        $type = CalendarEventType::find($id);
        return view('calendar.types.edit', compact('type', 'page_title'));
    }
    public function save(Request $request)
    {
        if (isset($request->id)) {
            $type = CalendarEventType::find($request->id);
        } else {
            $type = new CalendarEventType;
            $type->user_id = Auth::user()->id;
        }

        $type->type_name = $request->type_name;
        $type->color = $request->color;
        // $type->user_id = 0;
        $type->save();
        if ($request->ajax()) {
            return $type;
        }
        if (isset($request->id)) {
            return redirect(route('calendar.types.index'))->with('success', "You updated a calendar type successfully!");
        }
        return redirect()->back()->with('success', "You saved a calendar type successfully!");
    }

    public function delete($id)
    {
        if ($type = CalendarEventType::find($id)) {
            $type->delete();
            return redirect()->back()->with('success', 'You removed a type.');
        } else {
            abort(404);
        }
    }
}
