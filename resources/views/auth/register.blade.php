@extends('layouts.auth')
@section('content')

<form method="POST" class="login-form" action="{{ route('register') }}">
    @csrf
    
    <a href="{{ route('home') }}" class="sm:flex md:flex xl:hidden">
        <img src="{{ asset('global_assets/images/white_logo.png') }}" class="m-auto" alt="" style="width: 200px">
    </a>

    <div class="my-auto mx-auto xl:ml-20 bg-white xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto mt-5">
                            
        <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
        {{ __('SignUp') }}
        </h2>
        <div class="intro-x mt-2 text-gray-500 xl:hidden text-center">Your Dealership Management System. <br />Manage and grow your business with precise analytics and metrics.</div>
        <div class="intro-x mt-8">
        
            <input id="name" type="text" class="intro-x login__input input input--lg border border-gray-300 block mt-4 form-control @error('name') border-theme-6 @enderror" name="name"
                value="{{ old('name') }}" placeholder="{{ __('UserName') }}" required autocomplete="name" autofocus>
            
            @error('name')
            <span class="text-theme-6 mt-2" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror

            
            <input id="email" type="email" class="intro-x login__input input input--lg border border-gray-300 block mt-4 form-control @error('email') border-theme-6 @enderror" name="email"
                value="{{ old('email') }}" placeholder="{{ __('Email') }}" required autocomplete="email">

            @error('email')
            <span class="text-theme-6 mt-2" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror

    
            <input type="text" class="intro-x login__input input input--lg border border-gray-300 block form-control mt-4" name="first_name" placeholder="First name">
            @error('first_name')
            <span class="text-theme-6 mt-2" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror

            
            <input type="text" class="intro-x login__input input input--lg border border-gray-300 block mt-4" name="last_name" placeholder="Last name">
            @error('last_name')
            <span class="text-theme-6 mt-2" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror

            
            <input id="password" type="password" class="intro-x login__input input input--lg border border-gray-300 block mt-4 form-control @error('password') border-theme-6 @enderror"
                name="password" placeholder="{{ __('Password') }}" required autocomplete="new-password">
            @error('password')
            <span class="text-theme-6 mt-2" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror

            <input id="password-confirm" type="password" class="intro-x login__input input input--lg border border-gray-300 block mt-4 form-control" name="password_confirmation"
                placeholder="{{ __('PasswordConfirmation') }}" required autocomplete="new-password">
            <div class="form-control-feedback">
                <i class="icon-user-lock text-muted"></i>
            </div>

            <div class="flex flex-col sm:flex-row mt-4">
                <div class="flex items-center text-gray-700 mr-2">
                    <input type="radio" class="input border mr-2" id="male" name="gender" value="male" checked data-fouc>
                    <label class="cursor-pointer select-none" for="male">Male</label>
                </div>
                <div class="flex items-center text-gray-700 mr-2 mt-2 sm:mt-0">
                    <input type="radio" class="input border mr-2" id="female" name="gender" value="female" data-fouc>
                    <label class="cursor-pointer select-none" for="female">Female</label>
                </div>
            </div>
            
            @error('gender')
            <span class="text-theme-6 mt-2" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror

        </div>
        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
            <button type="submit" class="button button--lg w-full text-white bg-theme-1 xl:mr-3">
                Create Account
            </button>
        </div>
    </div>
</form>
<!-- 
<div class="card card-body shadow text-left">
    <h1 class="h5 text-center"></h1>
    <form method="POST" action="{{route('register')}}">
        @csrf
        <div class="form-group">
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                value="{{ old('name') }}" placeholder="{{ __('UserName') }}" required autocomplete="name" autofocus>

            <div class="form-control-feedback">
                <i class="icon-user-plus text-muted"></i>
            </div>
            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                value="{{ old('email') }}" placeholder="{{ __('Email') }}" required autocomplete="email">


            <div class="form-control-feedback">
                <i class="icon-mention text-muted"></i>
            </div>
            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group form-group-feedback form-group-feedback-right">
            <input type="text" class="form-control" name="first_name" placeholder="First name">
            <div class="form-control-feedback">
                <i class="icon-user-check text-muted"></i>
            </div>
            @error('first_name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group form-group-feedback form-group-feedback-right">
            <input type="text" class="form-control" name="last_name" placeholder="Last name">
            <div class="form-control-feedback">
                <i class="icon-user-check text-muted"></i>
            </div>
            @error('last_name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group form-group-feedback form-group-feedback-right">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                name="password" placeholder="{{ __('Password') }}" required autocomplete="new-password">
            <div class="form-control-feedback">
                <i class="icon-user-lock text-muted"></i>
            </div>
            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group form-group-feedback form-group-feedback-right">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                placeholder="{{ __('PasswordConfirmation') }}" required autocomplete="new-password">
            <div class="form-control-feedback">
                <i class="icon-user-lock text-muted"></i>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-lg-12">
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" class="form-input-styled" name="gender" value="Male" checked data-fouc>
                        Male
                    </label>
                </div>

                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" class="form-input-styled" name="gender" value="Female" data-fouc>
                        Female
                    </label>
                </div>
                @error('gender')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <button type="submit" class="btn btn-primary bg-teal-400 btn-labeled btn-labeled-right"><b><i
                    class="icon-plus3"></i></b>
            Create
            account</button>
    </form>
</div>
<div class="text-center text-small mt-3">
    Do you have already an account?
    <a href="{{ route('login') }}">{{__('Login')}}</a>
</div> -->
@endsection
