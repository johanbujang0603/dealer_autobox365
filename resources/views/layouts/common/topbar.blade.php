<!-- BEGIN: Search -->
<div class="intro-x relative mr-3 sm:mr-6 hidden md:block">
    <form action="{{ route('search') }}" method="post" role="search">
        {{ csrf_field() }}
        
        <div class="search hidden sm:block">
          <input type="text" class="search__input input placeholder-theme-13" name="q" placeholder="{{  __('app.search')  }} ..." value="{{ isset($search_param) ? $search_param : '' }}">
          <i data-feather="search" class="search__icon"></i> 
        </div>
        <a class="notification sm:hidden" href=""> <i data-feather="search" class="notification__icon"></i> </a>
    </form>
</div>
<!-- END: Search -->
<!-- BEGIN: Account Menu -->
<div class="intro-x dropdown w-8 h-8 relative hidden md:block">
    <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in">
        <img alt="User Avatar" src="{{ Auth::user()->profile_image_src}}">
    </div>
    <div class="dropdown-box mt-10 absolute w-56 top-0 right-0 z-20">
        <div class="dropdown-box__content box bg-theme-38 text-white">
            <div class="p-4 border-b border-theme-40">
                <div class="font-medium">{{ Auth::user()->full_name }}</div>
                <div class="text-xs text-theme-41">{{ Auth::user()->name  }}</div>
            </div>
            <div class="p-2">
                <a href="{{ route('profile.edit') }}" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 rounded-md"> <i data-feather="edit" class="w-4 h-4 mr-2"></i> Profile </a>
                <a href="{{ route('settings.index') }}" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 rounded-md"> <i data-feather="settings" class="w-4 h-4 mr-2"></i> Settings </a>
            </div>
            <div class="p-2 border-t border-theme-40">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="{{ route('logout') }}" 
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 rounded-md"> 
                    <i data-feather="toggle-right" class="w-4 h-4 mr-2"></i> 
                    Logout
                </a>
            </div>
        </div>
    </div>
</div>