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
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('app.documents')}}</a> 
    </div>
    <div class="p-5" id="icon-button">
        <div class="preview flex flex-wrap">
            @if (Auth::user()->hasPermission('document', 'write'))
                <a href="{{ route('documents.create') }}" class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white">
                    <i class="icon-plus3 mr-2"></i><span>{{ __('app.add_new') }}</span></a>
            @endif
        </div>
    </div>
</div>

@endsection

@section('page_content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <h2 class="text-lg font-medium mr-auto">
            {{ __('document.all') }}
        </h2>
    </div>
</div>

<div class="intro-y grid grid-cols-12 gap-3 sm:gap-6 mt-5">
    @foreach ($documents as $document)
    <div class="intro-y col-span-6 sm:col-span-4 md:col-span-3 xxl:col-span-2">
        <div class="file box rounded-md px-5 pt-8 pb-5 px-3 sm:px-5 relative zoom-in">
            <a href="" class="w-3/5 file__icon file__icon--file mx-auto">
                <div class="file__icon__file-name">{{ $document->type }}</div>
            </a>
            <a href="" class="block font-medium mt-4 text-center truncate">{{ $document->original_name }}</a> 
            <div class="text-gray-600 text-xs text-center">{{ formatSizeUnits($document->size) }}</div>
            <div class="absolute top-0 right-0 mr-2 mt-2 dropdown ml-auto">
                <a class="dropdown-toggle w-5 h-5 block" href="javascript:;"> <i data-feather="more-vertical" class="w-5 h-5 text-gray-500"></i> </a>
                <div class="dropdown-box mt-5 absolute w-32 top-0 right-0 z-10">
                    <div class="dropdown-box__content box p-2">
                        <a href="{{ route('documents.download', $document->id) }}" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md"><i class="icon-download mr-2"></i>Download</a>
                        @if (Auth::user()->hasPermission('document', 'write'))
                        <a target="_blank" href="/{{ $document->upload_path }}" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md"><i class="icon-eye mr-2"></i>View</a>
                        @endif
                        @if (Auth::user()->hasPermission('document', 'delete'))
                        <a class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md"  onclick="confirmRemove( '{{$document->original_name}}' , '{{$document->id}}')"><i class="icon-bin mr-2"></i>Delete</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="intro-y flex flex-wrap sm:flex-row sm:flex-no-wrap items-center mt-6">
    {{ $documents->links('pagination.default') }}
</div>

@endsection


@section('scripts_after')
<script>
    function confirmRemove( document_name, document_id) {
        var id = document_id;
        $.confirm({
            title: 'Are you sure you want to delete this listing?',
            content: '<p>' + document_name + '</p>',
            theme: 'supervan',
            buttons: {
                confirm: function () {
                    location.href = '/documents/delete/' + id;
                },
                cancel: function () {
                },
            }
        });
    }
</script>
@endsection
