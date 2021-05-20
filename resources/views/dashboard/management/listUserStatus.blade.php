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
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($user_statuses as $userStatus)
            <tr class="table-align-middle" @if($userStatus->is_active) style="background-color: #9bcbad" @endif>
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
                <td>
                    <div class="row">
                        @if($userStatus->is_active)
                            <div class="col-md-4">
                                <a href="">
                                    <i class="fa fa-remove"></i>
                                    اتمام
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('dashboard.premium.purchase', ['user_id' => $user->id, 'type' =>
                                \App\Constants\PurchaseType::UPGRADE, 'id' => $userStatus->id])
                                 }}">
                                    <i class="fa fa-user-plus"></i>
                                    ارتقا
                                </a>
                            </div>
                            <div class="col-md-4">
                                @if($userStatus->is_last_item)
                                    <a href="">
                                        <i class="fa fa-repeat"></i>
                                        تمدید
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
