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
                    @if(auth()->user()->can('edit_premium'))
                        @if($userStatus->is_active)
                            <div class="row">
                                <div class="col-md-4">
                                    <a href="#here" data-toggle="modal" data-target="#closePremiumModal">
                                        <i class="fa fa-remove"></i>
                                        اتمام
                                    </a>
                                    <div class="modal fade" id="closePremiumModal" tabindex="-1" role="dialog"
                                         aria-labelledby="closePremiumModalLabel" aria-hidden="true">
                                        <form action="{{ route('dashboard.premium.closePlan', ['user_id' =>
                                        $userStatus->user_id, 'id' => $userStatus->id])
                                        }}"
                                              method="post">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content modal-dialog-centered">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">اتمام طرح</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{--                                                    @dd($userStatus)--}}
                                                        با لغو طرح مبلغ {{ \App\Helpers\Helpers::formatNumber
                                                    ($userStatus->payable_amount) }} ریال به کاربر برگشت داده می‌شود.
                                                        <div class="row pt-4">
                                                            <label class="col-md-5 form-control-label text-md-left" for="text">
                                                                نوع برگشت
                                                            </label>
                                                            <select name="type" class="col-md-5 form-control">
                                                                <option value="wallet">برگشت به کیف پول</option>
                                                                <option value="card">کارت به کارت</option>
                                                            </select>
                                                        </div>
                                                        <div class="row pl-2 pt-2 pr-2">
                                                            <label class="col-md-5 form-control-label text-md-left" for="text">
                                                                توضیحات
                                                            </label>
                                                            <textarea required name="text" id="text" class="form-control col-md-7"></textarea>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-danger"
                                                                data-dismiss="modal">انصراف
                                                        </button>

                                                        @csrf
                                                        <input class="btn btn-success" value="تایید" type="submit">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('dashboard.premium.purchase', ['user_id' => $user->id, 'type' =>
                                \App\Constants\PurchaseType::UPGRADE, 'id' => $userStatus->id])
                                 }}">
                                        <i class="fa fa-user-plus"></i>
                                        ارتقا
                                    </a>
                                </div>
                                @if($userStatus->is_last_item)
                                    <div class="col-md-4">
                                        <a href="{{ route('dashboard.premium.purchase', ['user_id' => $user->id, 'type' =>
                                \App\Constants\PurchaseType::EXTEND, 'id' => $userStatus->id])
                                 }}">
                                            <i class="fa fa-repeat"></i>
                                            تمدید
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
