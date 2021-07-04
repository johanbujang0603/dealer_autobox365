@extends('layouts.app')

@section('page_title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.leads') }} - {{ __('menu.leads.edit') }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y p-5 mb-5 flex items-center border-b">

    <div class="-intro-x breadcrumb mr-auto hidden sm:flex">
        <a href="{{ route('dashboard') }}" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a>
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="{{ route('leads.index') }}" class="breadcrumb">{{ __('app.leads') }}</a>
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="#" class="breadcrumb--active">{{ __('menu.leads.edit') }}</a>
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
<script src="{{ asset('js/leads_form.js?mode=edit&leadId='.$lead->id) }}"></script>
@endsection
