@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.home') }} - {{ __('app.customers')}}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')

<div class="intro-y flex items-center flex-wrap border-b">

    <div class="-intro-x breadcrumb mr-auto hidden sm:flex p-5">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('menu.customers.index')}}</a> 
    </div>
    <div class="p-5">
        <div class="preview flex flex-wrap">
        
            <a href="{{ route('customers.dashboard') }}" class="tooltip button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Dashboard">
                <i class="icon-pie-chart2 xl:mr-2"></i><span class="hidden xl:block">{{ __('app.dashboard') }}</span>
            </a>
            @if (Auth::user()->hasPermission('customer', 'write'))
            <a href="{{ route('customers.create') }}" class="tooltip button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="New Customer">
                <i class="icon-plus3 xl:mr-2"></i><span class="hidden xl:block">{{ __('app.add_new') }}</span>
            </a>
            @endif
            <a href="{{ route('customers.import') }}" class="tooltip button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Import Customer">
                <i class="icon-import xl:mr-2"></i>
                <span class="hidden xl:block">{{ __('app.import') }}</span>
            </a>
            <a href="{{ route('customers.export') }}" class="tooltip button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Export Customer">
                <i class="icon-database-export xl:mr-2"></i>
                <span class="hidden xl:block">{{ __('app.export') }}</span>
            </a>
        </div>
    </div>
</div>

@endsection

@section('page_content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <h2 class="text-lg font-medium mr-auto">
            {{ __('app.customers') }}
        </h2>
    </div>
    <div class="intro-y datatable-wrapper box p-5 mt-5 col-span-12 sm:col-span-12">
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
                @foreach ($data as $customer)
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
