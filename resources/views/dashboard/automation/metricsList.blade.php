<div id="ajax-table" style="overflow-x: auto;">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ردیف</th>
            <th onclick="sortTable('date')">تاریخ</th>
            @foreach($metricKeys as $key)
                <th style="direction: ltr;" onclick="sortTable({{$key}})
                    "><p>{{$key}}</p></th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($metrics as $metric)
            <tr>
                @if(!$metrics instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <td>{{ $loop->iteration }}</td>
                @else
                    <td>{{($metrics->currentPage() - 1) * $metrics->perPage() + $loop->iteration}}</td>
                @endif
                <td>{{ $metric->date }}</td>
                @foreach($metricKeys as $key)
                    <td>{{$metric['metric'][$key]}}</td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
