@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.home') }} - {{ __('app.documents')}}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')

<div class="intro-y flex items-center flex-wrap border-b">

    <div class="-intro-x breadcrumb mr-auto hidden sm:flex">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.documents') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('menu.documents.create')}}</a> 
    </div>
    <div class="p-5">
        <div class="preview flex flex-wrap">
            <a href="{{ route('transactions.create') }}" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white">
                <i class="icon-plus3 mr-2"></i><span>{{ __('app.add_new') }}</span>
            </a>
        </div>
    </div>
</div>

@endsection

@section('page_content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <h2 class="text-lg font-medium mr-auto">
            {{ __('document.upload') }}
        </h2>
    </div>
    <div class="intro-y col-span-12 lg:col-span-12 box p-5" id="create_document_form">
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="intro-y col-span-12 lg:col-span-6">
                <label class="mb-3">{{ __('app.inventory') }}</label>
                <select class="select2 w-full" name='inventories' data-placeholder="Choose Inventory" multiple>
                    <option></option>
                    @foreach($inventories as $inventory)
                        <option value="{{$inventory->id}}">{{$inventory->inventory_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="intro-y col-span-12 lg:col-span-6">
                <label class="mb-3">{{ __('app.customer') }}</label>
                <select class="select2 w-full" name='customers' data-placeholder="Choose Customer" multiple>
                    <option></option>
                    @foreach($customers as $customer)
                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="intro-y col-span-12 lg:col-span-6">
                <label class="mb-3">{{ __('app.leads') }}</label>
                <select class="select2 w-full" name='leads' data-placeholder="Choose Lead" multiple>
                    <option></option>
                    @foreach($leads as $lead)
                        <option value="{{$lead->id}}">{{$lead->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="intro-y col-span-12 lg:col-span-6">
                <label class="mb-3">{{ __('app.location') }}</label>
                <select class="select2 w-full" name='locations' data-placeholder="Choose Location" multiple>
                    <option></option>
                    @foreach($locations as $location)
                        <option value="{{$location->id}}">{{$location->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="intro-y col-span-12 lg:col-span-6">
                <label class="mb-3">{{ __('app.users') }}</label>
                <select class="select2 w-full" name='users' data-placeholder="Choose User" multiple>
                    <option></option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->full_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="intro-y col-span-12 lg:col-span-6">
                <textarea class="input border w-full doc-description" placeholder="Add Description"></textarea>
            </div>
        </div>
        <div class="mt-5">
            <form action="{{ route('documents.upload') }}" class="dropzone border-gray-200 border-dashed" id="document_upload_form">
                @csrf
                <div class="fallback">
                    <input name="file" type="file" multiple/>
                </div>
                <div class="dz-message" data-dz-message>
                    <div class="text-lg font-medium">Drop files here or click to upload.</div>
                    <div class="text-gray-600"> This is just a demo dropzone. Selected files are <span class="font-medium">not</span> actually uploaded. </div>
                </div>
            </form>
        </div>
        <div class="mt-5">
            <button type="submit" class="button bg-theme-4 text-white" id="upload-doc">Submit</button>
        </div>
    </div>
</div>

@endsection
