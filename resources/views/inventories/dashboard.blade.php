@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.inventories') }} - {{ __('app.dashboard') }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')

<div class="intro-y flex items-center border-b">

    <div class="p-5 -intro-x breadcrumb mr-auto hidden sm:flex">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb">{{ __('app.inventories')}}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('app.dashboard')}}</a> 
    </div>
    <div class="p-5">
        <div class="preview flex flex-wrap">
            @if (Auth::user()->hasPermission('inventory', 'write'))
            <a href="{{ route('inventories.create') }}" class="tooltip button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Add New Inventory">
                <i class="icon-plus3 xl:mr-2"></i><span class="hidden xl:block">{{ __('app.add_new') }}</span>
            </a>
            @endif
            <a href="{{ route('inventories.import') }}" class="tooltip button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Import Inventory">
                <i class="icon-import xl:mr-2"></i>
                <span class="hidden xl:block">{{ __('app.import') }}</span>
            </a>
            <a href="{{ route('inventories.export') }}" class="tooltip button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Export Inventory">
                <i class="icon-database-export xl:mr-2"></i> <span class="hidden xl:block">{{ __('app.export') }}</span>
            </a>
        </div>
    </div>
</div>

@endsection

@section('page_content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 sm:col-span-4 xl:col-span-2 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i class="icon-bubbles4 icon-3x opacity-75"></i>
                </div>
                <div class="text-3xl font-bold leading-8 mt-6">{{ $currency_symbol }}{{ number_format($avg_price, 0, '.', ',') }}</div>
                <div class="text-base text-gray-600 mt-1">{{ __('inventory.avg_price') }}</div>
            </div>
        </div>
    </div>
    
    <div class="col-span-12 sm:col-span-4 xl:col-span-2 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i class="icon-bag icon-3x opacity-75"></i>
                </div>
                <div class="text-3xl font-bold leading-8 mt-6">{{ $currency_symbol }}{{ number_format($max_price, 0, '.', ',') }}</div>
                <div class="text-base text-gray-600 mt-1">{{ __('inventory.max_price') }}</div>
            </div>
        </div>
    </div>

    <div class="col-span-12 sm:col-span-4 xl:col-span-2 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i class="icon-pointer icon-3x opacity-75"></i>
                </div>
                <div class="text-3xl font-bold leading-8 mt-6">{{ $currency_symbol }}{{ number_format($min_price, 0, '.', ',') }}</div>
                <div class="text-base text-gray-600 mt-1">{{ __('inventory.min_price') }}</div>
            </div>
        </div>
    </div>


    <div class="col-span-12 sm:col-span-4 xl:col-span-2 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i class="icon-enter6 icon-3x opacity-75"></i>
                </div>
                <div class="text-3xl font-bold leading-8 mt-6">{{ $currency_symbol }}{{ number_format($median_price, 0, '.', ',') }}</div>
                <div class="text-base text-gray-600 mt-1">{{ __('inventory.median_price') }}</div>
            </div>
        </div>
    </div>

</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{__('inventory.mechanical_condition')}}
            </h2>
        </div>
        <div class="intro-y box p-5 mt-5">
        @if (empty(json_decode($mechanical_condition, true)))
            <div class="text-theme-6 flex items-center justify-center p-12">
                {{ __('lead.no_condition') }}
            </div>
        @else
            <canvas class="mt-3 m-auto" id="inventory_mechanical_condition_chart" width="280"></canvas>
            <script type="text/json" id="inventory_mechanical_condition_chart_data">
                {!! $mechanical_condition !!}
            </script>
            <div class="mt-8" id="inventory_mechanical_condition_chart_meta">
            </div>
        @endif
        </div>
    </div>
    
    <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{__('inventory.body_condition')}}
            </h2>
        </div>
        <div class="intro-y box p-5 mt-5">
        @if (empty(json_decode($body_condition, true)))
            <div class="text-theme-6 flex items-center justify-center p-12">
                {{ __('lead.no_condition') }}
            </div>
        @else
            <canvas class="mt-3 m-auto" id="inventory_body_condition_chart" width="280"></canvas>
            <script type="text/json" id="inventory_body_condition_chart_data">
                {!! $body_condition !!}
            </script>
            <div class="mt-8" id="inventory_body_condition_chart_meta">
            </div>
        @endif
        </div>
    </div>
    
    <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{__('inventory.inventory_by_status')}}
            </h2>
        </div>
        <div class="intro-y box p-5 mt-5">
        @if (empty(json_decode($inventory_by_status, true)))
            <div class="text-theme-6 flex items-center justify-center p-12">
                {{ __('lead.no_status') }}
            </div>
        @else
            <canvas class="mt-3 m-auto" id="inventory_status_chart" width="280"></canvas>
            <script type="text/json" id="inventory_status_chart_data">
                {!! $inventory_by_status !!}
            </script>
            <div class="mt-8" id="inventory_status_chart_meta">
            </div>
        @endif
        </div>
    </div>
    
    <div class="col-span-12 sm:col-span-4 xl:col-span-3 intro-y">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{ __('inventory.top_brands') }}
            </h2>
        </div>
        <div class="intro-y box p-5 mt-5">
        @if (empty(json_decode($brand_chart_details, true)))
            <div class="text-theme-6 flex items-center justify-center p-12">
                {{ __('lead.no_brands') }}
            </div>
        @else
            <canvas class="mt-3 m-auto" id="inventory_brands_chart" width="280"></canvas>
            <script type="text/json" id="inventory_brands_chart_data">
                {!! $brand_chart_details !!}
            </script>
            <div class="mt-8" id="inventory_brands_chart_meta">
            </div>
        @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 sm:col-span-12 xl:col-span-12 intro-y">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{__('inventory.published_inventories')}}
            </h2>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-6 xl:col-span-6 intro-y">
        <div class="intro-y box p-5 mt-5">
            <canvas class="mt-3 m-auto" id="inventory_photos_chart" width="280"></canvas>
            <script type="text/json" id="inventory_photos_chart_data">
                {!! $inventories_with_photos_chart !!}
            </script>
            <div class="mt-8" id="inventory_photos_chart_meta">
            </div>
        </div>
    </div>
    
    <div class="col-span-12 sm:col-span-6 xl:col-span-6 intro-y">
        <div class="intro-y box p-5 mt-5">
            <canvas class="mt-3 m-auto" id="inventory_stockage_chart" width="280"></canvas>
            <script type="text/json" id="inventory_stockage_chart_data">
                {!! $stock_age_chart_data !!}
            </script>
            <div class="mt-8" id="inventory_stockage_chart_meta">
            </div>
        </div>
    </div>

</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 md:col-span-3 sm:col-span-4 xl:col-span-2 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i class="icon-camera icon-3x text-theme-9"></i>
                </div>
                <div class="text-3xl font-bold leading-8 mt-6">{{ $inventories_without_photos }}</div>
                <div class="text-base text-gray-600 mt-1">{{ __('inventory.without_photos') }}</div>
            </div>
        </div>
    </div>
    
    <div class="col-span-12 md:col-span-3 sm:col-span-4 xl:col-span-2 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i class="icon-camera icon-3x text-theme-9"></i>
                </div>
                <div class="text-3xl font-bold leading-8 mt-6">{{ $inventories_less_than_5_photos }}</div>
                <div class="text-base text-gray-600 mt-1">{{ __('inventory.less_than_5_photos') }}</div>
            </div>
        </div>
    </div>

    <div class="col-span-12 md:col-span-3 sm:col-span-4 xl:col-span-2 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i class="icon-pointer icon-3x text-theme-9"></i>
                </div>
                <div class="text-3xl font-bold leading-8 mt-6">{{ $inventories_no_prices }}</div>
                <div class="text-base text-gray-600 mt-1">{{ __('inventory.no_price') }}</div>
            </div>
        </div>
    </div>

    <div class="col-span-12 md:col-span-3 sm:col-span-4 xl:col-span-2 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i class="icon-enter6 icon-3x text-theme-9"></i>
                </div>
                <div class="text-3xl font-bold leading-8 mt-6">{{ $inventories_with_missing_important_fields }}</div>
                <div class="text-base text-gray-600 mt-1">{{ __('inventory.missing_important_informations') }}</div>
            </div>
        </div>
    </div>

    <div class="col-span-12 md:col-span-3 sm:col-span-4 xl:col-span-2 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i class="icon-enter6 icon-3x text-theme-9"></i>
                </div>
                <div class="text-3xl font-bold leading-8 mt-6">{{ $price_updated_inventories_since_30_days }}</div>
                <div class="text-base text-gray-600 mt-1">{{ __('inventory.price_not_updated_since_30_days') }}</div>
            </div>
        </div>
    </div>

    <div class="col-span-12 md:col-span-3 sm:col-span-4 xl:col-span-2 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i class="icon-alarm icon-3x text-theme-9"></i>
                </div>
                <div class="text-3xl font-bold leading-8 mt-6">{{ $inventories_more_than_60_days }}</div>
                <div class="text-base text-gray-600 mt-1">{{ __('inventory.in_stock_more_than_60_days') }}</div>
            </div>
        </div>
    </div>

    <div class="col-span-12 md:col-span-3 sm:col-span-4 xl:col-span-2 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i class="icon-camera icon-3x text-theme-9"></i>
                </div>
                <div class="text-3xl font-bold leading-8 mt-6">{{ $average_time_in_stock }}</div>
                <div class="text-base text-gray-600 mt-1">{{ __('inventory.average_time_in_stock') }}</div>
            </div>
        </div>
    </div>
    
    <div class="col-span-12 md:col-span-3 sm:col-span-4 xl:col-span-2 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i class="icon-alert icon-3x text-theme-9"></i>
                </div>
                <div class="text-3xl font-bold leading-8 mt-6">{{ $average_mileage_of_cars }}</div>
                <div class="text-base text-gray-600 mt-1">{{ __('inventory.average_mileage_of_cars_in_stock') }}</div>
            </div>
        </div>
    </div>
    
    <div class="col-span-12 md:col-span-3 sm:col-span-4 xl:col-span-2 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i class="icon-alarm icon-3x text-theme-9"></i>
                </div>
                <div class="text-3xl font-bold leading-8 mt-6">{{ $average_marge_per_car }}</div>
                <div class="text-base text-gray-600 mt-1">{{ __('inventory.average_margin_per_car_in_stock') }}</div>
            </div>
        </div>
    </div>
    
    <div class="col-span-12 md:col-span-3 sm:col-span-4 xl:col-span-2 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i class="icon-alarm icon-3x text-theme-9"></i>
                </div>
                <div class="text-3xl font-bold leading-8 mt-6">{{ $profit_potential }}</div>
                <div class="text-base text-gray-600 mt-1">{{ __('inventory.profit_potential') }}</div>
            </div>
        </div>
    </div>
</div>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 sm:col-span-12 xl:col-span-12 intro-y">
        <div class="intro-y">
            <h2 class="text-lg font-medium mr-auto">
                {{ __('inventory.last_activity') }}
            </h2>
        </div>
        <div class="intro-y datatable-wrapper box p-5 mt-5">
            <table class="table table-report display datatable w-full">
                <thead>
                    <tr>
                        <th class="border-b-2 whitespace-no-wrap"></th>
                        <th class="border-b-2 text-center whitespace-no-wrap">{{ __('app.name') }}</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">{{ __('app.price') }}</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">{{ __('app.tags') }}</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">{{ __('app.status') }}</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">{{ __('app.leads') }}</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data as $inventory)
                    <tr>
                        <td class="border-b text-center ">
                            {!! $inventory[0] !!}
                        </td>
                        <td class="border-b text-center ">
                            {!! $inventory[1] !!}
                        </td>
                        <td class="text-center border-b">
                            {!! $inventory[2] !!}
                        </td>
                        <td class="w-40 border-b text-center ">
                            {!! $inventory[3] !!}
                        </td>
                        <td class="border-b text-center ">
                            {!! $inventory[4] !!}
                        </td>
                        <td class="border-b  text-center ">
                            {!! $inventory[5] !!}
                        </td>
                        <td class="border-b  text-center ">
                            {!! $inventory[6] !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- <div class="chart-container mb-5">
    <script type="text/json" id='last_added_chart_data'>
        {!! $vehicle_type_chat_data !!}
    </script>
    <div class="chart has-fixed-height" id="last_added_chart"></div>
</div>
<h5 class="card-title">{{__('inventory.inventories_per_location')}}</h5>
<div class="chart-container">
    <script type="text/json" id='location_chart_data'>
        {!! $location_chart_data !!}
    </script>
    <div class="chart has-fixed-height" id="location_chart"></div>
</div> -->

@endsection

@section('scripts_after')

<!-- <script src="{{ asset('js/inventories/dashboard.js') }}"></script> -->
<!-- <script src='{{ asset("js/inventories/picker_date.js?start_date=$start_date&end_date=$end_date") }}'></script> -->

@endsection
