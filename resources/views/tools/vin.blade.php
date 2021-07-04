@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.tools') }} - {{ __('menu.tools.vin_identification') }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')

<div class="intro-y flex items-center flex-wrap border-b">

    <div class="-intro-x breadcrumb mr-auto hidden sm:flex p-5">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb">{{ __('app.tools') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('menu.tools.vin_identification') }}</a> 
    </div>
</div>

@endsection

@section('page_content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <h2 class="text-lg font-medium mr-auto">
            {{ __('tools.vin_identification') }}
        </h2>
    </div>
    <div class="intro-y col-span-12">
        <div class="box p-5 mt-5">
            <h2>Comming Soon</h2>
        </div>
    </div>
</div>

@endsection
