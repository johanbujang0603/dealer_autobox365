@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.home') }} - Search Results
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y flex items-center border-b">
    
    <div class="-intro-x breadcrumb mr-auto hidden sm:flex p-5">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">Search Results</a> 
    </div>

</div>
@endsection

@section('page_content')

<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3 flex lg:block flex-col-reverse">
        <div class="intro-y box mt-5">
            <div class="relative flex items-center p-5">
                <h2>Search Results</h2>
            </div>
            <div class="nav-tabs custom-nav-tabs flex flex-col justify-center lg:justify-start p-5 border-t border-gray-200">
            
                <a href="javascript:;" data-toggle="tab" class="flex items-center active py-3" data-target="#inventories">
                    <i class="icon-user mr-2"></i>
                    Inventories
                    @if ($inventories->count() != 0)
                    <span class="badge bg-danger badge-pill ml-auto">{{$inventories->count()}}</span>
                    @endif
                </a>
                
                <a href="javascript:;" data-toggle="tab" class="flex items-center py-3" data-target="#leads">
                    <i class="icon-calendar3 mr-2"></i>
                    Leads
                    @if ($leads->count() != 0)
                    <span class="badge bg-danger badge-pill ml-auto">{{$leads->count()}}</span>
                    @endif
                </a>
                
                <a href="javascript:;" data-toggle="tab" class="flex items-center py-3" data-target="#customers">
                    <i class="icon-envelop2 mr-2"></i>
                    Customers
                    @if ($customers->count() != 0)
                    <span class="badge bg-danger badge-pill ml-auto">{{$customers->count()}}</span>
                    @endif
                </a>
                
                <a href="javascript:;" data-toggle="tab" class="flex items-center py-3" data-target="#locations">
                    <i class="icon-car mr-2"></i>
                    Locations
                    @if ($locations->count() != 0)
                    <span class="badge bg-danger badge-pill ml-auto">{{$locations->count()}}</span>
                    @endif
                </a>
                
                <a href="javascript:;" data-toggle="tab" class="flex items-center py-3" data-target="#users">
                    <i class="icon-file-empty2 mr-2"></i>
                    Users
                    @if ($users->count() != 0)
                    <span class="badge bg-danger badge-pill ml-auto">{{$users->count()}}</span>
                    @endif
                </a>

                <a href="javascript:;" data-toggle="tab" class="flex items-center py-3" data-target="#documents">
                    <i class="icon-file-empty2 mr-2"></i>
                    Documents
                    @if ($documents->count() != 0)
                    <span class="badge bg-danger badge-pill ml-auto">{{$documents->count()}}</span>
                    @endif
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-span-12 lg:col-span-9 xxl:col-span-9 tab-content mb-5">
        <div class="intro-y box mt-5 tab-content__pane active"  id="inventories">
            <table class="table">
                <thead>
                    <tr class="bg-gray-700 text-white">
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">Name</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">Price</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">status</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">Leads</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inventories as $inventory)
                    <tr>
                        <td class="border-b border-gray-600">
                        @php
                        $brand_logo = isset($inventory->make_details->name) ? asset('images/car_brand_logos/' . strtolower($inventory->make_details->name) . '.jpg') : asset('/global_assets/images/placeholders/placeholder.jpg');
                        $car_photo = isset($inventory->photo_details) && $inventory->photo_details->first() ?   $inventory->photo_details->first()->image_src  : asset('/global_assets/images/placeholders/placeholder.jpg');
                        $transmission = isset($inventory->transmission_details->transmission) ? $inventory->transmission_details->transmission : '';
                        $name = $inventory->inventory_name;
                        $generation = isset($inventory->generation_details->name) ? $inventory->generation_details->name : "";
                        $serie = isset($inventory->serie_details->name) ? $inventory->serie_details->name : "";
                        $trim = isset($inventory->trim_details->name) ? $inventory->trim_details->name : "";
                        $equipment = isset($inventory->equipment_details->name) ? $inventory->equipment_details->name : "";
                        if ($generation && $serie && $trim && $equipment) {
                            $version = "$generation  -  $serie  -  $trim  -  $equipment";
                        } else {
                            $version = "";
                        }
                        if (isset($inventory->location_details)) {
                            $location = $inventory->location_details->name;
                        } else {
                            $location = '';
                        }
                        @endphp
                        <div class="flex items-center">
                            <div class="mr-3 flex items-center flex-wrap">
                                <a href="#" class="mr-2">
                                    <img src="{{ $brand_logo }}" class="rounded-full" width="60" height="60" alt="">
                                </a>
                                <a href="javascript:;">
                                    <img src="{{$car_photo}}" class="car_photo" id="{{ $inventory->id }}"  width="100" height="60" alt=""  onclick="openGallery($inventory->id)">
                                </a>
                            </div>
                            <div>
                                <a href="#" class="text-theme-4 font-semibold">{{ $name != "" && $version != "" ? $name . "/" . $version : $name . $version }}</a>
                                <div class="text-theme-7 text-sm">
                                
                                    {{ $transmission != "" && $inventory->fuel_type && $inventory->mileage ? $transmission . "/" . $inventory->fuel_type . "/" . $inventory->mileage : "" }}

                                </div>
                                <div class="text-theme-7 text-sm">
                                
                                {{ $location != "" && $inventory->country && $inventory->city ? $location . "/" . $inventory->country . "-" . $inventory->city : "" }}

                                </div>
                            </div>
                        </div>
                        </td>
                        <td class="border-b border-gray-600"><h6 class="font-weight-semibold mb-0">{{ $inventory->price_with_currency }}</h6></td>
                        <td class="border-b border-gray-600">
                        @if (isset($inventory->status_details))
                            <span class="py-1 px-2 rounded-full text-xs text-white cursor-pointer font-medium bg-{{$inventory->status_details->color}}">
                                {{$inventory->status_details->status_name}}
                            </span>
                        @else
                            ---
                        @endif
                        </td>
                        <td class="border-b border-gray-600">{{ $inventory->leads_count }}</td>
                        <td class="border-b border-gray-600">
                        <div class="list-icons list-icons-extended">
                            <a href="{{ route('inventories.edit', $inventory->id) }}" class="list-icons-item" data-popup="tooltip" title data-trigger="hover" data-original-title="Edit">
                                <i class="icon-pencil"></i>
                            </a>
                            <a href="{{ route('inventories.view', $inventory->id) }}" class="list-icons-item" data-popup="tooltip" title data-trigger="hover" data-original-title="View">
                                <i class="icon-eye"></i>
                            </a>
                            <a href="javascript:;" onclick="confirmRemove('{{$car_photo}}', '{{$inventory->inventory_name}}', '{{$inventory->id}}', 'Inventory')" class="list-icons-item" data-popup="tooltip" title data-trigger="hover" data-original-title="Delete">
                                <i class="icon-bin"></i>
                            </a>

                        </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="intro-y box mt-5 tab-content__pane"  id="leads">
            <table class="table">
                <thead>
                    <tr class="bg-gray-700 text-white">
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap"></th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">{{ __('app.name') }}</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">{{ __('app.phone_numbers') }}</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">{{ __('app.email') }}</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leads as $lead)
                    <tr>
                        <td class="border-b border-gray-600"><img src='{{$lead->profile_image_src}}' width="60" height="60" alt="" class="img-preview rounded-circle" /></td>
                        <td class="border-b border-gray-600">{{ $lead->name }}</td>
                        <td class="border-b border-gray-600">
                        @foreach ($lead->phone_number_details as $phone)
                        {{ $phone->intl_formmated_number }}
                        <br />
                        @endforeach
                        {{ $lead->phone_nubmer_details }}
                        </td>
                        <td class="border-b border-gray-600">
                        @foreach ($lead->email_details as $email)
                        {{ $email->email }}
                        <br />
                        @endforeach
                        </td>
                        <td class="border-b border-gray-600">
                            <div class="flex items-center">
                                <a href="{{ route('leads.edit', $lead->id) }}" class="mr-2"><i class="icon-pencil"></i></a>
                                <a href="{{ route('leads.view', $lead->id) }}" class="mr-2"><i class="icon-eye"></i></a>
                                <a href="javascript:;" onclick="confirmRemove('{{$lead->profile_image_src}}', '{{$lead->name}}', '{{$lead->id}}', 'Lead')"><i class="icon-bin"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="intro-y box mt-5 tab-content__pane"  id="customers">
            <table class="table">
                <thead>
                    <tr class="bg-gray-700 text-white">
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap"></th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">{{ __('app.name') }}</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">{{ __('app.phone_numbers') }}</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">{{ __('app.email') }}</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                    <tr>
                        <td class="border-b border-gray-600"><img src='{{$customer->getProfileImageSrcAttribute()}}' width="60" height="60" alt="" class="img-preview rounded-circle" /></td>
                        <td class="border-b border-gray-600">{{ $customer->name }}</td>
                        <td class="border-b border-gray-600">
                        @foreach ($customer->phone_number_details as $phone)
                        {{ $phone->intl_formmated_number }}
                        <br />
                        @endforeach
                        {{ $customer->phone_nubmer_details }}
                        </td>
                        <td class="border-b border-gray-600">
                        @foreach ($customer->email_details as $email)
                        {{ $email->email }}
                        <br />
                        @endforeach
                        </td>
                        <td class="border-b border-gray-600">
                            <div class="flex items-center">
                                <a href="{{ route('customers.edit', $customer->id) }}" class="mr-2"><i class="icon-pencil"></i></a>
                                <a href="{{ route('customers.view', $customer->id) }}" class="mr-2"><i class="icon-eye"></i></a>
                                <a href="javascript:;" onclick="confirmRemove('{{$customer->getProfileImageSrcAttribute()}}', '{{$customer->name}}', '{{$customer->id}}', 'Customer')" class="dropdown-item"><i class="icon-bin"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="intro-y box mt-5 tab-content__pane"  id="locations">
            <table class="table">
                <thead>
                    <tr class="bg-gray-700 text-white">
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">{{ __('app.name') }}</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">Address</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">Phone</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">Email</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">Social Media</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">Website Address</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($locations as $location)
                    <tr>
                        <td class="border-b border-gray-600">
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <a href="#">
                                    <img src="{{$location->logo_url}}" class="rounded-circle" width="60" height="60" alt="">
                                </a>
                            </div>
                            <div>
                                <a href="#" class="text-default font-weight-semibold">{{ $location->name }}</a>
                                <div class="text-muted font-size-sm">
                                    {!! $location->description !!}
                                </div>
                            </div>
                        </div>
                        </td>
                        <td class="border-b border-gray-600">{{ $location->address }}</td>
                        <td class="border-b border-gray-600">
                            @foreach ($location->phone_numbers as $phone) 
                                {{ $phone->intl_formmated_number }}
                                <br />
                            @endforeach
                        </td>
                        <td class="border-b border-gray-600">{{ $location->email }}</td>
                        <td class="border-b border-gray-600">
                            @foreach ($location->social_medias as $media) 
                                {{ $media->social_url }}
                                <br />
                            @endforeach
                        </td>
                        <td class="border-b border-gray-600">
                            {{$location->website}}
                        </td>
                        <td class="border-b border-gray-600">
                            <div class="flex items-center">
                                <a href="{{ route('locations.edit', $location->id) }}" class="mr-2"><i class="icon-pencil"></i></a>
                                <a href="javascript:;" onclick="confirmRemove('{{$location->logo_url}}', '{{$location->name}}', '{{$location->id}}', 'Location')" class=""><i class="icon-bin"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="intro-y box mt-5 tab-content__pane"  id="users">
            <table class="table">
                <thead>
                    <tr class="bg-gray-700 text-white">
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap"></th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">{{ __('app.name') }}</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">{{ __('app.account_name') }}</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">{{ __('app.email') }}</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">{{ __('app.phone_numbers') }}</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td class="border-b border-gray-600">
                        <img class="rounded-circle" width="60" height="60" src="{{ $user->profile_image_src }}"/>
                        </td>
                        <td class="border-b border-gray-600">{{ $user->full_name }}</td>
                        <td class="border-b border-gray-600">
                            {{ $user->name }}
                        </td>
                        <td class="border-b border-gray-600">{{ $user->eemail }}</td>
                        <td class="border-b border-gray-600">
                            @foreach ($user->phone_number_details as $phone) 
                                {{ $phone->intl_formmated_number }}
                                <br />
                            @endforeach
                        </td>
                        <td class="border-b border-gray-600">
                            <div class="flex items-center">
                                <a href="{{ route('users.edit', $user->id) }}" class="mr-2"><i class="icon-pencil"></i></a>
                                <a href="javascript:;" onclick="confirmRemove('{{$user->profile_image_src}}', '{{$user->full_name}}', '{{$user->id}}', 'User')"><i class="icon-bin"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="intro-y box mt-5 tab-content__pane"  id="documents">
            <table class="table">
                <thead>
                    <tr class="bg-gray-700 text-white">
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">DateTime</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">File Name</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">Size</th>
                        <th class="border-b-2 border-gray-600 whitespace-no-wrap">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                
                    @foreach ($documents as $document)
                    <tr>
                        <td class="border-b border-gray-600">
                            {{ Carbon\Carbon::parse($document->created_at)->diffForHumans()}}
                        </td>
                        <td class="border-b border-gray-600">{{ $document->original_name }}</td>
                        <td class="border-b border-gray-600">
                            {{ $document->size ? formatSizeUnits($document->size) : "" }}
                        </td>
                        <td class="border-b border-gray-600">
                            <div class="flex items-center">
                                <a href="{{route('documents.download', $document->id) }}" class="mr-2"><i class="icon-download"></i></a>
                                <a target="_blank" href="/{{$document->upload_path }}" class="mr-2"><i class="icon-eye"></i></a>
                                <a href="javascript:;" onclick="confirmRemove('', '{{$document->original_name}}', '{{$document->id}}', 'Document')"><i class="icon-bin"></i></a>
                            </div>                
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts_after')
<script>
    function confirmRemove(avatar, name, id, type) {
        $.confirm({
            title: 'Are you sure you want to delete this listing?',
            content: '<img class="rounded-full m-auto" width="60" height="60" src="' + avatar + '" alt=""> <h4>' + name + '</h4>',
            theme: 'supervan',
            buttons: {
                confirm: function () {
                    if (type == 'Lead')
                        location.href = '/leads/delete/' + id;
                    if (type == 'Inventory')
                        location.href = '/inventories/delete/' + id;
                    if (type == 'Customer')
                        location.href = '/customers/delete/' + id;
                    if (type == 'Document')
                        location.href = '/documents/delete/' + id;
                    if (type == 'User')
                        location.href = '/users/delete/' + id;
                    if (type == 'Location')
                        location.href = '/locations/delete/' + id;
                },
                cancel: function () {
                },
            }
        });
    }
</script>
@endsection
