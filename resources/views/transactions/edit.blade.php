@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.transactions') }} - {{__('transaction.edit')}}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y mb-5 flex flex-wrap items-center border-b">

    <div class="-intro-x breadcrumb mr-auto hidden sm:flex p-5">
        <a href="{{ route('dashboard') }}" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a>
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="{{ route('transactions.index') }}" class="breadcrumb">{{ __('app.transactions') }}</a>
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="#" class="breadcrumb--active">{{__('transaction.edit')}}</a>
    </div>

    <div class="p-5">
        <div class="preview flex flex-wrap">
            <a href="{{ route('transactions.create') }}" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white"><i
                    class="icon-plus3 mr-2"></i><span>{{ __('app.add_new') }}</span></a>
            <a href="#" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white"><i class="icon-import mr-2"></i>
                <span>{{ __('app.import') }}</span></a>
            <a href="#" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white"><i
                    class="icon-database-export mr-2"></i> <span>{{ __('app.export') }}</span></a>
            <a href="#" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white"><i class="icon-pie-chart2 mr-2"></i>
                <span>{{ __('app.report') }}</span></a>
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
                    {{ __('transaction.transaction_details') }}
                </h2>
            </div>
            <script type="application/json" id='json_details'>
                {!! $json_data !!}
            </script>
            <div class="p-5" id="transaction_form">
            </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts_after')
<script src="{{ asset('js/transaction_form.js?mode=edit&transactionId='. $transactionId) }}"></script>
@endsection
