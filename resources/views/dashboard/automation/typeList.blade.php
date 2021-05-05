@extends('dashboard.layouts.master')
@section('title')
    <i class="fa fa-bar-chart"></i>
    گزارش وضعیت
@endsection
@section('content')
    <div id="ajax-table" style="overflow-x: auto;">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>نوع</th>
                <th>شرح</th>
                <th>تعداد</th>
            </tr>
            </thead>
            <tbody>
            @foreach($mappings as $type => $mapping)
                <tr class="clickableRow table-row-clickable"
                    data-href="{{ route('dashboard.automation.typeItem', ['id' => $type]) }}">
                    <td class="ltr">{{ $type }}</td>
                    <td>{{ $mapping }}</td>
                    <td>{{ $types->where('automation_state', $type)->first()->c ?? 0 }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('scripts')

@endsection

