@extends('dashboard.layouts.master')

@section('content')
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> کاربران غیرفعال
                        @switch($step)
                            @case(1)
                                24 ساعت گذشته
                                @break
                        @endswitch
                    </div>
                    <div class="card-body">
                        @include('dashboard.layouts.success_message')
                        <div class="top-list-action-button row mb-3">
                            <div class="col-sm-12">
                                <form method="GET" action="#">
                                    <input name="search" type="text"
                                           class="form-control" id="search-txt"
                                           placeholder="جستجو...">
                                </form>
                            </div>
                        </div>
                        <div id="ajax-table">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>ID</th>
                                    <th>نام</th>
                                    <th>تلفن</th>
                                    <th>تاریخ ثبت نام</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $item)
                                    <tr id="table-row-clickable{{ $item->id }}" class="table-row-clickable">
                                        <td>{{($data->currentPage() - 1) * $data->perPage() + $loop->iteration}}</td>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name . ' ' . $item->family }}</td>
                                        <td>{{ $item->phone_number }}</td>
                                        @php
                                            $userCreatedAtArr = explode(' ', $item->user_created_at);
                                            $jalaliUserCreatedAtDate = \App\Helpers\Helpers::gregorianDateStringToJalali($userCreatedAtArr[0]);
                                        @endphp
                                        <td>{{ $jalaliUserCreatedAtDate.' '.$userCreatedAtArr[1] }}</td>
                                    </tr>
                                    <script type="text/javascript">
                                        $().ready(function () {
                                            $('#table-row-clickable{{ $item->id }}').on('click', function () {
                                                window.location = "{{ route('dashboard.users.activation.show', ['userId' => $item->id]) }}";
                                            });
                                        });
                                    </script>
                                @endforeach
                                </tbody>
                            </table>
                            @if($data instanceof \Illuminate\Pagination\LengthAwarePaginator )
                                {{ $data->appends(request()->input())->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!--/.col-->
        </div>
        <!--/.row-->
    </div>
@endsection
