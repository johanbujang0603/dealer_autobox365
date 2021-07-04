

@if ($message = Session::get('success'))

<div class="rounded-md flex items-center px-5 py-4 mb-2 border border-theme-9 text-theme-9">
  <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i> 
  {{$message}} <i data-feather="x" class="w-4 h-4 ml-auto"></i> 
</div>

@endif


@if ($message = Session::get('error'))

<div class="rounded-md flex items-center px-5 py-4 mb-2 border border-theme-6 text-theme-6">
  <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i> 
  {{$message}} <i data-feather="x" class="w-4 h-4 ml-auto"></i> 
</div>

@endif


@if ($message = Session::get('warning'))

<div class="rounded-md flex items-center px-5 py-4 mb-2 border text-gray-700">
  <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i> 
  {{$message}} <i data-feather="x" class="w-4 h-4 ml-auto"></i> 
</div>

@endif

@if ($message = Session::get('info'))

<div class="rounded-md flex items-center px-5 py-4 mb-2 border text-gray-700">
  <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i> 
  {{$message}} <i data-feather="x" class="w-4 h-4 ml-auto"></i> 
</div>

@endif


@if ($errors->any())

<div class="rounded-md flex items-center px-5 py-4 mb-2 border border-theme-6 text-theme-6">
  <i data-feather="alert-circle" class="w-6 h-6 mr-2"></i> 
  {{$message}} <i data-feather="x" class="w-4 h-4 ml-auto"></i> 
</div>

@endif
