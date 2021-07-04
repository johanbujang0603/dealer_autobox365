@extends('layouts.admin')


@section('page_header')
<div class="page-header page-header-light">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4><i class="icon-arrow-left52 mr-2"></i> <span
                    class="font-weight-semibold">{{ __('app.inventories') }}</span> - Create New Status</h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>

        {{--  <div class="header-elements d-none">
            <div class="d-flex justify-content-center">
                <a href="{{ route('admin.inventories.status.create') }}" class="btn btn-link btn-float font-size-sm
        font-weight-semibold text-default legitRipple">
        <i class="icon-plus3 text-pink-300"></i>
        <span>{{ __('app.add_new') }}</span>
        </a>

        <a href="#" class="btn btn-link btn-float font-size-sm font-weight-semibold text-default legitRipple">
            <i class="icon-trash text-pink-300"></i>
            <span>Clear All</span>
        </a>
    </div>
</div> --}}
</div>

<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
    <div class="d-flex">
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item"><i
                    class="icon-home2 mr-2"></i>{{ __('app.home') }}</a>
            <a href="{{ route('admin.inventories.index') }}" class="breadcrumb-item"><i class="fas fa-car mr-2"></i>
                Inventories</a>
            <a href="{{ route('admin.inventories.status') }}" class="breadcrumb-item"><i class="fas fa-car mr-2"></i>
                {{ __('app.status_management') }}</a>
            <span class="breadcrumb-item active">Create New Status</span>
        </div>

        <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
    </div>

    {{--  <div class="header-elements d-none">
            <div class="breadcrumb justify-content-center">
                <a href="#" class="breadcrumb-elements-item">
                    <i class="icon-comment-discussion mr-2"></i>
                    Support
                </a>

                <div class="breadcrumb-elements-item dropdown p-0">
                    <a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-gear mr-2"></i>
                        Settings
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item"><i class="icon-user-lock"></i> Account security</a>
                        <a href="#" class="dropdown-item"><i class="icon-statistics"></i> Analytics</a>
                        <a href="#" class="dropdown-item"><i class="icon-accessibility"></i> Accessibility</a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item"><i class="icon-gear"></i> All settings</a>
                    </div>
                </div>
            </div>
        </div>  --}}
</div>
</div>
@endsection


@section('page_content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            {{--  <div class="card-header header-elements-inline">
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                            <a class="list-icons-item" data-action="reload"></a>
                            <a class="list-icons-item" data-action="remove"></a>
                        </div>
                    </div>
                </div>  --}}

            <div class="card-body">
                <form action="{{ route('admin.inventories.status.save') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset>
                                <legend class="font-weight-semibold"><i class="icon-truck mr-2"></i> Status details
                                </legend>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Status name:</label>
                                    <div class="col-lg-9">
                                        <input type="text" placeholder="Status name..." name="status_name"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">{{ __('app.label_color') }}</label>
                                    <div class="col-lg-9">
                                        {{--  <input type="text" placeholder="Status color..." name="color" class="form-control">  --}}
                                        <select name="color" class="form-control">
                                            <option value="">Status color...</option>
                                            <option value="primary">primary</option>
                                            <option value="danger">danger</option>
                                            <option value="success">success</option>
                                            <option value="warning">warning</option>
                                            <option value="info">info</option>
                                            <option value="pink">pink</option>
                                            <option value="purple">purple</option>
                                            <option value="violet">violet</option>
                                            <option value="indigo">indigo</option>
                                            <option value="blue">blue</option>
                                            <option value="teal">teal</option>
                                            <option value="green">green</option>
                                            <option value="orange">orange</option>
                                            <option value="brown">brown</option>
                                            <option value="grey">grey</option>
                                            <option value="slate">slate</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">&nbsp;</label>
                                    <div class="col-lg-9">
                                        <button type="submit"
                                            class="btn btn-primary legitRipple">{{ __('app.save') }}</button>
                                    </div>
                                </div>


                            </fieldset>
                        </div>
                    </div>
                    {{--  <div class="text-right">  --}}
                    {{--  <button type="submit" class="btn btn-primary legitRipple">Submit form <i class="icon-paperplane ml-2"></i></button>  --}}
                    {{--  </div>  --}}
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
