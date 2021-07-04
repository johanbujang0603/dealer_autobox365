<!DOCTYPE html>
<html lang="en">

<head>
    <title>Autobox365 - Inventory Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">

    <!-- External CSS libraries -->
    <link rel="stylesheet" type="text/css" href="{{ asset('inventory_assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('inventory_assets/css/bootstrap-submenu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('inventory_assets/css/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('inventory_assets/css/slider.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('inventory_assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('global_assets/css/icons/fontawesome/styles.min.css') }}">

    <link href="{{ asset('inventory_assets/css/bootstrap-select.min.css') }}" type="text/css" rel="stylesheet">

    <!-- Custom stylesheet -->
    <link rel="stylesheet" type="text/css" href="{{ asset('inventory_assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" id="style_sheet" href="{{ asset('inventory_assets/css/color/default.css') }}">


    <!-- Google fonts -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800%7CPlayfair+Display:400,700%7CRoboto:100,300,400,400i,500,700">



</head>

<body>
    <div class="page_loader"><img src="{{ asset('global_assets/images/loader.gif') }}" alt="Loader"></div>
  

    <!-- Main header start -->
    <header class="main-header">
        <div class="container">
            <nav class="navbar navbar-default">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navigation" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="javascript:;" class="logo">
                        <img src="{{$inventory->company_logo}}" alt="logo">
                    </a>
                </div>
                <!-- /.container -->
            </nav>
        </div>
    </header>
    <!-- Main header end -->

   

    <!-- Car details body start-->
    <div class="car-details-body content-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Option bar start-->
                    <div class="option-bar heading-car mb-30 details-option-bar">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">
                                    <h3>{{$inventory->inventory_name}}</h3>
                                    @if($inventory->location_details)
                                    <p><i class="fa fa-map-marker"></i>{{$inventory->location_details->full_address}}</p>
                                    @endif
                                </div>
                                <div class="p-r">
                                    <h3>{{$inventory->currency_details ? $inventory->currency_details->symbol : ''}}{{$inventory->price_original}}</h3>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Option bar end-->
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <div class="car-details sidebar-widget">
                        <!-- Car detail slider start-->
                        <div class="car-detail-slider simple-slider hidden-mb-30">
                            <div id="carousel-custom" class="carousel slide" data-ride="carousel">
                                <div class="carousel-outer">
                                    <!-- Wrapper for slides -->
                                    <div class="carousel-inner">
                                        @foreach ($inventory->photo_details as $key => $photo)
                                         <div class='item @if($key == 0) active @endif'>
                                            <img src='{{ $photo->image_src }}' class="thumb-preview" />
                                        </div>
                                        @endforeach
                                    </div>
                                    <!-- Controls -->
                                    <a class="left carousel-control" href="#carousel-custom" role="button"
                                        data-slide="prev">
                                        <span class="slider-mover-left no-bg" aria-hidden="true">
                                            <i class="fa fa-angle-left"></i>
                                        </span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="right carousel-control" href="#carousel-custom" role="button"
                                        data-slide="next">
                                        <span class="slider-mover-right no-bg" aria-hidden="true">
                                            <i class="fa fa-angle-right"></i>
                                        </span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                                <!-- Indicators -->
                                <ol class="carousel-indicators thumbs visible-lg visible-md">
                                    @foreach ($inventory->photo_details as $key => $photo)
                                    <li data-target="#carousel-custom" data-slide-to="0" class=""><img
                                            src="{{ $photo->image_src }}" alt="Chevrolet Impala"></li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                        <!-- Car detail slider end-->
                        <br />

                        <!-- Car details sidebar start  -->
                        <div class="single-block car-details-sidebar hidden-lg hidden-md">
                            <h2 class="title">Specifications</h2>
                            <ul>
                                <li>
                                    <span>Make</span>Ferrari
                                </li>
                                <li>
                                    <span>Model</span>Maxima
                                </li>
                                <li>
                                    <span>Body Style</span>Convertible
                                </li>
                                <li>
                                    <span>Year</span>2017
                                </li>
                                <li>
                                    <span>Condition</span>Brand New
                                </li>
                                <li>
                                    <span>Mileage</span>34,000 mi
                                </li>
                                <li>
                                    <span>Interior Color</span>Dark Grey
                                </li>
                                <li>
                                    <span>Transmission</span>6-speed Manual
                                </li>
                                <li>
                                    <span>Engine</span>3.4L Mid-Engine V6
                                </li>
                                <li>
                                    <span>No. of Gears:</span>5
                                </li>
                                <li>
                                    <span>Location</span>Toronto
                                </li>
                                <li>
                                    <span>Fuel Type</span>Gasoline Fuel
                                </li>
                            </ul>
                        </div>
                        <!-- Car details sidebar end -->

                        <!-- Panel box start -->
                        <div class="panel-box single-block">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab1default" data-toggle="tab" aria-expanded="true">General
                                        Information</a></li>
                                <li class=""><a href="#tab2default" data-toggle="tab" aria-expanded="false">Technical
                                        Specifications</a></li>
                              
                            </ul>
                            <div class="panel with-nav-tabs panel-default">
                                <div class="panel-body">
                                    <div class="tab-content">
                                        <div class="tab-pane fade active in" id="tab1default">
                                            <div class="general-information">
                                                <h2 class="title">
                                                    General Information About Car
                                                </h2>
                                                {!! $inventory->description !!}

                                                  <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 amenities-box">
                                                        <ul>
                                                            <li>
                                                                <span><i class="flaticon-sedan-car-front"></i></span>
                                                                <strong>Brand:</strong> {{ $inventory->make_details ? $inventory->make_details->name : 'N/A' }}
                                                            </li>
                                                            <li>
                                                                <span><i class="flaticon-racing-flag"></i></span>
                                                                <strong>Model:</strong> {{ $inventory->model_details ? $inventory->model_details->name : 'N/A' }}
                                                            </li>
                                                            <li>
                                                                <span><i class="flaticon-gasoline-pump"></i></span>
                                                                <strong>Generation:</strong> {{ $inventory->generation_details ? $inventory->generation_details->name : 'N/A' }}
                                                            </li>
                                                            <li>
                                                                <span><i class="flaticon-road-with-broken-line"></i></span>
                                                                <strong>Serie:</strong>{{ $inventory->serie_details ? $inventory->serie_details->name : 'N/A' }}
                                                            </li>
                                                            <li>
                                                                <span><i class="flaticon-engine"></i></span>
                                                                <strong>Trim:</strong> {{ $inventory->trim_details ? $inventory->trim_details->name : 'N/A' }}
                                                            </li>
                                                            <li>
                                                                <span><i class="flaticon-time"></i></span>
                                                                <strong>Equipment:</strong> {{ $inventory->equipment_details ? $inventory->equipment_details->name : 'N/A' }}
                                                            </li>
                                                            <li>
                                                                <span><i class="flaticon-transport"></i></span>
                                                                <strong>Year:</strong> {{$inventory->year ?? 'N/A'}}
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 amenities-box">
                                                        <ul>
                                                            <li>
                                                                <span><i class="flaticon-speedometer"></i></span>
                                                                <strong>Country:</strong> {!! $inventory->country ? ''.\PragmaRX\Countries\Package\Countries::where('cca2',
                                                $inventory->country)->first()->name->common : 'N/A' !!}
                                                            </li>
                                                            <li>
                                                                <span><i class="flaticon-automatic-flash-symbol"></i></span>
                                                                <strong>City:</strong> {{ $inventory->city ? $inventory->city : 'N/A' }}
                                                            </li>

                                                            <li>
                                                                <span><i class="flaticon-settings"></i></span>
                                                                <strong>Transmission</strong> {{ $inventory->transmission_details ? $inventory->transmission_details->transmission : 'N/A' }}
                                                            </li>
                                                            <li>
                                                                <span><i class="flaticon-rim"></i></span>
                                                                <strong>Engine:</strong>{{ $inventory->engine ? $inventory->engine : 'N/A' }}
                                                            </li>

                                                            <li>
                                                                <span><i class="flaticon-paint-brush"></i></span>
                                                                <strong>Steering Wheel:</strong> {{ $inventory->steering_wheel ? $inventory->steering_wheel : 'N/A' }}
                                                            </li>
                                                            <li>
                                                                <span><i class="flaticon-paint-brush"></i></span>
                                                                <strong>Mileage:</strong>  {{ $inventory->mileage ? $inventory->mileage.($inventory->mileage_unit ? $inventory->mileage_unit : '') : 'N/A' }}
                                                            </li>
                                                            <li>
                                                                <span><i class="flaticon-placeholder"></i></span>
                                                                <strong>Fuel Type:</strong>{{ $inventory->fuel_type ? $inventory->fuel_type : 'N/A' }}
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 amenities-box">
                                                        <ul>
                                                            <li>
                                                                <span><i class="flaticon-car-door"></i></span>
                                                                <strong>Doors:</strong> {{ $inventory->number_of_doors ? $inventory->number_of_doors : 'N/A' }}
                                                            </li>
                                                            <li>
                                                                <span><i class="flaticon-plug"></i></span>
                                                                <strong>Seats:</strong> {{ $inventory->number_of_seatch ? $inventory->number_of_seatch : 'N/A' }}
                                                            </li>
                                                            <li>
                                                                <span><i class="flaticon-dollar-symbol"></i></span>
                                                                <strong>CO2-Emissions:</strong> Dollar {{ $inventory->number_of_doors ? $inventory->number_of_doors : 'N/A' }}
                                                            </li>
                                                            <li>
                                                                <span><i class="flaticon-factory-stock-house"></i></span>
                                                                <strong>Cylinder:</strong>{{ $inventory->cylinder ? $inventory->cylinder : 'N/A' }}
                                                            </li>
                                                            <li>
                                                                <span><i class="flaticon-time"></i></span>
                                                                <strong>Color:</strong> {{ $inventory->color ? $inventory->color : 'N/A' }}
                                                            </li>
                                                            
                                                        </ul>
                                                    </div>
                                                </div>
                                               
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tab2default">
                                            <div class="features-opions">
                                                <div class="row">
                                                   
                                                            @foreach ($inventory->other_details as $key => $specific)
                                                            @if($key % 15 == 0)
                                                            <div class="col-md-6 col-sm-6">
                                                            <ul>
                                                            @endif
                                                             <li>
                                                               <i class="fa fa-circle"></i>
                                                                <strong>{{ $specific->specific_details->name }}:</strong> {{ $specific->value }} {{ $specific->unit != 'NULL' ? $specific->unit : '' }}
                                                            </li>
                                                            @if($key % 15 == 14 || $key == $inventory->other_details->count() -1)
                                                            </ul>
                                                            </div>
                                                            @endif
                                                            @endforeach
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Panel box end -->
                        <!-- About end-->


                      
                    </div>
                    <!-- Content div end-->

                </div>
                <div class="col-ld-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="details-sidebar pad-div sidebar-widget">
                       

                        <!-- Help center start -->
                        <div class="helping-Center single-block">
                            <h2 class="title">About Dealer</h2>
                            <ul class="contact-link">
                                <li>
                                    <i class="fas fa-envelope fa-2x"></i>
                                    <a href="mailto:{{$inventory->user_details->email}}">
                                        {{$inventory->user_details->email}}
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Social list start -->
                        <div class="social-box clearfix single-block">
                            <h2 class="title">Share with Social</h2>
                            <ul class="social-list-2">
                                <li>
                                    <a href="#" class="facebook-bg">
                                        <i class="fab fa-facebook fa-2x"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="twitter-bg">
                                        <i class="fab fa-twitter fa-2x"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="google-bg">
                                        <i class="fab fa-google fa-2x"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="linkedin-bg">
                                        <i class="fab fa-linkedin fa-2x"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="pinterest-bg">
                                        <i class="fab fa-pinterest fa-2x"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        @if($inventory->location_details)
                        <!-- Google map location start -->
                        <div class="map_sidebar single-block">
                            <h2 class="title">Location</h2>
                            <div class="section">
                                <div class="map">
                                    <div id="map" class="contact-map"></div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Private message to dealer start -->
                        <div class="private-message-to-dealer">
                            <h2 class="title">Submit your enquiry.</h2>
                            <div class=" contact-form">
                                <form action="{{ route('inventory.lead_generate') }}" method="POST">
                                @csrf
                                <input type="hidden" name="inventory_id" value="{{ $inventory->id }}" />
                                    <div class="row">
                                        <div class="form-group  col-xs-12 col-sm-12 col-md-12">
                                            <label>Name:</label>
                                            <input type="text" name="name" class="input-text" placeholder="Enter name">
                                        </div>
                                        <div class="form-group  col-xs-12 col-sm-12 col-md-12">
                                            <label>Email:</label>
                                            <input type="text" name="email" class="input-text" placeholder="Enter Email">
                                        </div>

                                        <div class="form-group col-xs-12 col-sm-12 col-md-12">
                                            <label>Phone Number:</label>
                                                    <input type="tel" id="phone_number" name="phone_number" class="input-text"
                                                        placeholder="Enter Phone Number">
                                        </div>
                                         <div class="form-group col-xs-12 col-sm-12 col-md-12">
                                                    <label>Post code:</label>
                                                    <input type="text" name="postal_code" class="input-text"
                                                        placeholder="Enter Postal code">
                                        </div>
                                        <div class="form-group   col-xs-12 col-sm-12 col-md-12">
                                            <label>Your message:</label>
                                            <textarea rows="5" cols="5" name="message" class="input-text"
                                                placeholder="Enter your message here"></textarea>
                                        </div>

                                       
                                        <div class="form-group mb-0 col-xs-12 col-sm-12 col-md-12">
                                            <button type="submit" class="btn btn-submit btn-block">Send Message</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Car details body end-->

   

    <!-- Copy right start-->
    <div class="copy-right">
        <div class="container">
            <p>&copy; {{date('Y')}} Autobox365</p>
        </div>
    </div>
    <!-- Copy right end-->

    

   

    <script src="{{ asset('inventory_assets/js/jquery-2.2.0.min.js') }}"></script>
    <script src="{{ asset('inventory_assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('inventory_assets/js/bootstrap-slider.js') }}"></script>
    <script src="{{ asset('inventory_assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('inventory_assets/js/jquery.scrollUp.js') }}"></script>
    <script src="{{ asset('inventory_assets/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('inventory_assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('inventory_assets/js/bootstrap-submenu.js') }}"></script>

  
    <!-- Custom javascript -->
    <script src="{{ asset('inventory_assets/js/app.js') }}"></script>
   
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAP_API_KEY')}}"></script>
    @if($inventory->location_details)
    <script>
        function LoadMap(propertes) {
        var defaultLat = {{$inventory->location_details->latitude}};
        var defaultLng = {{$inventory->location_details->longitude}};
        var mapOptions = {
            center: new google.maps.LatLng(defaultLat, defaultLng),
            zoom: 15,
            scrollwheel: false,
            styles: [
                {
                    featureType: "administrative",
                    elementType: "labels",
                    stylers: [
                        {visibility: "off"}
                    ]
                },
                {
                    featureType: "water",
                    elementType: "labels",
                    stylers: [
                        {visibility: "off"}
                    ]
                },
                {
                    featureType: 'poi.business',
                    stylers: [{visibility: 'off'}]
                },
                {
                    featureType: 'transit',
                    elementType: 'labels.icon',
                    stylers: [{visibility: 'off'}]
                },
            ]
        };
        var map = new google.maps.Map(document.getElementById("map"), mapOptions);
        var infoWindow = new google.maps.InfoWindow();
        var myLatlng = new google.maps.LatLng( {{$inventory->location_details->latitude}},  {{$inventory->location_details->longitude}});

        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map
        });
        (function (marker) {
            google.maps.event.addListener(marker, "click", function (e) {
                infoWindow.setContent("" +
                    "<div class='map-properties contact-map-content'>" +
                    "<div class='map-content'>" +
                    "<p class='address'>{{$inventory->location_details->full_address}}</p>" +
                    "<ul class='map-properties-list'> " +
                    @if($inventory->location_details->phone_numbers->count())
                    @foreach($inventory->location_details->phone_numbers as $phone_number)
                    @if($phone_number->international_format)
                    "<li><i class='fa fa-phone'></i>{{$phone_number->international_format}}</li> " +
                    @endif()
                    @endforeach
                    @endif()
                    @if($inventory->location_details->email)
                   "<li><i class='fas fa-envelope'></i>{{$inventory->location_details->email}}</li> " +
                     @endif()
                    @if($inventory->location_details->website)
                    "<li><a href='{{$inventory->location_details->website}}'><i class='fa fa-globe'></i>{{$inventory->location_details->website}}</li></a> " +
                     @endif()
                    "</ul>" +
                    "</div>" +
                    "</div>");
                infoWindow.open(map, marker);
            });
        })(marker);
    }
    LoadMap();
    </script>
    @endif

</body>

</html>
