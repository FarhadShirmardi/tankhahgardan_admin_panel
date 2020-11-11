<div id="ajax-table" style="overflow-x: auto;">
    <table class="table table-striped table-responsive">
        <thead>
        <tr style="cursor: pointer;">
            <th>ردیف</th>
            <th>پلتفرم</th>
            <th>سریال دستگاه</th>
            <th>مدل</th>
            <th>ورژن</th>
        </tr>
        </thead>
        <tbody>
        @foreach($devices as $device)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td nowrap="nowrap">{{ \App\Constants\Platform::getEnum($device->platform) }}</td>
                <td nowrap="nowrap">{{ $device->serial }}</td>
                <td nowrap="nowrap">{{ $device->model }}</td>
                <td nowrap="nowrap">{{ $device->os_version }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
