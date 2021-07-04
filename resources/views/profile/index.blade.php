@extends('layouts.app')
@section('page_header')
<div class="page-header page-header-light">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4><i class="icon-arrow-left52 mr-2"></i> <span
                    class="font-weight-semibold">{{ __('app.profile') }}</span>
            </h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
        <div class="header-elements d-none">
            <div class="d-flex justify-content-center">
                <a href="{{ route('profile.edit') }}" class="btn btn-link btn-float text-default"><i class="icon-pencil3 text-primary"></i><span>{{ __('app.edit') }}</span></a>
            </div>
        </div>

    </div>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>{{ __('app.home') }}</a>
                <a href="learning_list.html" class="breadcrumb-item">{{ __('app.profile') }}</a>

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
                <h5 class="card-title">{{__('app.profile')}}</h5>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                        <a class="list-icons-item" data-action="reload"></a>
                        <a class="list-icons-item" data-action="remove"></a>
                    </div>
                </div>
            </div>

            <div class="card-body">
            
            </div>
        </div>
    </div>
</div>
@endsection
 