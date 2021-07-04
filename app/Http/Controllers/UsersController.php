<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\UserPhone;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    //
    public function dashboard()
    {
        
        $page_title = 'Users';
        $data = array();
        $users = User::with(['phone_number_details'])
            ->where('dealer_id', Auth::user()->id)->get();
        foreach ($users as $user) {
            $phone_numbers = '';
            foreach ($user->phone_number_details as $phone_number) {
                $phone_numbers .= $phone_number->intl_formmated_number . '<br/>';
            }
            $emails = '';

            $tag_str = '';
            $action_str =     '<div class="dropdown relative">
                <button class="dropdown-toggle button m-auto text-theme-4"><i class="icon-menu7"></i></button>
                    <div class="dropdown-box mt-10 absolute w-32 top-0 right-0 z-20"><div class="dropdown-box__content box p-2">';
            if (Auth::user()->hasPermission('customer', 'write'))
                $action_str .= '<a href="' . route('users.edit', $user->id) . '" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md"><i class="icon-pencil w-4 h-4 mr-2"></i> Edit User</a>';
            $action_str .='
                         </div>
                </div>
            </div>
            ';
            $data[] = array(
                "<img
                    class=\"rounded-full m-auto\" width=\"60\" height=\"60\"
                    src='" . $user->profile_image_src . "'/>",
                $user->full_name,
                $user->name,
                $user->email,
                $phone_numbers,
                $action_str
            );
        }

        return view('users.dashboard', compact('page_title', 'data'));
    }
    public function create()
    {
        $page_title = 'Create User';
        $data = array();
        $roles = Role::where('user_id', Auth::user()->id)->get();
        $locations = Location::where('user_id', Auth::user()->id)->get();
        $data['roles'] = $roles;
        $data['locations'] = $locations;
        $json_data =  json_encode($data);
        return view('users.create', compact('json_data', 'page_title'));
    }
    public function edit($id)
    {
        $page_title = 'Edit User';
        $data = array();
        $roles = Role::where('user_id', Auth::user()->id)->get();
        $locations = Location::where('user_id', Auth::user()->id)->get();
        $data['roles'] = $roles;
        $data['locations'] = $locations;
        $json_data =  json_encode($data);
        return view('users.edit', compact('json_data', 'id', 'page_title'));
    }

    public function ajax_load_details($id)
    {
        $user = User::with(['phone_number_details'])->find($id);
        $selected_location = array();
        if (isset($user->locations)) {
            $locations = explode(",", $user->locations);
            
            if (!empty($locations)) {
                foreach ($locations as $location) {
                    if (!empty($location)) {

                        $t_location = Location::find($location);
                        $selected_location[] = array(
                            'label' => $t_location->name, 'value' => $t_location->id
                        );
                    }
                }
            }
            else
            $selected_location = null;
        } else {
            $selected_location = null;
        }
        return array(
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'civility' => $user->civility,
            'gender' => $user->gender,
            'profile_image_src' => $user->profile_image_src,
            'profile_image' => $user->profile_image,
            'phone_numbers' => $user->phone_number_details,
            'email' => $user->email,
            'name' => $user->name,
            'role' => $user->role,
            'selected_role' => $user->role ? array('label' => Role::find($user->role)->role_name, 'value' => $user->role) : null,
            'selected_location' => $selected_location
        );
    }
    public function roles()
    {
        return view('users.roles.index');
    }

    public function save(Request $request)
    {
        $isNew = false;
        $post_data = $request->post_data;
        $post_data = json_decode($post_data, true);
        if (isset($post_data['id']) && $post_data['id']) {
            $user = User::find($post_data['id']);
        } else {
            $user = new User();
            $user->dealer_id = Auth::user()->id;
            $user->type = 'User';
            $isNew = true;
        }
        if ($file = $request->file('profile_image')) {
            $path = 'images/users/profile';
            $ext = strtolower($file->getClientOriginalExtension());
            $upload_file_name = (time() . '-' . uniqid()) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($path), $upload_file_name);
            $user->profile_image =  $path . '/' . $upload_file_name;
        }
        $user->first_name = $post_data['first_name'];
        $user->civility = $post_data['civility'];
        $user->last_name = $post_data['last_name'];
        $user->gender = $post_data['gender'];
        $user->email = $post_data['email'];
        if (isset($post_data['password']) && $post_data['password']) {
            $user->password = Hash::make($post_data['password']);
        }
        $locations = [];
        if (isset($post_data['location'])) {
            foreach ($post_data['location'] as $selected_location) {
                $locations[] = $selected_location['value'];
            }
        }
        if (isset($post_data['role'])) {
            $user->role = $post_data['role'];
        }

        $user->locations = implode(',', $locations);

        $user->name = $post_data['name'];

        //
        $user->save();
        $user_id = $user->id;
        $phone_numbers = $post_data['phone_numbers'];
        foreach ($phone_numbers as $phone_number) {
            if (isset($phone_number['id'])) {
                $user_phone = UserPhone::find($phone_number['id']);
            } else {
                $user_phone = new UserPhone();
                $user_phone->user_id = $user_id;
            }
            $user_phone->valid = $phone_number['valid'];
            $user_phone->number = $phone_number['number'];
            $user_phone->mobile_no = $phone_number['mobile_no'];
            $user_phone->local_format = $phone_number['local_format'];
            $user_phone->international_format = $phone_number['international_format'];
            $user_phone->country_prefix = $phone_number['country_prefix'];
            $user_phone->country_code = $phone_number['country_code'];
            $user_phone->country_name = $phone_number['country_name'];
            $user_phone->location = $phone_number['location'];
            $user_phone->carrier = $phone_number['carrier'];
            $user_phone->line_type = $phone_number['line_type'];
            $user_phone->line_type = $phone_number['line_type'];
            $user_phone->intl_formmated_number = $phone_number['intl_formmated_number'];
            $user_phone->save();
        }
        return array(
            'status' => 'success',
            'user_id' => $user_id,
            'option' => $isNew ? 'created' : 'updated'
        );
    }
}
