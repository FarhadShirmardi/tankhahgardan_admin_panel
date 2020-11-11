<script>
    $(document).ready(function () {

        let dateCounts = JSON.parse('{!! json_encode($date_counts) !!}');
        let dates = JSON.parse('{!! json_encode($dates) !!}');

        let ranges = [];
        dates = Object.keys(dates).map(function (key) {
            ranges.push(dates[key]);
        });

        let values = [];

        dateCounts.forEach(function (item) {
            values.push({'name': item['name'], 'data': JSON.parse(item['data'])})
        });

        Highcharts.chart('chart2', {

            title: {
                text: 'نمودار تراکنش بر حسب تاریخ'
            },

            yAxis: {
                title: {
                    text: 'تعداد تراکنش'
                }
            },
            xAxis: {
                categories: ranges,
                tickmarkPlacement: 'on',
                title: {
                    enabled: true
                }
                // crosshair: true
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },

            plotOptions: {},


            series: values
        });
    });
</script>
