@extends('dashboard.layouts.master')
@section('title')
    <i class="fa fa-credit-card"></i>
    {{ \App\Constants\PurchaseType::getEnum($type) }} طرح /
    @include('dashboard.layouts.username')
@endsection
@section('content')
    <livewire:premium-purchase
        :user="$user"
        :type="$type"
    />
@endsection
@section('scripts')
    <script>
        function changePrice(selectedObject) {
            var value = selectedObject.value;
            var x = document.getElementById('custom_price')
            if (value === '{{ \App\Constants\PremiumDuration::SPECIAL }}') {
                x.style.display = "flex";
            } else {
                x.style.display = "none";
            }
        }

        $(document).ready(function () {
            $('#start_date').pDatepicker({
                format: "YYYY/MM/DD H:m",
                altFormat: "YYYY/MM/DD H:m",
                initialValue: true,
                initialValueType: 'gregorian',
                autoClose: true,
                minDate: '{{ now()->toDateTimeString() }}',
                calendar: {
                    persian: {
                        locale: 'fa',
                        showHint: false,
                        leapYearMode: 'algorithmic'
                    }
                },
                timePicker: {
                    enabled: true,
                    step: 1,
                    hour: {
                        enabled: true,
                        step: null
                    },
                    minute: {
                        enabled: true,
                        step: 5
                    },
                    second: {
                        enabled: false,
                        step: null
                    }
                },
            });

            $('#end_date').pDatepicker({
                format: "YYYY/MM/DD H:m",
                altFormat: "YYYY/MM/DD H:m",
                initialValue: true,
                initialValueType: 'gregorian',
                autoClose: true,
                minDate: '{{ \App\Helpers\Helpers::convertDateTimeToGregorian($selected_plan['end_date'] ?? '') }}',
                calendar: {
                    persian: {
                        locale: 'fa',
                        showHint: false,
                        leapYearMode: 'algorithmic'
                    }
                },
                timePicker: {
                    enabled: true,
                    step: 1,
                    hour: {
                        enabled: true,
                        step: null
                    },
                    minute: {
                        enabled: true,
                        step: 5
                    },
                    second: {
                        enabled: false,
                        step: null
                    }
                },
            });
        });
    </script>
@endsection
