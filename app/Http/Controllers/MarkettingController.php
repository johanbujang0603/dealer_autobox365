<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FBSubscribedPage;

class MarkettingController extends Controller
{
    //

    public function settings()
    {
        $page_title = 'Marketing Settings';
        $facebook_pages = FBSubscribedPage::where('user_id', auth()->user()->id)->get();
        return view('marketings.settings', compact('facebook_pages', 'page_title'));
    }

    public function getFBPages(Request $request)
    {
        $accessToken = $request->accessToken;
        $userid = $request->user_id;
        $fbClient = new \Facebook\Facebook([
            'app_id' => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
            'default_graph_version' => 'v2.3',
            'default_access_token' => $accessToken, // optional
        ]);

        try {

            $token_response = $fbClient->get('/oauth/access_token?grant_type=fb_exchange_token&client_id='.env('FB_APP_ID').'&client_secret='.env('FB_APP_SECRET').'&fb_exchange_token='.$accessToken);
            $token_result = ($token_response->getGraphNode());
            // Returns a `Facebook\FacebookResponse` object
            $response = $fbClient->get(
                "/$userid/accounts", $token_result['access_token']
            );

            $pages = $response->getGraphEdge();
            $result = array();
            foreach ($pages as $page) {
                //   dd($page['access_token']);
                $pageResponse = $fbClient->get(
                  "/{$page['id']}/picture?redirect=0&width=256",
                  $page['access_token']
                );
                $pagePicture = $pageResponse->getGraphNode();
                $result[] = array(
                    'id' => $page['id'],
                    'name' => $page['name'],
                    'picture' => $pagePicture['url'],
                    'access_token' => $page['access_token']
                );
            }

        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        
        return ($result);
    }


    public function subscribeFBPage(Request $request)
    {
        $accessToken = $request->accessToken;
        $pageId = $request->page_id;
        $fbClient = new \Facebook\Facebook([
            'app_id' => env('FB_APP_ID'),
            'app_secret' => env('FB_APP_SECRET'),
        ]);
        try {
            $response = $fbClient->post(
                "/$pageId/subscribed_apps",
                array(
                    'subscribed_fields' => 'messages',
                ),
                $accessToken
            );
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        try {
            $pageResponse = $fbClient->get(
                "/$pageId/picture?redirect=0&width=256",
                $accessToken
            );
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $pagePicture = $pageResponse->getGraphNode();
        try {
            $pageResponse = $fbClient->get(
                "/$pageId?fields=access_token,name",
                $accessToken
            );
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $page =  $pageResponse->getGraphNode();

        if ($subscribePage = FBSubscribedPage::where('page_id', $pageId)->first()) { } else {
            $subscribePage = new FBSubscribedPage();
        }
        $subscribePage->user_id = auth()->user()->id;
        $subscribePage->page_id  = $pageId;
        $subscribePage->page_name = $page['name'];
        $subscribePage->page_access_token = $page['access_token'];
        $subscribePage->image_url = $pagePicture['url'];
        $subscribePage->save();
        return array(
            'status' => 'success'
        );

        // dd($response->getGraphNode());

    }
}
