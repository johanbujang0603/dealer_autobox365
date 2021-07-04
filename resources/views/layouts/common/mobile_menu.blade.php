<div class="mobile-menu md:hidden">
    <div class="mobile-menu-bar">
        <a href="" class="flex mr-auto">
            <img alt="Autobox365" src="{{ asset('global_assets/images/white_logo.png') }}" width="120">
        </a>

        <a class="notification search-toggle mr-3 text-white" href="javascript:;"> <i data-feather="search" class="notification__icon"></i> </a>

        <a href="javascript:;" class="mr-5" id="mobile-menu-toggler"> <i data-feather="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i> </a>

        <div class="dropdown w-8 h-8 relative">
            <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in">
                <img alt="User Avatar" src="{{ Auth::user()->profile_image_src}}">
            </div>
            <div class="dropdown-box mt-10 absolute w-56 top-0 right-0 z-52">
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
    </div>
        
    <div class="intro-y hidden relative mr-3 sm:mr-6 w-full search-box-popup">
        <form action="{{ route('search') }}" method="post" role="search">
            {{ csrf_field() }}
            <div class="search mx-2 mb-2">
                <input type="text" class="search__input input placeholder-theme-13" name="q" placeholder="{{  __('app.search')  }} ..." value="{{ isset($search_param) ? $search_param : '' }}">
                <i data-feather="search" class="search__icon"></i>
            </div>
        </form>
    </div>
    <ul class="border-t border-theme-24 py-5 hidden" data-nav-type="accordion">
      <!-- Main -->
      <li>
          <a href="{{ route('home') }}" class="menu {{ Route::is('home') ? 'menu--active' : '' }}">
            <div class="menu__icon">
                <i data-feather="home"></i>
            </div>
            <div class="menu__title">
            {{ __('app.dashboard') }}
            </div>
          </a>
      </li>
      <li>
          <a href="javascript:;" class="menu {{ Route::is('inventories.*') ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="box"></i> </div>
            <div class="menu__title">
                {{ __('menu.inventories.inventory') }}
                <i data-feather="chevron-down" class="menu__sub-icon"></i>
            </div>
            @if($published_inventories > 0 )
            <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $published_inventories }}</div>
            @endif
          </a>
          <ul class="{{ Route::is('inventories.*') ? 'menu__sub-open' : '' }}">
            <li>
                <a href="{{ route('inventories.dashboard') }}" class="menu {{ Route::is('inventories.dashboard') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title"> {{ __('app.dashboard') }} </div>
                </a>
            </li>
            <li>
                <a href="{{ route('inventories.index') }}" class="menu {{ Route::is('inventories.index') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title"> {{ __('menu.inventory.all') }} </div>
                    @if ($all_inventories)
                        <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $all_inventories }}</div>
                    @endif
                </a>
            </li>
            @if (Auth::user()->hasPermission('inventory', 'write'))
            <li>
                <a href="{{ route('inventories.create') }}" class="menu {{ Route::is('inventories.create') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.inventory.create') }}</div>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('inventories.deleted') }}" class="menu {{ Route::is('inventories.deleted') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.inventory.create') }}</div>
                    @if ($deleted_inventories)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $deleted_inventories }}</div>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('inventories.import') }}" class="menu {{ Route::is('inventories.import') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.inventory.import') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('inventories.status') }}" class="menu {{ Route::is('inventories.status') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('app.status') }}</div>
                    @if ($inventory_status_count)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $inventory_status_count }}</div>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('inventories.tags') }}" class="menu {{ Route::is('inventories.tags') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('app.tags') }}</div>
                    @if ($inventory_tags)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $inventory_tags }}</div>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('inventories.options') }}" class="menu {{ Route::is('inventories.options') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.inventory.options') }}</div>
                    @if ($inventory_options)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $inventory_options }}</div>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('inventories.logs') }}" class="menu {{ Route::is('inventories.logs') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('app.log_timeline') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('inventories.valuation') }}" class="menu {{ Route::is('inventories.valuation') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('app.valuation') }}</div>
                </a>
            </li>
          </ul>
      </li>
      <li>
          <a href="javascript:;" class="menu {{ Route::is('leads.*') ? 'menu--active' : '' }}">
                <div class="menu__icon"> <i data-feather="users"></i> </div>
                <div class="menu__title">
                    {{ __('menu.leads.leads') }}
                    <i data-feather="chevron-down" class="menu__sub-icon"></i>
                </div>
          </a>
          <ul class="{{ Route::is('leads.*') ? 'menu__sub-open' : '' }}">
            <li>
                <a href="{{ route('leads.dashboard') }}" class="menu {{ Route::is('leads.dashboard') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('app.dashboard') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('leads.index') }}" class="menu {{ Route::is('leads.index') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.leads.index') }}</div>
                    @if ($all_leads)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $all_leads }}</div>
                    @endif
                </a>
            </li>
            @if (Auth::user()->hasPermission('lead', 'write'))
            <li>
                <a href="{{ route('leads.create') }}" class="menu {{ Route::is('leads.create') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.leads.create') }}</div>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('leads.import.index') }}" class="menu {{ Route::is('leads.import.index') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.leads.import') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('leads.status') }}" class="menu {{ Route::is('leads.status') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('app.status') }}</div>
                    @if ($lead_status)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $lead_status }}</div>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('leads.tags') }}" class="menu {{ Route::is('leads.tags') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('app.tags') }}</div>
                    @if ($lead_tags)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $lead_tags }}</div>
                    @endif
                </a>
            </li>
          </ul>
      </li>
      <li>
          <a href="javascript:;" class="menu {{ Route::is('customers.*') ? 'menu--active' : '' }}">
                <div class="menu__icon"> <i data-feather="users"></i> </div>
                <div class="menu__title">
                    {{ __('menu.customers.customers') }}
                    <i data-feather="chevron-down" class="menu__sub-icon"></i>
                </div>
          </a>
          <ul class="{{ Route::is('customers.*') ? 'menu__sub-open' : '' }}">
            <li>
                <a href="{{ route('customers.dashboard') }}" class="menu {{ Route::is('customers.dashboard') ? 'menu--active' : '' }}">            
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.customers.dashboard') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('customers.index') }}" class="menu {{ Route::is('customers.index') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.customers.index') }}</div>
                    @if ($all_customers)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $all_customers }}</div>
                    @endif
                </a>
            </li>
            @if (Auth::user()->hasPermission('customer', 'write'))
            <li>
                <a href="{{ route('customers.create') }}" class="menu {{ Route::is('customers.create') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.customers.create') }}</div>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('customers.tags') }}" class="menu {{ Route::is('customers.tags') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('app.tags') }}</div>
                    @if ($customer_tags)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $customer_tags }}</div>
                    @endif
                </a>
            </li>
          </ul>
      </li>
      <li>
        <a href="javascript:;" class="menu {{ Route::is('calendar.*') ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="calendar"></i> </div>
            <div class="menu__title">
                {{ __('menu.calendar.index') }}
                <i data-feather="chevron-down" class="menu__sub-icon"></i>
            </div>
        </a>
        <ul class="{{ Route::is('calendar.*') ? 'menu__sub-open' : '' }}">
            <li>
                <a href="{{ route('calendar.events.index') }}" class="menu {{ Route::is('calendar.events.index') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.calendar.events') }}</div>
                    @if ($all_events)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $all_events }}</div>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('calendar.types.index') }}" class="menu {{ Route::is('calendar.types.index') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.calendar.event_types') }}</div>
                    @if ($event_types)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $event_types }}</div>
                    @endif
                </a>
            </li>
        </ul>
      </li>
      <li>
        <a href="javascript:;" class="menu {{ Route::is('users.*') ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="users"></i> </div>
            <div class="menu__title">
                {{ __('menu.users.users') }}
                <i data-feather="chevron-down" class="menu__sub-icon"></i>
            </div>
        </a>
        <ul class="{{ Route::is('users.*') ? 'menu__sub-open' : '' }}">
            <li>
                <a href="{{ route('users.dashboard') }}" class="menu {{ Route::is('users.dashboard') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.users.index') }}</div>
                    @if ($all_users)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $all_users }}</div>
                    @endif
                </a>
            </li>
            @if (Auth::user()->hasPermission('user', 'write'))
            <li>
                <a href="{{ route('users.create') }}" class="menu {{ Route::is('users.create') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.users.create') }}</div>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('users.roles.index') }}" class="menu {{ Route::is('users.roles.index') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.users.roles') }}</div>
                    @if ($all_roles)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $all_roles }}</div>
                    @endif
                </a>
            </li>
        </ul>
      </li>
      <li>
        <a href="javascript:;" class="menu {{ Route::is('locations.*') ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="map"></i> </div>
            <div class="menu__title">
                {{ __('menu.locations.locations') }}
                <i data-feather="chevron-down" class="menu__sub-icon"></i>
            </div>
        </a>
        <ul class="{{ Route::is('locations.*') ? 'menu__sub-open' : '' }}">
            <li>
                <a href="{{ route('locations.index') }}" class="menu {{ Route::is('locations.index') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.locations.index') }}</div>
                    @if ($all_locations)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $all_locations }}</div>
                    @endif
                </a>
            </li>
            @if (Auth::user()->hasPermission('location', 'write'))
            <li>
                <a href="{{ route('locations.create') }}" class="menu {{ Route::is('locations.create') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.locations.create') }}</div>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('locations.types.index') }}" class="menu {{ Route::is('locations.types.index') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.locations.types') }}</div>
                    @if ($location_types)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $location_types }}</div>
                    @endif
                </a>
            </li>
        </ul>
      </li>
      <li>
        <a href="javascript:;" class="menu {{ Route::is('reports.*') ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="map"></i> </div>
            <div class="menu__title">
            {{ __('menu.reports.reports') }}
                <i data-feather="chevron-down" class="menu__sub-icon"></i>
            </div>
        </a>
        <ul>
        </ul>
      </li>
      <li>
        <a href="javascript:;" class="menu {{ Route::is('documents.*') ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="book"></i> </div>
            <div class="menu__title">
            {{ __('menu.documents.documents') }}
                <i data-feather="chevron-down" class="menu__sub-icon"></i>
            </div>
        </a>
        <ul class="{{ Route::is('documents.*') ? 'menu__sub-open' : '' }}">
            <li>
                <a href="{{ route('documents.index') }}" class="menu {{ Route::is('documents.index') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.documents.index') }}</div>
                    @if ($all_documents)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $all_documents }}</div>
                    @endif
                </a>
            </li>
            @if (Auth::user()->hasPermission('document', 'write'))
            <li>
                <a href="{{ route('documents.create') }}" class="menu {{ Route::is('documents.create') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.documents.create') }}</div>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('documents.tags') }}" class="menu {{ Route::is('documents.tags') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('app.tags') }}</div>
                    @if ($document_tags)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $document_tags }}</div>
                    @endif
                </a>
            </li>
        </ul>
      </li>
      <li>
        <a href="javascript:;" class="menu {{ Route::is('transactions.*') ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="shopping-bag"></i> </div>
            <div class="menu__title">
            {{ __('menu.transactions.transactions') }}
                <i data-feather="chevron-down" class="menu__sub-icon"></i>
            </div>
        </a>
        <ul class="{{ Route::is('transactions.*') ? 'menu__sub-open' : '' }}">
            <li>
                <a href="{{ route('transactions.index') }}" class="menu {{ Route::is('transactions.index') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.transactions.index') }}</div>
                    @if ($all_transactions)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $all_transactions }}</div>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('transactions.invoice.create') }}" class="menu {{ Route::is('transactions.invoice.create') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.transactions.create_invoice') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('transactions.create_quote') }}" class="menu {{ Route::is('transactions.create_quote') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.transactions.create_quote') }}</div>
                </a>
            </li>
        </ul>
      </li>
      <li>
        <a href="javascript:;" class="menu {{ Route::is('marketings.*') ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="radio"></i> </div>
            <div class="menu__title">
            {{ __('menu.marketings.marketings') }}
                <i data-feather="chevron-down" class="menu__sub-icon"></i>
            </div>
        </a>
        <ul class="{{ Route::is('marketings.*') ? 'menu__sub-open' : '' }}">
            <li>
                <a href="javascript:;" class="menu {{ Route::is('marketings.index') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.marketings.index') }}</div>
                </a>
            </li>
            <li>
                <a href="javascript:;" class="menu {{ Route::is('marketings.sms_campaigns') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="radio"></i> </div>
                    <div class="menu__title">
                    {{ __('menu.marketings.sms_campaigns') }}
                        <i data-feather="chevron-down" class="menu__sub-icon"></i>
                    </div>
                </a>
                <ul class="{{ Route::is('marketings.sms_campaigns') ? 'menu__sub-open' : '' }}">
                    <li>
                        <a href="{{ route('marketings.sms_campaigns.create') }}" class="menu {{ Route::is('marketings.sms_campaigns.create') ? 'menu--active' : '' }}">
                            <div class="menu__icon"> <i data-feather="activity"></i> </div>
                            <div class="menu__title">{{ __('menu.marketings.new_campaign') }}</div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;" class="menu">
                            <div class="menu__icon"> <i data-feather="activity"></i> </div>
                            <div class="menu__title">{{ __('menu.marketings.sent_campaigns') }}</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;" class="menu {{ Route::is('marketings.email_campaigns') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="mail"></i> </div>
                    <div class="menu__title">
                    {{ __('menu.marketings.email_campaigns') }}
                        <i data-feather="chevron-down" class="menu__sub-icon"></i>
                    </div>
                </a>
                <ul class="{{ Route::is('marketings.email_campaigns') ? 'menu__sub-open' : '' }}">
                    <li>
                        <a href="{{ route('marketings.email_campaigns.create') }}" class="menu {{ Route::is('marketings.email_campaigns.create') ? 'menu--active' : '' }}">
                            <div class="menu__icon"> <i data-feather="activity"></i> </div>
                            <div class="menu__title">{{ __('menu.marketings.new_campaign') }}</div>
                        </a>
                    </li>
                    <li>
                    
                        <a href="javascript:;" class="menu">
                            <div class="menu__icon"> <i data-feather="activity"></i> </div>
                            <div class="menu__title">{{ __('menu.marketings.sent_campaigns') }}</div>
                        </a>
                    </li>
                </ul>
            </li>
            
            <li>
                <a href="javascript:;" class="menu {{ Route::is('marketings.whatsapp_campaigns') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="phone"></i> </div>
                    <div class="menu__title">
                    {{ __('menu.marketings.whatsapp_campaigns') }}
                        <i data-feather="chevron-down" class="menu__sub-icon"></i>
                    </div>
                </a>
                <ul class="{{ Route::is('marketings.whatsapp_campaigns') ? 'menu__sub-open' : '' }}">
                    <li>
                        <a href="{{ route('marketings.whatsapp_campaigns') }}" class="menu {{ Route::is('marketings.whatsapp_campaigns') ? 'menu--active' : '' }}">
                            <div class="menu__icon"> <i data-feather="activity"></i> </div>
                            <div class="menu__title">{{ __('menu.marketings.new_campaign') }}</div>
                        </a>
                    </li>
                    <li>
                    
                        <a href="javascript:;" class="menu">
                            <div class="menu__icon"> <i data-feather="activity"></i> </div>
                            <div class="menu__title">{{ __('menu.marketings.sent_campaigns') }}</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;" class="menu {{ Route::is('marketings.reports') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.reports.reports') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('marketings.settings.index') }}" class="menu {{ Route::is('marketings.settings.index') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('app.settings') }}</div>
                </a>
            </li>
            <li>
                <a href="javascript:;" class="menu {{ Route::is('marketings.logs') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('app.log_timeline') }}</div>
                </a>
            </li>
        </ul>
      </li>
      <li>
        <a href="javascript:;" class="menu {{ Route::is('tools.*') ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="tool"></i> </div>
            <div class="menu__title">
                {{ __('menu.tools.tools') }}
                <i data-feather="chevron-down" class="menu__sub-icon"></i>
            </div>
        </a>
        <ul class="{{ Route::is('tools.*') ? 'menu__sub-open' : '' }}">
            <li>
                <a href="{{ route('tools.car_recognition') }}" class="menu {{ Route::is('tools.car_recognition') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title"> {{ __('menu.tools.car_recog') }}</div>
               </a>
            </li>
            <li>
                <a href="{{ route('tools.money_conversion') }}" class="menu {{ Route::is('tools.money_conversion') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title"> {{ __('menu.tools.money_conversion') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('tools.distance_conversion') }}" class="menu {{ Route::is('tools.distance_conversion') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.tools.distance_conversion') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('tools.vin_identification') }}" class="menu {{ Route::is('tools.vin_identification') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.tools.vin_identification') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('tools.valuation') }}" class="menu {{ Route::is('tools.valuation') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.tools.valuation') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('tools.verify_phone') }}" class="menu {{ Route::is('tools.verify_phone') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.tools.verify_phone') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('tools.mortgage_calculator') }}" class="menu {{ Route::is('tools.mortgage_calculator') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.tools.mortgage_calculator') }}</div>
                </a>
            </li>
        </ul>
      </li>
      <li>
        <a href="{{ route('settings.index') }}" class="menu {{ Route::is('settings.index') ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="settings"></i> </div>
            <div class="menu__title">
                {{ __('menu.settings.settings') }}
            </div>
        </a>
      </li>
      <li>
        <a href="{{ route('profile.edit') }}" class="menu {{ Route::is('profile.edit') ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="trello"></i> </div>
            <div class="menu__title">
                {{ __('app.profile') }}
            </div>
        </a>
      </li>
      <li>
        <a href="javascript:;" class="menu {{ Route::is('help.*') ? 'menu--active' : '' }}">
            <div class="menu__icon"> <i data-feather="help-circle"></i> </div>
            <div class="menu__title">
                {{ __('menu.help.help') }}
                <i data-feather="chevron-down" class="menu__sub-icon"></i>
            </div>
        </a>
        <ul class="{{ Route::is('help.*') ? 'menu__sub-open' : '' }}">
            <li>
                <a href="javascript:;" class="menu {{ Route::is('help.faq') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.help.faq') }}</div>
                </a>
            </li>
            <li>
                <a href="javascript:;"class="menu {{ Route::is('help.tutorials') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.help.tutorials') }}</div>
                </a>
            </li>
            <li>
                <a href="javascript:;" class="menu {{ Route::is('help.support') ? 'menu--active' : '' }}">
                    <div class="menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="menu__title">{{ __('menu.help.support') }}</div>
                </a>
            </li>
        </ul>
      </li>
      <!-- /main -->
    </ul>
</div>
