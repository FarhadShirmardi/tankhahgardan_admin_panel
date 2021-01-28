@extends('dashboard.layouts.master')

@section('title')
    <div class="row">
        <div class="col-md-6">
            <i class="fa fa-bar-chart"></i>
            وضعیت پروژه‌ها
        </div>
        <div class="col-md-6 ltr">
            @if(auth()->user()->hasRole('Admin'))
                <a href="{{ route('dashboard.generateReport') }}">
                    <i class="fa fa-refresh"></i>
                </a>
            @endif
        </div>
    </div>
@endsection
@section('filter')
    @include('dashboard.layouts.link_message')
    <form id="filter" method="get" action="">
        {{ csrf_field() }}
        <div class="row pt-5 justify-content-center">
            <div class="col-md-4">
                <table class="table table-bordered table-responsive">
                    <tr class="text-center">
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
            <div class="col-md-4">
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left">جستجوی نام</label>
                    <input type="text" id="name" name="name" value="{{$filter['name']}}"
                           placeholder="جستجوی نام" class="form-control col-md-7">
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <label class="col-md-4 col-form-label text-md-left">شهر و استان</label>
                    <div class="col-md-4">
                        <select style="width: 100%" id="state_id" name="state_id" onchange="changeSelect();"
                                onload="changeSelect();">
                            @foreach($states as $state)
                                <option value="{{$state['id']}}"
                                        @if($filter['state_id'] == $state['id']) selected @endif>
                                    {{$state['name']}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select style="width: 100%" id="city_id" name="city_id"></select>
                    </div>
                </div>
            </div>
            <input type="hidden" name="page" value="1"/>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4 justify-content-center">
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left">مرتب‌سازی</label>
                    <select id="sort_field" name="sort_field">
                        @foreach($sortable_fields as $key => $sortable_field)
                            <option @if ($key == $filter['sort_field']) selected
                                    @endif value="{{ $key }}">{{ $sortable_field }}</option>
                        @endforeach
                    </select>
                    <select id="sort_type" name="sort_type">
                        @foreach($sortable_types as $key => $sortable_type)
                            <option @if ($key == $filter['sort_type']) selected
                                    @endif value="{{ $key }}">{{ $sortable_type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4 row">
            </div>
        </div>
        <div class="row pb-5 pt-2 justify-content-center">
            <input class="btn btn-info pt-2" type="submit" value="اعمال فیلتر">
            @if(auth()->user()->hasRole('Admin'))
                <input class="btn btn-warning mr-2" type="button" value="فایل خروجی" onclick="exportClick()">
            @endif
        </div>
    </form>
@endsection
@section('content')
    <div>{{ $projects->appends(request()->input())->links() }}</div>
    <div id="ajax-table">
        <table class="table table-responsive">
            <thead>
            <tr style="cursor: pointer;">
                <th>ردیف</th>
                <th onclick="sortTable('name')">نام پروژه</th>
                <th>استان</th>
                <th>شهر</th>
                <th onclick="sortTable('type')">نوع پروژه</th>
                <th onclick="sortTable('created_at')">تاریخ ایجاد پروژه</th>
                <th onclick="sortTable('max_time')">آخرین ثبت</th>
                <th onclick="sortTable('user_count')">تعداد کل کاربران</th>
                <th onclick="sortTable('active_user_count')">کاربران فعال پروژه</th>
                <th onclick="sortTable('not_active_user_count')">کاربران غیرفعال</th>
                <th onclick="sortTable('payment_count')">تعداد پرداخت</th>
                <th onclick="sortTable('receive_count')">تعداد دریافت</th>
                <th onclick="sortTable('note_count')">تعداد یادداشت</th>
                <th onclick="sortTable('imprest_count')">تعداد تنخواه</th>
            </tr>
            </thead>
            <tbody>
            @foreach($projects as $project)
                <tr class="clickableRow table-row-clickable"
                    data-href="{{ route('dashboard.report.projectActivity', ['id' => $project->id]) }}"
                    style="background-color: {{ $colors[$project['project_type']][0] }}">
                    <td>{{($projects->currentPage() - 1) * $projects->perPage() + $loop->iteration}}</td>
                    <td>{{ $project->name }}</td>
                    <td>{{ $states->firstWhere('id', $project->state_id)['name'] }}</td>
                    <td>{{ $cities->firstWhere('id', $project->city_id)['name'] }}</td>
                    <td>{{ $project->type ? \App\Constants\ProjectTypes::getProjectType($project->type)['text'] : '' }}</td>
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
@section('scripts')
    <script>

        function changeUserType(projectType) {
            var input = document.getElementById('projectType');
            input.value = projectType;

            document.getElementById('filter').submit();
        }

        $('#state_id').select2({
            width: 'element',
        });

        $('#city_id').select2({
            width: 'element',
        });

        $('#sort_field').select2({
            width: 'element',
        });

        $('#sort_type').select2({
            width: 'element',
        });


        $(document).ready(function () {
            changeSelect();
        });

        function changeProjectType(projectType) {
            var input = document.getElementById('projectType');
            input.value = projectType;

            document.getElementById('filter').submit();
        }

        function removeOptions(selectElement) {
            var i, L = selectElement.options.length - 1;
            for (i = L; i >= 0; i--) {
                selectElement.remove(i);
            }
        }

        function changeSelect() {
            let cities = JSON.parse('{!! json_encode($cities) !!}');
            var stateSelect = document.getElementById('state_id');
            var citySelect = document.getElementById('city_id');
            removeOptions(citySelect);
            for (i in cities) {
                if (cities[i]['state_id'] == stateSelect.value) {
                    var opt = document.createElement("option");
                    opt.value = cities[i]['id'];
                    opt.textContent = cities[i]['name'];

                    // then append it to the select element
                    citySelect.appendChild(opt);
                }
            }
        }

        function exportClick() {
            var form = document.getElementById('filter');
            form.action = '{{ route('dashboard.report.export.allProjectsActivity') }}';

            document.getElementById('filter').submit();
            form.action = '';
        }

    </script>
@endsection
