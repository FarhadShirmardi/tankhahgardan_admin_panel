@extends('dashboard.layouts.master')

@section('title')
    <i class="fa fa-bar-chart"></i>
    وضعیت پروژه‌ها
@endsection
@section('filter')
    <form id="filter" method="get" action="">
        {{ csrf_field() }}
        <div class="row pt-5 justify-content-center">
            <div class="col-md-3">
                <table>
                    <tr>
                        <input id="projectType" type="hidden" value="{{ $filter['project_type'] }}" name="project_type">
                        <td style="border: solid black 1px;">
                            <div onclick="changeUserType({{0}})"
                                 style="cursor: pointer;">همه
                            </div>
                        </td>
                        @foreach($colors as $key => $color)
                            <td style="background-color: {{$color[0]}}">
                                <div onclick="changeUserType({{$key}})"
                                     style="cursor: pointer;">{{$color[1]}}</div>
                            </td>
                        @endforeach
                    </tr>
                </table>
            </div>
            <div class="col-md-3">
                <div class="pr-2">
                    <label>جستجوی نام</label>
                    <input type="text" id="name" name="name" value="{{$filter['name']}}"
                           placeholder="جستجوی نام">
                </div>
            </div>
            <div class="col-md-3">
                <div class="pr-2">
                    <label>شهر و استان</label>
                    <select id="state_id" name="state_id" onchange="changeSelect();" onload="changeSelect();">
                        @foreach($states as $state)
                            <option value="{{$state['id']}}" @if($filter['state_id'] == $state['id']) selected @endif>
                                {{$state['name']}}
                            </option>
                        @endforeach
                    </select>
                    <select id="city_id" name="city_id">
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="pr-2">
                    <label>مرتب‌سازی</label>
                    <select id="sort_field" name="sort_field">
                        @foreach($sortable_fields as $key => $sortable_field)
                            <option @if ($key == $filter['sort_field']) selected @endif value="{{ $key }}">{{ $sortable_field }}</option>
                        @endforeach
                    </select>
                    <select id="sort_type" name="sort_type">
                        @foreach($sortable_types as $key => $sortable_type)
                            <option value="{{ $key }}">{{ $sortable_type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <input type="hidden" name="page" value="1"/>

        </div>
        <div class="row pb-5 justify-content-center">
            <input class="btn btn-info pt-2" type="submit" value="اعمال فیلتر">
        </div>
    </form>
@endsection
@section('content')
    <div>{{ $projects }}</div>
    <div id="ajax-table">
        <table class="table">
            <thead>
            <tr>
                <th>ردیف</th>
                <th>نام پروژه</th>
                <th>استان</th>
                <th>شهر</th>
                <th>تاریخ ایجاد پروژه</th>
                <th>آخرین ثبت</th>
                <th>تعداد کل کاربران</th>
                <th>کاربران فعال پروژه</th>
                <th>کاربران غیرفعال</th>
                <th>تعداد پرداخت</th>
                <th>تعداد دریافت</th>
                <th>تعداد یادداشت</th>
                <th>تعداد تنخواه</th>
            </tr>
            </thead>
            <tbody>
            @foreach($projects as $project)
                <tr style="background-color: {{ $colors[$project['project_type']][0] }}">
                    <td>{{($projects->currentPage() - 1) * $projects->perPage() + $loop->iteration}}</td>
                    <td>{{ $project->name }}</td>
                    <td>{{ $states->firstWhere('id', $project->state_id)['name'] }}</td>
                    <td>{{ $cities->firstWhere('id', $project->city_id)['name'] }}</td>
                    <td>{{ \App\Helpers\Helpers::convertDateTimeToJalali($project->created_at) }}</td>
                    <td>{{ $project->max_time ? \App\Helpers\Helpers::convertDateTimeToJalali($project->max_time) : ' - ' }}</td>
                    <td>{{ $project->user_count }}</td>
                    <td>{{ $project->active_user_count }}</td>
                    <td>{{ $project->not_active_user_count }}</td>
                    <td>{{ $project->payment_count }}</td>
                    <td>{{ $project->receive_count }}</td>
                    <td>{{ $project->note_count }}</td>
                    <td>{{ $project->imprest_count }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $projects }}
    <div id="dateChart" style="width:100%;"></div>
@endsection
@section('chart')
    @include('dashboard.report.charts.allUserActivity')
@endsection

<script>

    window.onload = function () {
        changeSelect();
    };

    function changeProjectType(projectType) {
        var input = document.getElementById('projectType');
        input.value = projectType;

        document.getElementById('filter').submit();
    }

    function removeOptions(selectElement) {
        var i, L = selectElement.options.length - 1;
        for(i = L; i >= 0; i--) {
            selectElement.remove(i);
        }
    }

    function changeSelect() {
        let cities = JSON.parse('{!! json_encode($cities) !!}');
        var stateSelect = document.getElementById('state_id');
        var citySelect = document.getElementById('city_id');
        removeOptions(citySelect);
        for (i in cities) {
            if(cities[i]['state_id'] == stateSelect.value) {
                var opt = document.createElement("option");
                opt.value= cities[i]['id'];
                opt.textContent= cities[i]['name'];

                // then append it to the select element
                citySelect.appendChild(opt);
            }
        }
    }

</script>
