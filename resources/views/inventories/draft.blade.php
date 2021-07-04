@extends('layouts.app')

@section('page_header')
<div class="page-header page-header-light">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">{{ __('app.inventories') }}</span> - Draft</h4>
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
                <span class="breadcrumb-item">{{ __('menu.inventories.inventory')}}</span>
                <span class="breadcrumb-item active">{{ __('menu.inventory.draft')}}</span>
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
                    <h5 class="card-title">{{__('app.drafts')}}</h5>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                            <a class="list-icons-item" data-action="reload"></a>
                            <a class="list-icons-item" data-action="remove"></a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table datatable-ajax">
                        <thead>
                            <tr>
                                <th>{{ __('app.name') }}</th>
                                <th>{{ __('app.price') }}</th>
                                <th>{{ __('app.tags') }}</th>
                                <th>{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>


            </div>
            <!-- /header groups -->


    </div>

</div>
<!-- /main charts -->

@endsection


@section('scripts')
    <script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('global_assets/js/plugins/notifications/pnotify.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('.datatable-ajax').dataTable({
                ajax: '/inventories/ajax_load?option=draft'
            });
        })

        function deleteListing(id){
            var notice = new PNotify({
                title: 'Confirmation',
                text: '<p>Are you sure you want to delete this listing?</p>',
                hide: false,
                type: 'warning',
                width: "100%",
                cornerclass: "rounded-0",
                addclass: "stack-custom-top bg-danger border-danger",
                stack: {"dir1": "right", "dir2": "down", "push": "top", "spacing1": 1},
                confirm: {
                    confirm: true,
                    buttons: [
                        {
                            text: 'Yes',
                            addClass: 'btn btn-sm btn-danger'
                        },
                        {
                            addClass: 'btn btn-sm btn-info'
                        }
                    ]
                },
                buttons: {
                    closer: false,
                    sticker: false
                }
            })

            // On confirm
            notice.get().on('pnotify.confirm', function() {
                location.href = '/inventories/delete/' + id
            })

            // On cancel
            notice.get().on('pnotify.cancel', function() {
                //alert('Oh ok. Chicken, I see.');
            });
        }
        function publish(id){
            var notice = new PNotify({
                title: 'Confirmation',
                text: '<p>Are you sure you want to publish from draft this listing?</p>',
                hide: false,
                type: 'warning',
                width: "100%",
                cornerclass: "rounded-0",
                addclass: "stack-custom-top bg-warning border-warning",
                stack: {"dir1": "right", "dir2": "down", "push": "top", "spacing1": 1},
                confirm: {
                    confirm: true,
                    buttons: [
                        {
                            text: 'Yes',
                            addClass: 'btn btn-sm btn-warning'
                        },
                        {
                            addClass: 'btn btn-sm btn-info'
                        }
                    ]
                },
                buttons: {
                    closer: false,
                    sticker: false
                }
            })

            // On confirm
            notice.get().on('pnotify.confirm', function() {
                location.href = '/inventories/publish/' + id
            })

            // On cancel
            notice.get().on('pnotify.cancel', function() {
                //alert('Oh ok. Chicken, I see.');
            });
        }
    </script>
@endsection
