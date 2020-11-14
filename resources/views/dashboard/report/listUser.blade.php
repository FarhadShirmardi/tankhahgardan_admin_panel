<div id="ajax-table" style="overflow-x: auto;">
    <table class="table">
        <thead>
        <tr style="cursor: pointer;">
            <th>ردیف</th>
            <th onclick="sortTable('name')">نام و نام خانوادگی</th>
            <th onclick="sortTable('phone_number')">شماره تلفن</th>
            <th onclick="sortTable('registered_at')">تاریخ و ساعت ثبت نام</th>
            <th onclick="sortTable('max_time')">آخرین ثبت</th>
            <th onclick="sortTable('project_count')">تعداد کل پروژه</th>
            <th onclick="sortTable('own_project_count')">تعداد پروژه مالک</th>
            <th onclick="sortTable('not_own_project_count')">تعداد پروژه اشتراکی</th>
            <th onclick="sortTable('payment_count')">تعداد پرداخت</th>
            <th onclick="sortTable('receive_count')">تعداد دریافت</th>
            <th onclick="sortTable('note_count')">تعداد یادداشت</th>
            <th onclick="sortTable('imprest_count')">تعداد تنخواه</th>
            <th onclick="sortTable('file_count')">تعداد فایل‌ها</th>
            <th onclick="sortTable('image_count')">تعداد عکس‌ها</th>
            <th onclick="sortTable('image_size')">حجم عکس‌ها</th>
            <th onclick="sortTable('feedback_count')">تعداد بازخورد</th>
            <th onclick="sortTable('device_count')">تعداد دستگاه‌ها</th>
            <th onclick="sortTable('step_by_step')">گام به گام</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr class="clickableRow table-row-clickable"
                data-href="{{ route('dashboard.report.userActivity', ['id' => $user->id]) }}"
                style="background-color: {{ $colors[$user['user_type']][0] }}">
                <td>{{($users->currentPage() - 1) * $users->perPage() + $loop->iteration}}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->phone_number }}</td>
                <td>{{ \App\Helpers\Helpers::convertDateTimeToJalali($user->registered_at) }}</td>
                <td>{{ $user->max_time ? \App\Helpers\Helpers::convertDateTimeToJalali($user->max_time) : ' - ' }}</td>
                <td>{{ $user->project_count }}</td>
                <td>{{ $user->own_project_count }}</td>
                <td>{{ $user->not_own_project_count }}</td>
                <td>{{ $user->payment_count }}</td>
                <td>{{ $user->receive_count }}</td>
                <td>{{ $user->note_count }}</td>
                <td>{{ $user->imprest_count }}</td>
                <td>{{ $user->file_count }}</td>
                <td>{{ $user->image_count }}</td>
                <td>{{ $user->image_size ? round($user->image_size, 2) : ' - ' }}</td>
                <td>{{ $user->feedback_count }}</td>
                <td>{{ $user->device_count }}</td>
                <td>{{ $user->step_by_step }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
