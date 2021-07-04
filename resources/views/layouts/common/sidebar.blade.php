<!-- Main sidebar -->

<nav class="side-nav">
    <a href="" class="intro-x flex items-center pl-5 pt-4">
        <img alt="Autobox365 Logo" src="{{ asset('global_assets/images/white_logo.png') }}">
    </a>
    <div class="my-6"></div>
    <ul>
      <!-- Main -->
      <li>
          <a href="{{ route('home') }}" class="side-menu {{ Route::is('home') ? 'side-menu--active' : '' }}">
            <div class="side-menu__icon">
                <i data-feather="home"></i>
            </div>
            <div class="side-menu__title">
            {{ __('app.dashboard') }}
            </div>
          </a>
      </li>
      <li>
          <a href="javascript:;" class="side-menu {{ Route::is('inventories.*') ? 'side-menu--active' : '' }}">
            <div class="side-menu__icon"> <i data-feather="box"></i> </div>
            <div class="side-menu__title">
                {{ __('menu.inventories.inventory') }}
                <i data-feather="chevron-down" class="side-menu__sub-icon"></i>
            </div>
            @if($published_inventories > 0 )
            <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $published_inventories }}</div>
            @endif
          </a>
          <ul class="{{ Route::is('inventories.*') ? 'side-menu__sub-open' : '' }}">
            <li>
                <a href="{{ route('inventories.dashboard') }}" class="side-menu {{ Route::is('inventories.dashboard') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title"> {{ __('app.dashboard') }} </div>
                </a>
            </li>
            <li>
                <a href="{{ route('inventories.index') }}" class="side-menu {{ Route::is('inventories.index') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title"> {{ __('menu.inventory.all') }} </div>
                    @if ($all_inventories)
                        <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $all_inventories }}</div>
                    @endif
                </a>
            </li>
            @if (Auth::user()->hasPermission('inventory', 'write'))
            <li>
                <a href="{{ route('inventories.create') }}" class="side-menu {{ Route::is('inventories.create') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.inventory.create') }}</div>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('inventories.deleted') }}" class="side-menu {{ Route::is('inventories.deleted') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.inventory.deleted') }}</div>
                    @if ($deleted_inventories)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $deleted_inventories }}</div>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('inventories.import') }}" class="side-menu {{ Route::is('inventories.import') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.inventory.import') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('inventories.status') }}" class="side-menu {{ Route::is('inventories.status') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('app.status') }}</div>
                    @if ($inventory_status_count)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $inventory_status_count }}</div>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('inventories.tags') }}" class="side-menu {{ Route::is('inventories.tags') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('app.tags') }}</div>
                    @if ($inventory_tags)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $inventory_tags }}</div>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('inventories.options') }}" class="side-menu {{ Route::is('inventories.options') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.inventory.options') }}</div>
                    @if ($inventory_options)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $inventory_options }}</div>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('inventories.logs') }}" class="side-menu {{ Route::is('inventories.logs') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('app.log_timeline') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('inventories.valuation') }}" class="side-menu {{ Route::is('inventories.valuation') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('app.valuation') }}</div>
                </a>
            </li>
          </ul>
      </li>
      <li>
          <a href="javascript:;" class="side-menu {{ Route::is('leads.*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-feather="users"></i> </div>
                <div class="side-menu__title">
                    {{ __('menu.leads.leads') }}
                    <i data-feather="chevron-down" class="side-menu__sub-icon"></i>
                </div>
          </a>
          <ul class="{{ Route::is('leads.*') ? 'side-menu__sub-open' : '' }}">
            <li>
                <a href="{{ route('leads.dashboard') }}" class="side-menu {{ Route::is('leads.dashboard') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('app.dashboard') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('leads.index') }}" class="side-menu {{ Route::is('leads.index') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.leads.index') }}</div>
                    @if ($all_leads)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $all_leads }}</div>
                    @endif
                </a>
            </li>
            @if (Auth::user()->hasPermission('lead', 'write'))
            <li>
                <a href="{{ route('leads.create') }}" class="side-menu {{ Route::is('leads.create') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.leads.create') }}</div>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('leads.import.index') }}" class="side-menu {{ Route::is('leads.import.index') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.leads.import') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('leads.status') }}" class="side-menu {{ Route::is('leads.status') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('app.status') }}</div>
                    @if ($lead_status)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $lead_status }}</div>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('leads.tags') }}" class="side-menu {{ Route::is('leads.tags') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('app.tags') }}</div>
                    @if ($lead_tags)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $lead_tags }}</div>
                    @endif
                </a>
            </li>
          </ul>
      </li>
      <li>
          <a href="javascript:;" class="side-menu {{ Route::is('customers.*') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-feather="users"></i> </div>
                <div class="side-menu__title">
                    {{ __('menu.customers.customers') }}
                    <i data-feather="chevron-down" class="side-menu__sub-icon"></i>
                </div>
          </a>
          <ul class="{{ Route::is('customers.*') ? 'side-menu__sub-open' : '' }}">
            <li>
                <a href="{{ route('customers.dashboard') }}" class="side-menu {{ Route::is('customers.dashboard') ? 'side-menu--active' : '' }}">            
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.customers.dashboard') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('customers.index') }}" class="side-menu {{ Route::is('customers.index') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.customers.index') }}</div>
                    @if ($all_customers)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $all_customers }}</div>
                    @endif
                </a>
            </li>
            @if (Auth::user()->hasPermission('customer', 'write'))
            <li>
                <a href="{{ route('customers.create') }}" class="side-menu {{ Route::is('customers.create') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.customers.create') }}</div>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('customers.tags') }}" class="side-menu {{ Route::is('customers.tags') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('app.tags') }}</div>
                    @if ($customer_tags)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $customer_tags }}</div>
                    @endif
                </a>
            </li>
          </ul>
      </li>
      <li>
        <a href="javascript:;" class="side-menu {{ Route::is('calendar.*') ? 'side-menu--active' : '' }}">
            <div class="side-menu__icon"> <i data-feather="calendar"></i> </div>
            <div class="side-menu__title">
                {{ __('menu.calendar.index') }}
                <i data-feather="chevron-down" class="side-menu__sub-icon"></i>
            </div>
        </a>
        <ul class="{{ Route::is('calendar.*') ? 'side-menu__sub-open' : '' }}">
            <li>
                <a href="{{ route('calendar.events.index') }}" class="side-menu {{ Route::is('calendar.events.index') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.calendar.events') }}</div>
                    @if ($all_events)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $all_events }}</div>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('calendar.types.index') }}" class="side-menu {{ Route::is('calendar.types.index') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.calendar.event_types') }}</div>
                    @if ($event_types)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $event_types }}</div>
                    @endif
                </a>
            </li>
        </ul>
      </li>
      <li>
        <a href="javascript:;" class="side-menu {{ Route::is('users.*') ? 'side-menu--active' : '' }}">
            <div class="side-menu__icon"> <i data-feather="users"></i> </div>
            <div class="side-menu__title">
                {{ __('menu.users.users') }}
                <i data-feather="chevron-down" class="side-menu__sub-icon"></i>
            </div>
        </a>
        <ul class="{{ Route::is('users.*') ? 'side-menu__sub-open' : '' }}">
            <li>
                <a href="{{ route('users.dashboard') }}" class="side-menu {{ Route::is('users.dashboard') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.users.index') }}</div>
                    @if ($all_users)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $all_users }}</div>
                    @endif
                </a>
            </li>
            @if (Auth::user()->hasPermission('user', 'write'))
            <li>
                <a href="{{ route('users.create') }}" class="side-menu {{ Route::is('users.create') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.users.create') }}</div>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('users.roles.index') }}" class="side-menu {{ Route::is('users.roles.index') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.users.roles') }}</div>
                    @if ($all_roles)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $all_roles }}</div>
                    @endif
                </a>
            </li>
        </ul>
      </li>
      <li>
        <a href="javascript:;" class="side-menu {{ Route::is('locations.*') ? 'side-menu--active' : '' }}">
            <div class="side-menu__icon"> <i data-feather="map"></i> </div>
            <div class="side-menu__title">
                {{ __('menu.locations.locations') }}
                <i data-feather="chevron-down" class="side-menu__sub-icon"></i>
            </div>
        </a>
        <ul class="{{ Route::is('locations.*') ? 'side-menu__sub-open' : '' }}">
            <li>
                <a href="{{ route('locations.index') }}" class="side-menu {{ Route::is('locations.index') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.locations.index') }}</div>
                    @if ($all_locations)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $all_locations }}</div>
                    @endif
                </a>
            </li>
            @if (Auth::user()->hasPermission('location', 'write'))
            <li>
                <a href="{{ route('locations.create') }}" class="side-menu {{ Route::is('locations.create') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.locations.create') }}</div>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('locations.types.index') }}" class="side-menu {{ Route::is('locations.types.index') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.locations.types') }}</div>
                    @if ($location_types)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $location_types }}</div>
                    @endif
                </a>
            </li>
        </ul>
      </li>
      <li>
        <a href="javascript:;" class="side-menu {{ Route::is('reports.*') ? 'side-menu--active' : '' }}">
            <div class="side-menu__icon"> <i data-feather="map"></i> </div>
            <div class="side-menu__title">
            {{ __('menu.reports.reports') }}
                <i data-feather="chevron-down" class="side-menu__sub-icon"></i>
            </div>
        </a>
        <ul>
        </ul>
      </li>
      <li>
        <a href="javascript:;" class="side-menu {{ Route::is('documents.*') ? 'side-menu--active' : '' }}">
            <div class="side-menu__icon"> <i data-feather="book"></i> </div>
            <div class="side-menu__title">
            {{ __('menu.documents.documents') }}
                <i data-feather="chevron-down" class="side-menu__sub-icon"></i>
            </div>
        </a>
        <ul class="{{ Route::is('documents.*') ? 'side-menu__sub-open' : '' }}">
            <li>
                <a href="{{ route('documents.index') }}" class="side-menu {{ Route::is('documents.index') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.documents.index') }}</div>
                    @if ($all_documents)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $all_documents }}</div>
                    @endif
                </a>
            </li>
            @if (Auth::user()->hasPermission('document', 'write'))
            <li>
                <a href="{{ route('documents.create') }}" class="side-menu {{ Route::is('documents.create') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.documents.create') }}</div>
                </a>
            </li>
            @endif
            <li>
                <a href="{{ route('documents.tags') }}" class="side-menu {{ Route::is('documents.tags') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('app.tags') }}</div>
                    @if ($document_tags)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $document_tags }}</div>
                    @endif
                </a>
            </li>
        </ul>
      </li>
      <li>
        <a href="javascript:;" class="side-menu {{ Route::is('transactions.*') ? 'side-menu--active' : '' }}">
            <div class="side-menu__icon"> <i data-feather="shopping-bag"></i> </div>
            <div class="side-menu__title">
            {{ __('menu.transactions.transactions') }}
                <i data-feather="chevron-down" class="side-menu__sub-icon"></i>
            </div>
        </a>
        <ul class="{{ Route::is('transactions.*') ? 'side-menu__sub-open' : '' }}">
            <li>
                <a href="{{ route('transactions.index') }}" class="side-menu {{ Route::is('transactions.index') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.transactions.index') }}</div>
                    @if ($all_transactions)
                    <div class="bg-theme-5 text-red-700 rounded px-2 mr-2">{{ $all_transactions }}</div>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('transactions.invoice.create') }}" class="side-menu {{ Route::is('transactions.invoice.create') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.transactions.create_invoice') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('transactions.create_quote') }}" class="side-menu {{ Route::is('transactions.create_quote') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.transactions.create_quote') }}</div>
                </a>
            </li>
        </ul>
      </li>
      <li>
        <a href="javascript:;" class="side-menu {{ Route::is('marketings.*') ? 'side-menu--active' : '' }}">
            <div class="side-menu__icon"> <i data-feather="radio"></i> </div>
            <div class="side-menu__title">
            {{ __('menu.marketings.marketings') }}
                <i data-feather="chevron-down" class="side-menu__sub-icon"></i>
            </div>
        </a>
        <ul class="{{ Route::is('marketings.*') ? 'side-menu__sub-open' : '' }}">
            <li>
                <a href="javascript:;" class="side-menu {{ Route::is('marketings.index') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.marketings.index') }}</div>
                </a>
            </li>
            <li>
                <a href="javascript:;" class="side-menu {{ Route::is('marketings.sms_campaigns') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="radio"></i> </div>
                    <div class="side-menu__title">
                    {{ __('menu.marketings.sms_campaigns') }}
                        <i data-feather="chevron-down" class="side-menu__sub-icon"></i>
                    </div>
                </a>
                <ul class="{{ Route::is('marketings.sms_campaigns') ? 'side-menu__sub-open' : '' }}">
                    <li>
                        <a href="{{ route('marketings.sms_campaigns.create') }}" class="side-menu {{ Route::is('marketings.sms_campaigns.create') ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                            <div class="side-menu__title">{{ __('menu.marketings.new_campaign') }}</div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:;" class="side-menu">
                            <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                            <div class="side-menu__title">{{ __('menu.marketings.sent_campaigns') }}</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;" class="side-menu {{ Route::is('marketings.email_campaigns') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="mail"></i> </div>
                    <div class="side-menu__title">
                    {{ __('menu.marketings.email_campaigns') }}
                        <i data-feather="chevron-down" class="side-menu__sub-icon"></i>
                    </div>
                </a>
                <ul class="{{ Route::is('marketings.email_campaigns') ? 'side-menu__sub-open' : '' }}">
                    <li>
                        <a href="{{ route('marketings.email_campaigns.create') }}" class="side-menu {{ Route::is('marketings.email_campaigns.create') ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                            <div class="side-menu__title">{{ __('menu.marketings.new_campaign') }}</div>
                        </a>
                    </li>
                    <li>
                    
                        <a href="javascript:;" class="side-menu">
                            <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                            <div class="side-menu__title">{{ __('menu.marketings.sent_campaigns') }}</div>
                        </a>
                    </li>
                </ul>
            </li>
            
            <li>
                <a href="javascript:;" class="side-menu {{ Route::is('marketings.whatsapp_campaigns') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="phone"></i> </div>
                    <div class="side-menu__title">
                    {{ __('menu.marketings.whatsapp_campaigns') }}
                        <i data-feather="chevron-down" class="side-menu__sub-icon"></i>
                    </div>
                </a>
                <ul class="{{ Route::is('marketings.whatsapp_campaigns') ? 'side-menu__sub-open' : '' }}">
                    <li>
                        <a href="{{ route('marketings.whatsapp_campaigns') }}" class="side-menu {{ Route::is('marketings.whatsapp_campaigns') ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                            <div class="side-menu__title">{{ __('menu.marketings.new_campaign') }}</div>
                        </a>
                    </li>
                    <li>
                    
                        <a href="javascript:;" class="side-menu">
                            <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                            <div class="side-menu__title">{{ __('menu.marketings.sent_campaigns') }}</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;" class="side-menu {{ Route::is('marketings.reports') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.reports.reports') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('marketings.settings.index') }}" class="side-menu {{ Route::is('marketings.settings.index') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('app.settings') }}</div>
                </a>
            </li>
            <li>
                <a href="javascript:;" class="side-menu {{ Route::is('marketings.logs') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('app.log_timeline') }}</div>
                </a>
            </li>
        </ul>
      </li>
      <li>
        <a href="javascript:;" class="side-menu {{ Route::is('tools.*') ? 'side-menu--active' : '' }}">
            <div class="side-menu__icon"> <i data-feather="tool"></i> </div>
            <div class="side-menu__title">
                {{ __('menu.tools.tools') }}
                <i data-feather="chevron-down" class="side-menu__sub-icon"></i>
            </div>
        </a>
        <ul class="{{ Route::is('tools.*') ? 'side-menu__sub-open' : '' }}">
            <li>
                <a href="{{ route('tools.car_recognition') }}" class="side-menu {{ Route::is('tools.car_recognition') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title"> {{ __('menu.tools.car_recog') }}</div>
               </a>
            </li>
            <li>
                <a href="{{ route('tools.money_conversion') }}" class="side-menu {{ Route::is('tools.money_conversion') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title"> {{ __('menu.tools.money_conversion') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('tools.distance_conversion') }}" class="side-menu {{ Route::is('tools.distance_conversion') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.tools.distance_conversion') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('tools.vin_identification') }}" class="side-menu {{ Route::is('tools.vin_identification') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.tools.vin_identification') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('tools.valuation') }}" class="side-menu {{ Route::is('tools.valuation') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.tools.valuation') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('tools.verify_phone') }}" class="side-menu {{ Route::is('tools.verify_phone') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.tools.verify_phone') }}</div>
                </a>
            </li>
            <li>
                <a href="{{ route('tools.mortgage_calculator') }}" class="side-menu {{ Route::is('tools.mortgage_calculator') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.tools.mortgage_calculator') }}</div>
                </a>
            </li>
        </ul>
      </li>
      <li>
        <a href="{{ route('settings.index') }}" class="side-menu {{ Route::is('settings.index') ? 'side-menu--active' : '' }}">
            <div class="side-menu__icon"> <i data-feather="settings"></i> </div>
            <div class="side-menu__title">
                {{ __('menu.settings.settings') }}
            </div>
        </a>
      </li>
      <li>
        <a href="{{ route('profile.edit') }}" class="side-menu {{ Route::is('profile.edit') ? 'side-menu--active' : '' }}">
            <div class="side-menu__icon"> <i data-feather="trello"></i> </div>
            <div class="side-menu__title">
                {{ __('app.profile') }}
            </div>
        </a>
      </li>
      <li>
        <a href="javascript:;" class="side-menu {{ Route::is('help.*') ? 'side-menu--active' : '' }}">
            <div class="side-menu__icon"> <i data-feather="help-circle"></i> </div>
            <div class="side-menu__title">
                {{ __('menu.help.help') }}
                <i data-feather="chevron-down" class="side-menu__sub-icon"></i>
            </div>
        </a>
        <ul class="{{ Route::is('help.*') ? 'side-menu__sub-open' : '' }}">
            <li>
                <a href="javascript:;" class="side-menu {{ Route::is('help.faq') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.help.faq') }}</div>
                </a>
            </li>
            <li>
                <a href="javascript:;"class="side-menu {{ Route::is('help.tutorials') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.help.tutorials') }}</div>
                </a>
            </li>
            <li>
                <a href="javascript:;" class="side-menu {{ Route::is('help.support') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-feather="activity"></i> </div>
                    <div class="side-menu__title">{{ __('menu.help.support') }}</div>
                </a>
            </li>
        </ul>
      </li>
      <!-- /main -->
    </ul>
</nav>
<!-- /main sidebar -->
