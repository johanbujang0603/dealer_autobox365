<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\FBSubscribedPage;
use App\Models\Lead;
use App\Models\LeadEnquiry;
class FacebookWebhookController extends Controller
{
    //
    private $fb;
    public function __construct()
    {
        $this->fb = new \Facebook\Facebook([
            'app_id' => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
            'default_graph_version' => 'v2.10',
            'default_access_token' => env('FB_ACCESS_TOKEN'), // optional
          ]);
    }
    public function webhook(Request $request, $category){
        
        Log::info($request);
        if($request->object == 'page'){
            if(isset($request->entry[0]['messaging'][0]['sender']['id'])){
                // Received New Message
                $sender_id = $request->entry[0]['messaging'][0]['sender']['id'];
                $page_id = $request->entry[0]['messaging'][0]['recipient']['id'];
                Log::info('New message in this page:');
                Log::info($page_id);

                $fb_subscribed_page = FBSubscribedPage::where('page_id', $page_id)->first();

                if($fb_subscribed_page){
                  $page_owner_id = $fb_subscribed_page->user_id;
                  Log::info($page_owner_id);
                  try {
                    // Returns a `Facebook\FacebookResponse` object
                    $pageResponse = $this->fb->get(
                        $sender_id."?fields=gender,first_name,last_name,profile_pic,name",
                        $fb_subscribed_page->page_access_token
                    );
                    $graphNode = $pageResponse->getGraphNode();
                    Log::info($graphNode);

                    $isExist = Lead::where('user_id', $page_owner_id)->where('fb_id', $sender_id)->first();
                    if(!$isExist){
                      $lead = new Lead();
                      $lead->user_id = $page_owner_id;
                      $lead->fb_d = $sender_id;
                      $lead->first_name = $graphNode['first_name'];
                      $lead->last_name = $graphNode['last_name'];
                      $lead->gender = ucfirst($graphNode['gender']);
                      $lead->profile_image = $graphNode['profile_pic'];
                      $lead->profile_image_source = 'facebook';
                      $lead->save();
                    }
                    else{
                      $lead = $isExist;
                    }

                    
                    
                  } catch(\Facebook\Exceptions\FacebookResponseException $e) {
                    
                    Log::info($e->getMessage());
                    
                  } catch(\Facebook\Exceptions\FacebookSDKException $e) {
                    Log::info($e->getMessage());
                    
                  }
                }
                
                  
            }
        }
        
        Log::info("-----------------");
        return $request->hub_challenge;
    }
}
