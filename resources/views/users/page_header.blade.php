<div class="page-header page-header-light">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">{{ __('menu.users.users') }}</span> - {{ $title }}</h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>

        <div class="header-elements d-none">
            <div class="d-flex justify-content-center">
                <a href="#" class="btn btn-link btn-float text-default"><i class="icon-plus3 text-primary"></i><span>{{ __('app.add_new') }}</span></a>
                <a href="#" class="btn btn-link btn-float text-default"><i class="icon-import text-primary"></i> <span>{{ __('app.import') }}</span></a>
                <a href="#" class="btn btn-link btn-float text-default"><i class="icon-database-export text-primary"></i> <span>{{ __('app.export') }}</span></a>
                <a href="#" class="btn btn-link btn-float text-default"><i class="icon-pie-chart2 text-primary"></i> <span>{{ __('app.report') }}</span></a>
            </div>
        </div>
    </div>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>{{ __('app.home') }}</a>
                <a href="learning_list.html" class="breadcrumb-item">{{__('app.users')}}</a>
                <span class="breadcrumb-item active">{{ $title }}</span>
            </div>

            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
