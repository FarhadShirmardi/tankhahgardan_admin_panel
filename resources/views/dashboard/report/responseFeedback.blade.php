@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-reply"></i>
    پاسخ بازخورد
@endsection
@section('filter')
@endsection
@section('content')
    <form method="post"
          action="{{ route('dashboard.responseFeedback', ['feedback_id' => $feedback_item->feedback_id]) }}">
        {{ csrf_field() }}
        <div class="row">
            <label class="col-md-5 col-form-label text-md-left">تاریخ</label>
            <label class="col-md-7 col-form-label text-md-right ltr">{{ $feedback_item->date }}</label>
        </div>
        <div class="row">
            <label class="col-md-5 col-form-label text-md-left">موضوع</label>
            <label class="col-md-7 col-form-label text-md-right">{{ $feedback_item->title }}</label>
        </div>
        <div class="row">
            <label class="col-md-5 col-form-label text-md-left">نام</label>
            <label class="col-md-7 col-form-label text-md-right">{{ $feedback_item->full_name }}</label>
        </div>
        <div class="row">
            <label class="col-md-5 col-form-label text-md-left">شماره تلفن</label>
            <label class="col-md-7 col-form-label text-md-right">{{ $feedback_item->user_phone_number }}</label>
        </div>
        <div class="row">
            <label class="col-md-5 col-form-label text-md-left">متن</label>
            <div class="col-md-7 mt-2 mb-2">
                {{ $feedback_item->text }}
            </div>
        </div>
        <div class="row pt-3">
            <label class="col-md-5 col-form-label text-md-left">پاسخ</label>
            <textarea class="col-md-3" name="response">{{ $feedback_item->response_text }}</textarea>
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
        <div class="row pt-5 justify-content-center">
            <div class="col-md-2">
                <input class="form-control btn btn-success" type="submit" value="ثبت">
            </div>
        </div>
    </form>

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
