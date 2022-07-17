/**********************************************************
                Intitalize Data Table
***********************************************************/

if (typeof (get) !== "undefined" && get != "") {
    var start = moment(get.from_date);;
    var end = moment(get.to_date);
} else {
    var start = moment().subtract(1, 'days');
    var end = moment();
}
$(document).ready(function () {
    $("#fakeLoader").fakeLoader({
        timeToHide: 3000,
        spinner: "spinner5",
        bgColor: "",
    });
});

setTimeout(function () {
    var date = start.format('YYYY-MM-DD') + ':=:' + end.format('YYYY-MM-DD');
    var parts = $('#parts').val();
    setGraphData(date, parts);
}, 500);

/* ------------------------------------------------------------------------------
 *
 *  # Echarts - pies and donuts
 *
 *  Pies and donuts chart configurations
 *
 * ---------------------------------------------------------------------------- */

function setGraphData(date, parts) {
    $.ajax({
        url: site_url + 'reports/get_popular_graph_data',
        type: "GET",
        data: { "date": date, "parts": parts },
        success: function (response) {
            $('.chart').html("");
            response = JSON.parse(response);
            if (response.code == 200) {
                setTimeout(function () {
                    set_graph(response);
                    $('.pdf_button').show();
                }, 300);
            } else {
                $('.chart').html("No Data Found!!");
                $('.pdf_button').hide();
            }
        }
    });
}

$('.btn-save').on('click', function () {
    var date = $('#date_range').val();
    var parts = $('#parts').val();
    $("#fakeLoader").attr('style', '').html('');
    $(document).find("#fakeLoader").fakeLoader({
        timeToHide: 3000,
        spinner: "spinner5",
        bgColor: "",
    });
    // setTimeout(function () {
    //     setDataTable(date, user);
    //     setGraphData(date, user);
    // }, 200);
    var url = updateQueryStringParameter(site_url + 'reports/popular_items', "date", date);
    if (parts != null) {
        url = updateQueryStringParameter(url, "parts", parts);
    }
    window.location.href = encodeURI(url);
});

$(document).on('click', '.pdf_button', function (e) {
    $("#fakeLoader").attr('style', '').html('');
    $(document).find("#fakeLoader").fakeLoader({
        timeToHide: 3000,
        spinner: "spinner5",
        bgColor: "",
    });
    var date = $('#date_range').val();
    var parts = $('#parts').val();
    var url = updateQueryStringParameter(site_url + 'reports/print_popular_items', "date", date);
    if (parts != null) {
        url = updateQueryStringParameter(url, "parts", parts);
    }
    window.location.href = encodeURI(url);
});
//----------------------------- v! Change URL ---------------------------------------------

function updateQueryStringParameter(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    } else {
        return uri + separator + key + "=" + value;
    }
}
//-----------------------------------------------------------------------------------------

function set_graph(response) {

    $(function () {

        // Set paths
        // ------------------------------

        require.config({
            paths: {
                echarts: 'assets/js/plugins/visualization/echarts'
            }
        });


        // Configuration
        // ------------------------------

        require(
            [
                'echarts',
                'echarts/theme/limitless',
                'echarts/chart/pie',
                'echarts/chart/funnel'
            ],


            // Charts setup
            function (ec, limitless) {


                // Initialize charts
                // ------------------------------

                var basic_donut = ec.init(document.getElementById('basic_donut'), limitless);

                // Charts setup
                // ------------------------------                    
                //
                // Nightingale roses with visible labels options
                //

                //
                // Multiple donuts options
                //

                //
                // Basic donut options
                //

                basic_donut_options = {

                    // Add title
                    title: {
                        text: 'Top 25 Most Popular Parts',
                        subtext: 'Open source information',
                        x: 'center'
                    },

                    // Add legend
                    legend: {
                        orient: 'vertical',
                        x: 'left',
                        data: response.parts
                    },

                    // Display toolbox
                    // toolbox: {
                    //     show: true,
                    //     orient: 'vertical',
                    //     feature: {
                    //         mark: {
                    //             show: true,
                    //             title: {
                    //                 mark: 'Markline switch',
                    //                 markUndo: 'Undo markline',
                    //                 markClear: 'Clear markline'
                    //             }
                    //         },
                    //         dataView: {
                    //             show: true,
                    //             readOnly: false,
                    //             title: 'View data',
                    //             lang: ['View chart data', 'Close', 'Update']
                    //         },
                    //         magicType: {
                    //             show: true,
                    //             title: {
                    //                 pie: 'Switch to pies',
                    //                 funnel: 'Switch to funnel',
                    //             },
                    //             type: ['pie', 'funnel'],
                    //             option: {
                    //                 funnel: {
                    //                     x: '25%',
                    //                     y: '20%',
                    //                     width: '50%',
                    //                     height: '70%',
                    //                     funnelAlign: 'left',
                    //                     max: 1548
                    //                 }
                    //             }
                    //         },
                    //         restore: {
                    //             show: true,
                    //             title: 'Restore'
                    //         },
                    //         saveAsImage: {
                    //             show: true,
                    //             title: 'Same as image',
                    //             lang: ['Save']
                    //         }
                    //     }
                    // },

                    // Enable drag recalculate
                    calculable: true,

                    // Add series
                    series: [
                        {
                            name: 'Browsers',
                            type: 'pie',
                            radius: ['50%', '70%'],
                            center: ['50%', '57.5%'],
                            // max: response.max_value,
                            itemStyle: {
                                normal: {
                                    label: {
                                        show: true
                                    },
                                    labelLine: {
                                        show: true
                                    },
                                },
                                emphasis: {
                                    label: {
                                        show: true,
                                        formatter: '{b}' + '\n\n' + response.cur + '{c} ({d}%)',
                                        position: 'center',
                                        textStyle: {
                                            fontSize: '17',
                                            fontWeight: '500'
                                        }
                                    }
                                }
                            },

                            data: response.data
                        }
                    ]
                };

                // Apply options
                // ------------------------------

                basic_donut.setOption(basic_donut_options);

                // Resize charts
                // ------------------------------

                window.onresize = function () {
                    setTimeout(function () {
                        basic_donut.resize();
                    }, 200);
                }
            }
        );
    });
}
// Initialize with options
$('#date_range').daterangepicker(
    {
        startDate: start,
        endDate: end,
        // minDate: '01/01/2014',
        maxDate: moment(),
        dateLimit: { days: 60 },
        firstDayOfWeek: 1,
        ranges: {
            'Today': [moment(), moment()],
            'This Week': [moment().startOf('week'), moment()],
            'Last 7 Days': [moment().subtract('days', 6), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
            'Month To Date': [moment().startOf('month'), moment()],
            // 'This Year': [moment().startOf('year'), moment().endOf('month').endOf('year')],
            'Last Year': [moment().subtract('year', 1).startOf('year'), moment().subtract('year', 1).endOf('year')],
            'Year to Date': [moment().startOf('year'), moment()],
        },
        alwaysShowCalendars: true,
        opens: 'left',
        applyClass: 'btn-small bg-blue',
        cancelClass: 'btn-small btn-default'
    },
    function (start, end) {
        $('#date_range span').html(start.format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + end.format('MMMM D, YYYY'));
        $('#date_range').val(start.format('YYYY/MM/DD') + ':=:' + end.format('YYYY/MM/DD'));
        // $.jGrowl('Date range has been changed', { header: 'Update', theme: 'bg-primary', position: 'center', life: 1500 });
    }
);
// $('#date_range').on('apply.daterangepicker', function (ev, picker) {
//     console.log('change', $('#date_range').val());
//     $(this).val(start.format('YYYY/MM/DD') + ':=:' + end.format('YYYY/MM/DD'));
// });

// Display date format
$('#date_range span').html(start.format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + end.format('MMMM D, YYYY'));
$('#date_range').val(start.format('YYYY/MM/DD') + ':=:' + end.format('YYYY/MM/DD'));