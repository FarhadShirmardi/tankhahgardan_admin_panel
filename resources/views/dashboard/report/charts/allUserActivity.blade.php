<script>

    $(document).ready(function () {
        let information = JSON.parse('{!! json_encode($counts) !!}');
        let colors = JSON.parse('{!! json_encode($colors) !!}');
        let result = Object.keys(information).map(function (key) {
            return [information[key]];
        });
        let count = [0, 0, 0, 0];

        Highcharts.chart('dateChart', {
            colors: [
                colors[1][0],
                colors[2][0],
                colors[3][0],
                colors[4][0],
            ],
            chart: {
                type: 'column'
            },
            title: {
                text: 'نمودار تعداد بر حسب وضعیت'
            },
            xAxis: {
                categories: [
                    'یک هفته اخیر',
                    'دو هفته اخیر',
                    'یک ماه اخیر',
                    'غیرفعال'
                ]
            },
            yAxis: {
                title: {
                    text: 'تعداد'
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: true
                }
            },
            series: [{
                data: result,
                colorByPoint: true
            }]
        });
    });
</script>
