<!-- Content area -->
<div class="content">

    <!-- Inner container -->
    <div class="d-md-flex align-items-md-start">

        <!-- Left sidebar component -->
        <div class="sidebar sidebar-light sidebar-component sidebar-component-left sidebar-expand-md">

            <!-- Sidebar content -->
            <div class="sidebar-content">

                <!-- Sidebar search -->
                <div class="card">
                    <div class="card-header bg-transparent header-elements-inline">
                        <span class="text-uppercase font-size-sm font-weight-semibold">Search Filters</span>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="#">
                            <div class="form-group">
                                <label>Car Make</label>
                                <select class="form-control select-search" id="select_car_make" name="select_car_make"
                                    data-placeholder='Choose Car Make'>
                                    <option></option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label>Car Model</label>
                                <select class="form-control select-search" id="select_car_model" name="select_car_model"
                                    data-placeholder='Choose Car Model'></select>
                            </div>


                            <div class="form-group">
                                <label>Country</label>
                                <select class="form-control select-search" id="select_country" name="select_country"
                                    data-placeholder='Choose Country'>
                                    <option></option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label>City</label>
                                <select class="form-control select-search" id="select_city" name="select_city"
                                    data-placeholder='Choose City'></select>
                            </div>
                            <div class="form-group">
                                <label>Color</label>
                                <select class="form-control select-search" id="select_color" name="select_color"
                                    data-placeholder='Choose Color'>
                                    <option></option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label>Year</label>
                                <select class="form-control select-search" id="select_year" name="select_year"
                                    data-placeholder='Choose Year'>
                                    <option></option>
                                    @for ($i = 1950; $i <= date('Y'); $i++) <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Mileage(km)</label>
                                <input type="text" class="form-control ion-height-helper" id="ion-range" data-fouc>
                            </div>
                            <div class="form-group">
                                <label>Fuel Type</label>
                                <select class="form-control select-search" id="select_fuel_type" name="select_fuel_type"
                                    data-placeholder='Choose Fuel Type'>
                                    <option></option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label>Transmission</label>
                                <select class="form-control select-search" id="select_transmission"
                                    name="select_transmission" data-placeholder='Choose Transmission'>
                                    <option></option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label>Body Type</label>
                                <select class="form-control select-search" id="select_body_type" name="select_body_type"
                                    data-placeholder='Choose Body Type'>
                                    <option></option>

                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary"><i class=""></i>Filter</button>
                            </div>
                        </form>
                    </div>
                </div> <!-- /sidebar search -->


            </div>
            <!-- /sidebar content -->

        </div>
        <!-- /left sidebar component -->


        <!-- Right content -->
        <div class="w-100">



            <!-- Sidebars overview -->
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 class="card-title">Sidebars overview</h5>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                            <a class="list-icons-item" data-action="reload"></a>
                            <a class="list-icons-item" data-action="remove"></a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body bg-blue-400 has-bg-image">
                                <div class="media">
                                    <div class="media-body">
                                        <h3 class="mb-0">54,390</h3>
                                        <span class="text-uppercase font-size-xs">total comments</span>
                                    </div>

                                    <div class="ml-3 align-self-center">
                                        <i class="icon-bubbles4 icon-3x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body bg-danger-400 has-bg-image">
                                <div class="media">
                                    <div class="media-body">
                                        <h3 class="mb-0">389,438</h3>
                                        <span class="text-uppercase font-size-xs">total orders</span>
                                    </div>

                                    <div class="ml-3 align-self-center">
                                        <i class="icon-bag icon-3x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body bg-success-400 has-bg-image">
                                <div class="media">
                                    <div class="mr-3 align-self-center">
                                        <i class="icon-pointer icon-3x opacity-75"></i>
                                    </div>

                                    <div class="media-body text-right">
                                        <h3 class="mb-0">652,549</h3>
                                        <span class="text-uppercase font-size-xs">total clicks</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-xl-3">
                            <div class="card card-body bg-indigo-400 has-bg-image">
                                <div class="media">
                                    <div class="mr-3 align-self-center">
                                        <i class="icon-enter6 icon-3x opacity-75"></i>
                                    </div>

                                    <div class="media-body text-right">
                                        <h3 class="mb-0">245,382</h3>
                                        <span class="text-uppercase font-size-xs">total visits</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table datatable-ajax">
                        <thead>
                            <tr>
                                <th></th>
                                <th>{{ __('app.name') }}</th>
                                <th>{{ __('app.price') }}</th>
                                <th>{{ __('app.tags') }}</th>
                                <th>{{ __('app.status') }}</th>
                                <th>{{ __('app.action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- /sidebars overview -->



        </div>
        <!-- /right content -->

    </div>
    <!-- /inner container -->

</div>
<!-- /content area -->

@section('styles')
<link rel="stylesheet" href="{{ asset('global_assets/js/plugins/lightgallery/src/css/lightgallery.css') }}" />
@endsection

@section('scripts')
<script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/sliders/ion_rangeslider.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/notifications/pnotify.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/lightgallery/src/js/lightgallery.js') }}"></script>
<script src="{{ asset('js/inventories/gallery.js') }}"></script>
<script>
    $(document).ready(function(){
            $('.datatable-ajax').dataTable({
                ajax: '/inventories/ajax_load'
            });
            // Basic range slider
            $('#ion-range').ionRangeSlider({
                type: 'double',
                min: 0,
                max: 1000,
                from: 200,
                to: 800
            });
            $('.select-search').select2({
                placeholder: $(this).data('placeholder')
            })
            $("#select_car_make").on('change', function(e){
                console.log('---------', $("#select_car_make").val())
                $.get(`/car2db/`+$("#select_car_make").val()+`/models`, function(result) {
                    //console.log(result)
                    let html_str = ''
                    result.forEach(function(element){
                      //  console.log(element)
                        html_str += '<option id="'+element.id_car_model+'">' + element.name + '</option>'
                    })
                    $("#select_car_model").html(html_str).change()
                })
            })
            $("#select_country").on('change', function(e){
                console.log('---------', $("#select_country").val())
                $.get('/inventories/cities/'+ $("#select_country").val(), function(result){
                    let html_str = ''
                    result.forEach(function(element){
                        // console.log(element)
                        html_str += '<option id="'+element+'">' + element + '</option>'
                    })
                    $("#select_city").html(html_str).change()
                })
            })
        })
</script>

@endsection
