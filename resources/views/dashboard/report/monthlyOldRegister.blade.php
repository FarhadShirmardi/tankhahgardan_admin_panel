<table style="border: 1px solid black; text-align: center" class="table table-bordered table-striped">
    @foreach(\App\Constants\Platform::toArray() as $platform)
        <tr>
            <td colspan="72">{{ \App\Constants\Platform::getEnum($platform) }}</td>
        </tr>
        <tr>
            <td colspan="72">{{ $data->where('platform', $platform)->sum('count') }}</td>
        </tr>


        <tr>
            @foreach([1, 0] as $isPremium)
                <td colspan="{{$isPremium ? 60 : 12}}">{{ $isPremium ? 'حرفه‌ای' : 'رایگان' }}</td>
            @endforeach
        </tr>
        <tr>
            @foreach([1, 0] as $isPremium)
                <td colspan="{{$isPremium ? 60 : 12}}">{{ $data->where('platform', $platform)
                            ->where('is_premium', $isPremium)->sum('count') }}</td>
            @endforeach
        </tr>


        <tr>
            @foreach([1, 0] as $isPremium)
                @foreach($isPremium ? $prices : [0] as $price)
                    <td colspan="12">{{ \App\Constants\PremiumDuration::getTitle($price) }}</td>
                @endforeach
            @endforeach
        </tr>
        <tr>
            @foreach([1, 0] as $isPremium)
                @foreach($isPremium ? $prices : [0] as $price)
                    <td colspan="12">
                        {{ $data->where('platform', $platform)
                                ->where('is_premium', $isPremium)
                                ->where('price', $price)
                                ->sum('count')
                                }}
                    </td>
                @endforeach
            @endforeach
        </tr>


        <tr>
            @foreach([1, 0] as $isPremium)
                @foreach($isPremium ? $prices : [0] as $price)
                    @foreach([1, 0] as $transactionState)
                        <td colspan="6">{{ $transactionState ? 'بالای ۵۰ تراکنش' : 'زیر ۵۰ تراکنش' }}</td>
                    @endforeach
                @endforeach
            @endforeach
        </tr>
        <tr>
            @foreach([1, 0] as $isPremium)
                @foreach($isPremium ? $prices : [0] as $price)
                    @foreach([1, 0] as $transactionState)
                        <td colspan="6">
                            {{ $data->where('platform', $platform)
                                    ->where('is_premium', $isPremium)
                                    ->where('price', $price)
                                    ->where('transaction_state', $transactionState)
                                    ->sum('count')
                                    }}
                        </td>
                    @endforeach
                @endforeach
            @endforeach
        </tr>


        <tr>
            @foreach([1, 0] as $isPremium)
                @foreach($isPremium ? $prices : [0] as $price)
                    @foreach([1, 0] as $transactionState)
                        @foreach([1, 2, 3] as $imageSize)
                            <td colspan="2">{{ $imageSize == 1 ? 'زیر ۱۰۰ مگ' : ($imageSize == 2 ? 'زیر ۲۰۰ مگ' : 'بیش از ۲۰۰ مگ')
                                    }}</td>
                        @endforeach
                    @endforeach
                @endforeach
            @endforeach
        </tr>
        <tr>
            @foreach([1, 0] as $isPremium)
                @foreach($isPremium ? $prices : [0] as $price)
                    @foreach([1, 0] as $transactionState)
                        @foreach([1, 2, 3] as $imageSize)
                            <td colspan="2">
                                {{ $data->where('platform', $platform)
                                        ->where('is_premium', $isPremium)
                                        ->where('price', $price)
                                        ->where('transaction_state', $transactionState)
                                        ->where('image_size', $imageSize)
                                        ->sum('count')
                                        }}
                            </td>
                        @endforeach
                    @endforeach
                @endforeach
            @endforeach
        </tr>


        <tr>
            @foreach([1, 0] as $isPremium)
                @foreach($isPremium ? $prices : [0] as $price)
                    @foreach([1, 0] as $transactionState)
                        @foreach([1, 2, 3] as $imageSize)
                            <td>تعداد کاربر</td>
                            <td>میانگین تعداد عکس</td>
                        @endforeach
                    @endforeach
                @endforeach
            @endforeach
        </tr>
        <tr>
            @foreach([1, 0] as $isPremium)
                @foreach($isPremium ? $prices : [0] as $price)
                    @foreach([1, 0] as $transactionState)
                        @foreach([1, 2, 3] as $imageSize)
                            <td>
                                {{ $data->where('platform', $platform)
                                        ->where('is_premium', $isPremium)
                                        ->where('price', $price)
                                        ->where('transaction_state', $transactionState)
                                        ->where('image_size', $imageSize)
                                        ->sum('count')
                                        }}
                            </td>
                            <td>
                                {{ $data->where('platform', $platform)
                                        ->where('is_premium', $isPremium)
                                        ->where('price', $price)
                                        ->where('transaction_state', $transactionState)
                                        ->where('image_size', $imageSize)
                                        ->sum('image_count')
                                        }}
                            </td>
                        @endforeach
                    @endforeach
                @endforeach
            @endforeach
        </tr>


        <tr>
            <td colspan="72"></td>
        </tr>
        <tr>
            <td colspan="72"></td>
        </tr>
    @endforeach
</table>
