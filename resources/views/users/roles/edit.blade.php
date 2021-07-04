@extends('layouts.app')

@section('page_header')
<div class="intro-y p-5 mb-5 flex flex-wrap items-center border-b">

    <div class="-intro-x breadcrumb mr-auto hidden sm:flex">
        <a href="javascript:;" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.users') }}</a>
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="#" class="breadcrumb--active">{{__('user.edit_role')}}</a>
    </div>

</div>
@endsection

@section('page_content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y box mt-5 col-span-12 lg:col-span-12">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                {{__('app.role_details')}}
            </h2>
        </div>
            <script type="text/json" id="role_form_data">
                {!! $json_data !!}
            </script>
        <div class="p-5" id='user_role_form'>
        </div>
    </div>
</div>

@endsection

@section('scripts_after')
<script src="{{ asset('js/user_role_form.js?mode=edit&roleId='.$id) }}"></script>
@endsection
