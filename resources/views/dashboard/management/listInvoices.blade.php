<div id="ajax-table" style="overflow-x: auto;">
    <table class="table table-striped">
        <thead>
        <tr style="cursor: pointer;">
            <th>ردیف</th>
            <th>تاریخ شروع طرح</th>
            <th>تاریخ پایان طرح</th>
            <th>تعداد کاربر</th>
            <th>مقدار حجم</th>
            <th>مبلغ</th>
            <th>نوع</th>
            <th>طرح</th>
            <th>وضعیت</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoices as $invoice)
            <tr class="table-align-middle" style="background-color: @if($invoice->status ==
            \App\Constants\UserStatusType::SUCCEED) #9bcbad @elseif($invoice->status ==
            \App\Constants\UserStatusType::FAILED) #fdbda1 @else #c1e7f4 @endif">
                @if($invoices instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <td>{{ ($invoice->currentPage() - 1) * $invoice->perPage() + $loop->iteration }}</td>
                @else
                    <td>{{ $loop->iteration }}</td>
                @endif
                <td nowrap="nowrap" class="ltr text-right">{{ \App\Helpers\Helpers::convertDateTimeToJalali
                ($invoice->start_date) }}</td>
                <td nowrap="nowrap" class="ltr text-right">{{ \App\Helpers\Helpers::convertDateTimeToJalali
                ($invoice->end_date) }}</td>
                <td>{{ $invoice->user_count }}</td>
                <td>{{ $invoice->volume_size }}</td>
                <td>{{ \App\Helpers\Helpers::formatNumber(\App\Helpers\Helpers::getPayableAmount
                ($invoice->total_amount, $invoice->added_value_amount, $invoice->discount_amount,
                $invoice->wallet_amount)) }}</td>
                <td>{{ \App\Constants\PurchaseType::getEnum($invoice->type) }}</td>
                <td>{{ \App\Constants\PremiumPrices::getPrice($invoice->price_id)['title'] }}</td>
                <td>{{ \App\Constants\UserStatusType::getEnum($invoice->status) }}</td>
                <td>
                    @if($invoice->status == \App\Constants\UserStatusType::PENDING)
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <a href="{{ route('dashboard.premium.deleteInvoice', ['user_id' => $user->id, 'id' =>
                                $invoice->id]) }}">
                                        <i class="fa fa-remove"></i>
                                        حذف
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <a href="#here" data-toggle="modal" data-target="#exampleModal">
                                        <i class="fa fa-credit-card"></i>
                                        پرداخت
                                    </a>
                                </div>
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <form action="{{ route('dashboard.premium.payInvoice', ['user_id' =>
                                                $invoice->user_id, 'id' =>
                                                $invoice->id]) }}" method="post">
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
                                                $invoice->wallet_amount)) }} ریال را تایید می‌کنید؟<br>
                                                    <div class="row pl-2 pt-4 pr-2">
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
                                <div class="row">
                                    <a id="paymentLink" onclick="copyClipboard('{{ config('app.tankhah_url')  .
                                    '/api/v2/invoice/' . $invoice['id'] . '/preview' }}')" href="#">
                                        <i class="fa fa-copy"></i>
                                        لینک پرداخت
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
