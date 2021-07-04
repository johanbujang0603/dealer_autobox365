@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.home') }} - {{ __('app.inventories') }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y flex items-center border-b">

    <div class="-intro-x breadcrumb mr-auto hidden sm:flex p-5">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('menu.inventories.index')}}</a> 
    </div>
    <div class="p-5">
        <div class="preview flex flex-wrap">
            <a href="{{ route('inventories.dashboard') }}" class="tooltip button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Dashboard">
                <i class="xl:mr-2" data-feather="home"></i>
                <span class="hidden xl:block">{{ __('app.dashboard') }}</span>
            </a>
            @if (Auth::user()->hasPermission('inventory', 'write'))
            <a href="{{ route('inventories.create') }}" class="tooltip button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="New Inventory">
                <i class="xl:mr-2" data-feather="plus-circle"></i>
                <span class="hidden xl:block">{{ __('app.add_new') }}</span>
            </a>
            @endif
            
            <a href="{{ route('inventories.import') }}" class="tooltip button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Import Inventory">
                <i class="xl:mr-2" data-feather="database"></i>
                <span class="hidden xl:block">{{ __('app.import') }}</span>
            </a>
            <a href="{{ route('inventories.export') }}" class="tooltip button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Export Inventory">
                <i class="xl:mr-2" data-feather="log-out"></i> 
                <span class="hidden xl:block">{{ __('app.export') }}</span>
            </a>
        </div>
    </div>
</div>

@endsection

@section('page_content')
<script type="text/json" id="json_details">
    {!! $json_data !!}
</script>
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12" id="all_inventories">
    </div>
</div>
@endsection

@section('scripts_after')
<script src="{{ asset('/js/all_inventories.js') }}"></script>
@endsection
