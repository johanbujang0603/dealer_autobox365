@extends('layouts.auth')

@section('content')

<form method="POST" class="login-form" action="{{ route('password.email') }}">
    @csrf
    <div class="my-auto mx-auto xl:ml-20 bg-white xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                            
        <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
            {{ __('Reset Password') }}
        </h2>
        
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <div class="intro-x mt-2 text-gray-500 xl:hidden text-center">A few more clicks to sign in to your account. Manage all your e-commerce accounts in one place</div>
        <div class="intro-x mt-8">
        
            <input type="email" class="intro-x login__input input input--lg border border-gray-300 block form-control @error('email') border-theme-6 @enderror" name="email" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}" autofocus autocomplete="email" required>

            
            @error('email')
            <span class="text-theme-6 mt-2" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
            <button type="submit" class="button button--lg w-full text-white bg-theme-1 xl:mr-3">
                {{ __('Send Password Reset Link') }}
            </button>
        </div>
    </div>
</form>
@endsection
