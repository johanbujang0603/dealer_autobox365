@extends('layouts.admin')
@section('page_header')
<div class="page-header page-header-light">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4><i class="icon-arrow-left52 mr-2"></i> <span
                    class="font-weight-semibold">{{ __('menu.inventories.inventory') }}</span> -
                {{ __('app.valuation.upload') }}</h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>

        <div class="header-elements d-none">
            <div class="d-flex justify-content-center">
                <a href="{{ route('admin.inventories.valuation.index') }}"
                    class="btn btn-link btn-float font-size-sm font-weight-semibold text-default legitRipple">
                    <i class="icon-arrow-left7 icon-2x text-primary"></i>
                    <span>Go To Valuation</span>
                </a>

            </div>
        </div>
    </div>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>{{ __('app.home') }}</a>
                <span class="breadcrumb-item active">{{ __('app.dashboard') }}</span>
            </div>

            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>


    </div>
</div>
@endsection

@section('page_content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body" id="admin_valuation_upload_element"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('/js/admin/valuation_form.js') }}" socket_url="{{ env('SOCKET_SERVER') }}"></script>
@endsection
