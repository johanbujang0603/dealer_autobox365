<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Location;
use Auth;
class ProfileController extends Controller
{
    //
    public function index(){
        $page_title = 'Profile';
        return view('profile.index', compact('page_title'));
    }
    public function edit(){
        $page_title = 'Edit Profile';
        $data = array();
        $roles = Role::where('user_id', Auth::user()->id)->get();
        $locations = Location::where('user_id', Auth::user()->id)->get();
        $data['roles'] = $roles;
        $data['locations'] = $locations;
        $json_data =  json_encode($data);
        return view('profile.edit', compact('json_data', 'page_title'));
    }
}
