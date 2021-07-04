@extends('layouts.auth')

@section('content')
<form method="POST" class="login-form" action="{{ route('admin.auth.adminLogin') }}">
        @csrf
    <div class="card mb-0">
        <div class="card-body">



            <div class="text-center mb-3">
                <i class="icon-people icon-2x text-warning-400 border-warning-400 border-3 rounded-round p-3 mb-3 mt-1"></i>
                <h5 class="mb-0">{{ __('Login') }}</h5>
                <span class="d-block text-muted">Your credentials</span>
            </div>

            <div class="form-group form-group-feedback form-group-feedback-left">
                <input type="email"  class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}">
                <div class="form-control-feedback">
                    <i class="icon-user text-muted"></i>
                </div>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group form-group-feedback form-group-feedback-left">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password') }}" required autocomplete="current-password">


                <div class="form-control-feedback">
                    <i class="icon-lock2 text-muted"></i>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>


            <div class="form-group d-flex align-items-center">
                <div class="form-check mb-0">
                    <label class="form-check-label">
                        <input type="checkbox" name="remember" class="form-input-styled" checked data-fouc>
                        {{ __('Remember Me') }}
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a class="ml-auto" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">{{ __('Login') }}<i class="icon-circle-right2 ml-2"></i></button>
            </div>

            {{-- <div class="form-group text-center text-muted content-divider">
                <span class="px-2">or sign in with</span>
            </div> --}}

            {{-- <div class="form-group text-center">
                <button type="button" class="btn btn-outline bg-indigo border-indigo text-indigo btn-icon rounded-round border-2"><i class="icon-facebook"></i></button>
                <button type="button" class="btn btn-outline bg-pink-300 border-pink-300 text-pink-300 btn-icon rounded-round border-2 ml-2"><i class="icon-dribbble3"></i></button>
                <button type="button" class="btn btn-outline bg-slate-600 border-slate-600 text-slate-600 btn-icon rounded-round border-2 ml-2"><i class="icon-github"></i></button>
                <button type="button" class="btn btn-outline bg-info border-info text-info btn-icon rounded-round border-2 ml-2"><i class="icon-twitter"></i></button>
            </div> --}}

            {{-- <div class="form-group text-center text-muted content-divider">
                <span class="px-2">Don't have an account?</span>
            </div> --}}
            {{-- <span class="form-text text-center text-muted">By continuing, you're confirming that you've read our <a href="#">Terms &amp; Conditions</a> and <a href="#">Cookie Policy</a></span> --}}
        </div>
    </div>
</form>


@endsection

