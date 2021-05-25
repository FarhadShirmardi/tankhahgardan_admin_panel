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
            <th>وضعیت</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoices as $invoice)
            <tr class="table-align-middle" style="background-color: @if($invoice->status ==
            \App\Constants\UserStatusType::SUCCEED) #9bcbad @elseif($invoice->status ==
            \App\Constants\UserStatusType::FAILED) #dd9b7f @else #c1e7f4 @endif">
                @if($invoices instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <td>{{ ($invoice->currentPage() - 1) * $invoice->perPage() + $loop->iteration }}</td>
                @else
                    <td>{{ $loop->iteration }}</td>
                @endif
                <td nowrap="nowrap" class="ltr text-right">{{ $invoice->start_date }}</td>
                <td nowrap="nowrap" class="ltr text-right">{{ $invoice->end_date }}</td>
                <td>{{ $invoice->user_count }}</td>
                <td>{{ $invoice->volume_size }}</td>
                <td>{{ \App\Constants\PremiumPrices::getPrice($invoice->price_id)['title'] }}</td>
                <td>{{ \App\Constants\UserStatusType::getEnum($invoice->status) }}</td>
                <td>
                    @if($invoice->status == \App\Constants\UserStatusType::PENDING)
                        <div class="row">
                            <div class="col-md-4">
                                <a href="{{ route('dashboard.premium.deleteInvoice', ['user_id' => $user->id, 'id' =>
                                $invoice->id]) }}">
                                    <i class="fa fa-remove"></i>
                                    حذف
                                </a>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <a href="#here" data-toggle="modal" data-target="#exampleModal">
                                        <i class="fa fa-paypal"></i>
                                        پرداخت
                                    </a>
                                </div>
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content modal-dialog-centered">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">پرداخت پیش‌فاکتور</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                پرداخت پیش‌فاکتور به مبلغ {{
                                                \App\Helpers\Helpers::formatNumber(\App\Helpers\Helpers::getPayableAmount
                                                ($invoice->total_amount,
                                                $invoice->added_value_amount, $invoice->discount_amount,
                                                $invoice->wallet_amount)) }} را تایید می‌کنید؟
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-danger"
                                                        data-dismiss="modal">انصراف
                                                </button>
                                                <a href="{{ env('TANKHAH_URL')  . '/panel/' .
                                     env('TANKHAH_TOKEN') . '/invoices/' . $invoice['id'] . '/' }}" class="btn
                                     btn-success">تایید</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <a id="paymentLink" onclick="copyClipboard('{{ env('TANKHAH_URL')  . '/panel/' .
                                     env('TANKHAH_TOKEN') . '/invoices/' . $invoice['id'] }}')" href="">
                                        <i class="fa fa-copy"></i>
                                        تمدید
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">--}}
{{--    Launch demo modal--}}
{{--</button>--}}
