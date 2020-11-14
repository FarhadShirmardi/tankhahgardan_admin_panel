<script>
    $(document).ready(function () {
        let days = JSON.parse('{!! json_encode($days) !!}');
        let values = JSON.parse('{!! json_encode($users_day) !!}');
        let finalValues = [];
        days.reverse().map((day) => {
            for (const key in values) {
                if (key.toString() === day.toString()) {
                    finalValues.push(values[key]);
                }
            }
        });
        Highcharts.chart('container', {
            chart: {
                type: 'area'
            },
            title: {
                text: 'نمودار'
            },
            xAxis: {
                categories: days,
                tickmarkPlacement: 'on',
                title: {
                    enabled: true
                }
            },
            yAxis: {
                title: {
                    text: 'تعداد'
                },
                labels: {
                    formatter: function () {
                        return this.value;
                    }
                }
            },
            tooltip: {
                split: true,
            },
            plotOptions: {
                area: {
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
                name: 'روز',
                data: finalValues
            }]
        });
    });
</script>
