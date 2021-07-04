<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-light navbar-static">

    <!-- Header with logos -->
    <div class="navbar-header navbar-dark d-none d-md-flex align-items-md-center ">
        <div class="navbar-brand navbar-brand-md text-center">
            <a href="{{ route('dashboard') }}" class="d-inline-block">
                <img src="{{ asset('global_assets/images/autobox365.png')}}" alt="">
            </a>
        </div>

        <div class="navbar-brand navbar-brand-xs">
            <a href="{{ route('dashboard') }}" class="d-inline-block">
                <img src="{{ asset('global_assets/images/logo_icon_light.png')}}" alt="">
            </a>
        </div>
    </div>
    <!-- /header with logos -->


    <!-- Mobile controls -->
    <div class="d-flex flex-1 d-md-none">
        <div class="navbar-brand mr-auto">
            <a href="{{ route('dashboard') }}" class="d-inline-block">
                <img src="{{ asset('global_assets/images/logo_dark.png')}}" alt="">
            </a>
        </div>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>

        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>
    <!-- /mobile controls -->


    <!-- Navbar content -->
    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <form action="{{ route('search') }}" style="display: flex; align-items: center;" method="post" role="search">
                    {{ csrf_field() }}
                    <i class="fa fa-search" style="margin-right: -1rem"></i>
                    <input type="text" class="form-control" name="q" placeholder="{{  __('app.search')  }} ..." style="padding-left: 1.5rem; width: 300px;" value="{{ isset($search_param) ? $search_param : '' }}">
                    <button type="submit" class="btn btn-primary">{{ __('app.search') }}</button>
                </form>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ asset('global_assets/images/lang/'.\App::getLocale().'.svg')}}" class="img-flag mr-2" alt="">
                    @if (\App::isLocale('en'))
                    English
                    @else
                    Russian
                    @endif
                    / {{ config('app.currency') }}
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-content wmin-md-350">
                    <div class="dropdown-content-header">
                        <span class="font-weight-semibold">{{ __('app.language') }} & {{ __('app.currency') }}</span>
                    </div>

                    <div class="dropdown-content-body ">
                        <form class="form" action="{{ route('lang_currency') }}">
                            <div class="form-group">
                                <label>{{ __('app.language') }}</label>
                                <select class="form-control" name="lang">
                                    <option value="en">English</option>
                                    <option value="ru">Russian</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ __('app.currency') }}</label>
                                <select class="form-control" name="currency">
                                    @foreach (\App\Models\Currency::all() as $currency)
                                    <option value="{{ $currency->iso_code }}" @if(config('app.currency') ==$currency->iso_code ) selected @endif>{{ $currency->iso_code }}
                                        ({{ $currency->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">{{ __('app.save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ Auth::user()->profile_image_src}}" class="rounded-circle mr-2" height="34" alt="">
                    <div class="user-name-meta">
                        <span>{{ Auth::user()->name }}</span>
                        <p>{{ Auth::user()->full_name  }}</p>
                    </div>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('settings.index') }}" class="dropdown-item"><i class="icon-cog5"></i> {{ __('app.account_settings') }}</a>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item"><i class="icon-user-plus"></i>{{ __('app.edit_profile') }}</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();" class="dropdown-item">
                        <i class="icon-switch2"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
    </div>
<!-- /navbar content -->

</div>
<!-- /main navbar -->
