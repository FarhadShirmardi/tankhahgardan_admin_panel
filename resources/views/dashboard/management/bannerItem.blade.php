@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-images"></i>
    @if($id)
        ویرایش بنر
    @else
        افزودن بنر
    @endif
@endsection
@section('filter')
@endsection
@section('content')
    <form method="post" enctype="multipart/form-data"
          action="{{ route('dashboard.storeBanner', ['id' => $id]) }}">
        {{ csrf_field() }}
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
                            env('MAPSA_URL') . '/storage/' .  $banner['image_path']
                            ?? asset('img/no-img.png') }}" alt="" class="col-md-5"/>
                </label>
                <input type="file" accept="image/*" name="image" class="form-control col-md-7"
                       @if($id == 0) required @endif>
            </div>
            <div class="row col-md-4">
                <label class="col-md-5 form-check-inline text-md-left">
                    وضعیت
                    <input name="is_active" type="checkbox" class="form-check-input text-md-right"
                           @if($banner['is_active']) checked @endif/>
                </label>
                <div class="col-md-7">

                </div>
            </div>
        </div>
        <div class="row justify-content-center pt-3">
            <div class="col-md-2">
                <input class="form-control btn btn-success" type="submit" value="ثبت">
            </div>
        </div>
    </form>

    <script type="text/javascript">
    </script>
@endsection
