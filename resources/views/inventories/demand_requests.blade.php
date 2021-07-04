@extends('layouts.app')

@section('page_header')
<div class="page-header page-header-light">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4><i class="icon-arrow-left52 mr-2"></i> <span
                    class="font-weight-semibold">{{ __('app.inventories') }}</span> -
                Demand Requests</h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>


    </div>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>{{ __('app.home') }}</a>
                <span class="breadcrumb-item active">{{ __('menu.inventories.index')}}</span>
            </div>

            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>


    </div>
</div>

@endsection
@section('page_content')

<!-- Main charts -->
<div class="row">
    <div class="col-xl-12">

        <!-- Header groups -->
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">All Demand Requests</h5>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                        <a class="list-icons-item" data-action="reload"></a>
                        <a class="list-icons-item" data-action="remove"></a>
                    </div>
                </div>
            </div>

            <div class="card-body" id="demand_requests_container">
            </div>


        </div>
        <!-- /header groups -->


    </div>

</div>
<!-- /main charts -->

@endsection
@section('styles')
<link rel="stylesheet" href="{{ asset('global_assets/js/plugins/lightgallery/src/css/lightgallery.css') }}" />
@endsection

@section('scripts')
<script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/notifications/pnotify.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/lightgallery/src/js/lightgallery.js') }}"></script>
<script src="{{ asset('js/inventories/gallery.js') }}"></script>
<script>

</script>
@endsection
