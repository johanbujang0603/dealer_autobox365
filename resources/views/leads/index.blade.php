@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.home') }} - {{ __('menu.leads.index')}}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y flex items-center border-b">

    <div class="-intro-x breadcrumb mr-auto hidden sm:flex p-5">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('menu.leads.index')}}</a> 
    </div>
    <div class="p-5">
        <div class="preview flex flex-wrap">
            <a href="{{ route('leads.dashboard') }}" class="button tooltip mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Dashboard">
                <i class="icon-pie-chart2 xl:mr-2 w-4 h-4"></i><span class="hidden xl:block">{{ __('app.dashboard') }}</span>
            </a>  
            @if (Auth::user()->hasPermission('lead', 'write'))
            <a href="{{ route('leads.create') }}" class="button tooltip mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="New Lead">
                <i class="icon-plus3 xl:mr-2 w-4 h-4"></i><span class="hidden xl:block">{{ __('app.add_new') }}</span>
            </a>
            @endif
            <a href="{{ route('leads.import.index') }}" class="button tooltip mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Import Lead">
                <i class="icon-import xl:mr-2 w-4 h-4"></i>
                <span class="hidden xl:block">{{ __('app.import') }}</span>
            </a>
            <a href="{{ route('leads.export') }}" class="button tooltip mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Export Lead">
                <i class="icon-database-export xl:mr-2 w-4 h-4"></i> <span class="hidden xl:block">{{ __('app.export') }}</span>
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
    <div class="intro-y col-span-12 lg:col-span-12" id="all_leads">
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('global_assets/js/plugins/lightgallery/src/css/lightgallery.css') }}" />
@endsection

@section('scripts_after')
<script src="{{ asset('/js/all_leads.js') }}"></script>
@endsection
