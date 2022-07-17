/**********************************************************
 Intitalize Data Table
 ***********************************************************/
$("#fakeLoader").fakeLoader({
    timeToHide: 3000,
    spinner: "spinner5",
    bgColor: "",
});
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
//    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    dom: 'lBfrtip',
    buttons: [
        {
            extend: 'csv',
            exportOptions: {
                columns: [0, 1, 2, 3, 4]
            }
        },
    ],
    lengthMenu: [[10, 20, 30, 50, -1], [10, 20, 30, 50, "All"]],
    order: [[5, "asc"]],
    ajax: site_url + 'reports/get_low_inventory_items_data',
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
            data: "global_part_no",
            visible: true,
            // className: 'slct',
            render: function (data, type, full, meta) {
                if (full.global_part_no != null) {
                    result = '<b>Global Part No : </b>' + full.global_part_no;
                } else {
                    result = '<b>Non Global Part</b>';
                }
                result += '<br/>&nbsp;<b>Part No : </b>' + full.part_no;
                return result;
            }
        },
        {
            data: "dept_name",
            visible: true,
            // className: 'slct',
            render: function (data, type, full, meta) {
                return full.dept_name;
            }
        },
        {
            data: "pref_vendor_name",
            visible: true,
            // className: 'slct',
            render: function (data, type, full, meta) {
                result = '<b>Vendor : </b>' + full.pref_vendor_name;
                return result;
            }
        },
        {
            data: "retail_price",
            visible: true,
            // className: 'slct',
        },
        {
            data: "total_quantity",
            visible: true,
            className: 'slct',
            render: function (data, type, full, meta) {
                if (full.total_quantity > 0) {
                    action = '<span class="label bg-success-400">' + full.total_quantity + ' - IN STOCK</span>';
                } else {
                    action = '<span class="label bg-danger-400">OUT OF STOCK</span>';
                }
                return action;
            }
        },
        {
            data: "action",
            render: function (data, type, full, meta) {
                action = '';
                action += '<a href="javascript:void(0);" class="btn btn-xs custom_dt_action_button item_view_btn" title="View" id="\'' + btoa(full.id) + '\'">View</a>';
                if (full.total_quantity <= 1) {
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'receive_inventory/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Add Inventory">Add Inventory</a>';
                }
                return action;
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
    initComplete: function () {
        this.api().columns('.slct', {page: 'current'}).every(function () {
            var that = this;

            $('input', this.footer()).on('keyup change', function () {
                if (that.search() !== this.value) {
                    var url = updateQueryStringParameter($('.pdf_button').attr('href'), 'qty', this.value);
                    $('.pdf_button').attr('href', url);
                    that
                            .search(this.value)
                            .draw();
                }
            });
        });
    },
    "fnDrawCallback": function () {
        var info = document.querySelectorAll('.switchery-info');
        $(info).each(function () {
            var switchery = new Switchery(this, {color: '#95e0eb'});
        });
    }
});

$(document).ready(function () {
    // Setup - add a text input to each footer cell
    $('.datatable-responsive-control-right tfoot th').each(function () {
        var title = $(this).text();
        if ($(this).hasClass('slct')) {
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        }
    });
});

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});
$('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');

$('.buttons-csv').addClass('btn bg-teal-400 btn-labeled mb-3').empty().html('<b><i class="icon-download"></i></b> Download Reports');
var pdf_button = '&nbsp;<a href="' + site_url + 'reports/print_low_inventory_item" class="btn bg-teal-400 btn-labeled pdf_button mb-3 ml-2"><b><i class="icon-printer"></i></b> Print</a>';
$('.dt-buttons').append(pdf_button);

/* ------------------------------------------------------------------------------
 *
 *  # Echarts - pies and donuts
 *
 *  Pies and donuts chart configurations
 *
 * ---------------------------------------------------------------------------- */
$.ajax({
    url: site_url + 'reports/get_low_inventory_items_graph_data',
    type: "GET",
    success: function (response) {
        response = JSON.parse(response);
        $('.chart').html("");
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
//----------------------------- v! Change URL ---------------------------------------------
function ChangeUrl(page, url) {
    if (typeof (history.pushState) != "undefined") {
        var obj = {Page: page, Url: url};
        history.pushState(obj, obj.Page, obj.Url);
    } else {
        alert("Browser does not support HTML5.");
    }
}

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

                            var rose_diagram_visible = ec.init(document.getElementById('rose_diagram_visible'), limitless);

                            // Charts setup
                            // ------------------------------                    
                            //
                            // Nightingale roses with visible labels options
                            //

                            rose_diagram_visible_options = {

                                // Add title
                                title: {
                                    text: 'Low Inventory Items review',
                                    subtext: 'Items Inventory Count',
                                    x: 'center',
                                    padding: [
                                        120,  // down
                                        
                                    ]
                                },

                                // Add tooltip
                                tooltip: {
                                    trigger: 'item',
                                    formatter: "{a}: {b}<br/>Inventory: {c}"
                                },

                                // Add legend
                                legend: {
                                    x: 'left',
                                    y: 'top',
                                    orient: 'horizontal',
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
                                //             type: ['pie', 'funnel']
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
                                        name: 'Item Part',
                                        type: 'pie',
                                        radius: ['10%', '50%'],
                                        center: ['50%', '57%'],
                                        roseType: 'area',

                                        // Funnel
                                        width: '40%',
                                        height: '78%',
                                        x: '30%',
                                        y: '17.5%',
                                        max: response.max_value,
                                        sort: 'ascending',

                                        data: response.data,
                                    }
                                ]
                            };

                            // Apply options
                            // ------------------------------

                            rose_diagram_visible.setOption(rose_diagram_visible_options);

                            // Resize charts
                            // ------------------------------

                            window.onresize = function () {
                                setTimeout(function () {
                                    rose_diagram_visible.resize();
                                }, 200);
                            }
                        }
                );
            });
}
/**********************************************************
 Item View Popup
 ***********************************************************/
$(document).on('click', '.item_view_btn', function () {
    $('#custom_loading').removeClass('hide');
    $('#custom_loading').css('display', 'block');
    $.ajax({
        url: site_url + 'items/get_item_data_ajax_by_id',
        type: "POST",
        data: {id: this.id},
        success: function (response) {
            $('#custom_loading').removeClass('hide');
            $('#custom_loading').css('display', 'none');
            $('#items_view_body').html(response);
            $('#items_view_modal').modal('show');
        }
    });
});