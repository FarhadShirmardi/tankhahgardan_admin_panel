@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-images"></i>
    @if($id)
        ویرایش بنر -
    @else
        افزودن بنر -
    @endif
    @if($userIds)
        @if($user)
            <span class="ltr">{{ $user->fullname }}</span>
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
    @if(auth()->user()->can('edit_banner'))
        <form method="post" enctype="multipart/form-data"
              action="{{ route('dashboard.storeBanner', ['id' => $id, 'userIds' => $userIds]) }}">
            {{ csrf_field() }}
            @endif
            <input type="hidden" name="id" value="{{ $banner['id'] }}">
            <div class="row">
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">عنوان</label>
                    <input name="title" class="form-control col-md-7" required
                           value="{{ $banner['title'] }}"/>
                </div>
                <div class="row col-md-4">
                    <label class="col-md-5 col-form-label text-md-left">متن دکمه</label>
                    <input name="button_name" class="form-control col-md-7"
                           value="{{ $banner['button_name'] }}"/>
                </div>
                <div class="row col-md-4">
                    <label class="col-md-5 col-form-label text-md-left">لینک دکمه</label>
                    <input name="button_link" class="form-control col-md-7 ltr"
                           value="{{ $banner['button_link'] }}"/>
                </div>
            </div>
            <div class="row pt-2">
                <div class="row col-md-4">
                    <label for="image" class="col-md-5 col-form-label text-md-left">
                        عکس
                        <img src="{{
                            $banner['image_path'] ?
                            env('TANKHAH_URL') . '/storage/' .  $banner['image_path'] :
                            asset('img/no-img.png') }}" alt="" class="col-md-5"/>
                    </label>
                    <input type="file" accept="image/*" name="image" class="form-control col-md-7"
                           @if($id == 0) required @endif>
                </div>
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">تاریخ انقضا</label>
                    <input required style="direction: ltr;" value="{{ $banner['expire_at'] }}" id="date" class="form-control
                time_picker col-md-7" type="text"
                           name="expire_at">
                </div>
                <div class="col-md-4 row">
                    <label class="col-md-5 col-form-label text-md-left">زمان شروع</label>
                    <input required style="direction: ltr;" value="{{ $banner['start_at'] }}" id="date" class="form-control
                time_picker col-md-7" type="text"
                           name="start_at">
                </div>
            </div>
            @if(auth()->user()->can('edit_banner'))
                <div class="row justify-content-center pt-3">
                    <div class="col-md-2">
                        <input class="form-control btn btn-success" type="submit" value="ثبت">
                    </div>
                    @if($banner['id'] ?? false)
                        <div class="col-md-2">
                            <button class="form-control btn btn-danger">
                                <a href="{{ route('dashboard.deleteBanner', ['id' => $banner->id]) }}">حذف</a>
                            </button>
                        </div>
                    @endif

                </div>
        </form>
    @endif

    <script type="text/javascript">
    </script>
@endsection
