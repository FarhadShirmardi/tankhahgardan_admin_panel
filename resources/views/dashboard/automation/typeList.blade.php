@extends('dashboard.layouts.master')
@section('title')
    <i class="fa fa-bar-chart"></i>
    گزارش وضعیت
@endsection
@section('content')
    <div id="ajax-table" style="overflow-x: auto;">
        <table class="table">
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
                    style="background-color: @if($mapping['type'] == 'none') #F1F3F4 @elseif($mapping['type'] == 'call')
                        #FBE9E7 @else #DCE1FF @endif "
                    data-href="{{ route('dashboard.automation.typeItem', ['id' => $type]) }}">
                    <td class="text-center flex-column">
                        {{ $type }}
                        @if($mapping['type'] == 'call')
                            <i class="fa fa-phone">
                            </i>
                        @endif
                        @if($mapping['type'] != 'none')
                            <i class="fa fa-commenting">
                            </i>
                        @endif
                    </td>
                    <td>{{ $mapping['title'] }}</td>
                    <td>{{ $types->where('automation_state', $type)->first()->c ?? 0 }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('scripts')

@endsection

