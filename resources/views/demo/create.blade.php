@extends('layouts.app')

@section('page_header')

    @include('layouts.common.page_header', ['pageTitle' =>  __('menu.inventories.create') ] )
@endsection


@section('page_content')

<!-- Main charts -->
<div class="row">
    <div class="col-xl-12">

            <!-- Basic layout-->
						<div class="card">
							<div class="card-header header-elements-inline">
								<h5 class="card-title">Basic layout</h5>
								<div class="header-elements">
									<div class="list-icons">
				                		<a class="list-icons-item" data-action="collapse"></a>
				                		<a class="list-icons-item" data-action="reload"></a>
				                		<a class="list-icons-item" data-action="remove"></a>
				                	</div>
			                	</div>
							</div>

							<div class="card-body">
								<form action="#">
									<div class="form-group">
										<label>Name:</label>
										<input type="text" class="form-control" placeholder="Eugene Kopyov">
									</div>

									<div class="form-group">
										<label>Password:</label>
										<input type="password" class="form-control" placeholder="Your strong password">
									</div>

									<div class="form-group">
										<label>Your state:</label>
										<select class="form-control form-control-select2" data-fouc>
											<optgroup label="Alaskan/Hawaiian Time Zone">
												<option value="AK">Alaska</option>
												<option value="HI">Hawaii</option>
											</optgroup>
											<optgroup label="Pacific Time Zone">
												<option value="CA">California</option>
												<option value="NV">Nevada</option>
												<option value="WA">Washington</option>
											</optgroup>
											<optgroup label="Mountain Time Zone">
												<option value="AZ">Arizona</option>
												<option value="CO">Colorado</option>
												<option value="WY">Wyoming</option>
											</optgroup>
											<optgroup label="Central Time Zone">
												<option value="AL">Alabama</option>
												<option value="AR">Arkansas</option>
												<option value="KY">Kentucky</option>
											</optgroup>
											<optgroup label="Eastern Time Zone">
												<option value="CT">Connecticut</option>
												<option value="DE">Delaware</option>
												<option value="FL">Florida</option>
											</optgroup>
										</select>
									</div>

									<div class="form-group">
										<label class="d-block">Gender:</label>

										<div class="form-check form-check-inline">
											<label class="form-check-label">
												<input type="radio" class="form-input-styled" name="gender" checked data-fouc>
												Male
											</label>
										</div>

										<div class="form-check form-check-inline">
											<label class="form-check-label">
												<input type="radio" class="form-input-styled" name="gender" data-fouc>
												Female
											</label>
										</div>
									</div>

									<div class="form-group">
										<label>Your avatar:</label>
										<input type="file" class="form-input-styled" data-fouc>
										<span class="form-text text-muted">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
									</div>

									<div class="form-group">
										<label>Tags:</label>
										<select multiple="multiple" data-placeholder="Enter tags" class="form-control form-control-select2-icons" data-fouc>
											<optgroup label="Services">
												<option value="wordpress2" data-icon="wordpress2">Wordpress</option>
												<option value="tumblr2" data-icon="tumblr2">Tumblr</option>
												<option value="stumbleupon" data-icon="stumbleupon">Stumble upon</option>
												<option value="pinterest2" data-icon="pinterest2">Pinterest</option>
												<option value="lastfm2" data-icon="lastfm2">Lastfm</option>
											</optgroup>
											<optgroup label="File types">
												<option value="pdf" data-icon="file-pdf">PDF</option>
												<option value="word" data-icon="file-word">Word</option>
												<option value="excel" data-icon="file-excel">Excel</option>
												<option value="openoffice" data-icon="file-openoffice">Open office</option>
											</optgroup>
											<optgroup label="Browsers">
												<option value="chrome" data-icon="chrome" selected>Chrome</option>
												<option value="firefox" data-icon="firefox" selected>Firefox</option>
												<option value="safari" data-icon="safari">Safari</option>
												<option value="opera" data-icon="opera">Opera</option>
												<option value="IE" data-icon="IE">IE</option>
											</optgroup>
										</select>
									</div>

									<div class="form-group">
										<label>Your message:</label>
										<textarea rows="5" cols="5" class="form-control" placeholder="Enter your message here"></textarea>
									</div>

									<div class="text-right">
										<button type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
									</div>
								</form>
							</div>
						</div>
						<!-- /basic layout -->


    </div>

</div>
<!-- /main charts -->

@endsection


@section('scripts')
    <script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script>
        /* ------------------------------------------------------------------------------
        *
        *  # Form layouts
        *
        *  Demo JS code for form layouts pages
        *
        * ---------------------------------------------------------------------------- */


        // Setup module
        // ------------------------------

        var FormLayouts = function() {


            //
            // Setup module components
            //

            // Select2
            var _componentSelect2 = function() {
                if (!$().select2) {
                    console.warn('Warning - select2.min.js is not loaded.');
                    return;
                };

                // Basic example
                $('.form-control-select2').select2();


                //
                // Select with icons
                //

                // Format icon
                function iconFormat(icon) {
                    var originalOption = icon.element;
                    if (!icon.id) { return icon.text; }
                    var $icon = "<i class='icon-" + $(icon.element).data('icon') + "'></i>" + icon.text;

                    return $icon;
                }

                // Initialize with options
                $('.form-control-select2-icons').select2({
                    templateResult: iconFormat,
                    minimumResultsForSearch: Infinity,
                    templateSelection: iconFormat,
                    escapeMarkup: function(m) { return m; }
                });
            };

            // Uniform
            var _componentUniform = function() {
                if (!$().uniform) {
                    console.warn('Warning - uniform.min.js is not loaded.');
                    return;
                }

                // Initialize
                $('.form-input-styled').uniform({
                    fileButtonClass: 'action btn bg-pink-400'
                });
            };


            //
            // Return objects assigned to module
            //

            return {
                init: function() {
                    _componentSelect2();
                    _componentUniform();
                }
            }
        }();


        // Initialize module
        // ------------------------------

        document.addEventListener('DOMContentLoaded', function() {
            FormLayouts.init();
        });
    </script>
@endsection
