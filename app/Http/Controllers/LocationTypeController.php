<?php

namespace App\Http\Controllers;

use App\Models\LocationType;
use Illuminate\Http\Request;
use Auth;
class LocationTypeController extends Controller
{
    //
    public function index()
    {
        # code...
        $page_title = 'Location Type';    
        $type_list = LocationType::where('user_id',  Auth::user()->id)->get();
        return view('locations.types.index', compact('type_list', 'page_title'));
    }

    public function create()
    {
        # code...
        $page_title = 'Create Type';
        return view('locations\types\create', compact('page_title'));
    }

    public function edit ($id)
    {
        # code...
        $page_title = 'Edit Type';
        $type = LocationType::find($id);
        if($type){
            return view('locations\types\edit', compact('type', 'page_title'));
        }
        else{
            return abort(404);
        }

    }
    public function delete ($id)
    {
        # code...
        $type = LocationType::find($id);
        if($type){
           $type->delete();
           return redirect()->back()->with('success', 'You removed a type successfully!');
        }
        else{
            return abort(404);
        }

    }

    public function save(Request $request){
        $isCreate = false;
        if(isset($request->id)){
            $location_type = LocationType::find($request->id);
        }
        else{
            $isCreate = true;
            $location_type = new LocationType();
            $location_type->user_id = Auth::user()->id;
        }
        $location_type->type_name = $request->type_name;
        $location_type->color = $request->color;
        $location_type->save();
        if($isCreate){
            return redirect()->back()->with('success', 'You created a type successfully!');
        }
        else{
            return redirect(route('locations.types.index'))->with('success', 'You updated a type successfully!');
        }

    }
}
