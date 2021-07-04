@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.inventories') }} - {{ __('app.log_timeline') }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y p-5 mb-5 flex items-center border-b">

    <div class="-intro-x breadcrumb mr-auto hidden sm:flex">
        <a href="{{ route('dashboard') }}" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a>
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="{{ route('inventories.index') }}" class="breadcrumb">{{ __('app.inventories') }}</a>
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="#" class="breadcrumb--active">{{__('app.log_timeline')}}</a>
    </div>

</div>
@endsection


@section('page_content')
<div class="col-span-12 mt-6">
    <div class="col-span-12 md:col-span-6 xl:col-span-4 xxl:col-span-12 mt-3">
        <div class="intro-x flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                Recent Activities
            </h2>
        </div>
        <div class="report-timeline mt-5 relative">
        
            @foreach ($logs as $log)
            <!-- Inventory -->
            @php
                $color = 'bg-theme-1 text-white';
                $iconClass = 'icon-pencil';
                if($log->action == 'created' || $log->action == 'document_created'){
                    $color = 'bg-theme-1 text-white';
                    $iconClass = 'icon-plus3';
                }
                else if ($log->action == 'updated'){
                    $color = 'bg-theme-9 text-white';
                    $iconClass = 'icon-pencil';
                }
                else if ($log->action == 'deleted'){
                    $color = 'bg-theme-6 text-white';
                    $iconClass = 'icon-trash';
                }

                $image_url = asset('global_assets/images/placeholders/placeholder.jpg');
                if(isset($log->inventory_details->make_details)){
                    $image_url = asset('images/car_brand_logos/'.strtolower($log->inventory_details->make_details->name).'.jpg');
                }
            @endphp
            
            <div class="intro-x relative flex items-center mb-3">
                <div class="report-timeline__image">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full overflow-hidden {{$color}}" >
                        <i class="{{ $iconClass }}"></i>
                    </div>
                </div>
                
                @php
                $make = isset($log->inventory_details->make_details->name) ? $log->inventory_details->make_details->name : '';
                $model = isset($log->inventory_details->model_details->name) ? $log->inventory_details->model_details->name : '';
                $location = isset($log->inventory_details->location_details->full_address) ? $log->inventory_details->location_details->full_address : '';
                $user_name = isset($log->inventory_details->user_details->name) ? $log->inventory_details->user_details->name : '';
                $vehicle = isset($log->inventory_details->vehicle_details->name) ? $log->inventory_details->vehicle_details->name : 'Unknown Vehicle';
                $currency = isset($log->inventory_details->currency_details->symbol)? $log->inventory_details->currency_details->symbol : '$';
                $brand_logo = isset($log->inventory_details->make_details->name) ? asset('images/car_brand_logos/'.strtolower($log->inventory_details->make_details->name).'.jpg') : asset('/global_assets/images/placeholders/placeholder.jpg');
                $tags = $log->inventory_details->tags;
                $tag_str = '';

                $generation = isset($log->inventory_details->generation_details->name) ? $log->inventory_details->generation_details->name : "";
                $serie = isset($log->inventory_details->serie_details->name) ? $log->inventory_details->serie_details->name : "";
                $trim = isset($log->inventory_details->trim_details->name) ? $log->inventory_details->trim_details->name : "";
                $equipment = isset($log->inventory_details->equipment_details->name) ? $log->inventory_details->equipment_details->name : "";
                if($generation && $serie && $trim && $equipment){
                    $version = "$generation  -  $serie  -  $trim  -  $equipment";
                }
                else{
                    $version = "No Version";
                }

                $transmission = isset($log->inventory_details->transmission_details->transmission) ? $log->inventory_details->transmission_details->transmission : '';

                if($make && $model && $log->inventory_details->year){
                    $name = $make.' '.$model.' '.$log->inventory_details->year;
                }
                else{
                    $name = '---';
                }

                $make = isset($log->inventory_details->make_details->name) ? $log->inventory_details->make_details->name : '';
                $model = isset($log->inventory_details->model_details->name) ? $log->inventory_details->model_details->name : '';
                $year = $log->inventory_details->year;

                if($make && $model && $year){
                    $inventory_name = $make.' '.$model.' '.$year;
                }
                else{
                    $inventory_name = 'UnPublished Inventory';
                }

                if($log->inventory_details->price && $log->inventory_details->currency){
                    $price = $log->inventory_details->currency_details->symbol . number_format($log->inventory_details->price, 0, '.', ',');
                }
                else{
                    $price = "Unknown Price";
                }

                @endphp
                <div class="box px-5 py-3 ml-10 flex-1 zoom-in">
                    <div class="flex items-center">
                        <div class="font-medium flex items-center justify-center">
                            <img src="{{ $brand_logo }}" class="rounded-circle" width="60" height="60" alt="">
                            <a href='#' class="text-theme-1 mr-2">{{ $log->user_details->name }}</a>
                            have been {{ $log->action }} &nbsp; <span class="text-theme-1"> {{ $inventory_name }} </span>
                        </div>
                        <div class="text-xs text-gray-500 ml-auto">{{ Carbon\Carbon::parse($log->created_at)->diffForHumans()}}</div>
                    </div>
                    <div class="text-gray-600 mt-1">
                    
                        <ul class="list list-unstyled mb-0">
                            <li>{{__('inventories.inventory_id')}} #: &nbsp;{{ $log->inventory_details->id }}</li>
                            <li>{{__('inventories.version')}}: &nbsp;{{ $version }}</li>
                            <li>{{__('inventories.transmission_gas_mileage')}}: &nbsp;{{ $transmission }}/{{ $log->inventory_details->fuel_type }}/{{ $log->inventory_details->mileage }}</li>
                            <li>{{ ucfirst($log->action) }} on: <span class="font-weight-semibold">{{ $log->created_at }}</span></li>
                            
                        </ul>
                    </div>
                    <div class="p-5">
                        <a href="{{ route('inventories.edit', $log->inventory_details->id) }}" 
                            class="button bg-theme-1 text-white"><i class="icon-pencil"></i> Edit</a>
                    </div>
                </div>
            </div>

            @endforeach
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script src="{{ asset('global_assets/js/plugins/notifications/pnotify.min.js') }}"></script>
<script>

    function deleteListing(id){
        console.log(id)
        var notice = new PNotify({
            title: 'Confirmation',
            text: '<p>Are you sure you want to delete this listing?</p>',
            hide: false,
            type: 'warning',
            width: "100%",
            cornerclass: "rounded-0",
            addclass: "stack-custom-top bg-danger border-danger",
            stack: {"dir1": "right", "dir2": "down", "push": "top", "spacing1": 1},
            confirm: {
                confirm: true,
                buttons: [
                    {
                        text: 'Yes',
                        addClass: 'btn btn-sm btn-danger'
                    },
                    {
                        addClass: 'btn btn-sm btn-info'
                    }
                ]
            },
            buttons: {
                closer: false,
                sticker: false
            }
        })

        // On confirm
        notice.get().on('pnotify.confirm', function() {
            location.href = '/inventories/delete/' + id
        })

        // On cancel
        notice.get().on('pnotify.cancel', function() {
            //alert('Oh ok. Chicken, I see.');
        });
    }
    function publish(id){
        var notice = new PNotify({
            title: 'Confirmation',
            text: '<p>Are you sure you want to publish from draft this listing?</p>',
            hide: false,
            type: 'warning',
            width: "100%",
            cornerclass: "rounded-0",
            addclass: "stack-custom-top bg-warning border-warning",
            stack: {"dir1": "right", "dir2": "down", "push": "top", "spacing1": 1},
            confirm: {
                confirm: true,
                buttons: [
                    {
                        text: 'Yes',
                        addClass: 'btn btn-sm btn-warning'
                    },
                    {
                        addClass: 'btn btn-sm btn-info'
                    }
                ]
            },
            buttons: {
                closer: false,
                sticker: false
            }
        })

        // On confirm
        notice.get().on('pnotify.confirm', function() {
            location.href = '/inventories/publish/' + id
        })

        // On cancel
        notice.get().on('pnotify.cancel', function() {
            //alert('Oh ok. Chicken, I see.');
        });
    }
</script>
@endsection
