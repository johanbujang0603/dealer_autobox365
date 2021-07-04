@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.inventories') }} - {{ __('app.valuation') }}
</h2>
<!-- END: Breadcrumb -->
@endsection


@section('page_header')
<div class="intro-y p-5 mb-5 flex items-center border-b">

    <div class="-intro-x breadcrumb mr-auto hidden sm:flex p-5">
        <a href="{{ route('dashboard') }}" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a>
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="{{ route('inventories.index') }}" class="breadcrumb">{{ __('app.inventories') }}</a>
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="#" class="breadcrumb--active">{{__('app.log_timeline')}}</a>
    </div>

    <div class="p-5">
        <div class="preview flex flex-wrap">
            <a href="{{ route('inventories.dashboard') }}" class="button tooltip mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Dashboard">
                <i class="xl:mr-2 icon-pie-chart2"></i>
                <span class="xl:block hidden">{{ __('app.dashboard') }}</span>
            </a>
            <a href="{{ route('inventories.create') }}" class="button tooltip mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="New Inventory">
                <i class="xl:mr-2 icon-plus3"></i><span class="xl:block hidden">{{ __('app.add_new') }}</span>
            </a>
            <a href="{{ route('inventories.import') }}" class="button tooltip mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Import Inventory">
                <i class="xl:mr-2 icon-import"></i>
                <span class="xl:block hidden">{{ __('app.import') }}</span>
            </a>
            <a href="{{ route('inventories.export') }}" class="button tooltip mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Export Inventory">
                <i class="xl:mr-2 icon-database-export"></i> <span class="xl:block hidden">{{ __('app.export') }}</span>
            </a>
        </div>
    </div>

</div>
@endsection

@section('page_content')
<div class="content" id="valuation_content">

</div>
@endsection

@section('scripts')
<script src="{{ asset('js/valuation.js') }}"></script>
@endsection
