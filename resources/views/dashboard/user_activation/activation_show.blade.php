@extends('dashboard.layouts.master')

@section('content')
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <form method="POST" action="{{ route('dashboard.users.activation.call.update', ['userId' => $data->id]) }}">
                        {{ method_field(\Illuminate\Http\Request::METHOD_PUT) }}

                        <div class="card-header">
                            ثبت تماس با کاربر
                        </div>

                        <div class="card-body">
                            @include('dashboard.layouts.error_message')

                            <div class="form-group row">
                                <div class="col-3">نام کاربر:</div>
                                <div class="col-9">{{ $data->name }}</div>
                            </div>

                            <div class="form-group row">
                                <div class="col-3">تلفن:</div>
                                <div class="col-9">{{ $data->phone_number }}</div>
                            </div>

                            <div class="form-group row">
                                <div class="col-3">وضعیت کاربر:</div>
                                <div class="col-9">
                                    {{ \App\Constants\UserActivationConstant::getStates($data->state)['name'] }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-3">
                                    <label for="category-name">تاریخ تماس:</label>
                                </div>
                                <div class="col-9">
                                    <input name="call_date_time" type="text" class="form-control" id="call-datetime" value="">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-3">
                                    <label for="category-name">متن:</label>
                                </div>
                                <div class="col-9">
                                    <textarea name="description" type="text" class="form-control" id="description" value=""></textarea>
                                </div>
                            </div>
                            {{ csrf_field() }}
                        </div>
                        <div class="card-footer">
                            <button type="ارسال" class="btn btn-sm btn-primary"><i
                                    class="fa fa-dot-circle-o"></i> ارسال
                            </button>
                        </div>
                    </form>
                </div>

            </div>
            <!--/.col-->
        </div>
        <!--/.row-->
    </div>

    <script type="text/javascript">
        $().ready(function () {
            $('#call-datetime').pDatepicker({
                format: 'YYYY/M/D H:mm:ss',
                initialValue: true,
                inline: false,
                autoClose: true,
                timePicker: {
                    enabled: true,
                },
            });
        });
    </script>

@endsection
