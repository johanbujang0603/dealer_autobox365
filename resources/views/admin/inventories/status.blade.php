@extends('layouts.admin')


@section('page_header')
<div class="page-header page-header-light">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4><i class="icon-arrow-left52 mr-2"></i> <span
                    class="font-weight-semibold">{{ __('app.inventories') }}</span> - {{ __('app.status_management') }}
            </h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>

        <div class="header-elements d-none">
            <div class="d-flex justify-content-center">
                <a href="{{ route('admin.inventories.status.create') }}"
                    class="btn btn-link btn-float font-size-sm font-weight-semibold text-default legitRipple">
                    <i class="icon-plus3 text-pink-300"></i>
                    <span>{{ __('app.add_new') }}</span>
                </a>

                <a href="#" class="btn btn-link btn-float font-size-sm font-weight-semibold text-default legitRipple">
                    <i class="icon-trash text-pink-300"></i>
                    <span>Clear All</span>
                </a>
            </div>
        </div>
    </div>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item"><i
                        class="icon-home2 mr-2"></i>{{ __('app.home') }}</a>
                <a href="{{ route('admin.inventories.index') }}" class="breadcrumb-item"><i class="fas fa-car mr-2"></i>
                    Inventories</a>
                <span class="breadcrumb-item active">Status</span>
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
            <div class="card-header header-elements-inline">
                <h5 class="card-title">All Status</h5>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                        <a class="list-icons-item" data-action="reload"></a>
                        <a class="list-icons-item" data-action="remove"></a>
                    </div>
                </div>
            </div>



            <div class="table-responsive">
                <table class="table text-nowrap">
                    <thead>
                        <tr>
                            <th>Status Name</th>
                            <th>Status Color</th>
                            <th>CreatedBy</th>
                            <th>{{ __('app.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($statuses as $status)
                        <tr>
                            <td>{{ $status->status_name }}</td>
                            <td class="text-{{ $status->color }}">{{ $status->color }}</td>
                            <td>{{ $status->created_by }}</td>
                            <td class="">
                                <a class="mr-1"
                                    href="{{ route('admin.inventories.status.edit', $status->id) }}">{{ __('app.edit') }}</a>
                                <a class="mr-1"
                                    href="{{ route('admin.inventories.status.delete', $status->id) }}">{{ __('app.delete') }}</a>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
