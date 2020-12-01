@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-send"></i>
    @if($id)
        ویرایش اعلان -
    @else
        افزودن اعلان -
    @endif
    @if($userIds)
        @if($user)
            @include('dashboard.layouts.username')
        @else
            {{ 'جمعی از کابران' }}
        @endif
    @else
        {{ 'عمومی' }}
    @endif
@endsection
@section('filter')
@endsection
@section('content')
    <form method="post" enctype="multipart/form-data"
          action="{{ route('dashboard.storeAnnouncement', ['id' => $id, 'userIds' => $userIds]) }}">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{ $announcement['id'] }}">
        <div class="row">
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">عنوان اعلان</label>
                <input name="title" class="form-control col-md-7" required
                       value="{{ $announcement['title'] }}"/>
            </div>
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">تاریخ انقضا</label>
                <input required style="direction: ltr;" value="{{ $announcement['expire_at'] }}" id="date" class="form-control
                time_picker col-md-7" type="text"
                       name="expire_at">
            </div>
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">زمان ارسال</label>
                <input required style="direction: ltr;" value="{{ $announcement['send_at'] }}" id="date" class="form-control
                time_picker col-md-7" type="text"
                       name="send_at">
            </div>
        </div>
        <div class="row pt-2">
            <div class="col-md-4 row">
                <label class="col-md-5 col-form-label text-md-left">متن اعلان</label>
                <textarea name="text" type="text" rows="7" class="form-control col-md-7"
                >{{ $announcement['text'] }}</textarea>
            </div>
            <div class="col-md-4 row offset-md-4">
                <label class="col-md-5 col-form-label text-md-left">خلاصه متن</label>
                <textarea name="summary" type="text" rows="7" class="form-control col-md-7"
                >{{ $announcement['summary'] }}</textarea>
            </div>
        </div>
        <div class="row pt-2">
            <div class="row col-md-4">
                <label class="col-md-5 col-form-label text-md-left">نوع لینک</label>
                <div class="col-md-7 row">
                    <div class="col-md-6">
                        <label class="form-control-label">داخلی</label>
                        <input type="radio" name="link_type" value="1"
                               @if($announcement['link_type'] == 1) checked="checked" @endif>
                    </div>
                    <div class="col-md-6">
                        <label class="form-control-label">خارجی</label>
                        <input type="radio" name="link_type" value="2"
                               @if($announcement['link_type'] == 2) checked="checked" @endif>
                    </div>
                </div>
            </div>
            <div class="row col-md-4">
                <label class="col-md-5 col-form-label text-md-left">آدرس لینک خارجی</label>
                <input name="external_link" class="form-control col-md-7 ltr"
                       value="{{ $announcement['external_link'] }}"/>
            </div>
            <div class="row col-md-4">
            </div>
        </div>
        <div class="row pt-2">
            <div class="row col-md-4">
                <label class="col-md-5 col-form-label text-md-left">اسم دکمه داخل اعلان</label>
                <input name="button_name" class="form-control col-md-7"
                       value="{{ $announcement['button_name'] }}"/>
            </div>
            <div class="row col-md-4">
                <label class="col-md-5 col-form-label text-md-left">لینک دکمه داخل اعلان</label>
                <input name="button_link" class="form-control col-md-7 ltr"
                       value="{{ $announcement['button_link'] }}"/>
            </div>
            <div class="row col-md-4">
            </div>
        </div>
        <div class="row pt-2">
            <div class="row col-md-4">
                <label for="icon" class="col-md-5 col-form-label text-md-left">
                    آیکون اعلان
                    <img src="{{
                            $announcement['icon_path'] ?
                            env('MAPSA_URL') . '/storage/' .  $announcement['icon_path'] :
                            asset('img/no-img.png') }}" alt="" class="col-md-5"/>
                </label>
                <input type="file" accept="image/*" name="icon" class="form-control col-md-7">
            </div>
            <div class="row col-md-4">
                <label for="image" class="col-md-5 col-form-label text-md-left">
                    عکس ۱ اعلان
                    <img src="{{
                            $announcement['image_path'] ?
                            env('MAPSA_URL') . '/storage/' .  $announcement['image_path'] :
                            asset('img/no-img.png') }}" alt="" class="col-md-5"/>
                </label>
                <input type="file" accept="image/*" name="image" class="form-control col-md-7">
            </div>
            <div class="row col-md-4">
                <label for="gif" class="col-md-5 col-form-label text-md-left">
                    عکس ۲ اعلان
                    <img src="{{
                            $announcement['gif_path'] ?
                            env('MAPSA_URL') . '/storage/' .  $announcement['gif_path'] :
                            asset('img/no-img.png') }}" alt="" class="col-md-5"/>
                </label>
                <input type="file" accept="image/*" name="gif" class="form-control col-md-7">
            </div>
        </div>
        <div class="row justify-content-center pt-3">
            <div class="col-md-2">
                <input class="form-control btn btn-success" type="submit" value="ثبت">
            </div>
            @if($announcement['id'] ?? false)
                <div class="col-md-2">
                    <button class="form-control btn btn-danger">
                        <a href="{{ route('dashboard.deleteAnnouncement', ['id' => $announcement->id]) }}">حذف</a>
                    </button>
                </div>
            @endif
        </div>
    </form>
    @if($users->count())
        {{ $users->appends(request()->input())->links() }}
        <div class="row justify-content-center pt-5" id="ajax-table" style="overflow-x: auto">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ردیف</th>
                    <th>کاربر</th>
                    <th>وضعیت</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr class="table-row-clickable align-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user['username'] }}</td>
                        <td>{{ $user['state'] ? 'خوانده شده' : 'خوانده نشده' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
    <script type="text/javascript">
    </script>
@endsection
