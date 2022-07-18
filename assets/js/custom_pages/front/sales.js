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
    setGraphData(date);
    setDataTable(date);
    dailyInvoiceSales(date);
}, 500);

/* ------------------------------------------------------------------------------
 *
 *  # Echarts - pies and donuts
 *
 *  Pies and donuts chart configurations
 *
 * ---------------------------------------------------------------------------- */

function setGraphData(date) {
    $.ajax({
        url: site_url + 'reports/get_sales_graph_data',
        type: "GET",
        data: {"date": date},
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

function setDataTable(date, user) {
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
        order: [[1, "desc"]],
        ajax: {
            'type': 'GET',
            "url": site_url + 'reports/get_sales_data',
            "data": {
                "date": date,
                "user": user
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
                data: "estimate_date",
                visible: true,
                render: function (data, type, full, meta) {
                    result = '<b>Date : </b>' + full.estimate_date;
                    return result;
                }
            },
            {
                data: "estimate_rate",
                visible: true,
                className: 'sum',
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
        },
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            api.columns('.sum', {page: 'current'}).every(function () {
                var sum = this
                        .data()
                        .reduce(function (a, b) {
                            var x = parseFloat(a) || 0;
                            var y = parseFloat(b) || 0;
                            return x + y;
                        }, 0);
                $(this.footer()).html(parseFloat(sum).toFixed(2));
            });
        }
    });

    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
    $('.buttons-csv').addClass('btn bg-teal-400 btn-labeled mb-3').empty().html('<b><i class="icon-download"></i></b> Download Reports');
    var pdf_button = '&nbsp;&nbsp;<button type="button" class="btn bg-teal-400 btn-labeled pdf_button ml-2 mb-3"><b><i class="icon-printer"></i></b> Print</button>';
    $('.dt-buttons').append(pdf_button);
}

function dailyInvoiceSales(date) {
    $('.datatable-responsive-daily-invoice-sales').dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
            emptyTable: 'No data currently available.'
        },
        dom: 'lBfrtip',
        buttons: [
            'csv',
        ],
        lengthMenu: [[10, 20, 30, 50, -1], [10, 20, 30, 50, "All"]],
        order: [[1, "desc"]],
        ajax: {
            'type': 'GET',
            "url": site_url + 'reports/get_daily_sales_data',
            "data": {
                "date": date
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
                data: "invoice_date",
                visible: true,
                render: function (data, type, full, meta) {
                    result = '<b>Date : </b>' + full.invoice_date;
                    return result;
                }
            },
            {
                data: "estimate_id",
                visible: true,
            },
            {
                data: "estimate_rate",
                visible: true,
                className: 'sum',
                render: function (data, type, full, meta) {
                    return '$' + full.estimate_rate;
                }
            },
            {
                visible: true,
                render: function (data, type, full, meta) {
                    return '<a href="' + site_url + 'invoices/view/' + btoa(full.e_id) + '" class="btn btn-xs custom_dt_action_button" title="View">View</a>';
                },
                sortable: false,
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
        },
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            api.columns('.sum', {page: 'current'}).every(function () {
                var sum = this
                        .data()
                        .reduce(function (a, b) {
                            var x = parseFloat(a) || 0;
                            var y = parseFloat(b) || 0;
                            return x + y;
                        }, 0);
                $(this.footer()).html(parseFloat(sum).toFixed(2));
            });
        }
    });
    
    datatable_design();
}

function datatable_design() {
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
    $('.buttons-csv').addClass('btn bg-teal-400 btn-labeled').empty().html('<b><i class="icon-download"></i></b> Download Reports');
}

$('.btn-save').on('click', function () {
    var date = $('#date_range').val();
    $("#fakeLoader").attr('style', '').html('');
    $(document).find("#fakeLoader").fakeLoader({
        timeToHide: 3000,
        spinner: "spinner5",
        bgColor: "",
    });
    var url = updateQueryStringParameter(site_url + 'reports/sales', "date", date);
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
    var url = updateQueryStringParameter(site_url + 'reports/print_sales', "date", date);
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
    /* ------------------------------------------------------------------------------
     *
     *  # Echarts - columns and waterfalls
     *
     *  Columns and waterfalls chart configurations
     *
     *  Version: 1.0
     *  Latest update: August 1, 2015
     *
     * ---------------------------------------------------------------------------- */

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
                    'echarts/chart/bar',
                    'echarts/chart/line'
                ],
                // Charts setup
                        function (ec, limitless) {


                            // Initialize charts
                            // ------------------------------

                            var basic_columns = ec.init(document.getElementById('basic_columns'), limitless);

                            // Charts setup
                            // ------------------------------


                            //
                            // Basic columns options
                            //

                            basic_columns_options = {

                                // Setup grid
                                grid: {
                                    x: 40,
                                    x2: 40,
                                    y: 35,
                                    y2: 25
                                },

                                // Add tooltip
                                tooltip: {
                                    trigger: 'axis'
                                },

                                // Add legend
                                legend: {
                                    data: ['Sales']
                                },

                                // Enable drag recalculate
                                calculable: true,

                                // Horizontal axis
                                xAxis: [{
                                        type: 'category',
                                        data: response.xAxis
                                    }],

                                // Vertical axis
                                yAxis: [{
                                        type: 'value'
                                    }],

                                // Add series
                                series: [
                                    {
                                        name: 'Sales',
                                        type: 'bar',
                                        data: response.data,
                                        itemStyle: {
                                            normal: {
                                                label: {
                                                    show: false,
                                                    textStyle: {
                                                        fontWeight: 500
                                                    }
                                                },
                                                color: '#33a9f5'
                                            }
                                        },
                                        // markLine: {
                                        //     data: [{type: 'average', name: 'Average'}]
                                        // }
                                    },
                                ]
                            };


                            // Apply options
                            // ------------------------------

                            basic_columns.setOption(basic_columns_options);

                            // Resize charts
                            // ------------------------------

                            window.onresize = function () {
                                setTimeout(function () {
                                    basic_columns.resize();
                                }, 200);
                            }
                        }
                );
            });
}
// Initialize with options
$('#date_range').daterangepicker({
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
    opens: 'right',
    applyClass: 'btn-small bg-blue',
    cancelClass: 'btn-small btn-default'
}, function (start, end) {
    $('#date_range span').html(start.format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + end.format('MMMM D, YYYY'));
    $('#date_range').val(start.format('YYYY/MM/DD') + ':=:' + end.format('YYYY/MM/DD'));
    // $.jGrowl('Date range has been changed', { header: 'Update', theme: 'bg-primary', position: 'center', life: 1500 });
});

// Display date format
$('#date_range span').html(start.format('MMMM D, YYYY') + ' &nbsp; - &nbsp; ' + end.format('MMMM D, YYYY'));
$('#date_range').val(start.format('YYYY/MM/DD') + ':=:' + end.format('YYYY/MM/DD'));