@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('menu.marketings.marketings') }} - {{ __('app.settings') }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')

<div class="intro-y flex items-center flex-wrap border-b">

    <div class="-intro-x breadcrumb mr-auto hidden sm:flex">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb">{{ __('menu.marketings.marketings') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('app.settings') }}</a> 
    </div>
</div>

@endsection

@section('page_content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <h2 class="text-lg font-medium mr-auto">
            {{ __('app.customers') }}
        </h2>
    </div>
    <div class="intro-y col-span-12 lg:col-span-12">
        <div class="p-5 box">
            <div class="nav-tabs flex flex-col sm:flex-row justify-center lg:justify-start border-b-2">
                <a data-toggle="tab" data-target="#facebook" href="javascript:;" class="py-4 sm:mr-8 active">{{__('marketing.facebook_pages')}}</a>
                <a data-toggle="tab" data-target="#basic-tab2" href="javascript:;" class="py-4 sm:mr-8">{{__('marketing.inactive')}}</a>
            </div>
            <div class="mt-5 tab-content">
                <div class="tab-content__pane active" id="facebook">
                    <div class="text-right">
                        <button class="button bg-theme-4 text-white" onclick="logInWithFacebook()">{{__('marketing.subscribe_new_page')}}</button>
                    </div>
                    <div class="mt-5">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="border-b-2 whitespace-no-wrap">{{__('marketing.page')}}</th>
                                    <th class="border-b-2 whitespace-no-wrap text-center" style="width: 20px;"><i class="icon-arrow-down12"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($facebook_pages as $page)
                                <tr>
                                    <td class="border-b">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                <a href="#">
                                                    <img src="{{ $page->image_url  }}"
                                                        class="rounded-circle" width="32" height="32" alt="">
                                                </a>
                                            </div>
                                            <div>
                                                <a href="#" class="text-default font-weight-semibold">{{$page->page_name}}</a>
                                                <div class="text-muted font-size-sm">
                                                    <span class="badge badge-mark border-blue mr-1"></span>
                                                    {{$page->created_at->diffForHumans()}}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </td>
                                    <td class="border-b text-center">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-content__pane" id="basic-tab2">
                    Inactive
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Basic modal -->
<div id="modal_fb_pages" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('marketing.please_choose')}}.</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{{__('marketing.close')}}</button>
                <button type="button" class="btn bg-primary">{{__('marketing.save_changes')}}</button>
            </div>
        </div>
    </div>
</div>
<!-- /basic modal -->
<!-- /support tickets -->
@endsection


@section('scripts_after')
<script async defer src="https://connect.facebook.net/en_US/sdk.js"></script>

<script>
    logInWithFacebook = function() {
        FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
            // The user is logged in and has authenticated your
            // app, and response.authResponse supplies
            // the user's ID, a valid access token, a signed
            // request, and the time the access token 
            // and signed request each expire.
            var uid = response.authResponse.userID;
            console.log(uid)
            var accessToken = response.authResponse.accessToken;
            getFBPages(accessToken, uid)
        } else {
            FB.login(function(response) {
            if (response.authResponse) {
                // alert('You are logged in amp; cookie set!');
                var accessToken = (response.authResponse.accessToken)
                var uid = response.authResponse.userID;
                getFBPages(accessToken, uid)
                // Now you can redirect the user or do an AJAX request to
                // a PHP script that grabs the signed request from the cookie.
            } else {
                alert('User cancelled login or did not fully authorize.');
            }
            },{scope: 'pages_messaging'});
            return false;
        }
        });
        
    };
    getFBPages = function(token, uid){
        $.post('{{ route('marketings.settings.facebook.pages') }}', {accessToken: token, user_id: uid}, function(result) {
            console.log(result)
            openFBPageModal(result)
        })

       /*  FB.api(
            "/"+ uid +"/accounts",
            function (response) {
            if (response && !response.error) {
                console.log(response)
            }
            }
        ); */
    }
    subscribePage = function(id, pageToken){
        console.log(id,pageToken)
        $.post('{{ route('marketings.settings.facebook.subscribe') }}', {accessToken: pageToken, page_id: id}, function(result) {
            console.log(result)
            if(result.status == 'success'){
                location.reload();
            }
        })
         
    }
    openFBPageModal = function(pages){
        let html = "";
        pages.map((page) =>{
            html += '<div class="media">\
                        <div class="mr-3">\
                            <a href="#">\
                                <img src="'+page.picture+'" class="rounded-circle" width="44" height="44" alt="">\
                            </a>\
                        </div>\
                        <div class="media-body">\
                            <h6 class="media-title font-weight-semibold text-warning" onclick="subscribePage(' + page.id + ',\'' + page.access_token + '\')" style="cursor:pointer;">'+page.name+'</h6>\
                        </div>\
                    </div>';
        })
        $("#modal_fb_pages .modal-body").html(html)
        $("#modal_fb_pages").modal('show');
    }
    window.fbAsyncInit = function() {
        FB.init({
        appId: '{{env('FB_APP_ID')}}',
        cookie: true, // This is important, it's not enabled by default
        version: 'v2.2'
        });
    };

</script>
@endsection
