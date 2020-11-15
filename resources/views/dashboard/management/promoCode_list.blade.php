@if($promoCodes instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $promoCodes->appends(request()->input())->links() }}
@endif
<div id="ajax-table" style="overflow-x: auto;">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ردیف</th>
            <th>متن</th>
            <th>کد</th>
            <th>نام کاربر</th>
            <th>شماره کاربر</th>
            <th>درصد تخفیف</th>
            <th>سقف تخفیف</th>
            <th>تعداد</th>
            <th>تعداد استفاده شده</th>
            <th>تاریخ شروع</th>
            <th>تاریخ پایان</th>
            <th>کارشناس</th>
        </tr>
        </thead>
        <tbody>
        @foreach($promoCodes as $promoCode)
            <tr class="clickableRow table-row-clickable align-center"
                data-target="_self"
                data-href="{{ route('dashboard.promoCodeItem', ['campaignId' => $promoCode->campaign_id, 'id' => $promoCode->id, 'userIds' => (int)$promoCode->user_id]) }}"
            >
                @if($promoCodes instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <td>{{ ($promoCodes->currentPage() - 1) * $promoCodes->perPage() + $loop->iteration }}</td>
                @else
                    <td>{{ $loop->iteration }}</td>
                @endif
                <td>{{ $promoCode->text ?? '-' }}</td>
                <td>{{ $promoCode->code }}</td>
                <td>{{ $promoCode->name ?? '-' }}</td>
                <td nowrap="nowrap" class="ltr">{{ $promoCode->phone_number ?? '-' }}</td>
                <td>{{ $promoCode->discount_percent }}</td>
                <td>{{ $promoCode->max_discount ?? '-' }}</td>
                <td>{{ $promoCode->max_count }}</td>
                <td>{{ $promoCode->used_promo_code_count }}</td>
                <td class="ltr text-right">{{ $promoCode->start_at }}</td>
                <td class="ltr text-right">{{ $promoCode->expire_at }}</td>
                <td>{{ $promoCode->panel_user_name ?? 'سیستم' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@if($promoCodes instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $promoCodes->appends(request()->input())->links() }}
@endif
