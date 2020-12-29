<div id="ajax-table" style="overflow-x: auto;">
    <table class="table table-striped">
        <thead>
        <tr style="cursor: pointer;">
            <th>ردیف</th>
            <th onclick="sortTable('full_name')">نام کاربر</th>
            <th onclick="sortTable('phone_number')">شماره کاربر</th>
            <th onclick="sortTable('date')">تاریخ پرداخت</th>
            <th onclick="sortTable('bank_ref')">شماره تراکنش بانک</th>
            <th onclick="sortTable('mapsa_ref')">شماره تراکنش مپسا</th>
            <th onclick="sortTable('start_date')">تاریخ شروع طرح</th>
            <th onclick="sortTable('end_date')">تاریخ پایان طرح</th>
            <th onclick="sortTable('user_count')">تعداد کاربر</th>
            <th onclick="sortTable('volume_size')">مقدار حجم</th>
            <th onclick="sortTable('type')">نوع تراکنش</th>
            <th onclick="sortTable('payable_amount')">مبلغ قابل پرداخت</th>
            <th onclick="sortTable('total_amount')">مبلغ کل</th>
            <th onclick="sortTable('discount_amount')">مقدار تخفیف</th>
            <th onclick="sortTable('added_value_amount')">ارزش افزوده</th>
            <th onclick="sortTable('wallet_amount')">کیف پول</th>
            <th onclick="sortTable('state')">وضعیت</th>
            <th onclick="sortTable('bank')">بانک</th>
        </tr>
        </thead>
        <tbody>
        @foreach($transactions as $transaction)
            <tr class="clickableRow table-row-clickable align-center"
                data-href="{{ route('dashboard.report.userActivity', ['id' => $transaction->user_id]) }}"
            >
                @if($transactions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <td>{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}</td>
                @else
                    <td>{{ $loop->iteration }}</td>
                @endif
                <td>{{ $transaction->full_name }}</td>
                <td nowrap="nowrap" class="ltr">{{ $transaction->phone_number }}</td>
                <td nowrap="nowrap" class="ltr">{{ $transaction->date }}</td>
                <td nowrap="nowrap" class="ltr">{{ $transaction->bank_ref }}</td>
                <td nowrap="nowrap" class="ltr">{{ $transaction->mapsa_ref }}</td>
                <td nowrap="nowrap" class="ltr">{{ $transaction->start_date }}</td>
                <td nowrap="nowrap" class="ltr">{{ $transaction->end_date }}</td>
                <td>{{ $transaction->user_count }}</td>
                <td>{{ $transaction->volume_size }}</td>
                <td>{{ $transaction->type }}</td>
                <td>{{ \App\Helpers\Helpers::formatNumber($transaction->payable_amount) }}</td>
                <td>{{ \App\Helpers\Helpers::formatNumber($transaction->total_amount) }}</td>
                <td>{{ \App\Helpers\Helpers::formatNumber($transaction->discount_amount) }}</td>
                <td>{{ \App\Helpers\Helpers::formatNumber($transaction->added_value_amount) }}</td>
                <td>{{ \App\Helpers\Helpers::formatNumber($transaction->wallet_amount) }}</td>
                <td>{{ $transaction->state }}</td>
                <td>{{ $transaction->bank }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
