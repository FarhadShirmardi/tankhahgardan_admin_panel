<div>
    <div class="row">
        <div class="col-md-4 row">
            @if(auth()->user()->can('edit_premium'))
                <div class="col-md-6">
                    <a class="form-control btn btn-info"
                       href="{{ route('dashboard.premium.wallet', ['user_id' => $user->id]) }}">کیف پول</a>
                </div>
                @if($user_statuses->where('is_active', true)->count() == 0)
                    <div class="col-md-6">
                        <a class="form-control btn btn-success"
                           href="{{ route('dashboard.premium.purchase', ['user_id' => $user->id, 'type' =>
                                \App\Constants\PurchaseType::NEW, 'id' => 0]) }}">ایجاد طرح</a>
                    </div>
                @endif
            @endif
        </div>
        <div class="col-md-4"><h5 class="text-center pb-3">طرح‌های کاربر</h5></div>
        <div class="col-md-4"></div>
    </div>
    <div wire:init="loadUserStatuses">
        @if($readyToLoad)
            @include('dashboard.management.listUserStatus')
        @else
            @include('dashboard.layouts.loading')
        @endif
    </div>
</div>
