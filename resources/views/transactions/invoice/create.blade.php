@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.home') }} - {{ __('app.transactions')}}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')

<div class="intro-y flex items-center flex-wrap border-b">
    <div class="-intro-x breadcrumb mr-auto hidden sm:flex">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('app.documents')}}</a> 
    </div>
    <div class="p-5">
        <div class="preview flex flex-wrap">

            <a href="{{ route('transactions.create') }}" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white">
                <i class="icon-plus3 mr-2"></i><span>{{ __('app.add_new') }}</span></a>

        </div>
    </div>
</div>

@endsection

@section('page_content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="box mt-5 col-span-12 sm:col-span-12">
        <div class="p-5" id="invoice_form">

        </div>
    </div>
</div>
@endsection


@section('scripts_after')
<script src="{{ asset('js/invoice_form.js') }}"></script>
@endsection
