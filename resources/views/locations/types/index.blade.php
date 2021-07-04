@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('menu.locations.locations') }} - {{ __('menu.locations.types') }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y flex items-center border-b">
    <div class="-intro-x breadcrumb mr-auto hidden sm:flex">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('menu.locations.locations') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('menu.locations.types') }}</a> 
    </div>
    <div class="p-5" id="icon-button">
        <div class="preview flex flex-wrap">
            <a href="{{ route('locations.types.create') }}" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white">
                <i class="icon-plus3 mr-2"></i><span>{{ __('app.add_new') }}</span>
            </a>
        </div>
    </div>
</div>
@endsection

@section('page_content')
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y datatable-wrapper box mt-5 col-span-12 sm:col-span-12">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                {{__('location.list_of_location_type')}}
            </h2>
        </div>
        <div class="p-5 mt-5">
            <table class="table table-report table-report--bordered display datatable w-full">
                <thead>
                    <tr>
                        <th class="border-b-2 text-center whitespace-no-wrap">{{ __('location.type_name') }}</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">{{ __('location.color') }}</th>
                        <th class="border-b-2 whitespace-no-wrap">{{ __('location.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($type_list as $type)
                    <tr>
                        <td class="border-b text-center ">
                            {{ $type->type_name }}
                        </td>
                        
                        <td class="border text-center">
                            <span class='py-1 px-2 rounded-full text-xs bg-{{ $type->color }} text-white cursor-pointer font-medium'>
                                {{ ucfirst($type->color) }}
                            </span>
                        </td>

                        <td class="text-center border-b">
                            <div class="flex items-center">
                                <a class="flex items-center mr-3" href="{{ route('locations.types.edit', $type->id) }}">
                                    <i data-feather="check-square" class="w-4 h-4 mr-1"></i> {{ __('app.edit') }}
                                </a>
                                <a class="flex items-center text-theme-6" href="{{ route('locations.types.delete', $type->id) }}">
                                    <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> {{ __('app.delete') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection