@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-reply"></i>
    پاسخ بازخورد
@endsection
@section('filter')
@endsection
@section('content')
    @if(auth()->user()->can('response_feedback'))
        <form method="post"
              enctype="multipart/form-data"
              action="{{ route('dashboard.responseFeedback', ['feedback_id' => $feedback_item->feedback_id]) }}">
            {{ csrf_field() }}
            @endif
            <div class="row">
                <label class="col-md-5 col-form-label text-md-left">تاریخ</label>
                <label class="col-md-7 col-form-label text-md-right ltr">{{ $feedback_item->date }}</label>
            </div>
            <div class="row">
                <label class="col-md-5 col-form-label text-md-left">موضوع بازخورد</label>
                <label class="col-md-7 col-form-label text-md-right">{{ $feedback_item->title }}</label>
            </div>
            <div class="row">
                <label class="col-md-5 col-form-label text-md-left">نام کاربر</label>
                <label class="col-md-7 col-form-label text-md-right">{{ $feedback_item->full_name }}</label>
            </div>
            <div class="row">
                <label class="col-md-5 col-form-label text-md-left">شماره کاربر</label>
                <label class="col-md-7 col-form-label text-md-right">{{ $feedback_item->user_phone_number }}</label>
            </div>
            <div class="row">
                <label class="col-md-5 col-form-label text-md-left">پلتفرم</label>
                <label class="col-md-7 col-form-label text-md-right">{{ $feedback_item->platform }}</label>
            </div>
            <div class="row">
                <label class="col-md-5 col-form-label text-md-left">مدل دستگاه</label>
                <label class="col-md-7 col-form-label text-md-right">{{ $feedback_item->model }}</label>
            </div>
            <div class="row">
                <label class="col-md-5 col-form-label text-md-left">نسخه دستگاه</label>
                <label class="col-md-7 col-form-label text-md-right">{{ $feedback_item->os_version }}</label>
            </div>
            <div class="row">
                <label class="col-md-5 col-form-label text-md-left">متن بازخورد</label>
                <div class="col-md-7 mt-2 mb-2">
                    {{ $feedback_item->text }}
                </div>
            </div>
            <div class="row">
                <label class="col-md-5 col-form-label text-md-left">عکس‌ها</label>
                <div class="col-md-7">
                    <div class="row">
                        @foreach($feedback_item->images as $image)
                            <div class="col-md-3 clickableRow table-row-clickable"
                                 data-href="{{ env('TANKHAH_URL') . '/panel/' .  env('TANKHAH_TOKEN') . '/images?path=' . $image['path'] }}">
                                <img style="width: 100%; height: 100%"
                                     src="{{ env('TANKHAH_URL') . '/panel/' .  env('TANKHAH_TOKEN') . '/images?path=' . $image['path'] }}"
                                     alt="">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row pt-3">
                <label class="col-md-5 col-form-label text-md-left">پاسخ</label>
                <textarea class="col-md-6" rows="10" name="response">{{ $feedback_item->response_text }}</textarea>
            </div>
            <div class="row">
                <label class="col-md-5 col-form-label text-md-left">عکس‌های پاسخ</label>
                <div class="col-md-7">
                    @if($feedback_item->responseImages != [])
                        <div class="row pt-3">
                            <input class="col-md-1 form-control d-inline-block" type="checkbox" name="delete_image"/>
                            <label class="align-middle col-form-label text-md-right">پاک کردن عکس‌ها</label>
                        </div>
                    @endif
                    <div class="row pt-2">
                        @foreach($feedback_item->responseImages as $image)
                            <div class="col-md-3 clickableRow table-row-clickable"
                                 data-href="{{ env('TANKHAH_URL') . '/panel/' .  env('TANKHAH_TOKEN') . '/images?path=' . $image['path'] }}">
                                <img style="width: 100%; height: 100%"
                                     src="{{ env('TANKHAH_URL') . '/panel/' .  env('TANKHAH_TOKEN') . '/images?path=' . $image['path'] }}"
                                     alt="">
                            </div>
                        @endforeach
                    </div>
                    <div class="row pt-2 pb-3">
                        <input type="file" name="response_images[]" multiple accept="image/*"/>
                    </div>
                </div>
            </div>
            <div class="row pt-1">
                <label class="col-md-5 col-form-label text-md-left">وضعیت</label>
                <select required id="state" name="state" class="form-control custom-select-sm col-md-3">
                    @foreach(\App\Constants\FeedbackStatus::toArray() as $feedbackState)
                        <option @if($feedback_item->state == \App\Constants\FeedbackStatus::getEnum($feedbackState)) selected @endif
                        value="{{ $feedbackState }}">
                            {{ \App\Constants\FeedbackStatus::getEnum($feedbackState) }}</option>
                    @endforeach
                </select>
            </div>
            @if(auth()->user()->can('response_feedback'))
                <div class="row pt-5 justify-content-center">
                    <div class="col-md-2">
                        <input class="form-control btn btn-success" type="submit" value="ثبت">
                    </div>
                </div>
        </form>
    @endif

    <hr>

    @include('dashboard.report.listUser', ['clickable' => true])

    <hr>

    @include('dashboard.report.listFeedback')


    <script type="text/javascript">

        $(document).ready(function () {

            $('#state').select2({
                width: 'element',
            });
        });
    </script>
@endsection
