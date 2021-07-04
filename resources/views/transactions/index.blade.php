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
    <div class="intro-y col-span-12 lg:col-span-12">
        <h2 class="text-lg font-medium mr-auto">
            {{ __('transaction.transaction_details') }}
        </h2>
    </div>
    <div class="intro-y datatable-wrapper box p-5 mt-5 col-span-12 sm:col-span-12">
        <table class="table table-report table-report--bordered display datatable w-full">
            <thead>
                <tr>
                    <th class="border-b-2 whitespace-no-wrap">{{__('app.inventory')}}</th>
                    <th class="border-b-2 whitespace-no-wrap">{{__('app.customer')}}</th>
                    <th class="border-b-2 whitespace-no-wrap">{{__('app.users')}}</th>
                    <th class="border-b-2 whitespace-no-wrap">{{__('app.location')}}</th>
                    <th class="border-b-2 whitespace-no-wrap">{{__('app.price')}}</th>
                    <th class="border-b-2 whitespace-no-wrap">{{__('app.date')}}</th>
                    <th class="border-b-2 whitespace-no-wrap">{{ __('app.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $transaction)
                <tr>
                    <td class="border-b">
                        {!! $transaction['inventory_name'] !!}
                    </td>
                    <td class="border-b">
                        {!! $transaction['customer_name'] !!}
                    </td>
                    <td class="border-b">
                        {!! $transaction['assigned_users'] !!}
                    </td>
                    <td class="border-b">
                        {!! $transaction['assigned_locations'] !!}
                    </td>
                    <td class="border-b">
                        {!! $transaction['price'] !!}
                    </td>
                    <td class="border-b">
                        {!! $transaction['date'] !!}
                    </td>
                    <td class="border-b">
                        {!! $transaction['action_str'] !!}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

