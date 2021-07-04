@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.inventories') }} - {{ __('app.status_management') }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y flex items-center border-b">

    <div class="-intro-x breadcrumb mr-auto hidden sm:flex">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb">{{ __('app.inventories')}}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('app.status')}}</a> 
    </div>
    <div class="p-5" id="icon-button">
        <div class="preview flex flex-wrap">
            <a href="{{ route('inventories.status.create') }}"
                class="button w-40 mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white">
                <i data-feather="plus-circle" class="mr-2"></i>
                {{ __('app.add_new') }}
            </a>
        </div>
    </div>
</div>

@endsection


@section('page_content')
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y box mt-5 col-span-12 lg:col-span-12">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                {{__('app.all_status')}}
            </h2>
        </div>
        <div class="p-5" id="bordered-table">
            <div class="preview">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="border border-b-2 whitespace-no-wrap">{{__('app.status_name')}}</th>
                                <th class="border border-b-2 whitespace-no-wrap">{{__('app.status_color')}}</th>
                                <th class="border border-b-2 whitespace-no-wrap">{{__('app.created_by')}}</th>
                                <th class="border border-b-2 whitespace-no-wrap">{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($statuses as $status)
                            <tr>
                                <td class="border">{{ $status->status_name }}</td>
                                <td class="border">
                                    <span class='py-1 px-2 rounded-full text-xs bg-{{ $status->color }} text-white cursor-pointer font-medium'>
                                        {{ $status->color }}
                                    </span>
                                </td>
                                <td class="border">{{ $status->created_by }}</td>
                                <td class="border">
                                    <div class="flex items-center">
                                        <a class="flex items-center mr-3" href="{{ route('inventories.status.edit', $status->id) }}">
                                            <i data-feather="check-square" class="w-4 h-4 mr-1"></i> {{ __('app.edit') }}
                                        </a>
                                        <a class="flex items-center text-theme-6" href="{{ route('inventories.status.delete', $status->id) }}">
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
    </div>
</div>
@endsection
