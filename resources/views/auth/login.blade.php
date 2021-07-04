@extends('layouts.auth')
@section('content')

<form method="POST" class="login-form m-auto" action="{{ route('login') }}">
    @csrf
    
    <a href="{{ route('home') }}" class="sm:flex md:flex xl:hidden">
        <img src="{{ asset('global_assets/images/white_logo.png') }}" class="m-auto" alt="" style="width: 200px">
    </a>

    <div class="my-auto mx-auto xl:ml-20 bg-white xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto mt-5">
                            
        <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
            {{ __('Login') }}
        </h2>
        <div class="intro-x mt-2 text-gray-500 xl:hidden text-center">Your Dealership Management System. <br />Manage and grow your business with precise analytics and metrics</div>
        <div class="intro-x mt-8">
        
            <input type="email" class="intro-x login__input input input--lg border border-gray-300 block form-control @error('email') border-theme-6 @enderror"         name="email" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}">

            
            @error('email')
            <span class="text-theme-6 mt-2" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
    
            <input id="password" type="password" class="intro-x login__input input input--lg border border-gray-300 block mt-4 form-control @error('password') border-theme-6 @enderror" name="password" placeholder="{{ __('Password') }}" required autocomplete="current-password">
            
            @error('password')
            <span class="text-theme-6 mt-2" role="alert">
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
        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left flex items-center">
            <button type="submit" class="button button--lg w-full xl:w-32 text-white bg-theme-1 mr-3">
                {{ __('Login') }}
            </button>
            <a href="{{ route('register') }}" class="button button--lg w-full xl:w-32 text-gray-700 border border-gray-300 xl:mt-0">{{__('SignUp')}}</a>
        </div>
        <div class="intro-x mt-10 xl:mt-24 text-gray-700 text-center xl:text-left">
            By continuing, you're confirming that you've read our
            <br>
            <a class="text-theme-1" href="">Terms &amp; Conditions</a> & <a class="text-theme-1" href="">Cookie Policy</a> 
        </div>
    </div>
</form>
@endsection
