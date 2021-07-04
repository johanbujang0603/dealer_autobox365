@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('menu.settings.settings') }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y flex items-center border-b">
    
    <div class="-intro-x breadcrumb mr-auto hidden sm:flex p-5">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('menu.settings.settings') }}</a> 
    </div>

</div>

@endsection
@section('page_content')

<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 sm:col-span-12 lg:col-span-12 mt-8">
        <div class="intro-y flex items-center h-10">
            <h2 class="text-lg font-medium truncate mr-5">
                {{__('settings.basic_settings')}}
            </h2>
        </div>
    </div>
    <div class="col-span-12 sm:col-span-12 lg:col-span-12">
        <div class="intro-y box p-5 mt-5 box">
            <form action="{{ route('settings.save') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mt-5">
                    <label>{{__('settings.default_language')}}:</label>
                    <select class="select2 w-full" name='language' data-placeholder="Choose Default Language">
                        <option></option>
                        <option value="en" @if( isset($settings->language ) && $settings->language == 'en') selected
                            @endif>English</option>
                        <option value="ru" @if( isset($settings->language ) && $settings->language == 'ru') selected
                            @endif>Russian</option>
                    </select>
                </div>
                <div class="mt-5">
                    <label>{{__('settings.default_currency')}}:</label>
                    <select class="select2 w-full" name='currency' data-placeholder="Choose Default Currency">
                        <option></option>
                        @foreach ($currencies as $currency)
                        <option value="{{ $currency->iso_code }}" @if(isset($settings->currency) &&
                            $settings->currency ==
                            $currency->iso_code) selected @endif>{{ $currency->currency }} ({!! $currency->name !!})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-5">

                    <label>{{__('settings.default_measure')}}:</label>
                    <select class="select2 w-full" name='mesure' data-placeholder="Choose Default Mesure">
                        <option></option>
                        <option value="km" @if(isset($settings->mesure) && $settings->mesure == 'km') selected
                            @endif>KM</option>
                        <option value="mi" @if(isset($settings->mesure) && $settings->mesure == 'mi') selected
                            @endif>Mile</option>
                    </select>
                </div>
                <div class="mt-5 fiscal_year">
                    <label class="font-medium text-theme-4">{{__('settings.fiscal_year')}}:</label>
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-6 sm:col-span-6 lg:col-span-6 mt-5">
                            <label for="">{{__('settings.starts')}}</label>
                            <input class="input border w-full" id="fiscal_year_from" name='fiscal_year_from'
                                @if(isset($settings->fiscal_year_from) )
                                    value="{{ $settings->fiscal_year_from}}" @endif
                                    placeholder="from" />
                        </div>
                        <div class="col-span-6 sm:col-span-6 lg:col-span-6 mt-5">
                            <label for="">{{__('settings.ends')}}</label>
                            <input class="input border w-full" id="fiscal_year_to" name="fiscal_year_to"
                                @if(isset($settings->fiscal_year_to) ) value="{{ $settings->fiscal_year_to}}"
                            @endif
                            placeholder="to" />
                        </div>
                    </div>
                </div>

                <h5 class="text-theme-4 mt-5 font-medium">{{__('settings.company_settings')}}</h5>
                <div class="mt-5">
                    <label>{{__('settings.vat_number')}}:</label>
                    <input class="input border w-full" placeholder="Input VAT Number" name="vat_number"
                        @if(isset($settings->vat_number) ) value="{{ $settings->vat_number}}" @endif
                    id="vat_number_input" />
                </div>
                <div class="mt-5">
                    <label>{{__('settings.tax')}}:</label>
                    <input class="input border w-full" placeholder="Input Tax Percentage" name="company_tax"
                        @if(isset($settings->company_tax) ) value="{{ $settings->company_tax}}" @endif
                    id="company_tax_input" />
                </div>

                <div class="mt-5">
                    <label>{{__('settings.company_address')}}:</label>
                    <input type="text" class="input border w-full" name="company_address" id="company_address"
                        value="{{ $settings->company_address }}" />
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5 border p-3 border-theme-8">
                    <div class="col-span-3 sm:col-span-3 lg:col-span-3 mt-5">
                        <label>{{__('settings.street')}}:</label>
                        <input type="text" class="input border w-full" name="company_street_number" id="street_number"
                            value="{{ $settings->company_street_number }}" />
                    </div>
                    <div class="col-span-9 sm:col-span-9 lg:col-span-9 mt-5">
                        <label>{{__('settings.address')}}:</label>
                        <input type="text" class="input border w-full" name="company_route" id="route"
                            value="{{ $settings->company_route }}" />
                    </div>
                    <div class="col-span-12 sm:col-span-12 lg:col-span-12 mt-5">
                        <label>{{__('settings.city')}}:</label>
                        <input type="text" class="input border w-full" name="company_locality" id="locality"
                            value="{{ $settings->company_locality }}" />
                    </div>
                    <div class="col-span-3 sm:col-span-3 lg:col-span-3 mt-5">
                        <label>{{__('settings.state')}}:</label>
                        <input type="text" class="input border w-full" name="company_state"
                            id="administrative_area_level_1" value="{{ $settings->company_state }}" />
                    </div>
                    <div class="col-span-9 sm:col-span-9 lg:col-span-9 mt-5">
                        <label>{{__('settings.zipcode')}}:</label>
                        <input type="text" class="input border w-full" name="company_postal_code" id="postal_code"
                            value="{{ $settings->company_postal_code }}" />
                    </div>
                    <div class="col-span-12 sm:col-span-12 lg:col-span-12 mt-5">
                        <label>{{__('settings.country')}}:</label>
                        <input type="text" class="input border w-full" name="company_country" id="country"
                            value="{{ $settings->company_country }}" />
                    </div>
                </div>
                <div class="mt-5">
                    <label>{{__('settings.company_phone_number')}}:</label>
                    <input type="text" class="input border w-full" name="company_phone_number" id="company_phone_number"
                        value="{{ $settings->company_phone_number }}" />
                </div>
                <div class="mt-5 relative">
                    <label>{{__('settings.company_logo')}}:</label>
                    <input type="file" class="dropify" name="company_logo"
                        data-default-file="{{ isset($settings->company_logo) ? $settings->company_logo_src : '' }}" />
                </div>
                <div class="mt-5">
                    <label>{{__('settings.watermark_place')}}:</label>
                    <select class="w-full select2" name="watermark_place">
                        <option @if(isset($settings->watermark_place) && $settings->watermark_place ==
                            "north_east")
                            selected @endif value="north_east">North East</option>
                        <option @if(isset($settings->watermark_place) && $settings->watermark_place ==
                            "north") selected @endif value="north">North</option>
                        <option @if(isset($settings->watermark_place) && $settings->watermark_place ==
                            "north_west") selected @endif value="north_west">North West</option>
                        <option @if(isset($settings->watermark_place) && $settings->watermark_place ==
                            "west") selected @endif value="west">West</option>
                        <option @if(isset($settings->watermark_place) && $settings->watermark_place ==
                            "south_west") selected @endif value="south_west">South West</option>
                        <option @if(isset($settings->watermark_place) && $settings->watermark_place ==
                            "south") selected @endif value="south">South</option>
                        <option @if(isset($settings->watermark_place) && $settings->watermark_place ==
                            "south_east") selected @endif value="south_east">South East</option>
                        <option @if(isset($settings->watermark_place) && $settings->watermark_place ==
                            "east") selected @endif value="east">East</option>
                        <option @if(isset($settings->watermark_place) && $settings->watermark_place == "center")
                            selected @endif value="center">Center</option>
                    </select>
                </div>
                <div class="mt-5">
                    <label>{{__('settings.watermark_transparence')}}:</label>
                    <input class="w-full" type="range" name="watermark_transparence" min="0" max="100"
                        value="{{ isset($settings->watermark_transparence) ? $settings->watermark_transparence : 30 }}">
                </div>
                <div class="mt-5 text-right">
                    <button type="submit" class="button bg-theme-4 text-white">{{ __('app.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Main charts -->
<div class="row">
    <div class="col-xl-12">

        <!-- Header groups -->
        <div class="card">
            <div class="card-header header-elements-inline">


            </div>

            <div class="card-body">
                
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('global_assets/js/plugins/dropify/dist/css/dropify.min.css') }}" />
@endsection
@section('scripts_after')
<script src="{{ asset('global_assets/js/main/jquery.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/validation/validate.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/ui/moment/moment.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/pickers/daterangepicker.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/pickers/anytime.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/pickers/pickadate/legacy.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/notifications/jgrowl.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>

<script>
    $(document).ready(function(){
        $('#fiscal_year_from').AnyTime_picker({
            format: '%d %M'
        });
        $(".dropify").dropify()
        $("#fiscal_year_to").attr("readOnly","true");
        $("#fiscal_year_from").change(
            function(e) {
                try {
                    var rangeDemoFormat = "%d %M";
                    var rangeDemoConv = new AnyTime.Converter({format:rangeDemoFormat});
                    var oneDay = 24*60*60*1000;
                    var fromDay = rangeDemoConv.parse($("#fiscal_year_from").val()).getTime();
                    var dayLater = new Date(fromDay-oneDay);
                    dayLater.setHours(0,0,0,0);

                    $("#fiscal_year_to").
                    AnyTime_noPicker().
                    removeAttr("disabled").
                    val(rangeDemoConv.format(dayLater)).
                    AnyTime_picker( {
                        earliest: dayLater,
                        format: rangeDemoFormat,
                        } );
                    }
                catch(e) {
                    console.log(e)
                    $("#fiscal_year_to").val("").attr("disabled","disabled");
                    }
        } );
        $("#vat_number_input").blur(function(e){
            console.log(e)
        })
        initAutocomplete()
    })

    var placeSearch, autocomplete;

    var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'short_name',
    country: 'long_name',
    postal_code: 'short_name'
    };

    function initAutocomplete() {
    // Create the autocomplete object, restricting the search predictions to
    // geographical location types.
    autocomplete = new google.maps.places.Autocomplete(
        document.getElementById('company_address'), {types: ['geocode']});

    // Avoid paying for data that you don't need by restricting the set of
    // place fields that are returned to just the address components.
    autocomplete.setFields(['address_component']);

    // When the user selects an address from the drop-down, populate the
    // address fields in the form.
    autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress() {
    // Get the place details from the autocomplete object.
    var place = autocomplete.getPlace();

    for (var component in componentForm) {
        document.getElementById(component).value = '';
        document.getElementById(component).disabled = false;
    }

    // Get each component of the address from the place details,
    // and then fill-in the corresponding field on the form.
    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
        var val = place.address_components[i][componentForm[addressType]];
        document.getElementById(addressType).value = val;
        }
    }
    }

    // Bias the autocomplete object to the user's geographical location,
    // as supplied by the browser's 'navigator.geolocation' object.
    function geolocate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
        var geolocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
        };
        var circle = new google.maps.Circle(
            {center: geolocation, radius: position.coords.accuracy});
        autocomplete.setBounds(circle.getBounds());
        });
    }
    }
</script>
@endsection