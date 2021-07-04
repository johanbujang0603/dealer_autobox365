@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.home') }} - {{ __('app.inventories') }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y flex items-center border-b p-5">
    <div class="-intro-x breadcrumb mr-auto hidden sm:flex">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('menu.inventories.index')}}</a> 
    </div>
</div>

@endsection


@section('page_content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y mt-5 col-span-12 lg:col-span-12">
        <div class="intro-y box px-5 pt-5 mt-5">
            <div class="grid grid-cols-12 gap-6 mt-5">
                <div class="intro-x mt-5 col-span-12 lg:col-span-4 sm:col-span-12 flex justify-center items-center">
                    <div class="flex items-center justify-center">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                            <img alt="" class="rounded-full border-2 border-theme-8" src="{{ $inventory->brand_logo }}">
                        </div>
                        <div class="ml-5">
                            <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">{{ $inventory->inventory_name }}</div>
                            <div class="text-gray-600">{{ $inventory->version }}</div>
                        </div>
                    </div>
                </div>
                <div class="intro-x mt-5 col-span-12 sm:col-span-12 lg:col-span-8">
                    <div class="intro-x px-5 border-l">
                        <div class="slider mx-6 fade-mode">
                            @foreach ($inventory->photo_details as $key => $photo)
                            <div class="h-64 px-2">
                                <div class="h-full image-fit rounded-md overflow-hidden">
                                    <img alt="" src="{{ $photo->image_src }}" />
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="nav-tabs flex flex-col sm:flex-row justify-center lg:justify-start flex-wrap">
                <a data-toggle="tab" data-target="#dashboard" href="javascript:;" class="py-4 sm:mr-8 active">{{__('app.dashboard')}}</a>
                <a data-toggle="tab" data-target="#informations" href="javascript:;" class="py-4 sm:mr-8">{{__('app.informations')}}</a>
                <a data-toggle="tab" data-target="#photos" href="javascript:;" class="py-4 sm:mr-8">{{__('app.photos')}}</a>
                <a data-toggle="tab" data-target="#leads" href="javascript:;" class="py-4 sm:mr-8">{{__('app.leads')}}</a>
                <a data-toggle="tab" data-target="#calendar" href="javascript:;" class="py-4 sm:mr-8">{{ __('calendar.calendar') }}</a>
                <a data-toggle="tab" data-target="#documents" href="javascript:;" class="py-4 sm:mr-8">{{ __('app.documents') }}</a>
                <a data-toggle="tab" data-target="#transactions" href="javascript:;" class="py-4 sm:mr-8">{{ __('app.transactions') }}</a>
                <a data-toggle="tab" data-target="#timeline" href="javascript:;" class="py-4 sm:mr-8">{{ __('app.timeline') }}</a>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <div class="intro-y tab-content p-5">
            <div class="tab-content__pane active" id="dashboard">
                <div class="grid grid-cols-12 gap-6">
                    <div class="intro-y col-span-12 lg:col-span-8 md:col-span-8">
                        <div class="intro-y box p-5">
                            <h2 class="font-bold text-lg">
                                {{__('inventory.vehicle_details')}}
                            </h2>
                            <div class="grid grid-cols-12 gap-6 mt-5">
                                <div class="intro-x col-span-12 lg:col-span-2 md:col-span-2 text-center">
                                    <p class="mb-3"><i class="icon-car icon-2x d-inline-block text-theme-4"></i></p>
                                    <h5 class="font-semibold mb-0">
                                        {{ $inventory->make_details ? $inventory->make_details->name : 'N/A' }}</h5>
                                    <span class="text-muted font-size-sm">{{__('inventory.brand')}}</span>
                                </div>
                                <div class="intro-x col-span-12 lg:col-span-2 md:col-span-2 text-center">
                                    <p class="mb-3"><i class="icon-point-up icon-2x d-inline-block text-theme-9"></i></p>
                                    <h5 class="font-semibold mb-0">
                                        {{ $inventory->model_details ? $inventory->model_details->name : 'N/A' }}</h5>
                                    <span class="text-muted font-size-sm">{{__('inventory.model')}}</span>
                                </div>
                                <div class="intro-x col-span-12 lg:col-span-2 md:col-span-2 text-center">
                                    <p class="mb-3"><i class="icon-point-up icon-2x d-inline-block text-theme-9"></i></p>
                                    <h5 class="font-semibold mb-0">
                                        {{ $inventory->generation_details ? $inventory->generation_details->name : 'N/A' }}
                                    </h5>
                                    <span class="text-muted font-size-sm">{{__('inventory.generation')}}</span>
                                </div>
                                <div class="intro-x col-span-12 lg:col-span-2 md:col-span-2 text-center">
                                    <p class="mb-3"><i class="icon-point-up icon-2x d-inline-block text-theme-9"></i></p>
                                    <h5 class="font-semibold mb-0">
                                        {{ $inventory->serie_details ? $inventory->serie_details->name : 'N/A' }}</h5>
                                    <span class="text-muted font-size-sm">{{__('inventory.serie')}}</span>
                                </div>
                                <div class="intro-x col-span-12 lg:col-span-2 md:col-span-2 text-center">
                                    <p class="mb-3"><i class="icon-point-up icon-2x d-inline-block text-theme-9"></i></p>
                                    <h5 class="font-semibold mb-0">
                                        {{ $inventory->trim_details ? $inventory->trim_details->name : 'N/A' }}</h5>
                                    <span class="text-muted font-size-sm">{{__('inventory.trim')}}</span>
                                </div>
                                <div class="intro-x col-span-12 lg:col-span-2 md:col-span-2 text-center">
                                    <p class="mb-3"><i class="icon-point-up icon-2x d-inline-block text-theme-9"></i></p>
                                    <h5 class="font-semibold mb-0">
                                        {{ $inventory->equipment_details ? $inventory->equipment_details->name : 'N/A' }}
                                    </h5>
                                    <span class="text-muted font-size-sm">{{__('inventory.equipment')}}</span>
                                </div>
                            </div>
                            <div class="mt-5">
                                <label class="font-semibold text-theme-4 mb-3">{{__('inventory.description')}}:</label>
                                <p>{!! $inventory->description !!}</p>
                            </div>
                            <div class="mt-5">
                                <div class="mt-5 flex items-center flex-wrap border-b py-2">
                                    <span class="font-semibold">{{__('inventory.negotiable')}}:</span>
                                    <div class="ml-auto">
                                        <h6 class="font-bold">
                                            {{ $inventory->negotiable ? $inventory->negotiable : 'N/A' }}
                                        </h6>
                                    </div>
                                </div>

                                <div class="mt-5 flex items-center flex-wrap border-b py-2">
                                    <span class="font-semibold">{{__('app.country')}}:</span>
                                    <div class="ml-auto">
                                        <h6 class="font-bold flex items-center">
                                            {!! $inventory->country ? '<img class="mr-1"
                                                src="https://www.countryflags.io/'.$inventory->country.'/shiny/24.png">'.\PragmaRX\Countries\Package\Countries::where('cca2',
                                            $inventory->country)->first()->name->official : 'N/A' !!}
                                        </h6>
                                    </div>
                                </div>
                                <div class="mt-5 flex items-center flex-wrap border-b py-2">
                                    <span class="font-semibold">{{__('app.city')}}:</span>
                                    <div class="ml-auto">
                                        <h6 class="font-bold">
                                            {{ $inventory->city ? $inventory->city : 'N/A' }}
                                        </h6>
                                    </div>
                                </div>

                                <div class="mt-5 flex items-center flex-wrap border-b py-2">
                                    <span class="font-semibold">{{__('app.transmission')}}:</span>
                                    <div class="ml-auto">
                                        <h6 class="font-bold">
                                            {{ $inventory->transmission_details ? $inventory->transmission_details->transmission : 'N/A' }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="mt-5 flex items-center flex-wrap border-b py-2">
                                    <span class="font-semibold">{{__('inventory.engine')}}:</span>
                                    <div class="ml-auto">
                                        <h6 class="font-bold">
                                            {{ $inventory->engine ? $inventory->engine : 'N/A' }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="mt-5 flex items-center flex-wrap border-b py-2">
                                    <span class="font-semibold">{{__('inventory.steering_wheel')}}:</span>
                                    <div class="ml-auto">
                                        <h6 class="font-bold">
                                            {{ $inventory->steering_wheel ? $inventory->steering_wheel : 'N/A' }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="mt-5 flex items-center flex-wrap border-b py-2">
                                    <span class="font-semibold">VIN:</span>
                                    <div class="ml-auto">
                                        <h6 class="font-bold">
                                            {{ $inventory->vin ? $inventory->vin : 'N/A' }}
                                        </h6>
                                    </div>
                                </div>

                                <div class="mt-5 flex items-center flex-wrap border-b py-2">
                                    <span class="font-semibold">{{__("inventory.mileage")}}:</span>
                                    <div class="ml-auto">
                                        <h6 class="font-bold">
                                            {{ $inventory->mileage ? $inventory->mileage.($inventory->mileage_unit ? $inventory->mileage_unit : '') : 'N/A' }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="mt-5 flex items-center flex-wrap border-b py-2">
                                    <span class="font-semibold">{{__("inventory.fuel_type")}}:</span>
                                    <div class="ml-auto">
                                        <h6 class="font-bold">
                                            {{ $inventory->fuel_type ? $inventory->fuel_type : 'N/A' }}
                                        </h6>
                                    </div>
                                </div>

                                <div class="mt-5 flex items-center flex-wrap border-b py-2">
                                    <span class="font-semibold">{{__("app.tags")}}:</span>
                                    <div class="ml-auto">
                                        @foreach (explode(',', $inventory->tags) as $tag)
                                        <h5 class="font-bold float-left ml-1">
                                            <span
                                                class="badge bg-{{ isset(\App\Models\InventoryTag::find($tag)->color) ? \App\Models\InventoryTag::find($tag)->color : "" }} badge-pill">{{ isset(\App\Models\InventoryTag::find($tag)->tag_name) ? \App\Models\InventoryTag::find($tag)->tag_name : '' }}</span>
                                        </h5>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mt-5 flex items-center flex-wrap border-b py-2">
                                    <span class="font-semibold">{{__("app.status")}}:</span>
                                    <div class="ml-auto">
                                        <h6 class="font-bold">
                                            {{ $inventory->status ? $inventory->status_details->status_name : 'N/A' }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="mt-5 flex items-center flex-wrap border-b py-2">
                                    <span class="font-semibold">{{__('app.options')}}:</span>
                                    <div class="ml-auto">
                                        @foreach (explode(',', $inventory->options) as $option)
                                        <h5 class="font-bold float-left ml-1">
                                            <span class="badge">
                                                {{ (\App\Models\InventoryOption::find($option)) ? \App\Models\InventoryOption::find($option)->option_name : '' }}</span>
                                        </h5>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="intro-y col-span-12 lg:col-span-4 md:col-span-4">
                        <div class="intro-y box p-5">
                            <h2 class="text-theme-4 font-medium text-lg">{{__('inventory.price_valuation')}}</h2>
                            <div class="mt-2">
                                <h1 class="mb-0 font-semibold text-center text-lg text-theme-6">
                                    {{ $inventory->price ? number_format($inventory->price, 0, '.', ',') . ($inventory->currency_details ? $inventory->currency_details->symbol: '') : 'N/A' }}
                                </h1>
                                <p class="mb-3 text-center">We have total {{ $inventory->totalCounts() }} published inventories and
                                    {{ $inventory->draftCounts() }}
                                    drafts inventories.</p>
                                <div class="flex items-center justify-center flex-wrap">
                                    <div class="mr-2 text-center ml-2">
                                        <h5 class="font-semibold mb-0 ">
                                            {{ $inventory->avgPrice($inventory->currency) }}</h5>
                                        <span class="text-theme-7 text-sm">{{__('inventory.avg_price')}}</span>
                                    </div>

                                    <div class="mr-2 text-center ml-2">
                                        <h5 class="font-semibold mb-0">
                                            {{ $inventory->medianPrice($inventory->currency) }}</h5>
                                        <span class="text-theme-7 text-sm">{{__('inventory.median_price')}}</span>
                                    </div>

                                    <div class="mr-2 text-center ml-2">
                                        <h5 class="font-semibold mb-0">
                                            {{ $inventory->minimumPrice($inventory->currency) }}</h5>
                                        <span class="text-theme-7 text-sm">{{__('inventory.min_price')}}</span>
                                    </div>
                                    <div class="mr-2 text-center ml-2">
                                        <h5 class="font-semibold mb-0">
                                            {{ $inventory->maximumPrice($inventory->currency) }}</h5>
                                        <span class="text-theme-7 text-sm">{{__('inventory.max_price')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="intro-y box mt-5 p-5">
                            <div class="text-center">
                                <h4 class="font-semibold text-lg mb-2">{{__('inventory.on_inventory_since')}}</h4>
                                <h1 class="mb-0 text-black">
                                    {{ since($inventory->created_at) }}
                                </h1>
                            </div>
                        </div>
                        <div class="intro-y box mt-5 p-5">
                            <h2 class="text-theme-4 font-medium text-lg mb-5">{{__('inventory.leads_increasement')}}</h2>
                            <div class="flex items-center justify-between flex-wrap">
                                <div class="text-center ml-2 mr-2">
                                    <h5 class="font-semibold mb-0">
                                        {!! $inventory->getIncreasementPercentage('week') != 'N/A' ? ("<span
                                            class='".($inventory->getIncreasementPercentage(' week')> 0 ? 'text-success'
                                            :
                                            'text-danger')."'>".$inventory->getIncreasementPercentage('week').'%</span>')
                                        : 'N/A' !!}
                                    </h5>
                                    <span class="text-theme-7 text-sm">{{__('inventory.this_week')}}</span>
                                </div>

                                <div class="text-center ml-2 mr-2">
                                    <h5 class="font-semibold mb-0">
                                        {!! $inventory->getIncreasementPercentage('month') != 'N/A' ? ("<span
                                            class='".($inventory->getIncreasementPercentage(' month')> 0 ?
                                            'text-success' :
                                            'text-danger')."'>".$inventory->getIncreasementPercentage('month').'%</span>')
                                        : 'N/A' !!}
                                    </h5>
                                    <span class="text-theme-7 text-sm">{{__('inventory.this_month')}}</span>
                                </div>

                                <div class="text-center ml-2 mr-2">
                                    <h5 class="font-semibold mb-0">
                                        {!! $inventory->getIncreasementPercentage('total') != 'N/A' ? ("<span
                                            class='".($inventory->getIncreasementPercentage(' total')> 0 ?
                                            'text-success' :
                                            'text-danger')."'>".$inventory->getIncreasementPercentage('total').'%</span>')
                                        : 'N/A' !!}
                                    </h5>
                                    <span class="text-theme-7 text-sm">{{__('inventory.all_leads')}}</span>
                                </div>
                            </div>

                        </div>
                        <div class="intro-y box mt-5 p-5">
                            <h2 class="text-theme-4 font-medium text-lg mb-5">{{__('inventory.similar_vehicles')}}</h2>
                        </div>
                        <div class="intro-y box mt-5 p-5">
                            <h2 class="text-theme-4 font-medium text-lg">{{__('inventory.assigned_location')}}</h2>
                            <div class="mt-5">
                                <p class="mb-3">
                                    {{ $inventory->location_details ? $inventory->location_details->full_address : 'N/A' }}
                                </p>
                                <div class="map-container" id="location_map"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="tab-content__pane" id="informations">
                <div class="box p-5 intro-y">
                    <h2 class="font-bold text-lg">
                        {{__('inventory.vehicle_details')}}
                    </h2>
                    <div class="mt-5 intro-y">
                        <div class="mt-5 flex items-center flex-wrap border-b py-2">
                            <span class="font-semibold">{{__('inventory.make')}}:</span>
                            <div class="ml-auto">
                                <h6 class="font-bold">
                                    {{ $inventory->make_details ? $inventory->make_details->name : 'N/A' }}
                                </h6>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center flex-wrap border-b py-2">
                            <span class="font-semibold">{{__('inventory.model')}}:</span>
                            <div class="ml-auto">
                                <h6 class="font-bold">
                                    {{ $inventory->model_details ? $inventory->model_details->name : 'N/A' }}</h5>
                                </h6>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center flex-wrap border-b py-2">
                            <span class="font-semibold">{{__('inventory.generation')}}:</span>
                            <div class="ml-auto">
                                <h6 class="font-bold">
                                    {{ $inventory->generation_details ? $inventory->generation_details->name : 'N/A' }}
                                </h6>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center flex-wrap border-b py-2">
                            <span class="font-semibold">{{__('inventory.serie')}}:</span>
                            <div class="ml-auto">
                                <h6 class="font-bold">
                                    {{ $inventory->serie_details ? $inventory->serie_details->name : 'N/A' }}</h5>
                                </h6>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center flex-wrap border-b py-2">
                            <span class="font-semibold">{{__('inventory.trim')}}:</span>
                            <div class="ml-auto">
                                <h6 class="font-bold">
                                    {{ $inventory->trim_details ? $inventory->trim_details->name : 'N/A' }}</h5>
                                </h6>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center flex-wrap border-b py-2">
                            <span class="font-semibold">{{__('inventory.equipment')}}:</span>
                            <div class="ml-auto">
                                <h6 class="font-bold">
                                    {{ $inventory->equipment_details ? $inventory->equipment_details->name : 'N/A' }}
                                </h6>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center flex-wrap border-b py-2">
                            <span class="font-semibold">{{__('inventory.negotiable')}}:</span>
                            <div class="ml-auto">
                                <h6 class="font-bold">
                                    {{ $inventory->negotiable ? $inventory->negotiable : 'N/A' }}
                                </h6>
                            </div>
                        </div>

                        <div class="mt-5 flex items-center flex-wrap border-b py-2">
                            <span class="font-semibold">{{__('app.country')}}:</span>
                            <div class="ml-auto">
                                <h6 class="font-bold flex items-center">
                                    {!! $inventory->country ? '<img class="mr-1"
                                        src="https://www.countryflags.io/'.$inventory->country.'/shiny/24.png">'.\PragmaRX\Countries\Package\Countries::where('cca2',
                                    $inventory->country)->first()->name->common : 'N/A' !!}
                                </h6>
                            </div>
                    </div>
                        <div class="mt-5 flex items-center flex-wrap border-b py-2">
                            <span class="font-semibold">{{__('app.city')}}:</span>
                            <div class="ml-auto">
                                <h6 class="font-bold">
                                    {{ $inventory->city ? $inventory->city : 'N/A' }}
                                </h6>
                            </div>
                    </div>

                        <div class="mt-5 flex items-center flex-wrap border-b py-2">
                            <span class="font-semibold">{{__('inventory.transmission')}}:</span>
                            <div class="ml-auto">
                                <h6 class="font-bold">
                                    {{ $inventory->transmission_details ? $inventory->transmission_details->transmission : 'N/A' }}
                                </h6>
                            </div>
                    </div>
                        <div class="mt-5 flex items-center flex-wrap border-b py-2">
                            <span class="font-semibold">{{__('inventory.engine')}}:</span>
                            <div class="ml-auto">
                                <h6 class="font-bold">
                                    {{ $inventory->engine ? $inventory->engine : 'N/A' }}
                                </h6>
                            </div>
                    </div>
                        <div class="mt-5 flex items-center flex-wrap border-b py-2">
                            <span class="font-semibold">{{__('inventory.steering_wheel')}}:</span>
                            <div class="ml-auto">
                                <h6 class="font-bold">
                                    {{ $inventory->steering_wheel ? $inventory->steering_wheel : 'N/A' }}
                                </h6>
                            </div>
                    </div>
                        <div class="mt-5 flex items-center flex-wrap border-b py-2">
                            <span class="font-semibold">VIN:</span>
                            <div class="ml-auto">
                                <h6 class="font-bold">
                                    {{ $inventory->vin ? $inventory->vin : 'N/A' }}
                                </h6>
                            </div>
                    </div>

                        <div class="mt-5 flex items-center flex-wrap border-b py-2">
                            <span class="font-semibold">{{__('inventory.mileage')}}:</span>
                            <div class="ml-auto">
                                <h6 class="font-bold">
                                    {{ $inventory->mileage ? $inventory->mileage.($inventory->mileage_unit ? $inventory->mileage_unit : '') : 'N/A' }}
                                </h6>
                            </div>
                    </div>
                        <div class="mt-5 flex items-center flex-wrap border-b py-2">
                            <span class="font-semibold">{{__('inventory.fuel_type')}}:</span>
                            <div class="ml-auto">
                                <h6 class="font-bold">
                                    {{ $inventory->fuel_type ? $inventory->fuel_type : 'N/A' }}
                                </h6>
                            </div>
                    </div>

                        <div class="mt-5 flex items-center flex-wrap border-b py-2">
                            <span class="font-semibold">{{__('app.tags')}}:</span>
                            <div class="ml-auto">
                                @foreach (explode(',', $inventory->tags) as $tag)
                                <h5 class="font-bold float-left ml-1">
                                    <span
                                        class="badge bg-{{ isset(\App\Models\InventoryTag::find($tag)->color) ? \App\Models\InventoryTag::find($tag)->color : "" }} badge-pill">{{ isset(\App\Models\InventoryTag::find($tag)->tag_name) ? \App\Models\InventoryTag::find($tag)->tag_name : '' }}</span>
                                </h5>
                                @endforeach
                            </div>
                    </div>

                        <div class="mt-5 flex items-center flex-wrap border-b py-2">
                            <span class="font-semibold">{{__('app.status')}}:</span>
                            <div class="ml-auto">
                                <h6 class="font-bold">
                                    {{ $inventory->status ? $inventory->status_details->status_name : 'N/A' }}
                                </h6>
                            </div>
                    </div>
                        <div class="mt-5 flex items-center flex-wrap border-b py-2">
                            <span class="font-semibold">{{__('app.options')}}:</span>
                            <div class="ml-auto">
                                @foreach (explode(',', $inventory->options) as $option)
                                <h5 class="font-bold float-left ml-1">
                                    <span class="badge">
                                        {{ (\App\Models\InventoryOption::find($option)) ? \App\Models\InventoryOption::find($option)->option_name : '' }}</span>
                                </h5>
                                @endforeach

                            </div>
                    </div>
                        
                    </div>
                </div>
            </div>
            <div class="tab-content__pane" id="photos">
                <div class="grid grid-cols-12 gap-6">
                    @foreach ($inventory->photo_details as $photo)
                    <div class="intro-x col-span-12 lg:col-span-3">
                        <div class="box p-3 card-box-hover">
                            <img src="{{ $photo->image_src }}" alt="">
                            <div class="box-hover-actions m-3 flex justify-center items-center p-5">
                                <a href="{{ $photo->image_src }}"
                                    class="border button"
                                    data-popup="lightbox" rel="group">
                                    <i class="icon-plus3"></i>
                                </a>

                                <a href="#"
                                    class="border button ml-2">
                                    <i class="icon-link"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="tab-content__pane" id="leads">
                <div class="p-5 box @if (!$inventory->leads()->count())  text-center @endif">
                    @if ($inventory->leads()->count())
                    <ul>
                        @foreach ($inventory->leads() as $lead)
                        <li class="flex items-center flex-wrap mt-5">
                            <div class="mr-3">
                                <img src="{{ $lead->profile_image_src }}" class="rounded-full"
                                    width="40" height="40" alt="" />
                                </div>
                            <div>
                                <div class="font-semibold">{{ $lead->name }}</div>
                                <span class="flex items-center text-theme-7">{!! $lead->country_base_residence ? '<img class="mr-1"
                                        src="https://www.countryflags.io/'.$lead->country_base_residence.'/shiny/24.png">'.\PragmaRX\Countries\Package\Countries::where('cca2',
                                    $lead->country_base_residence)->first()->name->official : 'N/A' !!}</span>
                            </div>
                            <div class="ml-5 -mt-2 border-b p-2">
                                <a href='javascript:;' class="p-2"><i class="icon-pin mr-2"></i> {{ $lead->address ? $lead->address : 'N/A' }}</a>
                                @foreach ($lead->phone_number_details as $phone_number)
                                <a href='javascript:;' class="p-2">
                                    @foreach (explode(",",$phone_number->messaging_apps) as $messaging_app)
                                    @if ($messaging_app == 'WhatsApp')
                                    <i class="fab fa-whatsapp-square  text-success"></i>
                                    @elseif ($messaging_app == 'Viber')
                                    <i class="fab fa-viber  text-violet"></i>
                                    @elseif ($messaging_app == 'Telegram')
                                    <i class="fab fa-telegram  text-primary"></i>
                                    @elseif ($messaging_app == 'Line')
                                    <i class="fab fa-line  text-success"></i>
                                    @elseif ($messaging_app == 'Weixin')
                                    <i class="fab fa-weixin  text-success"></i>
                                    @endif
                                    @endforeach
                                    {{ $phone_number->mobile_no }}
                                </a>
                                @endforeach

                                @foreach ($lead->email_details as $email)
                                <a href='javascript:;' class="p-2 text-theme-4"><i class="icon-mail5 mr-2"></i> <a href="#">{{ $email->email }}</a></a>
                                @endforeach
                            </div>
                        </li>
                        @endforeach

                    </ul>
                    @else
                    <img src="{{ asset('global_assets/images/result_not_found.jpg') }}" />
                    @endif
                </div>
            </div>
            <div class="tab-content__pane" id="calendar">
                <div class="p-5 box fullcalendar-basic">
                </div>
            </div>
            <div class="tab-content__pane" id="documents">
                <div class="box p-5 intro-y">
                    <div class="flex items-center p-5 border-b border-gray-200">
                        <h2 class="font-bold text-lg">
                            {{__('app.documents')}}
                        </h2>
                        <a class="button ml-auto w-40 mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" href="javascript:;" data-toggle="modal" data-target="#add_documents_modal">
                            <i data-feather="plus-circle" class="mr-2"></i>Add {{ __('app.documents') }}
                        </a>
                    </div>

                    <div class="mt-5 intro-y">
                        <ul id="document_list" class="media-list"></ul>
                    </div>
                </div>
            </div>
            <div class="tab-content__pane" id="timeline">
                <div class="flex items-center p-5 border-b border-gray-200">
                    <h2 class="font-medium text-base mr-auto">
                    {{__('app.timeline')}}
                    </h2>
                </div>
                <div class="report-timeline mt-5 relative">
        
                    @foreach ($inventory->log_details() as $log)
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
    </div>
</div>
<!-- 
<script type="text/json" id="json_details">
    {!! $json_data !!}
</script> -->
<!-- 
<div class="tab-content mt-3">
    <div class="tab-pane fade" id="documents">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('app.documents') }}</h4>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item text-success" href="javascript:;" data-toggle="modal"
                            data-target="#add_documents_modal"> <i class="mi-add"></i>Add
                            {{ __('app.documents') }}</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul id="document_list" class="media-list"></ul>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="transactions">{{__('app.transactions')}}</div>
    <div class="tab-pane fade" id="timeline">
        <div class="timeline timeline-center">
            <div class="timeline-container">
                @foreach ($inventory->log_details() as $log)
                @php
                if($log->action == 'created' || $log->action == 'document_created'){
                $color = 'primary';
                $iconClass = 'icon-plus3';
                }
                else if ($log->action == 'updated'){
                $color = 'success';
                $iconClass = 'icon-pencil';
                }
                else if ($log->action == 'deleted'){
                $color = 'danger';
                $iconClass = 'icon-trash';
                }

                $image_url = asset('global_assets/images/placeholders/placeholder.jpg');
                if(isset($log->inventory_details->make_details)){
                $image_url =
                asset('images/car_brand_logos/'.strtolower($log->inventory_details->make_details->name).'.jpg');
                }
                @endphp
                <div class="timeline-row timeline-row-right">
                    <div class="timeline-icon">
                        <div class="bg-{{ $color }}-400">
                            <i class="{{ $iconClass }}"></i>
                            {{--  <img width="32" src="{{ $image_url }}"/> --}}
                        </div>
                    </div>
                    <div class="timeline-time">
                        Inventory from <a href="#">{{ $log->user_details->name }}</a> have been {{ $log->action }}.
                        <div class="text-muted"> {{ Carbon\Carbon::parse($log->created_at)->diffForHumans()}} </div>
                    </div>
                    <div class="card border-left-3 border-left-{{ $color }} rounded-left-0">
                        <div class="card-body">
                            <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
                                <div>
                                    @php
                                    $make = isset($log->inventory_details->make_details->name) ?
                                    $log->inventory_details->make_details->name : '';
                                    $model = isset($log->inventory_details->model_details->name) ?
                                    $log->inventory_details->model_details->name : '';
                                    $location = isset($log->inventory_details->location_details->full_address) ?
                                    $log->inventory_details->location_details->full_address : '';
                                    $user_name = isset($log->inventory_details->user_details->name) ?
                                    $log->inventory_details->user_details->name : '';
                                    $vehicle = isset($log->inventory_details->vehicle_details->name) ?
                                    $log->inventory_details->vehicle_details->name : 'Unknown Vehicle';
                                    $currency = isset($log->inventory_details->currency_details->symbol)?
                                    $log->inventory_details->currency_details->symbol : '$';
                                    $brand_logo = isset($log->inventory_details->make_details->name) ?
                                    asset('images/car_brand_logos/'.strtolower($log->inventory_details->make_details->name).'.jpg')
                                    : asset('/global_assets/images/placeholders/placeholder.jpg');
                                    $tags = $log->inventory_details->tags;
                                    $tag_str = '';

                                    $generation = isset($log->inventory_details->generation_details->name) ?
                                    $log->inventory_details->generation_details->name : "";
                                    $serie = isset($log->inventory_details->serie_details->name) ?
                                    $log->inventory_details->serie_details->name : "";
                                    $trim = isset($log->inventory_details->trim_details->name) ?
                                    $log->inventory_details->trim_details->name : "";
                                    $equipment = isset($log->inventory_details->equipment_details->name) ?
                                    $log->inventory_details->equipment_details->name : "";
                                    if($generation && $serie && $trim && $equipment){
                                    $version = "$generation - $serie - $trim - $equipment";
                                    }
                                    else{
                                    $version = "No Version";
                                    }

                                    $transmission = isset($log->inventory_details->transmission_details->transmission) ?
                                    $log->inventory_details->transmission_details->transmission : '';

                                    if($tags){
                                    $tags = explode(',', $tags);
                                    foreach($tags as $tag_id){
                                    $tag = \App\Models\InventoryTag::find($tag_id);
                                    $tag_str .= "<span class=\"badge bg-$tag->color\">$tag->tag_name</span>";
                                    }
                                    }
                                    if($make && $model && $log->inventory_details->year){
                                    $name = $make.' '.$model.' '.$log->inventory_details->year;
                                    }
                                    else{
                                    $name = '---';
                                    }

                                    $make = isset($log->inventory_details->make_details->name) ?
                                    $log->inventory_details->make_details->name : '';
                                    $model = isset($log->inventory_details->model_details->name) ?
                                    $log->inventory_details->model_details->name : '';
                                    $year = $log->inventory_details->year;

                                    if($make && $model && $year){
                                    $inventory_name = $make.' '.$model.' '.$year;
                                    }
                                    else{
                                    $inventory_name = 'UnPublished Inventory';
                                    }

                                    if($log->inventory_details->price && $log->inventory_details->currency){
                                    $price = $log->inventory_details->currency_details->symbol .
                                    number_format($log->inventory_details->price, 0, '.', ',');
                                    }
                                    else{
                                    $price = "Unknown Price";
                                    }

                                    if($log->inventory_details->status_details){
                                    $status_color = $log->inventory_details->status_details->color;
                                    $status = $log->inventory_details->status_details->status_name;
                                    }
                                    else{
                                    $status_color = 'warning';
                                    $status = 'Unknown Status';
                                    }
                                    @endphp
                                    <h6 class="font-semibold">
                                        <img src="{{ $brand_logo }}" class="rounded-circle" width="60" height="60"
                                            alt="">
                                        &nbsp;{{ $inventory_name }}</h6>
                                    <ul class="list list-unstyled mb-0">
                                        <li>InventoryID #: &nbsp;{{ $log->inventory_details->id }}</li>
                                        <li>Version: &nbsp;{{ $version }}</li>
                                        <li>Transmission/Gas/Mileage:
                                            &nbsp;{{ $transmission }}/{{ $log->inventory_details->fuel_type }}/{{ $log->inventory_details->mileage }}
                                        </li>
                                        {{--  <li>Gas: &nbsp;</li>  --}}
                                        {{--  <li>Mileage: &nbsp;</li>  --}}
                                        <li>{{ ucfirst($log->action) }} on: <span
                                                class="font-semibold">{{ $log->created_at }}</span></li>

                                    </ul>
                                </div>

                                <div class="text-sm-right mb-0 mt-3 mt-sm-0 ml-auto">
                                    @if ($log->inventory_details->price && $log->inventory_details->currency)

                                    <h6 class="font-semibold">{{ $price }}</h6>
                                    @endif
                                    <ul class="list list-unstyled mb-0">
                                        {{--  <li>Tags: <span class="font-semibold">SWIFT</span></li>  --}}
                                        @if($log->inventory_details->status_details)
                                        <li class="dropdown">
                                            Status: &nbsp;
                                            <a href="#" class="badge bg-{{ $status_color }}-400 align-top"
                                                data-toggle="dropdown">{{ ucfirst($status) }}</a>
                                        </li>
                                        <li class="dropdown">
                                            Tags: &nbsp;
                                            {!! $tag_str !!}
                                        </li>
                                        <li class="dropdown">
                                            Location: &nbsp;
                                            {!! $location !!}
                                        </li>
                                        <li class="dropdown">
                                            Country/City: &nbsp;
                                            {!! $log->inventory_details->country !!}/{!! $log->inventory_details->city
                                            !!}
                                        </li>

                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            @endforeach


        </div>
    </div>
</div> -->

<div id="add_documents_modal" class="modal">
    <div class="modal__content modal__content--xl px-5 py-10">
        <div class="text-center mb-3">
            <h5>{{__('app.attach')}} {{ __('app.documents') }}</h5>
        </div>
        
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
                <div class="preview flex items-center">
                    <span class="mr-2"><i class="icon-car"></i></span>
                    <select class="select2 w-full" name='inventories' data-placeholder="Choose Inventory" multiple>
                        <option></option>
                        @foreach($inventories as $inven)
                            @if ($inven->id == $inventory->id)
                                <option selected value="{{$inven->id}}">{{$inven->inventory_name}}</option>
                            @else
                                <option value="{{$inven->id}}">{{$inven->inventory_name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
                <div class="preview flex items-center">
                    <span class="mr-2"><i class="icon-check"></i></span>
                    <select class="select2 w-full" name='customers' data-placeholder="Choose Customer" multiple>
                        <option></option>
                        @foreach($customers as $customer)
                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
                <div class="preview flex items-center">
                    <span class="mr-2"><i class="icon-users"></i></span>

                    <select class="select2 w-full" name='leads' data-placeholder="Choose Lead" multiple>
                        <option></option>
                        @foreach($leads as $lead)
                            <option value="{{$lead->id}}">{{$lead->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
                <div class="preview flex items-center">
                    <span class="mr-2"><i class="icon-pin-alt"></i></span>
                    <select class="select2 w-full" name='locations' data-placeholder="Choose Location" multiple>
                        <option></option>
                        @foreach($locations as $location)
                            <option value="{{$location->id}}">{{$location->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
                <div class="flex preview items-center">
                    <span class="mr-2"><i class="icon-pin-alt"></i></span>
                    <select class="select2 w-full" name='users' data-placeholder="Choose User" multiple>
                        <option></option>
                        @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->full_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
                <div class="flex preview items-center">
                    <span class="mr-2"><i class="icon-car"></i></span>
                    <textarea class="input border w-full doc-description" placeholder="Add Description"></textarea>
                </div>
            </div>
        </div>

        <div class="preview mt-5">
            <form action="{{ route('inventories.documents.upload', $inventory->id) }}" id="upload-documents-modal" class="dropzone border-gray-200 border-dashed">
                @csrf
                <input type="hidden" value="{{ route('inventories.documents.load', $inventory->id) }}" id="upload-documents-modal-url"/>
                <div class="fallback">
                    <input name="file" type="file" multiple/>
                </div>
                <div class="dz-message" data-dz-message>
                    <div class="text-lg font-medium">Drop files here or click to upload.</div>
                    <div class="text-gray-600"> This is just a demo dropzone. Selected files are <span class="font-medium">not</span> actually uploaded. </div>
                </div>
            </form>
        </div>
        <div class="flex items-center flex-wrap mt-5">
            <button type="button" class="button bg-theme-6 text-white mr-5" data-dismiss="modal">{{__('app.close')}}</button>
            <button type="button" class="button bg-theme-4 text-white" id="upload-doc">{{__('app.save_changes')}}</button>
        </div>
    </div>
</div>


<!-- Large modal -->
<!-- <div id="add_documents_modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attach {{ __('app.documents') }}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">

                <div class="row">

                    <div class="form-group col-md-6">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-car"></i></span>
                            <select class="select2" name='inventories' data-placeholder="Choose Inventory" multiple>
                                <option></option>
                                @foreach($inventories as $inven)
                                    @if ($inven->id == $inventory->id)
                                        <option selected value="{{$inven->id}}">{{$inven->inventory_name}}</option>
                                    @else
                                        <option value="{{$inven->id}}">{{$inven->inventory_name}}</option>
                                    @endif
                                    <option value="{{$inven->id}}">{{$inven->inventory_name}}</option>
                                @endforeach
                            </select>
                        </span>
                    </div>
                    <div class="form-group col-md-6">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-check"></i></span>
                            <select class="select2" name='customers' data-placeholder="Choose Customer" multiple>
                                <option></option>
                                @foreach($customers as $customer)
                                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-users"></i></span>

                            <select class="select2" name='leads' data-placeholder="Choose Lead" multiple>
                                <option></option>
                                @foreach($leads as $lead)
                                    <option value="{{$lead->id}}">{{$lead->name}}</option>
                                @endforeach
                            </select>

                        </span>
                    </div>
                    <div class="form-group col-md-6">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-pin-alt"></i></span>
                            <select class="select2" name='locations' data-placeholder="Choose Location" multiple>
                                <option></option>
                                @foreach($locations as $location)
                                    <option value="{{$location->id}}">{{$location->name}}</option>
                                @endforeach
                            </select>

                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-pin-alt"></i></span>
                            <select class="select2" name='users' data-placeholder="Choose User" multiple>
                                <option></option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->full_name}}</option>
                                @endforeach
                            </select>

                        </span>
                    </div>
                    <div class="form-group col-md-6">
                        <span class="input-group-prepend">
                            <span class="input-group-text"><i class="icon-car"></i></span>
                            <textarea class="form-control doc-description" placeholder="Add Description"></textarea>
                        </span>
                    </div>
                </div>

                <form action="{{ route('inventories.documents.upload', $inventory->id) }}" class="dropzone"
                    id="dropzone_remove">
                    @csrf
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{{__('app.close')}}</button>
                <button type="button" class="btn bg-primary" id="upload-doc">{{__('app.save_changes')}}</button>
            </div>
        </div>
    </div>
</div> -->
<!-- /large modal -->
@endsection
@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
@endsection
<!-- @section('scripts_after')
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="{{ asset('global_assets/js/plugins/notifications/bootbox.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/ui/fullcalendar/core/main.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/ui/fullcalendar/daygrid/main.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/ui/fullcalendar/timegrid/main.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/ui/fullcalendar/list/main.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/ui/fullcalendar/interaction/main.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script src="{{ asset('global_assets/js/plugins/uploaders/dropzone.min.js') }}"></script>
<script>

    Dropzone.options.dropzoneRemove = {
        init: function () {
            this.on("complete", function (file) {

                $("#add_documents_modal").modal('hide')
                $("#document_list").load('{{ route('inventories.documents.load', $inventory->id) }}')

                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                    $("#add_documents_modal").modal('hide')
                    $("#document_list").load('{{ route('inventories.documents.load', $inventory->id) }}')
                }
            });

            // Event to send your custom data to your server
            this.on("sending", function(file, xhr, data) {

                // First param is the variable name used server side
                // Second param is the value, you can add what you what
                // Here I added an input value

                data.append("leads", $('.select2[name ="leads"]').val());
                data.append("customers", $('.select2[name ="customers"]').val());
                data.append("locations", $('.select2[name ="locations"]').val());
                data.append("users", $('.select2[name ="users"]').val());
                data.append("description", $('.doc-description').val());
                data.append("inventories", $('.select2[name ="inventories"]').val());

            });

            var myDropzone = this;
            $('#upload-doc').on("click", function() {
                myDropzone.processQueue(); // Tell Dropzone to process all queued files.
            });


        },
        autoProcessQueue: false,
        paramName: "file", // The name that will be used to transfer the file
        dictDefaultMessage: 'Drop document files to upload <span>or CLICK</span>',
        maxFilesize: 1024, // MB
        addRemoveLinks: true
    };

    $(document).ready(function(){
        $("#document_list").load('{{ route('inventories.documents.load', $inventory->id) }}')
        $('[data-popup="lightbox"]').fancybox({
            padding: 3
        });
        //initCalendar()
        var location_map_element = document.getElementById('location_map');

        // Options
        var mapOptions = {
            zoom: 10,
            // center: new google.maps.LatLng({{ $inventory->location_details ? $inventory->location_details->latitude : $inventory->latitude }}, {{ $inventory->location_details ? $inventory->location_details->longitude : $inventory->longitude }})
        };

        // Apply options
        var map = new google.maps.Map(location_map_element, mapOptions);

        // Add markers
        var marker = new google.maps.Marker({
            position: map.getCenter(),
            map: map,
            title: 'Click to zoom'
        });

        // "Change" event
        google.maps.event.addListener(map, 'center_changed', function() {

        // 3 seconds after the center of the map has changed, pan back to the marker
        window.setTimeout(function() {
            map.panTo(marker.getPosition());
            }, 3000);
        });

        // "Click" event
        google.maps.event.addListener(marker, 'click', function() {
            map.setZoom(14);
            map.setCenter(marker.getPosition());
        });
        $('#calendar_tab').on('shown.bs.tab', function(){
        //$(".fullcalendar-basic").fullCalendar('rerenderEvents')
            initCalendar()
        })
    })
    function initCalendar(defaultDate = null){
        var calendarBasicViewElement = document.querySelector('.fullcalendar-basic');
        calendarBasicViewElement.innerHTML = ''
        var calendarBasicViewInit = new FullCalendar.Calendar(calendarBasicViewElement, {
            plugins: [ 'dayGrid', 'interaction' ],
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek,dayGridDay'
            },
            contentHeight:"auto",
            fixedWeekCount: false,
            displayEventTime: false,
            defaultDate: defaultDate ? defaultDate : new Date(),
            //editable: true,
            events: function(info, successCallback, failureCallback) {
                console.log(info)
                $.ajax({
                    url: '{{ route('calendar.events.load') }}',
                    dataType: 'json',
                    data: {
                        start: info.startStr,
                        end: info.endStr,
                        kinds: 'inventory',
                        model_id : "{{ $inventory->id }}"
                    },
                    success: function(result) {
                        console.log(result)
                        var events = [];
                        $(result).each(function() {
                            events.push({
                                id: $(this).attr('id'),
                                title: $(this).attr('notes'),
                                start: $(this).attr('formated_date') // will be parsed
                            });
                        });
                        console.log(events)

                        successCallback(events);
                    }
                });
            },
            eventLimit: true,
            dateClick: function (info){
                console.log('Hello')
                // $("#event_detail_modal").modal('show')
                var dialog = bootbox.dialog({
                    title: 'A custom dialog with init',
                    message: '\
                        <div class="form-group">\
                            <label>Select Users</label>\
                            <select multiple="multiple" class="form-control" id="userselect"\
                                data-placeholder="Select users who will get notification.">\
                            </select>\
                        </div>\
                        <div class="form-group">\
                            <label>Note</label>\
                            <textarea class="form-control" id="reminder_notes" placeholder="Enter your notes."></textarea>\
                        </div>\
                    ',
                    buttons: {
                        cancel: {
                            label: "Cancel",
                            className: 'btn-danger',
                            callback: function(){

                            }
                        },
                        ok: {
                            label: "Save",
                            className: 'btn-info',
                            callback: function(){
                            //console.log('Custom OK clicked');
                                console.log('Custom cancel clicked', $("#userselect").val(),info, $("#reminder_notes").val());
                                $.post('{{ route('calendar.events.save') }}', {
                                    date: info.date,
                                    users: $("#userselect").val(),
                                    notes: $("#reminder_notes").val(),
                                    kinds: 'inventory',
                                    model_id : "{{ $inventory->id }}"
                                    }, function(res){
                                        initCalendar(info.date)
                                })
                            }
                        }
                    }
                });
                const JSON_DATA = JSON.parse(document.getElementById('json_details').innerHTML)
                dialog.on('shown.bs.modal', function(e){
                    $("#userselect").select2({
                        width: '100%',
                        placeholder: function() {
                            $(this).data('placeholder');
                        },
                        data: JSON_DATA['users'].map((user) => {
                            return {id: user.id, text: user.name}
                        })
                    })
                // Do something with the dialog just after it has been shown to the user...
                });
            },
            eventClick: function(info){

                $.ajax({
                    url: '{{ route('calendar.events.detail') }}',
                    dataType: 'json',
                    data: {
                        id: info.event.id
                    },
                    success: function(result) {
                        console.log(result, info)

                        var dialog = bootbox.dialog({
                            title: 'A custom dialog with init',
                            message: '\
                                <div class="form-group">\
                                    <label>Select Users</label>\
                                    <select multiple="multiple" class="form-control" id="userselect"\
                                        data-placeholder="Select users who will get notification.">\
                                    </select>\
                                </div>\
                                <div class="form-group">\
                                    <label>Note</label>\
                                    <textarea class="form-control" id="reminder_notes" placeholder="Enter your notes." >'+ result.calendar.notes +'</textarea>\
                                </div>\
                            ',
                            buttons: {
                                cancel: {
                                    label: "Cancel",
                                    className: 'btn-link',
                                    callback: function(){

                                    }
                                },
                                delete:{
                                    label: "Delete",
                                    className: 'btn-danger',
                                    callback: function(){
                                        $.post('{{ route('calendar.events.delete') }}', {
                                            event_id: info.event.id,
                                            }, function(res){
                                                initCalendar(info.event.start)
                                        })
                                    }
                                },
                                ok: {
                                    label: "Save",
                                    className: 'btn-info',
                                    callback: function(){
                                    //console.log('Custom OK clicked');
                                        console.log('Custom cancel clicked', $("#userselect").val(),info, $("#reminder_notes").val());
                                        $.post('{{ route('calendar.events.save') }}', {
                                            date: info.event.start,
                                            event_id: result.calendar.id,
                                            users: $("#userselect").val(),
                                            notes: $("#reminder_notes").val(),
                                            kinds: 'inventory',
                                            model_id : "{{ $inventory->id }}"
                                            }, function(res){
                                                initCalendar(info.event.start)
                                        })
                                    }
                                }
                            }
                        });
                        const JSON_DATA = JSON.parse(document.getElementById('json_details').innerHTML)
                        dialog.on('shown.bs.modal', function(e){
                            $("#userselect").select2({
                                width: '100%',
                                placeholder: function() {
                                    $(this).data('placeholder');
                                },
                                data: JSON_DATA['users'].map((user) => {
                                        return {id: user.id, text: user.name, "selected": result.calendar.user_id.split(',').includes(user.id + '') ?  true : false}
                                    })
                            })

                        // Do something with the dialog just after it has been shown to the user...
                        });
                    }
                });
            }
        }).render();
    }
</script>
@endsection -->
