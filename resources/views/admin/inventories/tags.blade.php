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
                <a href="{{ route('admin.inventories.tags.create') }}"
                    class="btn btn-link btn-float font-size-sm font-weight-semibold text-default legitRipple">
                    <i class="icon-plus3 text-pink-300"></i>
                    <span>{{ __('app.add_new') }}</span>
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
                    {{__('app.inventories')}}</a>
                <span class="breadcrumb-item active">{{ __('app.tags') }}</span>
            </div>

            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
@endsection


@section('page_content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title">{{__('app.all_tags')}}</h5>
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
                            <th>{{__('app.tag_name')}}</th>
                            <th>{{__('app.tag_color')}}</th>
                            <th>{{__('app.created_by')}}</th>
                            <th>{{ __('app.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tags as $tag)
                        <tr>
                            <td>{{ $tag->tag_name }}</td>
                            <td class="text-{{ $tag->color }}">{{ $tag->color }}</td>
                            <td>{{ $tag->created_by }}</td>
                            <td class="">
                                <a class="mr-1"
                                    href="{{ route('admin.inventories.tags.edit', $tag->id) }}">{{ __('app.edit') }}</a>
                                <a class="mr-1"
                                    href="{{ route('admin.inventories.tags.delete', $tag->id) }}">{{ __('app.delete') }}</a>
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
