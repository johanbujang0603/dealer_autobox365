@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.home') }} - {{ $lead->name }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y flex items-center border-b">
    <div class="-intro-x breadcrumb mr-auto hidden sm:flex p-5">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb">{{ __('menu.leads.index')}}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ $lead->name }}</a> 
    </div>
</div>

@endsection

@section('page_content')
<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3 flex lg:block flex-col-reverse">
        <div class="intro-y box mt-5">
            <div class="relative flex items-center p-5">
                <div class="w-12 h-12 image-fit">
                    <img alt="Midone Tailwind HTML Admin Template" class="rounded-full" src="{{ asset($lead->profile_image_src) }}">
                </div>
                <div class="ml-4 mr-auto">
                    <div class="font-medium text-base">{{ $lead->name }}</div>
                    <div class="text-gray-600">{{ isset(\PragmaRX\Countries\Package\Countries::where('cca2',$lead->country_base_residence)->first()->name->official) ? \PragmaRX\Countries\Package\Countries::where('cca2',$lead->country_base_residence)->first()->name->official : '' }}</div>
                </div>
            </div>
            <div class="nav-tabs custom-nav-tabs flex flex-col justify-center lg:justify-start p-5 border-t border-gray-200">
                <a href="javascript:;" data-target="#profile" data-toggle="tab" class="flex items-center active py-3">
                    <i class="icon-user w-5 h-5 mr-2"></i>
                    {{__('app.profile')}}
                </a>
                <a href="javascript:;" data-target="#timeline" data-toggle="tab" class="py-3 flex items-center">
                    <i class="icon-calendar3 w-5 h-5 mr-2"></i>
                    {{__('app.timeline')}}
                </a>
                <a href="javascript:;" data-target="#inbox" data-toggle="tab" class="py-3 flex items-center">
                    <i class="icon-envelop2 w-5 h-5 mr-2"></i>
                    {{__('lead.enquiries')}}
                    {{--  <span class="badge bg-danger badge-pill ml-auto">29</span>  --}}
                </a>
                <a data-target="#interests" href="javascript:;" data-toggle="tab" class="py-3 flex items-center">
                    <i class="icon-car w-5 h-5 mr-2"></i>
                    {{__('app.interest')}}
                </a>
                <a data-target="#documents" href="javascript:;" data-toggle="tab" class="py-3 flex items-center">
                    <i class="icon-file-empty2 w-5 h-5 mr-2"></i>
                    {{ __('app.documents') }}
                </a>
                <a data-target="#calendar" data-toggle="tab" href="javascript:;" id="calendar_tab" class="py-3 flex items-center">
                    <i data-feather="tablet" class="w-5 h-5 mr-2"></i>
                    {{__('app.reminder')}}
                </a>
                <a data-target="#notes" data-toggle="tab" href="javascript:;" class="py-3 flex items-center">
                    <i data-feather="tablet" class="w-5 h-5 mr-2"></i>
                    {{__('app.notes')}}
                </a>
                <a data-toggle="modal" data-target="#lead_convert_modal" data-toggle="tab"
                    class="py-3 flex items-center cursor-pointer">
                    <i class="icon-credit-card2 w-5 h-5 mr-2"></i>
                    {{__('lead.convert_as_customter')}}
                </a>
                <a data-target="#other" data-toggle="tab" href="javascript:;" class="py-3 flex items-center">
                    <i class="icon-credit-card2 w-5 h-5 mr-2"></i>
                    {{__('lead.other_info')}}
                </a>
                <a data-target="#assign" data-toggle="tab" href="javascript:;" class="py-3 flex items-center">
                    <i class="icon-credit-card2 w-5 h-5 mr-2"></i>
                    {{__('app.assign')}}
                </a>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-6 xxl:col-span-6 tab-content">
        <div class="intro-y box mt-5 tab-content__pane active"  id="profile">
            <div class="flex items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                {{ $lead->name }}
                </h2>
                <a class="text-theme-1" href="{{ route('leads.edit', $lead->id) }}">
                    <i class="mi-mode-edit"></i>{{ __('app.edit') }}
                </a>
            </div>
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <span class="font-weight-semibold">{{__("app.civility")}}:</span>
                    <h6 class="font-medium">{{ $lead->civility }}</h6>
                </div>
                <div class="flex items-center justify-between mt-5">
                    <span class="font-weight-semibold">{{__("app.full_name")}}:</span>
                    <h6 class="font-medium">{{ $lead->name }}</h6>
                </div>
                <div class="flex items-center justify-between mt-5">
                    <span class="font-weight-semibold">{{__("app.gender")}}:</span>
                    <h6 class="font-medium">{{ $lead->gender }}</h6>
                </div>
                <div class="flex items-center justify-between mt-5">
                    <span class="font-weight-semibold">{{__("app.country_of_residence")}}:</span>
                    <h6 class="font-medium">
                        {{ isset(\PragmaRX\Countries\Package\Countries::where('cca2',$lead->country_base_residence)->first()->name->official) ? \PragmaRX\Countries\Package\Countries::where('cca2',$lead->country_base_residence)->first()->name->official : '' }}
                    </h6>
                </div>
                <div class="flex items-center justify-between mt-5">
                    <span class="font-weight-semibold">{{__("app.tags")}}:</span>
                    <div>
                    @foreach (explode(',', $lead->tags) as $tag)
                    <h5 class="font-medium ml-1">
                        <span
                            class="py-1 px-2 rounded-full text-xs text-white font-medium bg-{{ isset(\App\Models\LeadTag::find($tag)->color) ? \App\Models\LeadTag::find($tag)->color : "" }}">{{ isset(\App\Models\LeadTag::find($tag)->tag_name) ? \App\Models\LeadTag::find($tag)->tag_name : '' }}</span>
                    </h5>
                    @endforeach
                    </div>
                </div>
                <div class="flex items-center justify-between mt-5">
                    <span class="font-weight-semibold">Phone Numbers:</span>
                    <div>
                        @foreach ($lead->phone_number_details as $key => $phone_number)
                            <div class="flex items-center justify-center">
                                <img src="https://www.countryflags.io/{{ $phone_number->country_code }}/shiny/24.png">{{ $phone_number->mobile_no }}
                            </div>
                            <span class="text-muted">{{ $phone_number->location }}({{ $phone_number->carrier }})</span>
                        @endforeach
                        <div class="ml-auto">
                            @foreach (explode(",",$phone_number->messaging_apps) as
                            $messaging_app)
                            @if ($messaging_app == 'WhatsApp')
                            <a href="javascript:;">

                                <i class="fab fa-whatsapp-square fa-3x text-success"></i>
                            </a>
                            @elseif ($messaging_app == 'Viber')
                            <a href="javascript:;">

                                <i class="fab fa-viber fa-3x text-violet"></i>
                            </a>
                            @elseif ($messaging_app == 'Telegram')
                            <a href="javascript:;">

                                <i class="fab fa-telegram fa-3x text-primary"></i>
                            </a>
                            @elseif ($messaging_app == 'Line')
                            <a href="javascript:;">

                                <i class="fab fa-line fa-3x text-success"></i>
                            </a>
                            @elseif ($messaging_app == 'Weixin')
                            <a href="javascript:;">

                                <i class="fab fa-weixin fa-3x text-success"></i>
                            </a>
                            @endif

                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-5">
                    <span class="font-weight-semibold">Emails:</span>
                    <div>
                    @if ($email_cnt != 0)
                        @foreach ($lead->email_details as $key => $email)
                            <div>{{ $email->email }}</div>
                        @endforeach
                    @else
                        <!-- <button class="button border relative flex items-center text-gray-700 hidden sm:flex add-email">
                        {{__('app.add_email')}}
                        </button> -->
                    @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="intro-y box mt-5 tab-content__pane" id="timeline">
            <div class="flex items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                {{__('app.timeline')}}
                </h2>
            </div>
            <div class="report-timeline mt-5 relative p-5">
                @foreach ($logs as $log)
                @php
                if($log->action == 'created'){
                $color = 'theme-1';
                $iconClass = 'icon-plus3';
                }
                else if ($log->action == 'updated'){
                $color = 'theme-9';
                $iconClass = 'icon-pencil';
                }
                else if ($log->action == 'deleted'){
                $color = 'theme-6';
                $iconClass = 'icon-trash';
                }
                else if ($log->action == 'converted'){
                $color = 'theme-12';
                $iconClass = 'icon-refresh';
                }
                else{
                $color = 'theme-12';
                $iconClass = "icon-pencil";
                }
                @endphp
                <!-- Sales stats -->
                <div class="intro-x relative flex items-center mb-3">
                    <div class="report-timeline__image">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full overflow-hidden bg-{{ $color }} text-white">
                            <i class="{{ $iconClass }}"></i>
                        </div>
                    </div>

                    <div class="box px-5 py-3 ml-10 flex-1 zoom-in">
                        <div class="flex items-center">
                            <h6 class="font-medium">{{ \ucfirst($log->user_details->full_name) }}</a>
                                {{ $log->action }} this
                                lead{{ $log->action == 'converted' ? ' to customer' : '' }}.</h6>
                        </div>
                        <div class="text-gray-600 mt-1">
                            <span>
                                <i class="icon-history mr-2 text-theme-1"></i>
                                {{ Carbon\Carbon::parse($log->created_at)->diffForHumans()}} ago
                            </span>
                        </div>
                    </div>
                </div>
                <!-- /sales stats -->
                @endforeach
            </div>
        </div>
        <div class="intro-y box mt-5 tab-content__pane" id="inbox">
            <div class="flex items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                    {{__('lead.enquiries')}}
                </h2>
            </div>
            <div class="p-5">
                <table class="table">
                    <tbody data-link="row">
                        <tr>

                            <td>
                                <img src="https://www.cargebeya.com/assets/cargebeya_logo-2721c749c9a8b608869be2dc80e43c81fa7bd4541c18edf43bd7b9dfcb60c5f0.png"
                                    class="rounded-circle" width="100%" alt="">
                            </td>

                            <td>
                                <div class="font-medium">https://www.cargebeya.com
                                </div>
                                <span class="font-normal text-gray-600 text-xs">Hello, I am interested in
                                    your
                                    Hyundai Avante car in Ethiopia - Addis–Ababa that I saw on
                                    CarGebeya.com.
                                    Can you please
                                    send me more details about it? Thanks.</span>
                            </td>
                            <td>
                                <i class="icon-attachment text-muted"></i>
                            </td>
                            <td>
                                11:09 pm
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="https://static.jiji.com.gh/static/img/logos/ghana.svg"
                                    class="rounded-circle" width="100%" alt="">
                            </td>

                            <td>
                                <div class="font-medium">
                                    https://jiji.com.gh/
                                </div>
                                <span class="text-gray-600 text-xs font-normal">Hello, I am interested in
                                    your
                                    Hyundai Avante car in Ethiopia - Addis–Ababa that I saw on
                                    CarGebeya.com.
                                    Can you please
                                    send me more details about it? Thanks.</span>
                            </td>
                            <td>
                                <i class="icon-attachment text-muted"></i>
                            </td>
                            <td>
                                11:09 pm
                            </td>
                        </tr>


                    </tbody>
                </table>
            </div>
        </div>
        <div class="intro-y box mt-5 tab-content__pane" id="interests">
            <div class="flex items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                    {{__('app.interest')}}
                </h2>
                <a class="button w-40 mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" href="javascript:;" data-toggle="modal" data-target="#add_interests_modal">
                    <i data-feather="plus-circle" class="mr-2"></i>
                    {{__('app.add_interest')}}
                </a>
            </div>
            <div class="p-5">
                <ul class="media-list" id="interests_list"
                    data-url="{{ route('leads.interests.load', $lead->id) }}">
                </ul>
            </div>
        </div>
        <div class="intro-y box mt-5 tab-content__pane" id="documents">
            <div class="flex items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                    {{ __('app.documents') }}
                </h2>
                <a class="button w-40 mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" href="javascript:;" data-toggle="modal" data-target="#add_documents_modal">
                    <i data-feather="plus-circle" class="mr-2"></i>
                    Add {{ __('app.documents') }}
                </a>
            </div>
            <div class="p-5">
                <ul id="document_list">
                </ul>
            </div>
        </div>
        <div class="intro-y box mt-5 tab-content__pane" id="notes">
            <div class="flex items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                   {{__('app.notes')}}
                </h2>
            </div>
            <div class="p-5">
                
                <div class="mb-4" id="notes_list">
                </div>
                <div class="flex items-center">
                    <img src="{{ Auth::user()->profile_image_src }}" class="rounded-circle mr-2" width="36" height="36" alt="">
                    <textarea class="input border w-full summernote" id="note_edit"></textarea>
                </div>
                <div class="mt-5 flex justify-end">
                    <button type="button" class="button w-40 mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" id="btn_add_note">
                        <i class="icon-plus22 mr-1"></i>
                        {{__('app.add_note')}}
                    </button>
                </div>
            </div>
        </div>
        <div class="intro-y box mt-5 tab-content__pane" id="calendar">
            <div class="flex items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                    {{__('app.reminder')}}
                </h2>
            </div>
            <div class="p-5">
                <div class="fullcalendar-basic"></div>
            </div>
        </div>
        <div class="intro-y box mt-5 tab-content__pane" id="other">
            <div class="flex items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                    {{__('app.other_info')}}
                </h2>
            </div>
            <div class="p-5 overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="border-b-2 whitespace-no-wrap">{{__('app.phone_numbers')}}</th>
                            <th class="border-b-2 whitespace-no-wrap">
                                <h4>
                                    {{__('app.valid')}}
                                </h4>
                            </th>
                            <th class="border-b-2 whitespace-no-wrap">
                                <h4>
                                    {{__('app.carrier')}}
                                </h4>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lead->phone_number_details as $phone_number)
                        <tr>
                            <td class="border-b">{{ $phone_number->mobile_no }}</td>
                            <td class="border-b">
                                @if ($phone_number->valid)
                                <i class="icon-checkbox-checked2 text-theme-1"></i>
                                @else
                                <i class="icon-cancel-square2 text-theme-6"></i>
                                @endif
                            </td>
                            <td class="border-b">{{ $phone_number->carrier }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <ul class="list-group list-group-flush">
                    <li class="flex justify-between mt-4">
                        <span class="font-medium">{{__('app.country')}}:</span>
                        <h6>{{ $lead->country_base_residence }}</h6>
                    </li>
                    <li class="flex justify-between mt-4">
                        <span class="font-medium">{{__('app.city')}}:</span>
                        <h6>{{ $lead->city }}</h6>
                    </li>
                    <li class="flex justify-between mt-4">
                        <span class="font-medium">{{__('app.address')}}:</span>
                        <h6>{{ $lead->address }}</h6>
                    </li>
                    <li class="flex justify-between mt-4">
                        <span class="font-medium">{{__('app.postal_code')}}:</span>
                        <h6>{{ $lead->postal_code }}</h6>
                    </li>
                </ul>
            </div>
        </div>
        <div class="intro-y box mt-5 tab-content__pane" id="assign">
            <div class="flex items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                    {{__('app.assign')}}
                </h2>
            </div>
            <div class="p-5">
                <form action="{{ route('leads.assign', $lead->id) }}" method="post">                
                    @csrf
                    <div class="preview">
                        <label>{{__('app.users')}}</label>
                        <select class="select2 w-full" name='users[]' data-placeholder="Choose Users" multiple>
                            <option></option>
                            @foreach($users as $user)
                                @if (strpos($lead->assign_users, $user->id) !== false)
                                <option value="{{$user->id}}" selected>{{$user->name}}</option>
                                @else
                                <option value="{{$user->id}}">{{$user->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="preview">
                        <label>{{__('app.location')}}</label>
                        <select class="select2 w-full" name='locations[]' data-placeholder="Choose Location" multiple>
                            <option></option>
                            @foreach($locations as $location)
                                @if (strpos($lead->assign_locations, $location->id) !== false)
                                <option value="{{$location->id}}" selected>{{$location->name}}</option>
                                @else
                                <option value="{{$location->id}}">{{$location->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-5">
                        <button type="submit" class="button w-40 mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white">{{__('app.submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3">
        <div class="intro-y box mt-5">
                <div class="border-b p-5">
                    <h6 class="card-title">{{__('lead.looking_to')}}</h6>
                </div>
                <div class="text-center p-5">
                    <div class="intro-y mt-5">
                        <h5 class="text-theme-4">{{ $lead->looking_to }}</h5>
                    </div>
                    <div class="flex items-center justify-between intro-y mt-5">
                        <div class="mb-3">
                            <h5 class="font-medium">
                                {{ number_format($lead->looking_to_price_from) }} {{ isset($lead->currency_details->symbol) ? $lead->currency_details->symbol : null }}</h5>
                            <span class="text-theme-7 text-sm">{{__('inventory.min_price')}}</span>
                        </div>
                        <div class="mb-3">
                            <h5 class="font-medium">
                                {{ number_format($lead->looking_to_price_to) }} {{ isset($lead->currency_details->symbol) ? $lead->currency_details->symbol : null }}</h5>
                            <span class="text-theme-7 text-sm">{{__('inventory.max_price')}}</span>
                        </div>

                    </div>
                </div>

        </div>
    </div>
</div>
<script type="text/json" id="json_details">
    {!! $json_data !!}
</script>


<div id="add_documents_modal" class="modal">
    <div class="modal__content modal__content--xl px-5 py-10">
        <div class="text-center mb-3">
            <h5>{{__('app.attach')}} {{ __('app.documents') }}</h5>
        </div>
        
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
                <div class="preview flex items-center">
                    <span class="mr-2"><i class="icon-car"></i></span>
                    <select class="select2 w-full" name='inventories' data-placeholder="Choose Inventory" multiple>
                        <option></option>
                        @foreach($inventories as $inventory)
                            <option value="{{$inventory->id}}">{{$inventory->inventory_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
                <div class="preview flex items-center">
                    <span class="mr-2"><i class="icon-check"></i></span>
                    <select class="select2 w-full" name='customers' data-placeholder="Choose Customer" multiple>
                        <option></option>
                        @foreach($customers as $customer)
                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
                <div class="preview flex items-center">
                    <span class="mr-2"><i class="icon-users"></i></span>

                    <select class="select2 w-full" name='leads' data-placeholder="Choose Lead" multiple>
                        <option></option>
                        @foreach($leads as $lead)
                            @if ($lead->id == $lead_id)
                                <option selected value="{{$lead->id}}">{{$lead->name}}</option>
                            @else
                                <option value="{{$lead->id}}">{{$lead->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
                <div class="preview flex items-center">
                    <span class="mr-2"><i class="icon-pin-alt"></i></span>
                    <select class="select2 w-full" name='locations' data-placeholder="Choose Location" multiple>
                        <option></option>
                        @foreach($locations as $location)
                            <option value="{{$location->id}}">{{$location->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
                <div class="flex preview items-center">
                    <span class="mr-2"><i class="icon-pin-alt"></i></span>
                    <select class="select2 w-full" name='users' data-placeholder="Choose User" multiple>
                        <option></option>
                        @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->full_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6 xxl:col-span-6">
                <div class="flex preview items-center">
                    <span class="mr-2"><i class="icon-car"></i></span>
                    <textarea class="input border w-full doc-description" placeholder="Add Description"></textarea>
                </div>
            </div>
        </div>

        <div class="preview mt-5">
            <form action="{{ route('leads.documents.upload', $lead->id) }}" id="upload-documents-modal" class="dropzone border-gray-200 border-dashed">
                @csrf
                <input type="hidden" value="{{ route('leads.documents.load', $lead->id) }}" id="upload-documents-modal-url"/>
                <div class="fallback">
                    <input name="file" type="file" multiple/>
                </div>
                <div class="dz-message" data-dz-message>
                    <div class="text-lg font-medium">Drop files here or click to upload.</div>
                    <div class="text-gray-600"> This is just a demo dropzone. Selected files are <span class="font-medium">not</span> actually uploaded. </div>
                </div>
            </form>
        </div>
        <div class="flex items-center flex-wrap mt-5">
            <button type="button" class="button bg-theme-6 text-white mr-5" data-dismiss="modal">{{__('app.close')}}</button>
            <button type="button" class="button bg-theme-4 text-white" id="upload-doc">{{__('app.save_changes')}}</button>
        </div>
    </div>
</div>

<div id="add_interests_modal" class="modal" tabindex="-1">
    <div class="modal__content modal__content--xl px-5 py-10" id="add_interests_form">
    </div>
</div>

<!-- Lead convert modal -->
<div id="lead_convert_modal" class="modal" tabindex="-1">
</div>
<!-- /Lead convert modal -->

<!-- Email Add modal -->
<!-- <div id="email-add-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('app.add_email')}}</h5><button type="button" class="close"
                                                                    data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">{{__('app.email')}}: </label>
                    <input type="email" class="form-control" id="email-field" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link legitRipple btn-add-email">{{__('app.add')}}</button>
                <button type="button" class="btn btn-link legitRipple" data-dismiss="modal">{{__('app.close')}}</button>
            </div>
        </div>
    </div>
</div> -->
<!-- /Email Add modal -->

<!-- Interest Detail modal -->
<div id="interest_detail_modal" class="modal" tabindex="-1">
    <div class="modal__content modal__content--xl px-5 py-10" id="add_interests_form">
        <div class="text-center">
            <h2 class="font-medium text-lg">{{__('lead.interest_detail')}}</h2>
        </div>
        <div class="mt-5 p-5 modal-body"></div>
        <div class="mt-5">
            <button type="button" class="button bg-theme-6 text-white" data-dismiss="modal">Close</button>
        </div>
    </div>
    </div>
</div>
<!-- /Interest Detail modal -->

<!-- Event Detail modal -->
<!-- <div id="event_detail_modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('app.reminder')}}</h5><button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Select Users</label>
                    <select multiple="multiple" class="form-control select"
                        data-placeholder='Select users who will get notification.'>
                        <option value="">Choose users</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>{{__("app.note")}}</label>
                    <textarea class="form-control" placeholder="Enter your notes."></textarea>
                </div>

            </div>
            <div class="modal-footer"><button type="button" class="btn btn-link legitRipple"
                    data-dismiss="modal">{{__("app.close")}}</button>
            </div>
        </div>
    </div>
</div> -->
<!-- /Event Detail modal -->
@endsection

@section('scripts_after')
<script src="{{ asset('js/add_interests_form.js') }}"></script>
<script src="{{ asset('js/lead_convert_form.js?leadId='.$lead->id) }}"></script>
<script>
    $(document).ready(function(){
            var lead_id = "{{$lead_id}}";
            var is_loading = 0;
            $("#document_list").load('{{ route('leads.documents.load', $lead->id) }}');
            $("#notes_list").load('{{ route('leads.notes.load', $lead->id) }}');
            $("#interests_list").load($("#interests_list").attr('data-url'));
            
            $("#btn_add_note").click(function(){
                $.post('{{ route('leads.notes.add', $lead->id) }}', {"_token": "{{ csrf_token() }}", "data": $("#note_edit").val()}, function(result) {
                    $("#notes_list").load('{{ route('leads.notes.load', $lead->id) }}')
                    $("#note_edit").summernote("reset");
                })
            });

            // $(".add-email").on("click", function() {
            //     $("#email-add-modal").modal();
            // });

            // $(".btn-add-email").on("click", function() {
            //     if (is_loading == 1) return;
            //     var email = $("#email-field").val();
            //     var email_verify_api_key = 'pnP4WnQjPKE55cMulbHI9';
            //     is_loading = 1;
            //     document.body.style.cursor='wait';
            //     $.ajax({
            //         type: 'post',
            //         url: "/leads/verify_email",
            //         data: {email: email},
            //         success: function (result)
            //         {
            //             is_loading = 0;
            //             document.body.style.cursor='default';
            //             if (result == 'ok') {
            //                 location.href = '/leads/add_email?lead_id=' + lead_id + '&email=' + email + '&valid=true';
            //             }
            //             else {

            //                 var notice = new PNotify({
            //                     title: 'Confirmation',
            //                     text: '<p>This email is invalid. Do you want to edit or continue?</p>',
            //                     hide: false,
            //                     type: 'warning',
            //                     width: "100%",
            //                     cornerclass: "rounded-0",
            //                     addclass: "stack-custom-top bg-danger border-danger",
            //                     stack: {"dir1": "right", "dir2": "down", "push": "top", "spacing1": 1},
            //                     confirm: {
            //                         confirm: true,
            //                         buttons: [
            //                             {
            //                                 text: 'Continue',
            //                                 addClass: 'btn btn-sm btn-danger'
            //                             },
            //                             {
            //                                 text: 'Cancel',
            //                                 addClass: 'btn btn-sm btn-info'
            //                             }
            //                         ]
            //                     },
            //                     buttons: {
            //                         closer: false,
            //                         sticker: false
            //                     }
            //                 })

            //                 // On confirm
            //                 notice.get().on('pnotify.confirm', function() {
            //                     location.href = '/leads/add_email?lead_id=' + lead_id + '&email=' + email + '&valid=false';
            //                 })

            //                 // On cancel
            //                 notice.get().on('pnotify.cancel', function() {
            //                     $("#email-field").focus();
            //                     //alert('Oh ok. Chicken, I see.');
            //                 });
            //             }
            //         },
            //         error:function()
            //         {
            //             console.log("something went wrong");
            //         }
            //     });
            // });


    })

    function openInterestDetail(id){

        $.get(`/leads/interests/detail/` + id, function(result){
            $("#interest_detail_modal .modal-body").html(result);
            $("#interest_detail_modal").modal('show')
        })

    }

    function openInterestEditModal(id){
        $("#add_interests_modal").attr("data-id", id);
        $("#add_interests_modal").modal('show')
    }
    function openInterestDeleteConfirm(id){
        
        $.confirm({
            title: 'Are you sure?',
            content: "You won't be able to revert this!",
            theme: 'supervan',
            buttons: {
                confirm: function () {
                    $.post('/leads/interests/delete', {id:id}, function(result){
                        $("#interests_list").load($("#interests_list").attr('data-url'))
                    })
                },
                cancel: function () {
                },
            }
        });
    }

    // function initCalendar(defaultDate = null){
    //     var calendarBasicViewElement = document.querySelector('.fullcalendar-basic');
    //     calendarBasicViewElement.innerHTML = ''
    //     var calendarBasicViewInit = new FullCalendar.Calendar(calendarBasicViewElement, {
    //         plugins: [ 'dayGrid', 'interaction' ],
    //         header: {
    //             left: 'prev,next today',
    //             center: 'title',
    //             right: 'dayGridMonth,dayGridWeek,dayGridDay'
    //         },
    //         contentHeight:"auto",
    //         fixedWeekCount: false,
    //         displayEventTime: false,
    //         defaultDate: defaultDate ? defaultDate : new Date(),
    //         //editable: true,
    //         events: function(info, successCallback, failureCallback) {
    //             $.ajax({
    //                 url: '{{ route('calendar.events.load') }}',
    //                 dataType: 'json',
    //                 data: {
    //                     start: info.startStr,
    //                     end: info.endStr,
    //                     kinds: 'lead',
    //                     model_id : "{{ $lead->id }}"
    //                 },
    //                 success: function(result) {
    //                     var events = [];
    //                     $(result).each(function() {
    //                         events.push({
    //                             id: $(this).attr('id'),
    //                             title: $(this).attr('notes'),
    //                             start: $(this).attr('formated_date') // will be parsed
    //                         });
    //                     });
    //                     successCallback(events);
    //                 }
    //             });
    //         },
    //         eventLimit: true,
    //         dateClick: function (info){
    //             // $("#event_detail_modal").modal('show')
    //             var dialog = bootbox.dialog({
    //                 title: 'A custom dialog with init',
    //                 message: '\
    //                     <div class="form-group">\
    //                         <label>Select Users</label>\
    //                         <select multiple="multiple" class="form-control" id="userselect"\
    //                             data-placeholder="Select users who will get notification.">\
    //                         </select>\
    //                     </div>\
    //                     <div class="form-group">\
    //                         <label>Note</label>\
    //                         <textarea class="form-control" id="reminder_notes" placeholder="Enter your notes."></textarea>\
    //                     </div>\
    //                 ',
    //                 buttons: {
    //                     cancel: {
    //                         label: "Cancel",
    //                         className: 'btn-danger',
    //                         callback: function(){

    //                         }
    //                     },
    //                     ok: {
    //                         label: "Save",
    //                         className: 'btn-info',
    //                         callback: function(){
    //                             $.post('{{ route('calendar.events.save') }}', {
    //                                 date: info.date,
    //                                 users: $("#userselect").val(),
    //                                 notes: $("#reminder_notes").val(),
    //                                 kinds: 'lead',
    //                                 model_id : "{{ $lead->id }}"
    //                                 }, function(res){
    //                                     initCalendar(info.date)
    //                             })
    //                         }
    //                     }
    //                 }
    //             });
    //             const JSON_DATA = JSON.parse(document.getElementById('json_details').innerHTML)
    //             dialog.on('shown.bs.modal', function(e){
    //                 $("#userselect").select2({
    //                     width: '100%',
    //                     placeholder: function() {
    //                         $(this).data('placeholder');
    //                     },
    //                     data: JSON_DATA['users'].map((user) => {
    //                         return {id: user.id, text: user.name}
    //                     })
    //                 })
    //             // Do something with the dialog just after it has been shown to the user...
    //             });
    //         },
    //         eventClick: function(info){

    //             $.ajax({
    //                 url: '{{ route('calendar.events.detail') }}',
    //                 dataType: 'json',
    //                 data: {
    //                     id: info.event.id
    //                 },
    //                 success: function(result) {
    //                     var dialog = bootbox.dialog({
    //                         title: 'A custom dialog with init',
    //                         message: '\
    //                             <div class="form-group">\
    //                                 <label>Select Users</label>\
    //                                 <select multiple="multiple" class="form-control" id="userselect"\
    //                                     data-placeholder="Select users who will get notification.">\
    //                                 </select>\
    //                             </div>\
    //                             <div class="form-group">\
    //                                 <label>Note</label>\
    //                                 <textarea class="form-control" id="reminder_notes" placeholder="Enter your notes." >'+ result.calendar.notes +'</textarea>\
    //                             </div>\
    //                         ',
    //                         buttons: {
    //                             cancel: {
    //                                 label: "Cancel",
    //                                 className: 'btn-link',
    //                                 callback: function(){

    //                                 }
    //                             },
    //                             delete:{
    //                                 label: "Delete",
    //                                 className: 'btn-danger',
    //                                 callback: function(){
    //                                     $.post('{{ route('calendar.events.delete') }}', {
    //                                         event_id: info.event.id,
    //                                         }, function(res){
    //                                             initCalendar(info.event.start)
    //                                     })
    //                                 }
    //                             },
    //                             ok: {
    //                                 label: "Save",
    //                                 className: 'btn-info',
    //                                 callback: function(){
    //                                     $.post('{{ route('calendar.events.save') }}', {
    //                                         date: info.event.start,
    //                                         event_id: result.calendar.id,
    //                                         users: $("#userselect").val(),
    //                                         notes: $("#reminder_notes").val(),
    //                                         kinds: 'lead',
    //                                         model_id : "{{ $lead->id }}"
    //                                         }, function(res){
    //                                             initCalendar(info.event.start)
    //                                     })
    //                                 }
    //                             }
    //                         }
    //                     });
    //                     const JSON_DATA = JSON.parse(document.getElementById('json_details').innerHTML)
    //                     dialog.on('shown.bs.modal', function(e){
    //                         $("#userselect").select2({
    //                             width: '100%',
    //                             placeholder: function() {
    //                                 $(this).data('placeholder');
    //                             },
    //                             data: JSON_DATA['users'].map((user) => {
    //                                     return {id: user.id, text: user.name, "selected": result.calendar.user_id.split(',').includes(user.id + '') ?  true : false}
    //                                 })
    //                         })

    //                     // Do something with the dialog just after it has been shown to the user...
    //                     });
    //                 }
    //             });
    //         }
    //     }).render();
    // }
</script>
@endsection
