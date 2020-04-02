<script>
    $(document).ready(function () {
        let times = JSON.parse('{!! json_encode($users_time) !!}');
        let ranges = [];
        let finalValues = [];
        times.forEach(function (item) {
            var s = (item['num'] * 4).toString() + ' - ' + ((item['num'] + 1) * 4).toString();
            ranges.push(s);
            finalValues.push(item['c']);
        });
        Highcharts.chart('container', {
            chart: {
                type: 'area',
            },
            title: {
                text: 'نمودار'
            },
            xAxis: {
                categories: ranges,
                tickmarkPlacement: 'on',
                title: {
                    enabled: true
                }
            },
            yAxis: {
                title: {
                    text: 'تعداد'
                }
            },

            tooltip: {
                split: true,
            },
            plotOptions: {
                area: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: true,
                    stacking: 'normal',
                    lineColor: '#666666',
                    lineWidth: 2,
                    marker: {
                        lineWidth: 1,
                        lineColor: '#666666'
                    }
                }
            },
            series: [{
                name: 'بازه زمان',
                data: finalValues
            }]
        });
    });

</script>
