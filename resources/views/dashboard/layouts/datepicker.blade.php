<script>
    $(document).ready(function () {
        $('.range_date').pDatepicker({
            format: "YYYY/MM/DD",
            initialValue: true,
            initialValueType: 'gregorian',
            autoClose: true,
            maxDate: new persianDate(),
            calendar: {
                persian: {
                    locale: 'fa',
                    showHint: false,
                    leapYearMode: 'algorithmic'
                }
            }
        });
        $('.time_picker').pDatepicker({
            format: "YYYY/MM/DD H:m",
            altFormat: "YYYY/MM/DD H:m",
            initialValue: true,
            initialValueType: 'gregorian',
            autoClose: true,
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
