<!-- Main sidebar -->
<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->
    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user-material">
            <div class="sidebar-user-material-body">
                <div class="card-body text-center">
                    <a href="#">
                        <img src="{{ asset('global_assets/images/placeholders/placeholder.jpg') }}"
                            class="img-fluid rounded-circle shadow-1 mb-3" width="80" height="80" alt="">
                    </a>
                    <h6 class="mb-0 text-white text-shadow-dark">{{ Auth::user()->first_name }}
                        {{ Auth::user()->last_name }}</h6>
                    <span class="font-size-sm text-white text-shadow-dark">Santa Ana, CA</span>
                </div>


            </div>


        </div>
        <!-- /user menu -->


        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <li class="nav-item">
                    <a href="{{ route('admin.home') }}" class="nav-link active">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>
                            {{ __('menu.dashboard') }}
                        </span>
                    </a>
                </li>
                <li
                    class="nav-item nav-item-submenu {{ Route::is('admin.inventories.*') ? 'open nav-item-open' : '' }}">
                    <a href="#" class="nav-link"><i class="fas fa-car"></i>
                        <span>{{ __('menu.inventories.inventory') }}</span><span
                            class="badge badge-pill bg-blue-400 align-self-center ml-auto">2.2</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Layouts"
                        style="display: {{ Route::is('admin.inventories.*') ? 'block' : 'none' }};">
                        <li class="nav-item"><a href="{{ route('admin.inventories.valuation.index') }}"
                                class="nav-link {{ Route::is('admin.inventories.valuation.index') ? 'active' : '' }}">{{ __('app.valuation') }}</a>
                        </li>

                    </ul>
                </li>



                <!-- /main -->


            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>
<!-- /main sidebar -->
