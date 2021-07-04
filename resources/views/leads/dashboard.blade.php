@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.leads') }} - {{ __('app.dashboard') }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y flex items-center border-b flex-wrap">
    
    <div class="-intro-x breadcrumb mr-auto hidden sm:flex">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('app.leads') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('app.dashboard') }}</a> 
    </div>
    <div class="p-5" id="icon-button">
        <div class="preview flex flex-wrap">
            
            @if (Auth::user()->hasPermission('lead', 'write'))
            <a href="{{ route('leads.create') }}" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white"><i
                    class="icon-plus3 mr-2"></i><span>{{ __('app.add_new') }}</span></a>
            @endif
            <a href="{{ route('leads.export') }}" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white"><i
                    class="icon-database-export mr-2"></i> <span>{{ __('app.export') }}</span></a>

        </div>
    </div>
</div>
@endsection

@section('page_content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 sm:col-span-6 md:col-span-4 xl:col-span-2 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">
                <div class="flex">
                    <i class="icon-pointer icon-3x text-theme-1"></i>
                </div>
                <div class="text-3xl font-bold leading-8 mt-6">{{ $total_leads }}</div>
                <div class="text-base text-gray-600 mt-1">{{__('lead.total_leads')}}</div>
            </div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-6 md:col-span-4 xl:col-span-2 intro-y">
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
    <div class="col-span-12 sm:col-span-6 md:col-span-4 xl:col-span-2 intro-y">
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
    <div class="col-span-12 sm:col-span-6 md:col-span-4 xl:col-span-2 intro-y">
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
    <div class="col-span-12 sm:col-span-6 md:col-span-4 xl:col-span-2 intro-y">
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
    <div class="col-span-12 sm:col-span-6 md:col-span-4 xl:col-span-2 intro-y">
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
    <div class="col-span-12 sm:col-span-6 xl:col-span-6 intro-y">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{__('lead.leads_by_countries')}}
            </h2>
        </div>
        <div class="intro-y box p-5 mt-5">
        @if (empty(json_decode($countries_chart_details, true)))
            <div class="text-theme-6 flex items-center justify-center p-12">
                {{ __('lead.no_country') }}
            </div>
        @else
            <canvas class="mt-3 m-auto" id="lead_country_pie_chart" width="280"></canvas>
            <script type="text/json" id="lead_country_pie_chart_data">
                {!! $countries_chart_details !!}
            </script>
            <div class="mt-8" id="lead_country_pie_chart_meta">
            </div>
        @endif
        </div>

    </div>
    <div class="col-span-12 sm:col-span-6 xl:col-span-6 intro-y">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{__('lead.leads_by_cities')}}
            </h2>
        </div>
        <div class="intro-y box p-5 mt-5">
        @if (empty(json_decode($cities_chart_details, true)))
            <div class="text-theme-6 flex items-center justify-center p-12">
                {{ __('lead.no_cities') }}
            </div>
        @else
            <canvas class="mt-3 m-atuo" id="lead_city_pie_chart" width="280"></canvas>
            <script type="text/json" id="lead_city_pie_chart_data">
                {!! $cities_chart_details !!}
            </script>
            <div class="mt-8" id="lead_city_pie_chart_meta">
            </div>
        @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    
    <div class="col-span-12 sm:col-span-4 xl:col-span-4 intro-y">
        
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{__('lead.leads_by_gender')}}
            </h2>
        </div>
        <div class="intro-y box p-5 mt-5">
        @if (empty(json_decode($gender_chart_data, true)))
            <div class="text-theme-6 flex items-center justify-center p-12">
                {{ __('lead.no_gender') }}
            </div>
        @else
            <canvas class="mt-3" id="lead_gender_pie_chart" width="280"></canvas>
            <script type="text/json" id="lead_gender_pie_chart_data">
                {!! $gender_chart_data !!}
            </script>
            <div class="mt-8" id="lead_gender_pie_chart_meta">
            </div>
        @endif
        </div>

    </div>
    
    <div class="col-span-12 sm:col-span-4 xl:col-span-4 intro-y">
        
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{__('lead.leads_by_tags')}}
            </h2>
        </div>
        <div class="intro-y box p-5 mt-5">
        @if (empty(json_decode($tag_chart_data, true)))
            <div class="text-theme-6 flex items-center justify-center p-12">
                {{ __('lead.no_tags') }}
            </div>
        @else
            <canvas class="mt-3 m-auto" id="lead_tags_pie_chart" width="280"></canvas>
            <script type="text/json" id="lead_tags_pie_chart_data">
                {!! $tag_chart_data !!}
            </script>
            <div class="mt-8" id="lead_tags_pie_chart_meta">
            </div>
        @endif
        </div>

    </div>
    
    <div class="col-span-12 sm:col-span-4 xl:col-span-4 intro-y">
        
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{__('lead.leads_by_status')}}
            </h2>
        </div>
        <div class="intro-y box p-5 mt-5">
        @if (empty(json_decode($status_chart_data, true)))
            <div class="text-theme-6 flex items-center justify-center p-12">
                {{ __('lead.no_status') }}
            </div>
        @else
            <canvas class="mt-3 m-auto" id="lead_status_pie_chart" width="280"></canvas>
            <script type="text/json" id="lead_status_pie_chart_data">
                {!! $status_chart_data !!}
            </script>
            <div class="mt-8" id="lead_status_pie_chart_meta">
            </div>
        @endif
        </div>

    </div>

</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="col-span-12 sm:col-span-12 md:col-span-12 xl:col-span-4 intro-y">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{__('lead.last_10_activities')}}
            </h2>
        </div>
        <div class="intro-y mt-2">
            <div class="border-b border-gray-200 box p-5">
                <div class="flex items-center mb-2">
                    <i class="icon-mobile icon-2x text-theme-4 mr-2"></i>
                    <span class="truncate">{{__('lead.no_phonenumber_leads')}}</span> 
                    <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                    <span class="font-medium xl:ml-auto">{{ $no_phone_number_leads }}</span> 
                </div>
                <div class="flex items-center mb-2">
                    <i class="icon-envelope icon-2x text-theme-6 mr-2"></i>
                    <span class="truncate">{{__('lead.no_email_leads')}}</span> 
                    <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                    <span class="font-medium xl:ml-auto">{{ $no_email_leads }}</span> 
                </div>
                <div class="flex items-center mb-2">
                    <i class="icon-earth icon-2x text-theme-4 mr-2"></i>
                    <span class="truncate">{{__('lead.no_country_leads')}}</span> 
                    <div class="h-px flex-1 border border-r border-dashed border-gray-300 mx-3 xl:hidden"></div>
                    <span class="font-medium xl:ml-auto">{{ $no_country_leads }}</span> 
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
                                    "<a class='text-theme-1' href='/leads/view/".$log->lead_details->id
                                        . "'>".
                                        $log->lead_details->name ."</a>"]) !!}
                                </div>
                            </div>
                            <div class="text-gray-600 text-xs text-center">{{ $log->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </li>
                    @endforeach
            @endif            
            </div>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-12 md:col-span-12 xl:col-span-8 intro-y">
        <div class="intro-y">
            <h2 class="text-lg font-medium mr-auto">
                {{ __('lead.all') }}
            </h2>
        </div>
        <div class="intro-y datatable-wrapper box p-5 mt-5">
            <table class="table table-report table-report--bordered display datatable w-full">
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
                    @foreach ($table_data as $lead)
                    <tr>
                        <td class="border-b text-center ">
                            {!! $lead[0] !!}
                        </td>
                        <td class="border-b text-center ">
                            {!! $lead[1] !!}
                        </td>
                        <td class="text-center border-b">
                            {!! $lead[2] !!}
                        </td>
                        <td class="w-40 border-b text-center ">
                            {!! $lead[3] !!}
                        </td>
                        <td class="border-b text-center ">
                            {!! $lead[4] !!}
                        </td>
                        <td class="border-b  text-center ">
                            {!! $lead[5] !!}
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
    function confirmRemove(avatar, lead_name, lead_id) {
        var id = lead_id;
        
        $.confirm({
            title: 'Are you sure you want to delete this listing?',
            content: '<img class="rounded-full m-auto" width="60" height="60" src="' + avatar + '" alt=""> <h4>' + lead_name + '</h4>',
            theme: 'supervan',
            buttons: {
                confirm: function () {
                    location.href = '/leads/delete/' + id;
                },
                cancel: function () {
                },
            }
        });
    }
        
</script>
@endsection
