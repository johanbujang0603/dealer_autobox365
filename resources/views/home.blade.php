@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.home') }} - {{ __('app.dashboard') }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')

<div class="intro-y flex items-center border-b">
    <div class="p-5 -intro-x breadcrumb mr-auto hidden sm:flex">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('app.dashboard') }}</a> 
    </div>
    <div class="p-5 hidden xl:block">
        <div class="preview flex flex-wrap">
            @if (Auth::user()->hasPermission('inventory', 'write'))
            <a href="{{ route('inventories.create') }}" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white">
                <i class="icon-car w-4 h-4 mr-2"></i>
                <span>{{ __('inventory.new_inventory') }}</span>
            </a>
            @endif
            
            @if (Auth::user()->hasPermission('lead', 'write'))
            <a href="{{ route('leads.create') }}" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white">
                <i data-feather="user-plus" class="w-4 h-4 mr-2"></i>
                <span>{{ __('lead.new_lead') }}</span>
            </a>
            @endif
            
            @if (Auth::user()->hasPermission('customer', 'write'))
            <a href="{{ route('customers.create') }}" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" >
                <i data-feather="user-plus" class="w-4 h-4 mr-2"></i>
                <span>{{ __('customer.new_customer') }}</span>
            </a>
            @endif
            
            @if (Auth::user()->hasPermission('location', 'write'))
            <a href="{{ route('locations.create') }}" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" >
                <i data-feather="map" class="w-4 h-4 mr-2"></i>
                <span>{{ __('location.new_location') }}</span>
            </a>
            @endif

            @if (Auth::user()->hasPermission('user', 'write'))
            <a href="{{ route('users.create') }}" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" >
                <i data-feather="user" class="w-4 h-4 mr-2"></i>
                <span>{{ __('user.new_user') }}</span>
            </a>
            @endif
        </div>
    </div>
    <div class="p-5 xl:hidden flex w-full justify-end">
        <div class="dropdown w-8 h-8 relative">
            <div class="dropdown-toggle w-8 h-8 overflow-hidden shadow-lg zoom-in">
                <a class="button bg-theme-4 text-white justify-center flex items-center font-bold">
                    <i class="icon-plus3"></i>
                </a>
            </div>
            <div class="dropdown-box box p-2 mt-10 absolute text-white bg-theme-4 w-56 top-0 right-0 z-20">
            
                @if (Auth::user()->hasPermission('inventory', 'write'))
                <a href="{{ route('inventories.create') }}" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 rounded-md">
                    <i class="icon-car w-4 h-4 mr-2"></i>
                    <span>{{ __('inventory.new_inventory') }}</span>
                </a>
                @endif
                
                @if (Auth::user()->hasPermission('lead', 'write'))
                <a href="{{ route('leads.create') }}" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 rounded-md">
                    <i data-feather="user-plus" class="w-4 h-4 mr-2"></i>
                    <span>{{ __('lead.new_lead') }}</span>
                </a>
                @endif
                
                @if (Auth::user()->hasPermission('customer', 'write'))
                <a href="{{ route('customers.create') }}" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 rounded-md" >
                    <i data-feather="user-plus" class="w-4 h-4 mr-2"></i>
                    <span>{{ __('customer.new_customer') }}</span>
                </a>
                @endif
                
                @if (Auth::user()->hasPermission('location', 'write'))
                <a href="{{ route('locations.create') }}" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 rounded-md" >
                    <i data-feather="map" class="w-4 h-4 mr-2"></i>
                    <span>{{ __('location.new_location') }}</span>
                </a>
                @endif

                @if (Auth::user()->hasPermission('user', 'write'))
                <a href="{{ route('users.create') }}" class="flex items-center block p-2 transition duration-300 ease-in-out hover:bg-theme-1 rounded-md" >
                    <i data-feather="user" class="w-4 h-4 mr-2"></i>
                    <span>{{ __('user.new_user') }}</span>
                </a>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection


@section('page_content')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 grid grid-cols-12 gap-6">
        <!-- BEGIN: General Report -->
        <div class="col-span-12 mt-8">
            <div class="grid grid-cols-12 gap-6 mt-5">
                <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <img src="{{ asset('images/icons/icons8-car-dealership-building-50.png') }}"/>
                                <div class="ml-auto">
                                    <div class="report-box__indicator bg-theme-9 tooltip cursor-pointer" title="Average {{$cur_symbol}} {{$avg_price_inventory}}">
                                        {{$cur_symbol}} {{$avg_price_inventory}}
                                    </div>
                                </div>
                            </div>
                            <div class="text-3xl font-bold leading-8 mt-6">{{$cur_symbol}} {{$total_price_inventory}}</div>
                            <div class="text-base text-gray-600 mt-1">Total value of your vehicles</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <img src="{{ asset('images/icons/icons8-car-dealership-building-50.png') }}"/>
                            </div>
                            <div class="text-3xl font-bold leading-8 mt-6">{{$total_inventories}}</div>
                            <div class="text-base text-gray-600 mt-1">Vehicles</div>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <img src="{{ asset('images/icons/icons8-user-account-50.png') }}"/>
                                <div class="ml-auto">
                                </div>
                            </div>
                            <div class="text-3xl font-bold leading-8 mt-6">{{$total_leads}}</div>
                            <div class="text-base text-gray-600 mt-1">{{ __('app.leads') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <img src="{{ asset('images/icons/icons8-user-account-50.png') }}"/>
                                <div class="ml-auto">
                                    <div class="report-box__indicator bg-theme-9 tooltip cursor-pointer" title="Conversion Rate">
                                        {{$total_customers / ($total_customers + $total_leads) * 100}} %
                                    </div>
                                </div>
                            </div>
                            <div class="text-3xl font-bold leading-8 mt-6">{{ $total_customers }}</div>
                            <div class="text-base text-gray-600 mt-1">{{ __('app.customers') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                    <div class="report-box zoom-in">
                        <div class="box p-5">
                            <div class="flex">
                                <i data-feather="user" class="report-box__icon text-theme-9"></i> 
                                <div class="ml-auto">
                                    <div class="report-box__indicator bg-theme-9 tooltip cursor-pointer" title="22% Higher than last month"> 22% <i data-feather="chevron-up" class="w-4 h-4"></i> </div>
                                </div>
                            </div>
                            <div class="text-3xl font-bold leading-8 mt-6">{{ $total_transactions }}</div>
                            <div class="text-base text-gray-600 mt-1">{{ __('app.transactions') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-4 lg:col-span-3 mt-8">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{ __('inventory.inventories_by_type') }}
            </h2>
        </div>
        <div class="intro-y box p-5 mt-5">
        @if (empty($inventory_by_type))
            <div class="svg-center text-danger" style="min-height: 168px;display: flex; align-items: center; justify-content: center;">
                {{ __('app.no_types_found') }}
            </div>
        @else
            <canvas class="mt-3" id="inventories_by_type" height="280"></canvas>
            <script type="text/json" id="inventories_by_type_data">
                {!! json_encode($inventory_by_type) !!}
            </script>
            <div class="mt-8" id="inventories_by_type_meta">
            </div>
        @endif
        </div>
    </div>

    <div class="col-span-12 sm:col-span-4 lg:col-span-3 mt-8">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                Top Vehicle Makes
            </h2>
        </div>
        <div class="intro-y box p-5 mt-5">
        @if (empty($inventory_by_make))
            <div class="svg-center text-danger" style="min-height: 168px;display: flex; align-items: center; justify-content: center;">
                {{ __('app.no_types_found') }}
            </div>
        @else
            <canvas class="mt-3" id="inventories_by_make" height="280"></canvas>
            <script type="text/json" id="inventories_by_make_data">
                {!! json_encode($inventory_by_make) !!}
            </script>
            <div class="mt-8" id="inventories_by_make_meta">
            </div>
        @endif
        </div>
    </div>

    <div class="col-span-12 sm:col-span-4 lg:col-span-3 mt-8">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{ __('lead.leads_by_status') }}
            </h2>
        </div>
        <div class="intro-y box p-5 mt-5">
        @if (empty($leads_by_status))
            <div class="svg-center text-danger" style="min-height: 168px;display: flex; align-items: center; justify-content: center;">
                {{ __('app.no_status_found') }}
            </div>
        @else
            <canvas class="mt-3" id="leads_by_status" height="280"></canvas>
            <script type="text/json" id="leads_by_status_data">
                {!! json_encode($leads_by_status) !!}
            </script>
            <div class="mt-8" id="leads_by_status_meta">
            </div>
        @endif
        </div>
    </div>

    <div class="col-span-12 sm:col-span-4 lg:col-span-3 mt-8">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{ __('lead.leads_by_tags') }}
            </h2>
        </div>
        <div class="intro-y box p-5 mt-5">
        @if (empty($leads_by_tags))
            <div class="svg-center text-danger" style="min-height: 168px;display: flex; align-items: center; justify-content: center;">
                {{ __('app.no_tags_found') }}
            </div>
        @else
            <canvas class="mt-3" id="leads_by_tags" height="280"></canvas>
            <script type="text/json" id="leads_by_tags_data">
                {!! json_encode($leads_by_tags) !!}
            </script>
            <div class="mt-8" id="leads_by_tags_meta">
            </div>
        @endif
        </div>
    </div>
    <div class="col-span-12 xl:col-span-6 mt-6">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{ __('app.last_documents_uploaded') }}
            </h2>
        </div>
        <div class="mt-5">
            @if ($num_documents == 0)
                <div class="box px-5 py-5">
                    <p class="text-theme-6 text-center">{{ __('app.no_documents_found') }}</p>
                </div>
            @else
                @foreach ($last_documents as $document)
                <div class="intro-y">
                    <div class="box px-4 py-4 mb-3 flex items-center zoom-in">
                        <div class="w-12 h-12 flex items-center justify-center rounded-md overflow-hidden">
                            
                            @if ($document->type == 'xls' || $document->type == 'xlsx')
                            <i class="icon-file-excel mr-3"></i>
                            @elseif ($document->type == 'doc' || $document->type == 'docx')
                            <i class="icon-file-word mr-3"></i>
                            @elseif ($document->type == 'jpg' || $document->type == 'jpeg' || $document->type == 'png')
                            <i class="icon-file-picture mr-3"></i>
                            @elseif ($document->type == 'pdf')
                            <i class="icon-file-pdf mr-3"></i>
                            @elseif ($document->type == 'zip')
                            <i class="icon-file-zip"></i>
                            @else
                            <i class="icon-files-empty"></i>
                            @endif
                            
                        </div>
                        <div class="ml-4 mr-auto">
                            <div class="font-medium">{{$document->original_name}}</div>
                        </div>
                    </div>
                </div>
                @endforeach
                <a href="{{ route('documents.index') }}" class="intro-y w-full block text-center rounded-md py-4 border border-dotted border-theme-15 text-theme-16">
                    {{ __('app.show_all_files') }} ({{$num_documents}})
                </a> 
            @endif
        </div>
    </div>
    <div class="col-span-12 xl:col-span-6 mt-6">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{ __('app.latest_updates') }}
            </h2>
        </div>
        <div class="mt-5">
            @if (count($logs) == 0)
                <div class="box px-5 py-5">
                    <p class="text-theme-6 text-center">{{ __('app.no_activities_found') }}</p>
                </div>
            @else
                @foreach ($logs as $log)

                @php
                    $color = '';
                    $icon = '';
                    if(strpos($log->action, 'created') !== false){
                        $color = 'primary';
                        $icon = 'plus-circle';
                    }
                    else if(strpos($log->action, 'updated') !== false){
                        $color = 'success';
                        $icon = 'edit';
                    }else if(strpos($log->action, 'deleted') !== false){
                        $color = 'danger';
                        $icon = 'delete';
                    }
                @endphp
                
                <div class="intro-y">
                    <div class="box px-4 py-4 mb-3 flex items-center zoom-in">
                        <div class="w-10 h-10 flex items-center rounded-md overflow-hidden">
                            <i data-feather="{{ $icon }}"></i>
                        </div>
                        <div class="ml-4 mr-auto">
                            <div class="font-medium">
                                
                                @if ($log->category=='leads')
                                    {!! __('actions.'.$log->action, ['user' => $log->user_details->full_name, 'model' =>
                                    "<a class='text-theme-1 font-semibold' href='#'>".
                                        $log->lead_details->name ."</a>"]) !!}
                                @elseif ($log->category == 'inventory')
                                    @php
                                    $make = isset($log->inventory_details->make_details->name) ? $log->inventory_details->make_details->name : '';
                                    $model = isset($log->inventory_details->model_details->name) ? $log->inventory_details->model_details->name : '';
                                    $year = $log->inventory_details->year;
                                    @endphp

                                    {!! __('actions.'.$log->action, ['user' => $log->user_details->full_name, 'model' =>
                                    "<a class='text-theme-1 font-semibold' href='#'>".
                                        $make.' '.$model.' '.$year ."</a>"]) !!}
                                @elseif ($log->category == 'location')
                                    {!! __('actions.'.$log->action, ['user' => $log->user_details->full_name, 'model' =>
                                    "<a class='text-theme-1 font-semibold' href='#'>".
                                        $log->location_details->name ."</a>"]) !!}
                                @elseif ($log->category == 'customers')
                                    {!! __('actions.'.$log->action, ['user' => $log->user_details->full_name, 'model' =>
                                    "<a class='text-theme-1 font-semibold' href='#'>".
                                        $log->customer_details->name ."</a>"]) !!}
                                @endif
                                
                            </div>
                            <div class="text-gray-600 text-xs">{{ $log->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif            
        </div>
    </div>
</div>

@endsection


@section('scripts_after')
<script>
    var leads_by_tag = {!! json_encode($leads_by_tag) !!}
    var leads_by_status = {!! json_encode($leads_by_status) !!}
    var inventory_by_type = {!! json_encode($inventory_by_type) !!}
</script>
<script src="{{ asset('js/dashboard/widgets_stats.js') }}"></script>
@endsection
