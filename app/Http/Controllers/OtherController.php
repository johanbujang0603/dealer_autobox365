<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Lead;
use App\Models\LeadEmail;
use App\Models\LeadEnquiry;
use App\Models\LeadInterest;
use App\Models\LeadPhoneNumber;
use Illuminate\Http\Request;

class OtherController extends Controller
{
    //

    public function inventoryShare($id)
    {
        $inventory = Inventory::find($id);
        return view('share.inventory', [
            'inventory' => $inventory
        ]);
    }
    public function leadGenerate(Request $request)
    {
        $inventory = Inventory::find($request->inventory_id);
        $phone_number = $request->phone_number;
        $isExistPhone = LeadPhoneNumber::where('international_format', $phone_number)
            ->orWhere('number', $phone_number)
            ->orWhere('local_format', $phone_number)->first();
        if ($isExistPhone) {
            $lead = Lead::find($isExistPhone->lead_id);
            $leadId = $lead->id;
        } else {
            $lead = new Lead();
            $lead->is_converted = 0;
            $lead->user_id = $inventory->user_id;
            $name = $request->name;
            $nameArray = explode(' ', $name);
            if (count($nameArray) == 3) {
                $lead->first_name = $nameArray[0];
                $lead->middle_name = $nameArray[1];
                $lead->last_name = $nameArray[2];
            } else if (count($nameArray) == 2) {
                $lead->first_name = $nameArray[0];
                $lead->last_name = $nameArray[1];
            } else {
                $lead->first_name = $nameArray[0];
            }
            $postal_code = $request->postal_code;
            if ($postal_code) {
                $lead->postal_code = $postal_code;
                $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($postal_code) . "&sensor=false&key=" . env('GOOGLE_MAP_API_KEY');
                $result_string = file_get_contents($url);
                $result = json_decode($result_string, true);
                if ($result['status'] == 'OK') {
                    $address_result = $result['results'];
                    if ($address_result[0]) {
                        $lead->address = $address_result[0]['formatted_address'];

                        foreach ($address_result[0]['address_components'] as $address_component) {
                            if ($address_component['types'][0] == 'administrative_area_level_2') {
                                $lead->city = $address_component['long_name'];
                            }
                            if ($address_component['types'][0] == 'administrative_area_level_1') {
                                $lead->state = $address_component['long_name'];
                            }
                            if ($address_component['types'][0] == 'country') {
                                $lead->country_base_residence = $address_component['short_name'];
                            }
                        }
                    }
                    // dd($result['status'], $result);
                }
            }
            $lead->save();
            $leadId = $lead->id;
            if ($email = $request->email) {
                $url = "https://apps.emaillistverify.com/api/verifyEmail?secret=" . env('EMAILVERIFY_API_KEY') . "&email=" . $email;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                $response = curl_exec($ch);
                curl_close($ch);
                $leadEmail = new LeadEmail();
                $leadEmail->email = $email;
                $leadEmail->lead_id = $leadId;
                if ($response == 'error_credit') {
                    $leadEmail->valid = false;
                }
                $leadEmail->save();
            }

            if ($phone_number) {
                $leadPhone = new LeadPhoneNumber();
                $url = "https://apilayer.net/api/validate?access_key=" . env('NUMVERIFY_API_KEY') . "&number=$phone_number";
                $result = file_get_contents($url);
                $result = json_decode($result, true);
                $leadPhone->lead_id = $leadId;
                $leadPhone->valid = $result['valid'];
                $leadPhone->number = $result['number'];
                $leadPhone->mobile_no = $result['mobile_no'] ?? null;
                $leadPhone->local_format = $result['local_format'] ?? null;
                $leadPhone->international_format = $result['international_format'] ?? null;
                $leadPhone->country_prefix = $result['country_prefix'] ?? null;
                $leadPhone->country_code = $result['country_code'] ?? null;
                $leadPhone->country_name = $result['country_name'] ?? null;
                $leadPhone->location = $result['location'] ?? null;
                $leadPhone->carrier = $result['carrier'] ?? null;
                $leadPhone->line_type = $result['line_type'] ?? null;
                // dd($result);
                $leadPhone->save();
            }
            // $leadEmail = new LeadEmail();
            // $leadEmail->email = $request->email;

            // $leadEmail->
        }
        $leadInterest = new LeadInterest();
        $leadInterest->lead_id = $leadId;
        $leadInterest->inventory_id =  $request->inventory_id;
        $leadInterest->save();
        $leadEnquiry = new LeadEnquiry();
        $leadEnquiry->lead_id = $leadId;
        $leadEnquiry->inventory_id = $request->inventory_id;
        $leadEnquiry->message = $request->message;
        $leadEnquiry->channel = 'web';
        $leadEnquiry->save();
        // dd($request);
        return redirect(route('thank_you', $request->inventory_id));
    }
    public function thankyou()
    {
        return view('thank_you');
    }
}
