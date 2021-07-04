@extends('layouts.admin')


@section('page_header')
<div class="page-header page-header-light">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4><i class="icon-arrow-left52 mr-2"></i> <span
                    class="font-weight-semibold">{{ __('app.inventories') }}</span> - Edit Tag</h4>
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
                Tag Management</a>
            <span class="breadcrumb-item active">Edit Tag</span>
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
                <form action="{{ route('admin.inventories.tags.save') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $tag->id }}" />
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset>
                                <legend class="font-weight-semibold"><i class="icon-truck mr-2"></i> Tag details
                                </legend>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Tag name:</label>
                                    <div class="col-lg-9">
                                        <input type="text" placeholder="Tag name..." name="tag_name"
                                            class="form-control" value="{{ $tag->tag_name }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">{{ __('app.label_color') }}</label>
                                    <div class="col-lg-9">
                                        {{--  <input type="text" placeholder="Tag color..." name="color" class="form-control">  --}}
                                        <select name="color" class="form-control">
                                            <option value="">Tag color...</option>
                                            <option @if($tag->color == "primary") selected @endif
                                                value="primary">primary</option>
                                            <option @if($tag->color == "danger") selected @endif value="danger">danger
                                            </option>
                                            <option @if($tag->color == "success") selected @endif
                                                value="success">success</option>
                                            <option @if($tag->color == "warning") selected @endif
                                                value="warning">warning</option>
                                            <option @if($tag->color == "info") selected @endif value="info">info
                                            </option>
                                            <option @if($tag->color == "pink") selected @endif value="pink">pink
                                            </option>
                                            <option @if($tag->color == "purple") selected @endif value="purple">purple
                                            </option>
                                            <option @if($tag->color == "violet") selected @endif value="violet">violet
                                            </option>
                                            <option @if($tag->color == "indigo") selected @endif value="indigo">indigo
                                            </option>
                                            <option @if($tag->color == "blue") selected @endif value="blue">blue
                                            </option>
                                            <option @if($tag->color == "teal") selected @endif value="teal">teal
                                            </option>
                                            <option @if($tag->color == "green") selected @endif value="green">green
                                            </option>
                                            <option @if($tag->color == "orange") selected @endif value="orange">orange
                                            </option>
                                            <option @if($tag->color == "brown") selected @endif value="brown">brown
                                            </option>
                                            <option @if($tag->color == "grey") selected @endif value="grey">grey
                                            </option>
                                            <option @if($tag->color == "slate") selected @endif value="slate">slate
                                            </option>
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
