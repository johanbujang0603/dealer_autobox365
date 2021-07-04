<?php

namespace App\Http\Controllers;

use App\Jobs\EmailCampignSend;
use App\Models\CustomerEmail;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\LeadEmail;
use Auth;

class EmailMarketingController extends Controller
{
    //
    public function create()
    {
        # code...
        $page_title = 'Email Marketing';
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
        return view('marketings.email_campaigns.create', compact('json_data', 'page_title'));
    }

    public function send(Request $request)
    {
        $leads = $request->leads;
        $customers = $request->customers;
        $emails = array_map('trim', explode("\n", str_replace('\r', '', $request->emails)));
        foreach ($leads as $leadId) {
            $leadEmail = LeadEmail::where('lead_id', $leadId)->get();
            foreach ($leadEmail as $email) {
                $emails[] = $email->email;
            }
        }
        foreach ($customers as $customerId) {
            $customerEmail = CustomerEmail::where('customer_id', $customerId)->get();
            foreach ($customerEmail as $email) {
                $emails[] = $email->email;
            }
        }
        EmailCampignSend::dispatch($emails, $request->html, Auth::user()->email);
        return array('status' => 'success');
    }
}
