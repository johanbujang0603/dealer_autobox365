@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.inventories') }} - {{ $inventory->inventory_name }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y flex items-center border-b">
    
    <div class="-intro-x breadcrumb mr-auto hidden sm:flex p-5">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb">{{ __('app.inventories') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i>
        <a href="" class="breadcrumb--active">{{ $inventory->inventory_name }}</a> 
    </div>
    <div class="p-5">
        <div class="preview flex flex-wrap">
            <a href="{{ route('inventories.create') }}" class="button tooltip mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="New Inventory">
                <i class="icon-plus3 w-4 h-4 xl:mr-2"></i>
                <span class="hidden xl:block">{{ __('app.add_new') }}</span>
            </a>
            <a href="#" class="button tooltip mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Import Inventory">
                <i class="icon-database-export w-4 h-4 xl:mr-2"></i>
                <span class="hidden xl:block">{{ __('app.import') }}</span>
            </a>
            <a href="#" class="button tooltip mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Report Inventory">
                <i class="icon-pie-chart2 xl:mr-2 w-4 h-4"></i> <span class="hidden xl:block">{{ __('app.report') }}</span>
            </a>
        </div>
    </div>
</div>
@endsection

@section('page_content')
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <div class="intro-y box">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                    {{ __('inventory.details') }}
                </h2>
            </div>
            <script type="application/json" id='inventory_details'>
                {!! $json_data !!}
            </script>
            <div class="p-5" id="inventory_form">
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts_after')
<script src="{{ asset('js/inventory_form.js?mode=edit&inventoryId='.$inventory->id) }}"></script>
@endsection
