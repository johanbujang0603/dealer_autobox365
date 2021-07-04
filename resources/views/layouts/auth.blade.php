<!DOCTYPE html>
<!--
Template Name: Midone - HTML Admin Dashboard Template
Author: Left4code
Website: http://www.left4code.com/
Contact: muhammadrizki@left4code.com
Purchase: https://themeforest.net/user/left4code/portfolio
Renew Support: https://themeforest.net/user/left4code/portfolio
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">
    <!-- BEGIN: Head -->
    <head>
        <meta charset="utf-8">
        <link href="dist/images/logo.svg" rel="shortcut icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Midone admin is super flexible, powerful, clean & modern responsive tailwind admin template with unlimited possibilities.">
        <meta name="keywords" content="admin template, Midone admin template, dashboard template, flat admin template, responsive admin template, web app">
        <meta name="author" content="LEFT4CODE">
        <title>Dealer -login</title>
        <!-- BEGIN: CSS Assets-->
        <link href="{{ asset('new_assets/css/app.css') }}" rel="stylesheet" type="text/css" media="all" />
        <!-- END: CSS Assets-->
        <link rel="icon" type="image/png" href="assets/favicon.png" />
    </head>
    <!-- END: Head -->
    <body class="login">
        <div class="container sm:px-10">
            <div class="block xl:grid grid-cols-2 gap-4">
                <!-- BEGIN: Login Info -->
                <div class="hidden xl:flex flex-col min-h-screen">
                    <a href="{{ route('home') }}" class="-intro-x flex items-center pt-5">
                        <img src="{{ asset('global_assets/images/white_logo.png') }}" alt="" style="width: 200px">
                    </a>
                    <div class="my-auto">
                        <img alt="Midone Tailwind HTML Admin Template" class="-intro-x w-1/2 -mt-16" src="{{ asset('new_assets/images/illustration.png') }}">
                        <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                            Your Dealership
                            <br />
                            Management System
                        </div>
                        <div class="-intro-x mt-5 text-lg text-white">Manage and grow your business with precise analytics and metrics</div>
                    </div>
                </div>
                <!-- END: Login Info -->
                <!-- BEGIN: Login Form -->
                <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0 items-center m-auto">
                    @yield('content')
                </div>
                <!-- END: Login Form -->
            </div>
        </div>
        <!-- BEGIN: JS Assets-->
        <script src="{{ asset('new_assets/js/app.js') }}"></script>
        <!-- END: JS Assets-->
    </body>
</html>




@section('content_1')
<form method="POST" class="login-form" action="{{ route('login') }}">
    @csrf
    <div class="my-auto mx-auto xl:ml-20 bg-white xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                            
        <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
            {{ __('Login') }}
        </h2>
        <div class="intro-x mt-2 text-gray-500 xl:hidden text-center">A few more clicks to sign in to your account. Manage all your e-commerce accounts in one place</div>
        <div class="intro-x mt-8">
        
            <input type="email" class="intro-x login__input input input--lg border border-gray-300 block form-control @error('email') is-invalid @enderror"         name="email" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}">

            
            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
    
            <input id="password" type="password" class="intro-x login__input input input--lg border border-gray-300 block mt-4 form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password') }}" required autocomplete="current-password">
            
            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror

        </div>
        <div class="intro-x flex text-gray-700 text-xs sm:text-sm mt-4">
            <div class="flex items-center mr-auto">
                <input type="checkbox" class="input border mr-2" name="remember" id="remember"  checked data-fouc>
                <label class="cursor-pointer select-none" for="remember">{{ __('Remember Me') }}</label>
            </div>
            
            @if (Route::has('password.request'))
                <a class="ml-auto" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
        </div>
        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
            <button type="submit" class="button button--lg w-full xl:w-32 text-white bg-theme-1 xl:mr-3">
                {{ __('Login') }}
            </button>
            <a href="{{ route('register') }}" class="button button--lg w-full xl:w-32 text-gray-700 border border-gray-300 mt-3 xl:mt-0">{{__('SignUp')}}</a>
        </div>
        <div class="intro-x mt-10 xl:mt-24 text-gray-700 text-center xl:text-left">
            By continuing, you're confirming that you've read our
            <br>
            <a class="text-theme-1" href="">Terms &amp; Conditions</a> & <a class="text-theme-1" href="">Cookie Policy</a> 
        </div>
    </div>
</form>
@endsection
