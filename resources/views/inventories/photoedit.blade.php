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
								<h5 class="card-title">Photo Upload Form</h5>
								<div class="header-elements">
									<div class="list-icons">
				                		<a class="list-icons-item" data-action="collapse"></a>
				                		<a class="list-icons-item" data-action="reload"></a>
				                		<a class="list-icons-item" data-action="remove"></a>
				                	</div>
			                	</div>
							</div>

							<div class="card-body">
                                {{-- <form id="" class="dropzone"></form> --}}
                                <form action="{{ route('inventories.photosave', $inventoryId) }}" method="POST" class="dropzone" id="inventory_photos_form">
                                    @csrf
                                </form>
                                <a href="{{ route('inventories.index') }}">Go to Inventories</a>
							</div>
						</div>
						<!-- /basic layout -->


    </div>

</div>
<!-- /main charts -->

@endsection


@section('scripts_after')
    <script src="{{ asset('global_assets/js/plugins/uploaders/dropzone.min.js') }}"></script>
    <script>
/* ------------------------------------------------------------------------------
 *
 *  # Dropzone multiple file uploader
 *
 *  Demo JS code for uploader_dropzone.html page
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var DropzoneUploader = function() {


    //
    // Setup module components
    //

    // Dropzone file uploader
    var _componentDropzone = function() {
        if (typeof Dropzone == 'undefined') {
            console.warn('Warning - dropzone.min.js is not loaded.');
            return;
        }





        // Removable thumbnails
        Dropzone.options.inventoryPhotosForm = {
            init: function() {
                thisDropzone = this;
                let photos = JSON.parse('{!! $photos !!}')
                photos.map((photo, index) =>{
                    var mockFile = { name: photo.file_name, size: photo.file_size, id: photo.id };

                    thisDropzone.options.addedfile.call(thisDropzone, mockFile);

                    thisDropzone.options.thumbnail.call(thisDropzone, mockFile,  photo.image_src);
                })
            },
            paramName: "photo", // The name that will be used to transfer the file
            dictDefaultMessage: 'Drop files to upload <span>or Click</span> to upload your inventory photos',
            maxFilesize: 1, // MB
            acceptedFiles: 'image/*',
            addRemoveLinks: true,
            removedfile: function(file) {
                console.log(file)
                // x = confirm('Do you want to delete?');
                // if(!x)  return false;
                if(file.id){
                    $.get('{{ route('inventories.removephoto') }}',{id: file.id})
                    return true
                    this.removeFile(file);
                }
            }
        };


    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _componentDropzone();
        }
    }
}();


// Initialize module
// ------------------------------

DropzoneUploader.init();
    </script>
@endsection
