@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.inventories') }} - {{ __('menu.leads.create') }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y flex items-center border-b">
    
    <div class="-intro-x breadcrumb mr-auto hidden sm:flex p-5">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb">{{ __('app.leads') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i>
        <a href="" class="breadcrumb--active">{{ __('menu.leads.create') }}</a> 
    </div>
    <div class="p-5" id="icon-button">
        <div class="preview flex flex-wrap">
            <a href="{{ route('leads.dashboard') }}" class="button tooltip mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Dashboard">
                <i class="icon-pie-chart2 xl:mr-2 w-5 h-5"></i>
                <span class="hidden xl:block">{{ __('app.dashboard') }}</span>
            </a>
            <a href="{{ route('leads.export') }}" class="button tooltip mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Export Lead">
                <i class="icon-database-export xl:mr-2 w-5 h-5"></i> <span class="hidden xl:block">{{ __('app.export') }}</span>
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
                    {{ __('lead.details') }}
                </h2>
            </div>
            <script type="application/json" id='json_details'>
                {!! $json_data !!}
            </script>
            <div class="p-5" id="leads_form">
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts_after')
<script src="{{ asset('js/leads_form.js') }}"></script>
@endsection
