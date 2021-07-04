@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.home') }} - {{ __('menu.customers.create')}}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <div class="intro-y box">
            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                    {{ __('customer.details') }}
                </h2>
            </div>
            <script type="application/json" id='json_details'>
                {!! $json_data !!}
            </script>
            <div class="p-5" id="customers_form">
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts_after')
<script src="{{ asset('js/customers_form.js') }}"></script>
@endsection
