@extends('layouts.auth')

@section('content')
<!-- User Settings Form -->
<div class="card card-body shadow text-left">
    <form action="{{ route('settings.save') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="from" value="register" />
        <div class="form-group">
            <label>{{__('settings.default_language')}}:</label>
            <select class="form-control" name='language' data-placeholder="Choose Default Language"
                id="select_language">
                <option></option>
                <option value="en">English</option>
                <option value="ru">Russian</option>
            </select>
        </div>
        <div class="form-group">
            <label>{{__('settings.default_currency')}}:</label>
            <select class="form-control" name='currency' id="select_currency"
                data-placeholder="Choose Default Currency">
                <option></option>
                @foreach ($currencies as $currency)
                <option value="{{ $currency->iso_code }}">{{ $currency->currency }} ({!!
                    $currency->name !!})
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">

            <label>{{__('settings.default_measure')}}:</label>
            <select class="form-control" name='mesure' id="select_mesure" data-placeholder="Choose Default Mesure">
                <option></option>
                <option value="km" selected>KM</option>
                <option value="mi">Mile</option>
            </select>
        </div>
        <div class="form-group fiscal_year">
            <label>{{__('settings.fiscal_year')}}:</label>
            <div class="form-group row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input class="form-control" id="fiscal_year_from" name='fiscal_year_from' placeholder="from" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input class="form-control" id="fiscal_year_to" name="fiscal_year_to" placeholder="to" />
                    </div>
                </div>
            </div>

        </div>
        <div class="form-group">
            <label>{{__('settings.vat_number')}}:</label>
            <input class="form-control" placeholder="Input VAT Number" name="vat_number" id="vat_number_input" />
        </div>
        <div class="form-group">
            <label>{{__('settings.company_logo')}}:</label>
            <input type="file" class="dropify" name="company_logo"
                data-default-file="{{ isset($settings->company_logo) ? $settings->company_logo_src : '' }}" />
        </div>
        <div class="form-group">
            <label>{{__('settings.watermark_place')}}:</label>
            <select class="form-control" name="watermark_place">
                <option value="north_east">North East</option>
                <option value="north">North</option>
                <option value="north_west">North West</option>
                <option value="west">West</option>
                <option value="south_west">South West</option>
                <option value="south">South</option>
                <option value="south_east">South East</option>
                <option value="east">East</option>
                <option value="center">Center</option>
            </select>
        </div>
        <div class="form-group">
            <label>{{__('settings.watermark_transparence')}}:</label>
            <input class="form-control" type="range" name="watermark_transparence" min="0" max="100" value="30">
        </div>
        <div class="text-right">
            <button type="submit" class="btn btn-primary legitRipple">{{ __('app.save') }}</button>
        </div>


    </form>
</div>
<!-- User Settings Form -->

@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('global_assets/js/plugins/dropify/dist/css/dropify.min.css') }}" />
@endsection
@section('scripts')
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
        var fiscal_years = [
            {"country": "AF", "date":"21 December - 20 December", "from" : "12-21", "to": "12-20"},
            {"country": "AL", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "AG", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "AQ", "date":"1 October - 30 September", "from": "10-01", "to": "09-30"},
            {"country": "AN", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "AO", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "AV", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "AC", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "AR", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "AM", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "AA", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "AS", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "AU", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "AJ", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "BF", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "BA", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "BG", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "BB", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "BO", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "BE", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "BH", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "BN", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "BD", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "BT", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "BL", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "BK", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "BC", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "BR", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "VI", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "BX", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "BU", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "UV", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "BM", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "BY", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "CV", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "CB", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "CM", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "CA", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "CJ", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "CT", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "CD", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "CI", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "CH", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "KT", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "CK", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "CO", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "CN", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "CG", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "CF", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "CW", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "CS", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "IV", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "HR", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "CU", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "CY", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "EZ", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "DA", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "DJ", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "DO", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "DR", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "EC", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "EG", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "ES", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "EK", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "ER", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "EN", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "WZ", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "ET", "date":"8 July - 7 July", "from": "07-08",
            "to": "07-07"},
            {"country": "EE", "date":"NA", "from" : "01-01", "to": "12-31"},
            {"country": "FK", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "FO", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "FJ", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "FI", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "FR", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "FP", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "GB", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "GA", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "GZ", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "GG", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "GM", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "GH", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "GI", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "GR", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "GL", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "GJ", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "GQ", "date":"1 October - 30 September", "from": "10-01", "to": "09-30"},
            {"country": "GT", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "GK", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "GV", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "PU", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "GY", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "HA", "date":"1 October - 30 September", "from": "10-01", "to": "09-30"},
            {"country": "VT", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "HO", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "HK", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "HU", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "IC", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "IN", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "ID", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "IR", "date":"21 March - 20 March", "from": "03-21", "to": "03-20"},
            {"country": "IZ", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "EI", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "IM", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "IS", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "IT", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "JM", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "JA", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "JE", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "JO", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "KZ", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "KE", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "KR", "date":"NA", "from" : "01-01", "to": "12-31"},
            {"country": "KN", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "KS", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "KU", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "KG", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "LA", "date":"1 October - 30 September", "from": "10-01", "to": "09-30"},
            {"country": "LG", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "LE", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "LT", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "LI", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "LY", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "LS", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "LH", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "LU", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "MC", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "MA", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "MI", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "MY", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "MV", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "ML", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "MT", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "RM", "date":"1 October - 30 September", "from": "10-01", "to": "09-30"},
            {"country": "MR", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "MP", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "MX", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "FM", "date":"1 October - 30 September", "from": "10-01", "to": "09-30"},
            {"country": "MD", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "MN", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "MG", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "MJ", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "MH", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "MO", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "MZ", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "WA", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "NR", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "NP", "date":"16 July - 15 July", "from": "07-16", "to": "07-15"},
            {"country": "NL", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "NC", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "NZ", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "NU", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "NG", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "NI", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "NE", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "NF", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "MK", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "CQ", "date":"1 October - 30 September", "from": "10-01", "to": "09-30"},
            {"country": "NO", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "MU", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "PK", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "PS", "date":"1 October - 30 September", "from": "10-01", "to": "09-30"},
            {"country": "PM", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "PP", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "PA", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "PE", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "RP", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "PC", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "PL", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "PO", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "RQ", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "QA", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "RO", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "RU", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "RW", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "SH", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "SC", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "ST", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "SB", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "VC", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "WS", "date":"June 1 - May 31", "from": "06-01", "to": "03-31"},
            {"country": "SM", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "TP", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "SA", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "SG", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "SE", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "SL", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "SN", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "LO", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "SI", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "BP", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "SO", "date":"NA", "from" : "01-01", "to": "12-31"},
            {"country": "SF", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "SP", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "CE", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "SU", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "NS", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "SW", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "SZ", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "SY", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "TW", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "TI", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "TZ", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "TH", "date":"1 October - 30 September", "from": "10-01", "to": "09-30"},
            {"country": "TT", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "TO", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "TL", "date":"1 April - 31 March", "from": "04-01", "to": "03-31"},
            {"country": "TN", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "TD", "date":"1 October - 30 September", "from": "10-01", "to": "09-30"},
            {"country": "TS", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "TU", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "TX", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "TK", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "TV", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "UG", "date":"1 July - 30 June", "from": "07-01", "to": "06-30"},
            {"country": "UP", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "AE", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "UK", "date":"6 April - 5 April", "from": "04-06", "to": "04-05"},
            {"country": "US", "date":"1 October - 30 September", "from": "10-01", "to": "09-30"},
            {"country": "UY", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "UZ", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "NH", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "VE", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "VM", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "VQ", "date":"1 October - 30 September", "from": "10-01", "to": "09-30"},
            {"country": "WF", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "WE", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "WI", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "YM", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "ZA", "date":"calendar year", "from" : "01-01", "to": "12-31"},
            {"country": "ZI", "date":"calendar year", "from" : "01-01", "to": "12-31"}
        ]
        $('.form-control').select2();
        var offset   = new Date().toString().match(/\((.*)\)/).pop();
        console.log(offset, new Date());
        $.ajax({
            url: "https://ajaxhttpheaders.appspot.com",
            dataType: 'jsonp',
            success: function(headers) {
                language = headers['Accept-Language'];
                language = language.split(',')[0]
                $('#select_language').val(language).trigger('change');
                country = headers['X-Appengine-Country'];
                console.log(language, headers);
                fiscal_years.map((fiscal_year) =>{
                    if(fiscal_year.country == country){
                        $("#fiscal_year_from").val(fiscal_year.from)
                        $("#fiscal_year_to").val(fiscal_year.to)
                    }
                })
                $.get('https://restcountries.eu/rest/v1/alpha/' + country, function(result){
                    console.log(result)
                    if(result.currencies && result.currencies.length){
                        currency = result.currencies[0]
                        console.log(currency)
                        $('#select_currency').val(currency).trigger('change');
                    }
                })
            }
        });

        // Month and day
        $('#fiscal_year_from').AnyTime_picker({
            format: '%m-%d'
        });
        $(".dropify").dropify()
        $("#fiscal_year_to").attr("readOnly","true");
        $("#fiscal_year_from").change(
            function(e) {
                try {
                    var rangeDemoFormat = "%m-%d";
                    var rangeDemoConv = new AnyTime.Converter({format:rangeDemoFormat});
                    console.log(rangeDemoConv)
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
    })
</script>
@endsection
