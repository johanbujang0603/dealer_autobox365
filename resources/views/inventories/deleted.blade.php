@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.inventories') }} - Deleted Listings
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')

<div class="intro-y flex items-center border-b">

    <div class="-intro-x breadcrumb mr-auto hidden sm:flex p-5">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb">{{ __('menu.inventories.inventory')}}</a>
        <i data-feather="chevron-right" class="breadcrumb__icon"></i>
        <a href="" class="breadcrumb--active">{{ __('menu.inventory.deleted')}}</a>
    </div>
    <div class="p-5" id="icon-button">
        <div class="preview flex flex-wrap">
            <a href="{{ route('inventories.dashboard') }}" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Dashboard">
                <i class="xl:mr-2" data-feather="home"></i>
                <span class="hidden xl:block">{{ __('app.dashboard') }}</span>
            </a>
            <a href="{{ route('inventories.create') }}" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="New Inventory">
                <i class="xl:mr-2" data-feather="plus-circle"></i>
                <span class="hidden xl:block">{{ __('app.add_new') }}</span>
            </a>
            <a href="{{ route('inventories.import') }}" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Import Inventory">
                <i class="xl:mr-2" data-feather="database"></i>
                <span class="hidden xl:block">{{ __('app.import') }}</span>
            </a>
        </div>
    </div>
</div>

@endsection

@section('page_content')

<!-- Main charts -->
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
            <h2 class="text-lg font-medium mr-auto">
                {{ __('inventory.deleted') }}
            </h2>
        </div>
        <div class="intro-y datatable-wrapper box p-5 mt-5">
            <table class="table table-report table-report--bordered display datatable w-full">
                <thead>
                    <tr>
                        <th></th>
                        <th class="border-b-2 whitespace-no-wrap">{{ __('app.name') }}</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">{{ __('app.price') }}</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">{{ __('app.tags') }}</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">{{ __('app.status') }}</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td class="border-b">
                            {!! $item['state'] !!}
                        </td>
                        <td class="border-b">
                            {!! $item['name_field'] !!}
                        </td>
                        <td class="text-center border-b">
                            {!! $item['price_field'] !!}
                        </td>
                        <td class="w-40 text-center border-b">
                            {!! $item['tag_str'] !!}
                        </td>
                        <td class="text-center border-b">
                            {!! $item['status'] !!}
                        </td>
                        <td class="border-b text-center w-5">
                            {!! $item['action_str'] !!}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /header groups -->
    </div>
</div>
<!-- /main charts -->

@endsection
