<table style="border: 1px solid black; text-align: center">
    <tr>
        @foreach($prices as $price)
            <td colspan="30">{{ \App\Constants\PremiumDuration::getTitle($price) }}</td>
        @endforeach
    </tr>
    <tr>
        @foreach($prices as $price)
            <td colspan="30">
                {{ $data->where('price', $price)
                        ->sum('count')
                        }}
            </td>
        @endforeach
    </tr>

    <tr>
        @foreach($prices as $price)
            @foreach([0, 1] as $isActive)
                <td colspan="15">{{ $isActive ? 'موفق' : 'ناموفق' }}</td>
            @endforeach
        @endforeach
    </tr>
    <tr>
        @foreach($prices as $price)
            @foreach([0, 1] as $isActive)
                <td colspan="15">
                    {{
                        $data->where('price', $price)
                            ->where('is_active', $isActive)
                            ->sum('count')
                        }}
                </td>
            @endforeach
        @endforeach
    </tr>

    <tr>
        @foreach($prices as $price)
            @foreach([0, 1] as $isActive)
                @foreach([1,2,3,4,5] as $userCount)
                    <td colspan="3">{{ $userCount == 1 ? 'صفر' : ($userCount == 2 ? 'یک' : ($userCount == 3 ? '۲-۳' :
                     ($userCount == 4 ? '۴-۷' : 'بالای ۸'))) }}</td>
                @endforeach
            @endforeach
        @endforeach
    </tr>
    <tr>
        @foreach($prices as $price)
            @foreach([0, 1] as $isActive)
                @foreach([1,2,3,4,5] as $userCount)
                    <td colspan="3">
                        {{
                            $data->where('price', $price)
                                ->where('is_active', $isActive)
                                ->where('user_count', $userCount)
                                ->sum('count')
                            }}
                    </td>
                @endforeach
            @endforeach
        @endforeach
    </tr>

    <tr>
        @foreach($prices as $price)
            @foreach([0, 1] as $isActive)
                @foreach([1,2,3,4,5] as $userCount)
                    @foreach([1, 2, 3] as $imageSize)
                        <td>{{ $imageSize == 1 ? 'زیر ۱۰۰ مگ' : ($imageSize == 2 ? 'زیر ۲۰۰ مگ' : 'بیش از ۲۰۰ مگ')
                                    }}</td>
                    @endforeach
                @endforeach
            @endforeach
        @endforeach
    </tr>
    <tr>
        @foreach($prices as $price)
            @foreach([0, 1] as $isActive)
                @foreach([1,2,3,4,5] as $userCount)
                    @foreach([1,2,3] as $imageSize)
                        <td>
                            {{
                                $data->where('price', $price)
                                    ->where('is_active', $isActive)
                                    ->where('user_count', $userCount)
                                    ->where('image_size', $imageSize)
                                    ->sum('count')
                                }}
                        </td>
                    @endforeach
                @endforeach
            @endforeach
        @endforeach
    </tr>
</table>
