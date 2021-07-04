@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.home') }} - {{ __('app.inventories') }} - {{ __('app.import') }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y flex items-center border-b">

    <div class="-intro-x breadcrumb mr-auto hidden sm:flex p-5">
        <a href="{{ route('inventories.dashboard') }}" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('app.inventories') }} - {{ __('app.import') }}</a> 
    </div>
    <div class="p-5" id="icon-button">
        <div class="preview flex flex-wrap">
            <a href="{{ route('inventories.dashboard') }}" class="button tooltip mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="Dashboard">
                <i class="icon-pie-chart2 xl:mr-2"></i>
                <span class="hidden xl:block">{{ __('app.dashboard') }}</span></a>
            <a href="{{ route('inventories.create') }}" class="button tooltip mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" title="New Inventory">
                <i class="icon-plus3 xl:mr-2"></i>
                <span class="hidden xl:block">{{ __('app.add_new') }}</span>
            </a>
        </div>
    </div>
</div>
@endsection


@section('page_content')
<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12" id="all_inventories">
    
        <div class="p-5">
            <div class="preview">
                <div class="rounded-md px-5 py-4 mb-2 bg-theme-1 text-white">
                    <div class="flex items-center">
                        <div class="font-medium text-lg">Note</div>
                    </div>
                    <div class="mt-3">
                        You can import inventories from your files. You must choose
                        <code>.xlsx</code> or <code>.csv</code> file to import in our database.
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    <div class="intro-y col-span-12 lg:col-span-12" id="all_inventories">
        <div class="p-5" 
            id="inventory_import_form"
            data-status="{{ $running_import_job ? 'running': 'norunning' }}"
            data-progress="{{ isset($running_import_job->progress) ? $running_import_job->progress: 0 }}">
        </div>
    </div>
</div>
@endsection

@section('scripts_after')
<script src="{{ asset('js/inventory_import_form.js') }}" socket_url='{{ env('SOCKET_SERVER') }}'>
</script>
@endsection
