<script>
    $(document).ready(function () {
        let information = JSON.parse('{!! json_encode($range_counts) !!}');

        let result = Object.keys(information).map(function (key) {
            return [key, information[key]];
        });
        let ranges = [];
        let payment = [];
        let receive = [];
        let imprest = [];
        let note = [];

        result.forEach(function (item) {
            ranges.push(item[0]);
            payment.push(item[1]['payments_count']);
            receive.push(item[1]['receives_count']);
            note.push(item[1]['notes_count']);
            imprest.push(item[1]['imprests_count']);
        });

        Highcharts.chart('chart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'تعداد تراکنش در بازه'
            },
            xAxis: {
                categories: ranges,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'تعداد'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table style="direction: rtl">',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'پرداخت',
                data: payment,
                color: '#4d86ff',
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    format: '{point.y}', // one decimal
                    y: 10, // 10 pixels down from the top
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }, {
                name: 'دریافت',
                data: receive,
                color: '#ff5a60',
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    format: '{point.y}', // one decimal
                    y: 10, // 10 pixels down from the top
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }, {
                name: 'تنخواه',
                data: imprest,
                color: '#5bb655',
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    format: '{point.y}', // one decimal
                    y: 10, // 10 pixels down from the top
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }, {
                name: 'یادداشت',
                data: note,
                color: '#d866ff',
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    format: '{point.y}', // one decimal
                    y: 10, // 10 pixels down from the top
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }
            ]
        });
    });

    $(document).ready(function () {

        let dateCounts = JSON.parse('{!! json_encode($date_counts) !!}');
        let dates = JSON.parse('{!! json_encode($dates) !!}');

        let ranges = [];
        dates = Object.keys(dates).map(function (key) {
            ranges.push(dates[key]);
        });

        console.log(ranges);
        console.log(dateCounts);

        let values = [];

        dateCounts.forEach(function (item) {
            values.push({'name': item['name'], 'data': JSON.parse(item['data'])})
        });

        console.log(values);

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
