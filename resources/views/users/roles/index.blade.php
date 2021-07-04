@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.users') }} - {{ __('app.role_management') }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')

<div class="intro-y flex flex-wrap items-center border-b">

    <div class="-intro-x breadcrumb mr-auto hidden sm:flex">
        <a href="{{ route('dashboard') }}" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="{{ route('users.index') }}" class="breadcrumb">{{ __('app.users') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="#" class="breadcrumb--active">{{ __('app.roles') }}</a> 
    </div>
    <div class="p-5" id="icon-button">
        <div class="preview flex flex-wrap">

            <a href="{{ route('users.roles.create') }}" class="button mr-2 mb-2 flex items-center bg-theme-1 text-white">
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
                {{__('user.all_roles')}}
            </h2>
        </div>
        <div class="p-5" id="bordered-table">
            <div class="preview">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="border border-b-2 whitespace-no-wrap">{{__('app.role_name')}}</th>
                                <th class="border border-b-2 whitespace-no-wrap">{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                            <tr>
                                <td class="border">{{ $role->role_name }}</td>
                                <td class="border">
                                    <div class="flex items-center">
                                        <a class="mr-1" href="{{ route('users.roles.edit', $role->id) }}">{{ __('app.edit') }}</a>
                                        <a class="mr-1" href="{{ route('users.roles.delete', $role->id) }}">{{ __('app.delete') }}</a>
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
