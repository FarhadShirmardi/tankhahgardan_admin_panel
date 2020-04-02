<script>
    $(document).ready(function () {
        let information = JSON.parse('{!! json_encode($days) !!}');
        let days = [];
        let values = [];
        information.map((info) => {
            days.push(info['date']);
            values.push(info['count']);
        });
        Highcharts.chart('container', {
            chart: {
                type: 'area'
            },
            title: {
                text: 'تفکیک روز'
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
                data: values
            }]
        });
    });

    $(document).ready(function () {
        let information = JSON.parse('{!! json_encode($days) !!}');
        let days = [];
        let values = [];
        let c = 0;
        information.map((info) => {
            days.push(info['date']);
            values.push(info['count'] + c);
            c = info['count'] + c;
        });
        Highcharts.chart('containerC', {
            chart: {
                type: 'area'
            },
            title: {
                text: 'تفکیک روز تجمعی'
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
                data: values
            }]
        });
    });
</script>
