@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.customers')}} - {{ $customer->name }}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y flex items-center flex-wrap border-b p-5">

    <div class="-intro-x breadcrumb mr-auto hidden sm:flex">
        <a href="{{ route('customers.dashboard') }}" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.customers') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="javascript:;" class="breadcrumb--active">{{$customer->name}}</a> 
    </div>

</div>

@endsection

@section('page_content')

<div class="grid grid-cols-12 gap-6">
    <div class="col-span-12 lg:col-span-3 xxl:col-span-3 flex lg:block flex-col-reverse">
        <div class="intro-y box mt-5">
            <div class="relative flex items-center p-5">
                <div class="w-12 h-12 image-fit">
                    <img alt="customer avatar" class="rounded-full" src="{{ asset($customer->profile_image_src) }}">
                </div>
                <div class="ml-4 mr-auto">
                    <div class="font-medium text-base">{{ $customer->name }}</div>
                    <div class="text-gray-600">{{ isset(\PragmaRX\Countries\Package\Countries::where('cca2',$customer->country_base_residence)->first()->name->official) ? \PragmaRX\Countries\Package\Countries::where('cca2',$customer->country_base_residence)->first()->name->official : '' }}</div>
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
                <a data-target="#documents" href="javascript:;" data-toggle="tab" class="py-3 flex items-center">
                    <i class="icon-file-empty2 w-5 h-5 mr-2"></i>
                    {{ __('app.documents') }}
                </a>
                <a data-target="#notes" data-toggle="tab" href="javascript:;" class="py-3 flex items-center">
                    <i data-feather="tablet" class="w-5 h-5 mr-2"></i>
                    {{__('app.notes')}}
                </a>
                <a data-toggle="tab" href="javascript:;" data-target="#transactions" data-toggle="tab"
                    class="py-3 flex items-center">
                    <i class="icon-credit-card2 w-5 h-5 mr-2"></i>
                    {{__('app.transactions')}}
                </a>
                <a data-target="#assign" data-toggle="tab" href="javascript:;" class="py-3 flex items-center">
                    <i class="icon-credit-card2 w-5 h-5 mr-2"></i>
                    {{__('app.assign')}}
                </a>
            </div>
        </div>
    </div>
    <div class="col-span-12 lg:col-span-9 xxl:col-span-9 tab-content mb-5">
        <div class="intro-y box mt-5 tab-content__pane active"  id="profile">
            <div class="flex items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                    {{ __('app.profile') }}
                </h2>
                <a class="text-theme-13" href="{{ route('customers.edit', $customer->id) }}">
                    <i class="icon-pencil mr-2"></i>{{ __('app.edit') }}
                </a>
            </div>
            <div class="p-5">
                <div class="flex items-center justify-between mt-5">
                    <span class="font-weight-semibold">{{ __('app.civility') }}:</span>
                    <h6 class="font-medium">{{ $customer->civility }}</h6>
                </div>
                <div class="flex items-center justify-between mt-5">
                    <span class="font-weight-semibold">{{ __('app.full_name') }}:</span>
                    <h6 class="font-medium">{{ $customer->name }}</h6>
                </div>
                <div class="flex items-center justify-between mt-5">
                    <span class="font-weight-semibold">{{ __('app.gender') }}:</span>
                    <h6 class="font-medium">{{ $customer->gender }}</h6>
                </div>
                <div class="flex items-center justify-between mt-5">
                    <span class="font-weight-semibold">{{ __('app.country_of_residence') }}:</span>
                    <h6 class="font-medium">
                        {{ isset(\PragmaRX\Countries\Package\Countries::where('cca2',$customer->country_base_residence)->first()->name->official) ? \PragmaRX\Countries\Package\Countries::where('cca2',$customer->country_base_residence)->first()->name->official : '' }}
                    </h6>
                </div>
                <div class="flex items-center justify-between mt-5">
                    <span class="font-weight-semibold">{{__("app.tags")}}:</span>
                    <div>
                    @foreach (explode(',', $customer->tags) as $tag)
                    <h5 class="font-medium ml-1">
                        <span class="py-1 px-2 rounded-full text-xs text-white font-medium bg-{{ isset(\App\Models\LeadTag::find($tag)->color) ? \App\Models\LeadTag::find($tag)->color : "" }}">
                            {{ isset(\App\Models\LeadTag::find($tag)->tag_name) ? \App\Models\LeadTag::find($tag)->tag_name : '' }}
                        </span>
                    </h5>
                    @endforeach
                    </div>
                </div>
                <div class="flex items-center justify-between mt-5">
                    <span class="font-weight-semibold">Phone Numbers:</span>
                    <div>
                        @foreach ($customer->phone_number_details as $key => $phone_number)
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

                                <i class="fab fa-whatsapp-square fa-3x text-theme-1"></i>
                            </a>
                            @elseif ($messaging_app == 'Viber')
                            <a href="javascript:;">

                                <i class="fab fa-viber fa-3x text-theme-4"></i>
                            </a>
                            @elseif ($messaging_app == 'Telegram')
                            <a href="javascript:;">

                                <i class="fab fa-telegram fa-3x text-theme-1"></i>
                            </a>
                            @elseif ($messaging_app == 'Line')
                            <a href="javascript:;">

                                <i class="fab fa-line fa-3x text-theme-1"></i>
                            </a>
                            @elseif ($messaging_app == 'Weixin')
                            <a href="javascript:;">

                                <i class="fab fa-weixin fa-3x text-theme-1"></i>
                            </a>
                            @endif

                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-5">
                    <span class="font-weight-semibold">Emails:</span>
                    <div>
                        @foreach ($customer->email_details as $key => $email)
                            <div><i class="icon-mail5 text-theme-1 mr-2"></i>{{ $email->email }}</div>
                        @endforeach
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
                                customer{{ $log->action == 'converted' ? ' to customer' : '' }}.</h6>
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
        
        <div class="intro-y box mt-5 tab-content__pane" id="transactions">
            <div class="flex items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                   {{__('app.notes')}}
                </h2>
                <a class="button mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white" href="javascript:;" data-toggle="modal" data-target="#create_transaction_modal">
                    <i data-feather="plus-circle" class="mr-2"></i>
                    Add {{ __('app.transactions') }}
                </a>
            </div>
            <div class="p-5">
                <div class="intro-y datatable-wrapper box p-5 mt-5">
                    <table class="table table-report table-report--bordered display w-full datatable" id="transaction_table">
                        <thead>
                            <tr>
                                <th class="border-b-2 whitespace-no-wrap">{{__('app.inventory')}}</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">{{__('app.customer')}}</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">{{__('app.users')}}</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">{{__('app.location')}}</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">{{__('app.price')}}</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">{{__('app.date')}}</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">{{__('app.action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                            <tr>
                                <td class="border-b">
                                    {!! $transaction->name_field !!}
                                </td>
                                <td class="text-center border-b">
                                    {!! $transaction->customer_name !!}
                                </td>
                                <td class="text-center border-b">
                                    {!! $transaction->user_name !!}
                                </td>
                                <td class="text-center border-b">
                                    {!! $transaction->location_name !!}
                                </td>
                                <td class="text-center border-b">
                                    {!! $transaction->price !!}
                                </td>
                                <td class="text-center border-b">
                                    {!! $transaction->date !!}
                                </td>
                                <td class="text-center border-b">
                                    {!! $transaction->action !!}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="intro-y box mt-5 tab-content__pane" id="assign">
            
            <div class="flex items-center p-5 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">
                   {{__('app.assign')}}
                </h2>
            </div>
            <div class="p-5 relative">
                <form action="{{ route('customers.assign', $customer->id) }}" method="post">                
                    @csrf
                    <div class="flex-row mt-5">
                        <div class="preview">
                            <label>{{__('app.users')}}</label>
                            <select class="w-full select2" name='users[]' data-placeholder="Choose Users" multiple="multiple">
                                <option></option>
                                @foreach($users as $user)
                                    @if (strpos($customer->assign_users, $user->id) !== false)
                                    <option value="{{$user->id}}" selected>{{$user->name}}</option>
                                    @else
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex-row mt-5">
                        <div class="preview">
                            <label>{{__('app.location')}}</label>
                            <select class="w-full select2" name='locations[]' data-placeholder="Choose Locations" multiple="multiple">
                                <option></option>
                                    @foreach($locations as $location)
                                        @if (strpos($customer->assign_locations, $location->id) !== false)
                                        <option value="{{$location->id}}" selected>{{$location->name}}</option>
                                        @else
                                        <option value="{{$location->id}}">{{$location->name}}</option>
                                        @endif
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex-row mt-5">
                        <button type="submit" class="button bg-theme-4 text-white">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/json" id="json_details">
    {!! $json_data !!}
</script>


<!-- Large modal -->
<div class="modal" id="add_documents_modal">
    <div class="modal__content px-5 py-10">
        <div class="text-center">
            <div class="mb-3">Attach {{ __('app.documents') }}</div>
        </div>
        <form action="{{ route('customers.documents.upload', $customer->id) }}" class="dropzone border-gray-200 border-dashed">
            @csrf
            <div class="fallback">
                <input name="file" type="file" multiple/>
            </div>
            <div class="dz-message" data-dz-message>
                <div class="text-lg font-medium">Drop files here or click to upload.</div>
                <div class="text-gray-600"> This is just a demo dropzone. Selected files are <span class="font-medium">not</span> actually uploaded. </div>
            </div>
        </form>
        <div class="mt-5 text-center">
            <button data-dismiss="modal" class="button inline-block bg-theme-1 text-white">Close</button> 
        </div>
    </div>
</div>

<div id="create_transaction_modal" class="modal">
    <div class="modal__content modal__content--lg px-5 py-10">
        <div class="text-center">
            <div class="mb-3 text-sm text-theme-4 font-medium">New Transaction</div>
        </div>
        <div id="customer_transaction_form"></div>
    </div>
</div>


@endsection


@section('scripts_after')
<script src="{{ asset('js/customer_transaction_form.js?customerID='.$customer->id) }}"></script>
<script>
    $(document).ready(function(){

        if(window.location.hash) {
        // Fragment exists
        } else {
        }
        $("#document_list").load('{{ route('customers.documents.load', $customer->id) }}');
        $("#notes_list").load('{{ route('customers.notes.load', $customer->id) }}');
        $("#btn_add_note").click(function(){
            $.post('{{ route('customers.notes.add', $customer->id) }}', {data: $("#note_edit").val()}, function(result) {
                $("#notes_list").load('{{ route('customers.notes.load', $customer->id) }}')
                $("#note_edit").summernote("reset");
            })
        });
    });
</script>
@endsection
