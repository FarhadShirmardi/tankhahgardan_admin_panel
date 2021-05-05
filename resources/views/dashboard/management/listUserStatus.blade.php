<div id="ajax-table" style="overflow-x: auto;">
    <table class="table table-striped">
        <thead>
        <tr style="cursor: pointer;">
            <th>ردیف</th>
            <th>تاریخ شروع طرح</th>
            <th>تاریخ پایان طرح</th>
            <th>تعداد کاربر</th>
            <th>مقدار حجم</th>
            <th>طرح</th>
        </tr>
        </thead>
        <tbody>
        @foreach($user_statuses as $userStatus)
            <tr>
                @if($userStatus instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <td>{{ ($userStatus->currentPage() - 1) * $userStatus->perPage() + $loop->iteration }}</td>
                @else
                    <td>{{ $loop->iteration }}</td>
                @endif
                <td nowrap="nowrap" class="ltr text-right">{{ $userStatus->start_date }}</td>
                <td nowrap="nowrap" class="ltr text-right">{{ $userStatus->end_date }}</td>
                <td>{{ $userStatus->user_count }}</td>
                <td>{{ $userStatus->volume_size }}</td>
                <td>{{ \App\Constants\PremiumPrices::getPrice($userStatus->price_id)['title'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
