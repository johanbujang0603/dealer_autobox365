@extends('layouts.app')

@section('page-title')
<!-- BEGIN: Breadcrumb -->
<h2 class="text-lg font-medium mr-auto">
    {{ __('app.home') }} - {{ __('app.all_users') }}
</h2>
<!-- END: Breadcrumb -->
@endsection


@section('page_header')
<div class="intro-y flex items-center border-b">
    <div class="-intro-x breadcrumb mr-auto hidden sm:flex p-5">
        <a href="" class="flex items-center"><i data-feather="home" class="mr-2"></i>{{ __('app.home') }}</a> 
        <i data-feather="chevron-right" class="breadcrumb__icon"></i> 
        <a href="" class="breadcrumb--active">{{ __('app.all_users') }}</a> 
    </div>
</div>
@endsection

@section('page_content')

<div class="grid grid-cols-12 gap-6 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <h2 class="text-lg font-medium mr-auto">
            {{ __('app.all_users') }}
        </h2>
    </div>
    <div class="intro-y datatable-wrapper box p-5 mt-5 col-span-12 sm:col-span-12">
        <table class="table table-report table-report--bordered display datatable w-full">
            <thead>
                <tr>
                    <th class="border-b-2 whitespace-no-wrap"></th>
                    <th class="border-b-2 text-center whitespace-no-wrap">{{ __('app.name') }}</th>
                    <th class="border-b-2 text-center whitespace-no-wrap">{{ __('app.account_name') }}</th>
                    <th class="border-b-2 text-center whitespace-no-wrap">{{ __('app.email') }}</th>
                    <th class="border-b-2 text-center whitespace-no-wrap">{{ __('app.phone_numbers') }}</th>
                    <th class="border-b-2whitespace-no-wrap">{{ __('app.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $user)
                <tr>
                    <td class="border-b text-center ">
                        {!! $user[0] !!}
                    </td>
                    <td class="border-b text-center ">
                        {!! $user[1] !!}
                    </td>
                    <td class="text-center border-b">
                        {!! $user[2] !!}
                    </td>
                    <td class="border-b text-center ">
                        {!! $user[3] !!}
                    </td>
                    <td class="border-b text-center ">
                        {!! $user[4] !!}
                    </td>
                    <td class="border-b">
                        {!! $user[5] !!}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts_after')
<script>
    function deleteListing(id){
        var notice = new PNotify({
            title: 'Confirmation',
            text: '<p>Are you sure you want to delete this listing?</p>',
            hide: false,
            type: 'warning',
            width: "100%",
            cornerclass: "rounded-0",
            addclass: "stack-custom-top bg-danger border-danger",
            stack: {"dir1": "right", "dir2": "down", "push": "top", "spacing1": 1},
            confirm: {
                confirm: true,
                buttons: [
                    {
                        text: 'Yes',
                        addClass: 'btn btn-sm btn-danger'
                    },
                    {
                        addClass: 'btn btn-sm btn-info'
                    }
                ]
            },
            buttons: {
                closer: false,
                sticker: false
            }
        })

        // On confirm
        notice.get().on('pnotify.confirm', function() {
            location.href = '/users/delete/' + id
        })

        // On cancel
        notice.get().on('pnotify.cancel', function() {
            //alert('Oh ok. Chicken, I see.');
        });
    }
</script>
@endsection
