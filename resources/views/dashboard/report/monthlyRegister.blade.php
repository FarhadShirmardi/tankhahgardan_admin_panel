<table style="border: 1px solid black; text-align: center">
    @foreach(\App\Constants\Platform::toArray() as $platform)
        <tr>
            <td colspan="12">{{ \App\Constants\Platform::getEnum($platform) }}</td>
        </tr>
        <tr>
            <td colspan="12">{{ $data->where('platform', $platform)->sum('count') }}</td>
        </tr>


        <tr>
            @foreach([1, 0] as $isPremium)
                <td colspan="{{$isPremium ? 10 : 2}}">{{ $isPremium ? 'حرفه‌ای' : 'رایگان' }}</td>
            @endforeach
        </tr>
        <tr>
            @foreach([1, 0] as $isPremium)
                <td colspan="{{$isPremium ? 10 : 2}}">{{ $data->where('platform', $platform)
                            ->where('is_premium', $isPremium)->sum('count') }}</td>
            @endforeach
        </tr>


        <tr>
            @foreach([1, 0] as $isPremium)
                @foreach($isPremium ? $prices : [0] as $price)
                    <td colspan="2">{{ \App\Constants\PremiumDuration::getTitle($price) }}</td>
                @endforeach
            @endforeach
        </tr>
        <tr>
            @foreach([1, 0] as $isPremium)
                @foreach($isPremium ? $prices : [0] as $price)
                    <td colspan="2">
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
                        <td>{{ $transactionState ? 'بالای ۱۰ تراکنش' : 'زیر ۱۰ تراکنش' }}</td>
                    @endforeach
                @endforeach
            @endforeach
        </tr>
        <tr>
            @foreach([1, 0] as $isPremium)
                @foreach($isPremium ? $prices : [0] as $price)
                    @foreach([1, 0] as $transactionState)
                        <td>
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
            <td colspan="12"></td>
        </tr>
        <tr>
            <td colspan="12"></td>
        </tr>
    @endforeach
</table>
