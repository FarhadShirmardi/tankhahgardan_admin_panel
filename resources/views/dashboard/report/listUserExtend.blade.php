<div id="ajax-table" style="overflow-x: auto;">
    <table class="table">
        <thead>
        <tr>
            <th>ردیف</th>
            <th>شماره تلفن</th>
            <th>نام و نام خانوادگی</th>
            <th>آخرین طرح</th>
            <th>تعداد یوزر</th>
            <th>مقدار حجم</th>
            <th>روز گذشته</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr class="clickableRow table-row-clickable"
                data-href="{{ route('dashboard.report.userActivity', ['id' => $user['id']]) }}">
                <td>{{($users->currentPage() - 1) * $users->perPage() + $loop->iteration}}</td>
                <td>{{ $user['phone_number'] }}</td>
                <td>{{ $user['username'] }}</td>
                <td>{{ $user['price'] }}</td>
                <td>{{ $user['user_count'] }}</td>
                <td>{{ $user['volume_size'] }}</td>
                <td>{{ $user['days'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
