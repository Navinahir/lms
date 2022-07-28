/**********************************************************
 Intitalize Data Table
 ***********************************************************/

if (typeof (get) !== "undefined" && get != "") {
    var start = moment(get.from_date);
    ;
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
    var service = $('#services').val();

    setDataTable(date, service);
    setGraphData(date, service);
}, 500);

function setDataTable(date, service) {
    $(document).find('.datatable-responsive-control-right').DataTable().clear().draw();
    $(document).find('.datatable-responsive-control-right').DataTable().destroy();
    $('.datatable-header').append('');
    var table = $('.datatable-responsive-control-right').dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
            emptyTable: 'No data currently available.'
        },
//        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        dom: 'lBfrtip',
        buttons: [
            'csv',
        ],
        lengthMenu: [[10, 20, 30, 50, -1], [10, 20, 30, 50, "All"]],
        order: [[3, "desc"]],
        ajax: {
            'type': 'GET',
            "url": site_url + 'reports/get_labor_and_services_data',
            "data": {
                "date": date,
                "service": service
            },
        },
        responsive: {
            details: {
                type: 'column',
                target: -1
            }
        },
        columns: [
            {
                data: "sr_no",
                visible: true,
                sortable: false,
            },
            {
                data: "service_name",
                visible: true,
                className: 'slct',
                render: function (data, type, full, meta) {
                    result = '<b>Name : </b>' + full.service_name;
                    result += '<br/><b>Rate : </b>' + full.service_rate;
                    return result;
                }
            },
            {
                data: "total",
                visible: true,
                className: 'slct',
                render: function (data, type, full, meta) {
                    return full.total;
                }
            },
            {
                data: "estimate_rate",
                visible: true,
                className: 'slct',
                render: function (data, type, full, meta) {
                    return full.estimate_rate;
                }
            },
            {
                data: 'responsive',
                className: 'control',
                orderable: false,
                targets: -1,
            }
        ],
        "fnDrawCallback": function () {
            var info = document.querySelectorAll('.switchery-info');
            $(info).each(function () {
                var switchery = new Switchery(this, {color: '#95e0eb'});
            });
        }
    });

    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');

    $('.buttons-csv').addClass('btn bg-teal-400 btn-labeled mb-3').empty().html('<b><i class="icon-download"></i></b> Download Reports');
    var pdf_button = '&nbsp;<button type="button" class="btn bg-teal-400 btn-labeled pdf_button mb-3 ml-2"><b><i class="icon-printer"></i></b> Print</button>';
    $('.dt-buttons').append(pdf_button);
}


/* ------------------------------------------------------------------------------
 *
 *  # Echarts - pies and donuts
 *
 *  Pies and donuts chart configurations
 *
 * ---------------------------------------------------------------------------- */

function setGraphData(date, service) {
    $.ajax({
        url: site_url + 'reports/get_labor_and_services_graph_data',
        type: "GET",
        data: {"date": date, "service": service},
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
    var service = $('#services').val();
    $("#fakeLoader").attr('style', '').html('');
    $("#fakeLoader").fakeLoader({
        timeToHide: 3000,
        spinner: "spinner5",
        bgColor: "",
    });
    setTimeout(function () {
        setDataTable(date, service);
        setGraphData(date, service);
    }, 200);
});

$(document).on('click', '.pdf_button', function (e) {
    $("#fakeLoader").attr('style', '').html('');
    $(document).find("#fakeLoader").fakeLoader({
        timeToHide: 3000,
        spinner: "spinner5",
        bgColor: "",
    });
    var date = $('#date_range').val();
    var service = $('#services').val();
    var url = updateQueryStringParameter(site_url + 'reports/print_labor_and_services', "date", date);
    if (service != null) {
        url = updateQueryStringParameter(url, "service", service);
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

                            var multiple_donuts = ec.init(document.getElementById('multiple_donuts'), limitless);

                            // Charts setup
                            // ------------------------------                    
                            //
                            // Nightingale roses with visible labels options
                            //

                            //
                            // Multiple donuts options
                            //

                            // Top text label
                            var labelTop = {
                                normal: {
                                    label: {
                                        show: true,
                                        position: 'center',
                                        formatter: '{b}\n' + response.cur + '{c}\n\n{d}%',
                                        textStyle: {
                                            baseline: 'middle',
                                            fontWeight: 300,
                                            fontSize: 14
                                        }
                                    },
                                    labelLine: {
                                        show: false
                                    },
                                    color: '#33a9f5',
                                }
                            };

                            // Format bottom label
                            var labelFromatter = {
                                normal: {
                                    label: {
                                        formatter: function (params) {
                                            return '\n\n' + params.value;
                                        }
                                    }
                                }
                            }

                            // Bottom text label
                            var labelBottom = {
                                normal: {
                                    color: '#eee',
                                    label: {
                                        show: false,
                                        position: 'center',
                                        textStyle: {
                                            baseline: 'middle'
                                        }
                                    },
                                    labelLine: {
                                        show: false
                                    }
                                },
                                emphasis: {
                                    color: 'rgba(0,0,0,0)'
                                }
                            };
                            // Set inner and outer radius
                            var radius = [60, 75];

                            var series_data = [];
                            var start = 12;
                            var last = 32.5;
                            $.each(response.data, function (i, v) {
                                if (i > 0 && i % 4 == 0) {
                                    start = 12;
                                    last = (parseFloat(last) + 50);
                                }
                                obj = {
                                    type: 'pie',
                                    center: [start + '%', last + '%'],
                                    radius: radius,
                                    itemStyle: labelFromatter,
                                    data: [
                                        {name: (v.value / response.sum), value: (response.sum - v.value), itemStyle: labelBottom},
                                        {name: v.name, value: parseFloat(v.value).toFixed(2), itemStyle: labelTop}
                                    ]
                                };
                                start = (parseInt(start) + 25);
                                series_data.push(obj);
                            });
                            // Add options
                            multiple_donuts_options = {

                                // Add title
                                title: {
                                    text: 'Labor and Services',
                                    subtext: 'from invoices',
                                    x: 'center'
                                },

                                // Add legend
                                legend: {
                                    x: 'center',
                                    y: '56%',
                                    data: response.services
                                },

                                // Add series
                                series: series_data
                            };

                            // Apply options
                            // ------------------------------

                            multiple_donuts.setOption(multiple_donuts_options);

                            // Resize charts
                            // ------------------------------

                            window.onresize = function () {
                                setTimeout(function () {
                                    multiple_donuts.resize();
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
            dateLimit: {days: 60},
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