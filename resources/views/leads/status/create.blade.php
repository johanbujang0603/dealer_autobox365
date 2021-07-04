@extends('layouts.app')

@section('page_title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.leads') }} - {{__('app.create_new_status')}}
</h2>
<!-- END: Breadcrumb -->
@endsection

@section('page_header')
<div class="intro-y p-5 mb-5 flex items-center border-b">

    <div class="-intro-x breadcrumb mr-auto hidden sm:flex">
        <a href="{{ route('dashboard') }}" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a>
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="{{ route('leads.status') }}" class="breadcrumb">{{ __('app.status_management') }}</a>
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="#" class="breadcrumb--active">{{__('app.create_new_status')}}</a>
    </div>

</div>
@endsection

@section('page_content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y box mt-5 col-span-12 lg:col-span-12">
        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
            <h2 class="font-medium text-base mr-auto">
                {{__('app.status_details')}}
            </h2>
        </div>
        <div class="p-5">
            <form action="{{ route('leads.status.save') }}" method="POST" id="create_form">
                @csrf
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <label class="col-span-12 lg:col-span-3">{{__('app.status_name')}}:</label>
                    <div class="col-span-12 lg:col-span-9">
                        <input type="text" placeholder="Status name..." name="status_name" id="status_name"
                            class="input w-full border">
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <label class="col-span-12 lg:col-span-3">{{ __('app.label_color') }}:</label>
                    <div class="col-span-12 lg:col-span-9">
                        <select name="color" class="input border mr-2 w-full" id="color_selector">
                            <option value="">{{__('app.status_color')}}...</option>
                            @foreach(config('constants.colors') as $color)
                                <option value="{{$color['value']}}">{{ $color['value'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <label class="col-span-12 lg:col-span-3"></label>
                    <div class="col-span-12 lg:col-span-9">
                        <button type="submit" class="button w-40 mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white">
                            <i data-feather="save" class="mr-2"></i>
                            {{ __('app.save') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


@section('scripts_after')
<script>
    $(document).ready(function(){
            function colorDropdownFormat(state) {
                if (!state.id) {
                  return state.text;
                }
                var baseUrl = "/user/pages/images/flags";
                var $state = $(
                  '<span class="py-1 px-2 rounded-full text-xs text-theme-6 font-medium bg bg- bg-' + state.text+ '">&nbsp;&nbsp;</span>&nbsp;<span>' + state.text.charAt(0).toUpperCase() + state.text.slice(1) + '</span>'
                );
                return $state;
            };
            $("#color_selector").select2({
                placeholder: 'Select an color',
                templateResult: colorDropdownFormat
            })
            $("#create_form").submit(function(e){
                e.preventDefault()
                $.post("{{ route('leads.status.save') }}", {
                    _token: "{{ csrf_token() }}",
                    status_name : $("#status_name").val(),
                    color : $("#color_selector").val(),
                }, function(result){
                    $.toast({
                        text: "<p>You have created new status successfully!</p>",
                        hideAfter : false,
                        position: 'top-right',
                    });
                    $("#status_name").val('');
                    // var notice = new PNotify({
                    //     title: 'Thank you!',
                    //     text: '<p>You created new status successfully! Do you want to create another status continue?</p>',
                    //     hide: false,
                    //     type: 'warning',
                    //     width: "100%",
                    //     cornerclass: "rounded-0",
                    //     addclass: "stack-custom-top bg-danger border-danger",
                    //     stack: {"dir1": "right", "dir2": "down", "push": "top", "spacing1": 1},
                    //     confirm: {
                    //         confirm: true,
                    //         buttons: [
                    //             {
                    //                 text: 'Yes',
                    //                 addClass: 'btn btn-sm btn-danger'
                    //             },
                    //             {
                    //                 text: 'No, Thanks',
                    //                 addClass: 'btn btn-sm btn-info'
                    //             }
                    //         ]
                    //     },
                    //     buttons: {
                    //         closer: false,
                    //         sticker: false
                    //     }
                    // })

                    // // On confirm
                    // notice.get().on('pnotify.confirm', function() {
                    //     $("#status_name").val('')
                    // })

                    // // On cancel
                    // notice.get().on('pnotify.cancel', function() {
                    //     //alert('Oh ok. Chicken, I see.');
                    //     location.href = '/leads/status/'
                    // });
                })
            })
        })
</script>
@endsection
