<?php

namespace App\Http\Controllers;

use App\Models\PermissionApp;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Auth;

class UserRolesController extends Controller
{
    //

    public function index()
    {
        $page_title = 'User Roles';
        $roles = Role::where('user_id', Auth::user()->id)->get();

        return view('users.roles.index', compact('roles', 'page_title'));
    }

    public function create()
    {
        # code...
        $page_title = 'Create Role';
        $permission_apps = PermissionApp::all();
        $json_data = json_encode(
            array(
                'permission_apps'  => $permission_apps
            )
        );
        return view("users.roles.create", compact('json_data', 'page_title'));
    }

    public function edit($id)
    {
        # code...
        $page_title = 'Edit Role';
        $permission_apps = PermissionApp::all();
        $json_data = json_encode(
            array(
                'permission_apps'  => $permission_apps
            )
        );

        return view("users.roles.edit", compact('json_data', 'id', 'page_title'));
    }

    public function ajax_load_detail($id)
    {
        $role = Role::find($id);
        $data = array(
            'id' => $id,
            'role_name' => $role->role_name,
            'permissions' => $role->permission_details
        );
        return $data;
    }
    public function save(Request $request)
    {
        $isNew = false;
        if (isset($request->id) && $request->id) {
            $role = Role::find($request->id);
        } else {
            $role = new Role();
            $role->user_id = Auth::user()->id;
            $isNew = true;
        }
        $role->role_name = $request->role_name;
        $role->save();
        $role_id = $role->id;
        $permissions =   $request->permissions;
        foreach ($permissions as $permission) {
            $permission_model = RolePermission::where('app_id', $permission['app_id'])->where('role_id', $role_id)->first();
            if (!$permission_model) {
                $permission_model = new RolePermission();
                $permission_model->app_id = $permission['app_id'];
                $permission_model->role_id = $role_id;
            }
            $permission_model->read = isset($permission['read']) ? $permission['read'] : 0;
            $permission_model->write = isset($permission['write']) ? $permission['write'] : 0;
            $permission_model->delete = isset($permission['delete']) ? $permission['delete'] : 0;
            $permission_model->save();
        }
        return  array(
            'status' => 'success',
            'role_id' => $role_id,
            'option' => $isNew ? 'created' : 'updated'
        );
    }

    public function delete($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->delete();
            return redirect()->back()->with('success', "You removed a role successfully!");
        } else {
            abort(404);
        }
    }
}
