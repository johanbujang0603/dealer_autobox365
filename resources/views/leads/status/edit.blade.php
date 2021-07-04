@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.leads') }} - {{__('app.edit_status')}}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y p-5 mb-5 flex items-center border-b">

    <div class="-intro-x breadcrumb mr-auto hidden sm:flex">
        <a href="{{ route('dashboard') }}" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a>
        <i data-feather="chevron-right" class="breadcrumb__icon"></i>
        <a href="{{ route('leads.index') }}" class="flex items-center">{{ __('app.leads') }}</a>
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="{{ route('leads.status') }}" class="breadcrumb">{{ __('app.status_management') }}</a>
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="#" class="breadcrumb--active">{{__('app.edit_status')}}</a>
    </div>

</div>
@endsection


@section('page_content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y box mt-5 col-span-12 lg:col-span-12">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                {{__('app.status_details')}}
            </h2>
        </div>
        <div class="p-5">
            <form action="{{ route('leads.status.save') }}" method="POST" id="create_form">
                @csrf
                <input type="hidden" name="id" value="{{ $status->id }}" />
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <label class="col-span-12 lg:col-span-3">{{__('app.status_name')}}:</label>
                    <div class="col-span-12 lg:col-span-9">
                        <input type="text" placeholder="Status name..." name="status_name" id="status_name"
                            class="input w-full border" value="{{ $status->status_name }}">
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <label class="col-span-12 lg:col-span-3">{{ __('app.label_color') }}:</label>
                    <div class="col-span-12 lg:col-span-9">
                        <select name="color" class="input border mr-2 w-full" id="color_selector">
                            <option value="">{{__('app.status_color')}}...</option>
                                @foreach(config('constants.colors') as $color)
                                    <option value="{{$color['value']}}" @if($status->color == $color['value']) selected @endif>{{ $color['value'] }}</option>
                                @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <label class="col-span-12 lg:col-span-3"></label>
                    <div class="col-span-12 lg:col-span-9">
                        <button type="submit" class="button w-40 mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white">
                            <i data-feather="save" class="mr-2"></i>
                            {{ __('app.save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts_after')
<script>
    $(document).ready(function(){
            function colorDropdownFormat(state) {
                if (!state.id) {
                  return state.text;
                }
                var baseUrl = "/user/pages/images/flags";
                var $state = $(
                    '<span class="py-1 px-2 rounded-full text-xs text-theme-6 font-medium bg bg- bg-' + state.text+ '">&nbsp;&nbsp;</span>&nbsp;<span>' + state.text.charAt(0).toUpperCase() + state.text.slice(1) + '</span>'
                );
                return $state;
            };
            $("#color_selector").select2({
                placeholder: 'Select an color',
                templateResult: colorDropdownFormat
            })
        })
</script>
@endsection
