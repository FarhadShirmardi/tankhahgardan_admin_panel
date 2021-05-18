@extends('dashboard.layouts.master')
@section('title')
    <i class="fa fa-file"></i>
    فایل‌ها
@endsection
@section('content')
    <div id="ajax-table">
        <table class="table table-striped">
            <thead>
            <tr style="cursor: pointer;">
                <th>ردیف</th>
                <th>شرح</th>
                <th>زمان گزارش</th>
                <th>دانلود</th>
            </tr>
            </thead>
            <tbody>
            @foreach($files as $file)
                <tr>
                    <td>{{ ($files->currentPage() - 1) * $files->perPage() + $loop->iteration}}</td>
                    <td>{{ $file->description }}</td>
                    <td>{{ \App\Helpers\Helpers::convertDateTimeToJalali($file->date_time) }}</td>
                    <td>
                        <a target="_blank" href="{{ route('dashboard.downloadFile', ['id' => $file->id]) }}">
                            <i class="fa fa-download"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('scripts')


@endsection
