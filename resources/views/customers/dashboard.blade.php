@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.customers') }} - {{ __('app.dashboard') }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y flex items-center border-b flex-wrap">
    
    <div class="-intro-x breadcrumb mr-auto hidden sm:flex p-5">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('app.customers') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('app.dashboard') }}</a> 
    </div>
    <div class="p-5">
        <div class="preview flex flex-wrap">
            @if (Auth::user()->hasPermission('customer', 'write'))
            <a href="{{ route('customers.create') }}" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="New Customer">
                <i class="icon-plus3 xl:mr-2"></i><span class="hidden xl:block">{{ __('app.add_new') }}</span>
            </a>
            @endif
            <a href="{{ route('customers.import') }}" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Import Customer">
                <i class="icon-import xl:mr-2"></i>
                <span class="hidden xl:block">{{ __('app.import') }}</span>
            </a>
            <a href="{{ route('customers.export') }}" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Export Customer">
                <i class="icon-database-export xl:mr-2"></i>
                <span class="hidden xl:block">{{ __('app.export') }}</span>
            </a>
        </div>
    </div>
</div>
@endsection

@section('page_content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i class="icon-pointer icon-3x text-theme-1"></i>
                </div>
                <div class="text-3xl font-bold leading-8 mt-6">{{ $total_customers }}</div>
                <div class="text-base text-gray-600 mt-1">{{__('customer.total')}}</div>
            </div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i data-feather="shopping-cart" class="report-box__icon text-theme-10"></i> 
                    <div class="ml-auto">
                        <div class="report-box__indicator bg-theme-9 tooltip cursor-pointer" title="33% Higher than last month"> 33% <i data-feather="chevron-up" class="w-4 h-4"></i> </div>
                    </div>
                </div>
                <div class="text-3xl font-bold leading-8 mt-6">4.510</div>
                <div class="text-base text-gray-600 mt-1">Item Sales</div>
            </div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i data-feather="shopping-cart" class="report-box__icon text-theme-10"></i> 
                    <div class="ml-auto">
                        <div class="report-box__indicator bg-theme-9 tooltip cursor-pointer" title="33% Higher than last month"> 33% <i data-feather="chevron-up" class="w-4 h-4"></i> </div>
                    </div>
                </div>
                <div class="text-3xl font-bold leading-8 mt-6">4.510</div>
                <div class="text-base text-gray-600 mt-1">Item Sales</div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 sm:col-span-4 xl:col-span-4 intro-y">
        <div class="intro-y box p-5 mt-5">
            <canvas class="mt-3" id="customer_top_countries_chart" width="280"></canvas>
            <script type="text/json" id="customer_top_countries_chart_data">
                {!! $countries_chart_details !!}
            </script>
            <div class="mt-8" id="customer_top_countries_chart_meta">
            </div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-4 xl:col-span-4 intro-y">
        <div class="intro-y box p-5 mt-5">
            <canvas class="mt-3" id="customer_top_cities_chart" width="280"></canvas>
            <script type="text/json" id="customer_top_cities_chart_data">
                {!! $cities_chart_details !!}
            </script>
            <div class="mt-8" id="customer_top_cities_chart_meta">
            </div>
        </div>
    </div>
</div>


<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 sm:col-span-4 xl:col-span-2 intro-y">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{__('customer.by_gender')}}
            </h2>
        </div>
        <div class="intro-y box p-5 mt-5">
        @if (empty(json_decode($gender_chart_data, true)))
            <div class="svg-center text-danger" style="min-height: 168px;display: flex; align-items: center; justify-content: center;">
                {{ __('customer.no_gender') }}
            </div>
        @else
            <canvas class="mt-3" id="customer_gender_pie_chart" height="280"></canvas>
            <script type="text/json" id="customer_gender_pie_chart_data">
                {!! $gender_chart_data !!}
            </script>
            <div class="mt-8" id="customer_gender_pie_chart_meta">
            </div>
        @endif
        </div>
    </div>

    <div class="col-span-12 sm:col-span-4 xl:col-span-2 intro-y">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{__('customer.by_status')}}
            </h2>
        </div>
        <div class="intro-y box p-5 mt-5">
        @if (empty(json_decode($status_chart_data, true)))
            <div class="svg-center text-danger" style="min-height: 168px;display: flex; align-items: center; justify-content: center;">
                {{ __('customer.no_status') }}
            </div>
        @else
            <canvas class="mt-3" id="customer_status_chart" height="280"></canvas>
            <script type="text/json" id="customer_status_chart_data">
                {!! $status_chart_data !!}
            </script>
            <div class="mt-8" id="customer_status_chart_data_meta">
            </div>
        @endif
        </div>
    </div>
    
    <div class="col-span-12 sm:col-span-4 xl:col-span-2 intro-y">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{__('customer.by_tags')}}
            </h2>
        </div>
        <div class="intro-y box p-5 mt-5">
        @if (empty(json_decode($tag_chart_data, true)))
            <div class="svg-center text-danger" style="min-height: 168px;display: flex; align-items: center; justify-content: center;">
                {{ __('customer.no_tags') }}
            </div>
        @else
            <canvas class="mt-3" id="customer_tag_chart" height="280"></canvas>
            <script type="text/json" id="customer_tag_chart_data">
                {!! $tag_chart_data !!}
            </script>
            <div class="mt-8" id="customer_tag_chart_data_meta">
            </div>
        @endif
        </div>
    </div>

    <div class="col-span-12 sm:col-span-12 xl:col-span-6 intro-y">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{__('customer.last_10_acitivities')}}
            </h2>
        </div>
        <div class="intro-y mt-5">
            <div class="border-b border-gray-200 box p-5">
                <div class="flex items-center mb-2">
                    <i class="icon-mobile icon-2x text-theme-4 mr-2"></i>
                    <span class="truncate">{{__('customer.no_phone_number')}}</span> 
                    <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                    <span class="font-medium xl:ml-auto">{{ $no_phone_number_customers }}</span> 
                </div>
                <div class="flex items-center mb-2">
                    <i class="icon-envelope icon-2x text-theme-6 mr-2"></i>
                    <span class="truncate">{{__('customer.no_email')}}</span> 
                    <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                    <span class="font-medium xl:ml-auto">{{ $no_email_customers }}</span> 
                </div>
                <div class="flex items-center mb-2">
                    <i class="icon-earth icon-2x text-theme-4 mr-2"></i>
                    <span class="truncate">{{__('customer.no_country')}}</span> 
                    <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                    <span class="font-medium xl:ml-auto">{{ $no_country_customers }}</span> 
                </div>
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
                    $color = 'text-theme-1';
                    $icon = 'icon-plus3';
                    }
                    else if(strpos($log->action, 'updated') !== false){
                    $color = 'text-theme-13';
                    $icon = 'icon-pencil5';
                    }else if(strpos($log->action, 'deleted') !== false){
                    $color = 'text-theme-6';
                    $icon = 'icon-bin';
                    }

                    @endphp
                    <div class="intro-y">
                        <div class="box px-4 py-4 mb-3 flex items-center zoom-in">
                            <div class="w-10 h-10 flex items-center rounded-md overflow-hidden">
                                <i class="{{ $icon }} {{ $color }}"></i>
                            </div>
                            <div class="ml-4 mr-auto">
                                <div class="font-medium">
                                {!! __('actions.'.$log->action, ['user' => $log->user_details->name, 'model' =>
                                    "<a class='text-theme-1' href='/leads/view/".$log->customer_details->id
                                        . "'>".
                                        $log->customer_details->name ."</a>"]) !!}
                                </div>
                            </div>
                            <div class="text-gray-600 text-xs">{{ $log->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </li>
                    @endforeach
            @endif            
            </div>
        </div>
        

    </div>
</div>


<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 sm:col-span-12 xl:col-span-12 intro-y">
        <div class="intro-y">
            <h2 class="text-lg font-medium mr-auto">
                {{ __('app.customers') }}
            </h2>
        </div>
        <div class="intro-y datatable-wrapper box p-5 mt-5">
            <table class="table table-report display datatable w-full">
                <thead>
                    <tr>
                        <th class="border-b-2 whitespace-no-wrap"></th>
                        <th class="border-b-2 text-center whitespace-no-wrap">{{ __('app.name') }}</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">{{ __('app.phone_numbers') }}</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">{{ __('app.email') }}</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">{{ __('app.tags') }}</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data as $customer)
                    <tr>
                        <td class="border-b text-center ">
                            {!! $customer[0] !!}
                        </td>
                        <td class="border-b text-center ">
                            {!! $customer[1] !!}
                        </td>
                        <td class="text-center border-b">
                            {!! $customer[2] !!}
                        </td>
                        <td class="w-40 border-b text-center ">
                            {!! $customer[3] !!}
                        </td>
                        <td class="border-b text-center ">
                            {!! $customer[4] !!}
                        </td>
                        <td class="border-b  text-center ">
                            {!! $customer[5] !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts_after')
<script>
    function confirmRemove(avatar, customer_name, customer_id) {
        var id = customer_id;
        $.confirm({
            title: 'Are you sure you want to delete this listing?',
            content: '<img class="rounded-full m-auto" width="60" height="60" src="' + avatar + '" alt=""> <h4>' + customer_name + '</h4>',
            theme: 'supervan',
            buttons: {
                confirm: function () {
                    location.href = '/customers/delete/' + id;
                },
                cancel: function () {
                },
            }
        });
    }
</script>
@endsection
