<?php

namespace App\Http\Controllers;

use App\Jobs\SMSCampaignSend;
use App\Models\CustomerPhoneNumber;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\LeadPhoneNumber;
use Auth;
use Illuminate\Support\Facades\Log;

class SmsMarketingController extends Controller
{
    //
    public function create()
    {
        # code...
        $page_title = 'SMS Marketing';
        $inventories = Inventory::with([
            'vehicle_details',
            'location_details',
            'user_details',
            'make_details',
            'model_details',
            'generation_details',
            'serie_details',
            'location_details',
            'user_details',
            'photo_details',

        ])->where('user_id', Auth::user()->id)->get();
        $json_data = json_encode(array(
            'inventories' => $inventories
        ));

        return view('marketings.sms_campaigns.create', compact('json_data', 'page_title'));
    }

    public function send(Request $request)
    {
        $leads = $request->leads;
        $customers = $request->customers;
        $phones = array_map('trim', explode("\n", str_replace('\r', '', $request->phones)));
        foreach ($leads as $leadId) {
            $leadPhone = LeadPhoneNumber::where('lead_id', $leadId)->get();
            foreach ($leadPhone as $phone) {
                if ($phone->valid == true) {


                    $phones[] = $phone->international_format;
                }
            }
        }
        foreach ($customers as $customerId) {
            $customerPhone = CustomerPhoneNumber::where('customer_id', $customerId)->get();
            foreach ($customerPhone as $phone) {
                if ($phone->valid == true) {
                    $phones[] = $phone->international_format;
                }
            }
        }

        Log::info($phones);;

        SMSCampaignSend::dispatch($phones, $request->sms);
        return array('status' => 'success');
    }
}
