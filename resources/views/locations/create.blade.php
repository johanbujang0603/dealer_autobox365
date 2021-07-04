@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('menu.locations.locations') }} - {{ __('menu.locations.create') }}
</h2>
<!-- END: Breadcrumb -->
@endsection


@section('page_header')
<div class="intro-y flex items-center border-b">
    <div class="-intro-x breadcrumb mr-auto hidden sm:flex p-5">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb">{{ __('menu.locations.locations') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('menu.locations.create') }}</a> 
    </div>
</div>
@endsection

@section('page_content')
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12 box">
        <script type="text/json" id="location_form_data">{!! $json_data !!}</script>
        <div class="p-5" id="location_form">
        </div>
    </div>
</div>
@endsection


@section('scripts_after')
    <script src="{{ asset('js/location_form.js') }}"></script>
@endsection
