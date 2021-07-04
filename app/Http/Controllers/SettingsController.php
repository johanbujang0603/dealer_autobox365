<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\UserSetting;
use Auth;
use Illuminate\Support\Facades\App;
use Cloudder;

class SettingsController extends Controller
{
    //
    public function index()
    {
        # code...
        $page_title = 'Settings';
        $currencies = Currency::all();
        $user = Auth::user();
        $settings = $user->settings;

        return view('settings.index', compact('currencies', 'settings', 'page_title'));
    }

    public function profile()
    {
        $page_title = 'Profile';
        return view('settings.profile', compact('page_title'));
    }

    public function save(Request $request)
    {
        $user = Auth::user();
        $settings = $user->settings;
        // dd($settings);
        if (!$settings) {
            $settings = new UserSetting;
            $settings->user_id = Auth::user()->id;
        }
        $settings->currency = $request->currency;
        $settings->language = $request->language;
        if (isset($request->language) && $settings->language != $request->language) {
            session(['my_locale' => $request->language]);
        }
        if (isset($request->currency) && $settings->currency != $request->currency) {
            session(['my_currency' => $request->currency]);
        }
        $settings->mesure = $request->mesure;
        $settings->fiscal_year_from = $request->fiscal_year_from;
        $settings->fiscal_year_to = $request->fiscal_year_to;
        $settings->vat_number = $request->vat_number;
        $settings->watermark_place = $request->watermark_place;
        $settings->watermark_transparence = $request->watermark_transparence;
        $settings->company_address = $request->company_address;
        $settings->company_street_number = $request->company_street_number;
        $settings->company_route = $request->company_route;
        $settings->company_locality = $request->company_locality;
        $settings->company_state = $request->company_state;
        $settings->company_postal_code = $request->company_postal_code;
        $settings->company_country = $request->company_country;
        $settings->company_phone_number = $request->company_phone_number;
        $settings->company_tax = (float)$request->company_tax;

        if ($file = $request->file('company_logo')) {
            // $path = "images/users/logos";
            // $upload_file_name = (time() . '-' . uniqid()) . '.' . $file->getClientOriginalExtension();
            // $file->move(public_path($path), $upload_file_name);
            $image_name = $request->file('company_logo')->getRealPath();;
            $option1 = array(
                // "folder" => , //"/images/rooms/$photo->room_id",
                "public_id" => "dealers_" . Auth::user()->id . '_logo' . (time() . '_' . uniqid()),
                "quality" => "auto:low",
                "flags" => "lossy",
                "resource_type" => "image"
            );
            Cloudder::upload($image_name, null, $option1);
            $resposne = Cloudder::getResult();
            $settings->company_logo  =   $resposne['public_id'];
            $settings->company_logo_source  =   'cloudinary';

            // $settings->company_logo = $path . '/' . $upload_file_name;
        }
        $settings->save();
        if (isset($request->from) && $request->from == 'register') {
            return redirect(route('dashboard'))->with('success', "Congratulations!");
        }
        return redirect()->back()->with('success', "You saved your settings successfully!");
    }
    public function register()
    {
        $page_title = 'Register';
        $currencies = Currency::all();

        // return "Register Settings";
        return view('settings.register', compact('currencies', 'page_title'));
    }
}
