<div id="ajax-table" style="overflow-x: auto;">
    <table class="table">
        <thead>
        <tr style="cursor: pointer;">
            <th>ردیف</th>
            <th onclick="sortTable('name')">نام و نام خانوادگی</th>
            <th onclick="sortTable('phone_number')">شماره تلفن</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr class="clickableRow table-row-clickable"
                data-href="{{ route('dashboard.admin.user_item', ['id' => $user->id]) }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->phone_number }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
