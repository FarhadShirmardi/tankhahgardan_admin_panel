@extends('dashboard.layouts.master')
@section('title')
    <i class="fa fa-phone"></i>
    تماس‌های کاربر -  @include('dashboard.layouts.username')
@endsection
@section('content')
    <h5 class="text-center pb-3">پیام‌ها</h5>
    <div id="ajax-table" style="overflow-x: auto;">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ردیف</th>
                <th>نوع</th>
                <th>متن پیام</th>
                <th>زمان</th>
            </tr>
            </thead>
            <tbody>
            @foreach($userMessages as $userMessage)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $typeMappings[$userMessage->type]['title'] }} (وضعیت {{ $userMessage->type }})</td>
                    <td>{{ $userMessage->sms_text }}</td>
                    <td class="ltr text-right">{{ \App\Helpers\Helpers::convertDateTimeToJalali($userMessage->sent_time)
                    }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="row  pb-3 pt-5 justify-content-center">
        <h5 class="text-center col-md-2">
            تماس‌ها
        </h5>
        <div class="col-md-2">
            <a class="form-control btn btn-success"
               href="{{ route('dashboard.automation.callView', ['userId' => $user->id, 'id' => 0])
               }}">افزودن
                تماس</a>
        </div>
    </div>

    <div id="ajax-table" style="overflow-x: auto;">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ردیف</th>
                <th>نوع</th>
                <th>متن</th>
                <th>زمان تماس</th>
            </tr>
            </thead>
            <tbody>
            @foreach($calls as $call)
                <tr class="clickableRow table-row-clickable"
                    data-href="{{ route('dashboard.automation.callView', ['userId' => $user->id, 'id' => $call->id])
                    }}" data-target="_self">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $typeMappings[$call->type]['title'] }} (وضعیت {{ $call->type }})</td>
                    <td>@if($call['is_missed_call']) <img width="32px" src="{{ asset('dashboard/icons/missed-call.png')
                     }}">
                        @else {{ $call->text }}
                        @endif</td>
                    <td class=" ltr text-right">{{ \App\Helpers\Helpers::convertDateTimeToJalali($call->call_time) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if($burn or $userState->automation_state == 26)
        <hr>
        <h5 class="pb-3 pt-5 text-center">
            افزودن به لیست سوخته
        </h5>
        <div class="row justify-content-center">
            <form id="filter" method="POST" action="{{ route('dashboard.automation.burnUser', ['id' => $user->id]) }}">
                {{ csrf_field() }}
                <div class="pb-5 pt-5 justify-content-center">
                    <div class="row">
                        <div class="col-md-2 row"></div>
                        <div class="col-md-6 row">
                            <label class="col-md-5 col-form-label text-md-left">متن</label>
                            <div class="ms-list col-md-7">
                                <textarea required name="text" id="text" cols="50" rows="10">{{ $burn->text ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="pt-5 pb-3 justify-content-center row">
                        <div class="col-md-3">
                            @if(!$burn)
                                <input class="form-control btn btn-success" type="submit" value="افزودن">
                            @else
                                <input class="form-control btn btn-warning" type="submit" value="ویرایش">
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif
@endsection
@section('scripts')

@endsection

