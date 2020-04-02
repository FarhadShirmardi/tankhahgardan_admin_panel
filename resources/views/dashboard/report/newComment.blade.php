@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-comment"></i>
    افزودن بازخورد
@endsection
@section('filter')
@endsection
@section('content')
    <form method="post" action="{{ route('dashboard.newComment', ['id' => $id]) }}">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{$comment->id}}">
        <input type="hidden" name="user_id" value="{{$filter['user_id'] ? $filter['user_id'] : $comment->user_id}}">
        <div class="row">
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">کارشناس</label>
                <select required id="panel_user_id" name="panel_user_id" class="form-control custom-select-sm col-md-7">
                    <option></option>
                    @foreach($panel_users as $panelUser)
                        <option @if(auth()->id() == $panelUser['id']) selected @endif
                        value="{{$panelUser['id']}}">
                            {{$panelUser['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">منبع بازخورد</label>
                <select required id="source" name="source" id="source_types" class="form-control custom-select-sm col-md-7">
                    <option></option>
                    @foreach($source_types as $sourceType)
                        <option @if($comment->source == $sourceType['value']) selected @endif
                        value="{{$sourceType['value']}}">{{$sourceType['text']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">موضوع</label>
                <select required id="feedback_title_id" name="feedback_title_id" class="form-control custom-select-sm col-md-7">
                    <option></option>
                    @foreach($feedback_titles as $feedbackTitle)
                        <option @if($comment->feedback_title_id == $feedbackTitle['id']) selected @endif
                        value="{{$feedbackTitle['id']}}">
                            {{$feedbackTitle['title']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">نام</label>
                <input name="name" value="{{$filter['user_id'] ? $selected_user['name'] . ' ' . $selected_user['family'] : $comment->name}}"
                       class="form-control custom-select-sm col-md-7"/>
            </div>
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">شماره تلفن</label>
                <input name="phone_number" value="{{$filter['user_id'] ? $selected_user['phone_number'] : $comment->phone_number}}" class="form-control custom-select-sm
                col-md-7" maxlength="11"/>
            </div>
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">ایمیل</label>
                <input name="email" value="{{$filter['user_id'] ? $selected_user['email'] : $comment->email}}" type="email" class="form-control custom-select-sm
                col-md-7"/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">تاریخ</label>
                <input required style="direction: ltr;" value="{{$comment->date}}" id="date" class="form-control
                time_picker col-md-7" type="text"
                       name="date">
            </div>
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">تاریخ پاسخ</label>
                <input style="direction: ltr;" id="date" value="{{$comment->response_date}}"
                       class="form-control time_picker col-md-7" type="text"
                       name="response_date">
            </div>
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">وضعیت</label>
                <select required id="state" name="state" class="form-control custom-select-sm col-md-7">
                    @foreach(\App\Constants\FeedbackStatus::toArray() as $feedbackState)
                        <option @if($comment->state == $feedbackState) selected @endif
                        value="{{ $feedbackState }}">
                            {{ \App\Constants\FeedbackStatus::getEnum($feedbackState) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">متن</label>
                <textarea required cols="50" rows="10" id="text" class="form-control col-md-7 mt-2 mb-2" type="text"
                          name="text">{{$comment->text}}</textarea>
            </div>
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">متن پاسخ</label>
                <textarea cols="50" rows="10" id="text" class="form-control col-md-7 mt-2 mb-2" type="text"
                          name="response">{{$comment->response}}</textarea>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-2">
                <input class="form-control btn btn-success" type="submit" value="ثبت">
            </div>
        </div>
    </form>

    <hr>

    <form id="filter" method="get" action="">
        {{ csrf_field() }}
        <div class="pb-5 pt-5 justify-content-center">
            <div class="row">
                <div class="col-md-6 row">
                    <label class="col-md-5 col-form-label text-md-left">جستجوی شماره</label>
                    <input class="form-control custom-select-sm col-md-7" type="text" id="phone_number" name="phone_number" value="{{$filter['phone_number']}}"
                           placeholder="جستجوی شماره">
                </div>
                <div class="col-md-6 row">
                    <label class="col-md-5 col-form-label text-md-left">کاربر</label>
                    <div class="ms-list col-md-7">
                        <select name="user_id" id="user_id" style="width: 100%">
                            <option></option>
                            @foreach($users as $key => $user)
                                <option @if($user['is_selected']) selected @endif
                                value="{{$user->id}}">{{$user->full_name . ' - ' . $user->phone_number}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center pt-4">
                <div class="col-md-2">
                    <input class="form-control btn btn-warning" type="submit" value="جستجو">
                </div>
            </div>
        </div>
    </form>
    @include('dashboard.report.listFeedback')


    <script type="text/javascript">

        $(document).ready(function () {
            $('#user_id').select2({
                width: 'element',
                allowClear: true,
                placeholder: 'جستجوی کاربر'
            });

            $('#panel_user_id').select2({
                width: 'element',
                allowClear: true,
                placeholder: 'انتخاب کارشناس'
            });

            $('#source').select2({
                width: 'element',
                allowClear: true,
                placeholder: 'انتخاب منبع بازخورد'
            });

            $('#feedback_title_id').select2({
                width: 'element',
                allowClear: true,
                placeholder: 'انتخاب موضوع'
            });
        });
    </script>
@endsection
