@extends('dashboard.layouts.master')
@section('title')
    <i class="fa fa-phone"></i>
    افزودن تماس -  @include('dashboard.layouts.username')
@endsection
@section('content')

    <form id="filter" method="POST" action="{{ route('dashboard.automation.newCall', ['userId' => $user->id, 'id' =>
    $id])
    }}">
        {{ csrf_field() }}
        <div class="pb-5 pt-5 justify-content-center">
            <div class="row">
                <div class="col-md-2 row"></div>
                <div class="col-md-6 row">
                    <label class="col-md-5 col-form-label text-md-left">متن</label>
                    <div class="ms-list col-md-7">
                        <textarea name="text" id="text" cols="50" rows="10">{{ $call->text ?? '' }}</textarea>
                    </div>
                </div>
            </div>
            <div class="pt-5 pb-3 justify-content-center align-center row">
                <div class="col-md-2">
                    @if($id == 0)
                        <input class="form-control btn btn-success" type="submit" value="افزودن">
                    @else
                        <input class="form-control btn btn-warning" type="submit" value="ویرایش">
                    @endif
                </div>
            </div>
        </div>
    </form>
@endsection
@section('scripts')

@endsection

