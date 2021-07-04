@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('calendar.calendar') }} - {{ __('calendar.types') }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')

<div class="intro-y flex flex-wrap items-center border-b">

    <div class="-intro-x breadcrumb mr-auto hidden sm:flex">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('calendar.calendar') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('calendar.types') }}</a> 
    </div>
    <div class="p-5" id="icon-button">
        <div class="preview flex flex-wrap">

            <a href="{{ route('calendar.types.create') }}" class="button mr-2 mb-2 flex items-center bg-theme-1 text-white">
                <i class="icon-plus3 mr-2"></i>
                <span>{{ __('app.add_new') }}</span>
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
                {{ __('calendar.calendar') }} - {{ __('calendar.types') }}
            </h2>
        </div>
        <div class="p-5" id="bordered-table">
            <div class="preview">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="border border-b-2 whitespace-no-wrap">{{__('app.type')}}</th>
                                <th class="border border-b-2 whitespace-no-wrap">{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($types as $type)
                            <tr>
                                <td class="border">{{ $type->type_name }}</td>
                                <td class="border">
                                    <div class="flex items-center">
                                        <a class="flex items-center mr-3" href="{{ route('calendar.types.edit', $type->id) }}">
                                            <i data-feather="check-square" class="w-4 h-4 mr-1"></i> {{ __('app.edit') }}
                                        </a>
                                        <a class="flex items-center text-theme-6" href="{{ route('calendar.types.delete', $type->id) }}" data-toggle="modal" data-target="#delete-confirmation-modal">
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
