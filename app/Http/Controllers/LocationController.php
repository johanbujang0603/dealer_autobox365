<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\LocationPhone;
use App\Models\LocationPhoto;
use App\Models\LocationSocial;
use App\Models\LocationType;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Auth;

class LocationController extends Controller
{
    //
    public function index()
    {
        $page_title = 'Locations';$locationsQuery = Location::where('user_id', Auth::user()->id);
        
        $locations = $locationsQuery->get();

        $data = array();
        foreach ($locations as $location) {
            $status_str = '';
            if ($location->is_deleted == 0 && $location->is_draft == 0) {
                $status_str = '<span class="py-1 px-2 rounded-full text-xs bg-theme-4 text-white font-medium">Published</span>';
            } else if ($location->is_deleted == 1) {
                $status_str = '<span class="py-1 px-2 rounded-full text-xs bg-theme-6 text-white font-medium">Deleted</span>';
            } else if ($location->is_draft == 1) {
                $status_str = '<span class="py-1 px-2 rounded-full text-xs bg-theme-11 text-white font-medium">Draft</span>';
            }
            $action_str =     '<div class="dropdown relative">
                <button class="dropdown-toggle button m-auto text-theme-4"><i class="icon-menu7"></i></button>
                    <div class="dropdown-box mt-10 absolute w-48 top-0 right-0 z-20"><div class="dropdown-box__content box p-2">';
            if (Auth::user()->hasPermission('location', 'write'))
                $action_str .= '<a href="' . route('locations.edit', $location->id) . '" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md"><i class="icon-pencil mr-2"></i> Edit Location</a>';
            if (Auth::user()->hasPermission('location', 'delete'))
                $action_str .='<a href="#" onclick="deleteLocation(' . $location->id . ');" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md removeLocation" data-id="' . $location->id . '"><i class="icon-close2 mr-2"></i> Delete Location</a>';
            $action_str .='' . ($location->is_draft == 0 ? '<a href="#" onclick="moveToDraft(' . $location->id . ');" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md"><i class="icon-database-insert mr-2"></i> Move To Draft</a>' : '<a href="#" onclick="publish(' . $location->id . ');" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md"><i class="icon-database-insert mr-2"></i> Publish</a>')
                . '
                    </div>
                </div>
            </div>
            ';
            $phone_str = '';
            foreach ($location->phone_numbers as $phone_number) {
                $phone_str .= "<p>$phone_number->intl_formmated_number</p>";
            }
            $social_str = '';
            foreach ($location->social_medias as $social_media) {
                $social_str .= "<p>$social_media->social_url</p>";
            }
            $name_field = "<div class=\"flex items-center\">
                <div class=\"mr-3\">
                    <a href=\"#\">
                        <img src=\"$location->logo_url\" class=\"rounded-full\" width=\"60\" height=\"60\" alt=\"\">
                    </a>
                </div>
                <div>
                    <a href=\"#\" class=\"text-theme-3 font-medium\">$location->name</a>
                    <div class=\"font-sm\">
                        $location->description
                    </div>
                </div>
            </div>";
            $data[] = array(
                $status_str,
                $name_field,
                $location->address,
                $phone_str,
                $location->email,
                $social_str,
                $location->website,
                isset($location->type_details->type_name) ? $location->type_details->type_name : '----',
                $action_str
            );
        }
        return view('locations.index', compact('page_title', 'data'));
    }
    public function all()
    {
        # code...
        return Location::where('user_id', Auth::user()->id)->get();
    }
    public function ajax_load(Request $request)
    {
        $option = 'all';
        $locationsQuery = Location::where('user_id', Auth::user()->id);
        if (isset($request->option)) {
            $option = $request->option;
        }
        if ($option == 'all') {
            $locations = $locationsQuery->get();
        } else if ($option == 'draft') {
            $locations = $locationsQuery->where('is_deleted', 0)->where('is_draft', 1)->get();
        } else if ($option == 'deleted') {
            $locations = $locationsQuery->where('is_deleted', 1)->get();
        }

        $data = array();
        foreach ($locations as $location) {
            $status_str = '';
            if ($location->is_deleted == 0 && $location->is_draft == 0) {
                $status_str = '<span class="badge badge-success badge-pill">Published</span>';
            } else if ($location->is_deleted == 1) {
                $status_str = '<span class="badge badge-danger badge-pill">Deleted</span>';
            } else if ($location->is_draft == 1) {
                $status_str = '<span class="badge badge-dark badge-pill">Draft</span>';
            }
            $action_str =     '<div class="list-icons">
                <div class="list-icons-item dropdown">
                    <a href="#" class="list-icons-item dropdown-toggle caret-0" data-toggle="dropdown" aria-expanded="false"><i class="icon-menu7"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">';
            if (Auth::user()->hasPermission('location', 'write'))
                $action_str .= '<a href="' . route('locations.edit', $location->id) . '" class="dropdown-item"><i class="icon-pencil"></i> Edit Location</a>';
            if (Auth::user()->hasPermission('location', 'delete'))
                $action_str .='' . ($option != 'deleted' ? '<a href="#" onclick="deleteLocation(' . $location->id . ');" class="dropdown-item removeLocation" data-id="' . $location->id . '"><i class="icon-close2"></i> Delete Location</a>' : '') . '';
            $action_str .='' . ($location->is_draft == 0 ? '<a href="#" onclick="moveToDraft(' . $location->id . ');" class="dropdown-item"><i class="icon-database-insert"></i> Move To Draft</a>' : '<a href="#" onclick="publish(' . $location->id . ');" class="dropdown-item"><i class="icon-database-insert"></i> Publish</a>')
                . '
                    </div>
                </div>
            </div>
            ';
            $phone_str = '';
            foreach ($location->phone_numbers as $phone_number) {
                $phone_str .= "<p>$phone_number->intl_formmated_number</p>";
            }
            $social_str = '';
            foreach ($location->social_medias as $social_media) {
                $social_str .= "<p>$social_media->social_url</p>";
            }
            $name_field = "<div class=\"d-flex align-items-center\">
                <div class=\"mr-3\">
                    <a href=\"#\">
                        <img src=\"$location->logo_url\" class=\"rounded-circle\" width=\"60\" height=\"60\" alt=\"\">
                    </a>
                </div>
                <div>
                    <a href=\"#\" class=\"text-default font-weight-semibold\">$location->name</a>
                    <div class=\"text-muted font-size-sm\">
                        $location->description
                    </div>
                </div>
            </div>";
            $data[] = array(
                $status_str,
                $name_field,
                $location->address,
                $phone_str,
                $location->email,
                $social_str,
                $location->website,
                isset($location->type_details->type_name) ? $location->type_details->type_name : '----',
                $action_str
            );
        }
        return array(
            'data' => $data,

        );
    }

    public function ajax_load_details($id)
    {
        $location = Location::find($id);
        return array(
            '_id' => $location->_id,
            'id' => $location->id,
            'name' => $location->name,
            'address' => $location->address,
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
            'phone_numbers' => $location->phone_numbers,
            'email' => $location->email,
            'social_medias' => $location->social_medias,
            'photos' => $location->photo_details,
            'website' => $location->website,
            'type' => $location->type,
            'description' => $location->description,
            'logo' => $location->logo,
            'logo_url' => $location->logo_url,
            // form data
            'select_type' => $location->type ? array('value' => $location->type, 'label' => $location->type_details->type_name) : null,
        );
    }
    public function create()
    {
        $page_title = 'Create Location';
        $type_list = LocationType::where('user_id',  Auth::user()->id)->get();
        $json_array  = array(
            'type_list' => $type_list
        );
        $json_data = json_encode($json_array);
        return view('locations.create', compact('json_data', 'page_title'));
    }
    public function edit($id)
    {
        $page_title = 'Edit Location';
        $location = Location::find($id);
        $type_list = LocationType::where('user_id',  Auth::user()->id)->get();
        $json_array  = array(
            'type_list' => $type_list
        );
        $json_data = json_encode($json_array);
        return view('locations.edit', compact('json_data', 'location', 'page_title'));
    }

    public function delete($id)
    {
        if ($location = Location::find($id)) {
            $location->delete();
            return redirect()->back()->with('success', 'You removed a location successfully!');
        } else {
            abort(404);
        }
    }
    public function save(Request $request)
    {

        $post_data = $request->post_data;
        $post_data = json_decode($post_data, true);
        if (isset($post_data['_id']) && $post_data['_id']) {
            $location = Location::find($post_data['_id']);
            $isCreate = false;
        } else {
            $location = new Location();
            $location->user_id = Auth::user()->id;
            $isCreate = true;
        }

        $address = $post_data['address'];
        $location->address = $post_data['address'];
        $location->full_address = $post_data['address'];
        $address      = str_replace([" ", "%2C"], ["+", ","], "$address");
        $map_api_url = 'https://maps.google.com/maps/api/geocode/json?key=' . env('GOOGLE_MAP_API_KEY') . '&address=' . $address . '&sensor=false&libraries=places';
        $geocode      = @file_get_contents($map_api_url);
        $json         = json_decode($geocode);
        if (@$json->results) {
            foreach ($json->results as $result) {
                foreach ($result->address_components as $addressPart) {
                    if ((in_array('locality', $addressPart->types)) && (in_array('political', $addressPart->types))) {
                        $location->city = $addressPart->long_name;
                    }
                    if ((in_array('administrative_area_level_1', $addressPart->types)) && (in_array('political', $addressPart->types))) {
                        $location->state = $addressPart->long_name;
                    }
                    if ((in_array('country', $addressPart->types)) && (in_array('political', $addressPart->types))) {
                        $location->country = $addressPart->short_name;
                    }
                }
                if (isset($result->geometry)) {
                    $geometry = $result->geometry;
                    $geolocation = $geometry->location;
                    $location->latitude  = $geolocation->lat;
                    $location->longitude = $geolocation->lng;
                }
            }
        }

        $location->name = $post_data['name'];
        $location->email = $post_data['email'];
        $location->website = $post_data['website'];
        $location->type = $post_data['type'];
        $location->description = $post_data['description'];
        if ($file = $request->file('logo')) {
            $path = 'images/locations/logo';

            $ext = strtolower($file->getClientOriginalExtension());
            $upload_file_name = (time() . '-' . uniqid()) . '.' . $file->getClientOriginalExtension();


            $file->move(public_path($path), $upload_file_name);
            $location->logo =  $path . '/' . $upload_file_name;
        }
        $location->save();

        $location_id = $location->id;

        $social_medias = $post_data['social_medias'];
        foreach ($social_medias as $social_media) {
            if (isset($social_media['_id'])) {
                $social_media_model = LocationSocial::find($social_media['_id']);
            } else {
                $social_media_model = new LocationSocial();
                $social_media_model->location_id = $location_id;
            }
            $social_media_model->social_url = $social_media['social_url'];
            $social_media_model->save();
        }

        $location_photos = $post_data['photos'];
        foreach ($location_photos as $location_photo) {
            $photo_model = LocationPhoto::find($location_photo['_id']);
            $photo_model->location_id = $location_id;
            $photo_model->save();
        }

        $phone_numbers = $post_data['phone_numbers'];
        foreach ($phone_numbers as $phone_number) {
            $number = $phone_number['mobile_no'];
            $numverify_url = 'http://apilayer.net/api/validate?access_key=' . env('NUMVERIFY_API_KEY') . '&number=' . $number;
            $response      = @file_get_contents($numverify_url);
            $json         = json_decode($response);

            $numverify_result = @$json;
            if (isset($phone_number['_id'])) {
                $phone_model = LocationPhone::find($phone_number['_id']);
            } else {
                $phone_model = new LocationPhone();
                $phone_model->location_id = $location_id;
            }
            $phone_model->valid = $phone_number['valid'];
            $phone_model->mobile_no = $number;
            $phone_model->number = $phone_number['number'];
            $phone_model->local_format = $phone_number['local_format'];
            $phone_model->international_format = $phone_number['international_format'];
            $phone_model->country_prefix = $phone_number['country_prefix'];
            $phone_model->country_code = $phone_number['country_code'];
            $phone_model->country_name = $phone_number['country_name'];
            $phone_model->location = $phone_number['location'];
            $phone_model->carrier = $phone_number['carrier'];
            $phone_model->line_type = $phone_number['line_type'];
            $phone_model->intl_formmated_number = $phone_number['intl_formmated_number'];
            $phone_model->save();
        }
        $log = new SystemLog;
        $log->user_id = Auth::user()->id;
        if ($isCreate) {
            $log->action = 'created';
        } else {
            $log->action = 'updated';
        }
        $log->category = 'location';
        $log->model = Location::class;
        $log->model_id = $location->id;
        $log->save();
        return array(
            'status' => 'success',
            'option' => $isCreate ? 'created' : 'updated',
            'data' => $location->toArray()
        );
    }
    public function uploadphoto(Request $request)
    {
        $photo = new LocationPhoto();
        if ($file = $request->file('file')) {
            $path = 'images/locations/';

            $ext = strtolower($file->getClientOriginalExtension());
            $upload_file_name = (time() . '-' . uniqid()) . '.' . $file->getClientOriginalExtension();
            $photo->upload_path =   $path . '/' . $upload_file_name;
            $photo->file_name =   $file->getClientOriginalName();
            $photo->file_size =   $file->getSize();
            $file->move(public_path($path), $upload_file_name);
            $photo->user_id = Auth::user()->id;
            $photo->save();
            return  array('status' => 'success', 'file' => $photo);
        }
    }
    public function logoUpload(Request $request)
    {
        if ($file = $request->file('logo')) {
            $path = 'images/locations/logo/';

            $ext = strtolower($file->getClientOriginalExtension());
            $upload_file_name = (time() . '-' . uniqid()) . '.' . $file->getClientOriginalExtension();


            $file->move(public_path($path), $upload_file_name);
            return $path . '/' . $upload_file_name;
        }
        return $request->all();
    }
}
