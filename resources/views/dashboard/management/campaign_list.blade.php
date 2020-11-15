@if($campaigns instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $campaigns->appends(request()->input())->links() }}
@endif
<div id="ajax-table" style="overflow-x: auto;">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ردیف</th>
            <th>نام</th>
            <th>نماد</th>
            <th>تاریخ شروع</th>
            <th>تاریخ پایان</th>
            <th>تعداد</th>
            <th>کارشناس</th>
        </tr>
        </thead>
        <tbody>
        @foreach($campaigns as $campaign)
            <tr class="clickableRow table-row-clickable align-center"
                data-href="{{ $userIds ? route('dashboard.promoCodeItem', ['campaignId' => $campaign->id, 'id' => 0, 'userIds' => $userIds]) : route('dashboard.campaignItem', ['id' => $campaign->id]) }}" data-target="_self"
            >
                @if($campaigns instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <td>{{ ($campaigns->currentPage() - 1) * $campaigns->perPage() + $loop->iteration }}</td>
                @else
                    <td>{{ $loop->iteration }}</td>
                @endif
                <td>{{ $campaign->name }}</td>
                <td class="ltr">{{ $campaign->symbol }}</td>
                <td class="ltr text-right">{{ $campaign->start_date }}</td>
                <td class="ltr text-right">{{ $campaign->end_date }}</td>
                <td>{{ $campaign->count }}</td>
                <td>{{ $campaign->panel_user_name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($campaigns instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $campaigns->appends(request()->input())->links() }}
@endif
