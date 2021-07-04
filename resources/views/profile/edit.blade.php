@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('menu.users.users') }} - {{ __('app.edit') }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y flex items-center border-b">
    
    <div class="-intro-x breadcrumb mr-auto hidden sm:flex p-5">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.profile') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('menu.settings.edit_profile') }}</a> 
    </div>

</div>

@endsection

@section('page_content')

<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 sm:col-span-12 lg:col-span-12 mt-8">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{__('settings.edit_profile')}}
            </h2>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-12 lg:col-span-12">
        <script type="application/json" id='user_form_details'>
            {!! $json_data !!}
        </script>
        <div class="intro-y box p-5 mt-5 box" id="user_form"></div>
    </div>
</div>

@endsection


@section('scripts_after')
<script src="{{ asset('js/user_form.js?mode=self_edit&userId='.Auth::user()->id) }}"></script>
@endsection
