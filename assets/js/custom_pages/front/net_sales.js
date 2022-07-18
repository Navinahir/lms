/**********************************************************
 Intitalize Part Data Table
 ***********************************************************/
var table = $('.datatable-responsive-control-right-net-sale').dataTable({
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
        { 
            text: 'Print',
            className: 'part_net_sale_print' 
        },
    ],
    order: [[1, "desc"]],
    lengthMenu: [[10, 20, 30, 50, -1], [10, 20, 30, 50, "All"]],
    ajax: site_url + 'reports/get_net_sales_part/'+from_date+to_date,
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
        },
        {
            data: "estimate_id",
            visible: true,
        },
        {
            data: "full_name",
            visible: true,
        },
        {
            data: "part_discount",
            render: function (data, type, full, meta) {
                var discount = parseFloat(full.part_discount).toFixed(2);
                return discount;
            },
            visible: true,
            sortable: false,
        },
        // {
        //     render: function (data, type, full, meta) {
        //         var discount = parseFloat(full.part_discount).toFixed(2);
        //         var invoice_id = full.id;
        //         var final_discount = null;
        //         $.ajax({
        //             async: false,
        //             global: false,
        //             url: 'reports/get_net_sales_service',
        //             type: 'POST',
        //             dataType: 'json',
        //             data: {from_date: from_date, to_date:to_date, invoice_id: invoice_id},
        //             success: function(data){
        //                 final_discount = parseFloat(0).toFixed(2);
        //                 final_discount = discount;
        //                 if(data.s_estimate_id != undefined)
        //                 {
        //                     if(full.id == data.s_estimate_id)
        //                     {
        //                         var service_discount = parseFloat(data.service_discount).toFixed(2);
        //                         var part_service_discount = parseFloat(discount) + parseFloat(data.service_discount);
        //                         final_discount = parseFloat(part_service_discount).toFixed(2);
        //                     }
        //                 }
        //             },
        //         });
        //         return final_discount;
        //     },
        //     className: 'final_discount',
        //     visible: true,
        //     sortable: false,
        // },
        {
            data: "part_amount",
            visible: true,
            sortable: false,
            render: function (data, type, full, meta) {
                return parseFloat(full.part_amount).toFixed(2);
            }
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
                    that
                    .search(this.value)
                    .draw();
                }
            });
        });
    },
    "fnDrawCallback": function (row, data, start, end, display) {
        var api = this.api();

        var total = api.column(5, {page: 'current'}).data().sum();
        total = parseFloat(total).toFixed(2);
        $(api.column(5).footer()).html(total);

        var discount = api.column(4, {page: 'current'}).data().sum();
        discount = parseFloat(discount).toFixed(2);
        $(api.column(4).footer()).html(discount);
    }
});

/**********************************************************
 Intitalize Service Data Table
 ***********************************************************/
var table = $('.datatable-responsive-control-right-net-sale-service').dataTable({
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
        { 
            text: 'Print',
            className: 'service_net_sale_print' 
        },
    ],
    order: [[1, "desc"]],
    lengthMenu: [[10, 20, 30, 50, -1], [10, 20, 30, 50, "All"]],
    ajax: site_url + 'reports/get_net_sales_service/'+from_date+to_date,
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
        },
        {
            data: "estimate_id",
            visible: true,
        },
        {
            data: "full_name",
            visible: true,
        },
        {
            data: "service_discount",
            render: function (data, type, full, meta) {
                var discount = parseFloat(full.service_discount).toFixed(2);
                return discount;
            },
            visible: true,
            sortable: false,
        },
        {
            data: "service_amount",
            visible: true,
            sortable: false,
            render: function (data, type, full, meta) {
                return parseFloat(full.service_amount).toFixed(2);
            }
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
                    that
                    .search(this.value)
                    .draw();
                }
            });
        });
    },
    "fnDrawCallback": function (row, data, start, end, display) {
        var api = this.api();

        var total = api.column(5, {page: 'current'}).data().sum();
        total = parseFloat(total).toFixed(2);
        $(api.column(5).footer()).html(total);

        var discount = api.column(4, {page: 'current'}).data().sum();
        discount = parseFloat(discount).toFixed(2);
        $(api.column(4).footer()).html(discount);
    }
});

/**********************************************************
 Intitalize Part Data Table
 ***********************************************************/
var table = $('.datatable-responsive-control-right-part').dataTable({
    autoWidth: false,
    processing: true,
    serverSide: true,
    language: {
        search: '<span>Filter:</span> _INPUT_',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
        emptyTable: 'No data currently available.'
    },
    // dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    dom: 'lBfrtip',
    buttons: [
        'csv',
        { 
            text: 'Print',
            className: 'part_print' 
        },
    ],
    order: [[1, "desc"]],
    lengthMenu: [[10, 20, 30, 50, -1], [10, 20, 30, 50, "All"]],
    ajax: site_url + 'reports/get_part_net_sales/'+from_date+to_date,
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
            data: "estimate_id",
            visible: true,
        },
        {
            data: "part_no",
            visible: true,
        },
        {
            data: "quantity",
            visible: true,
        },
        {
            render: function (data, type, full, meta) {
                if(full.discount_type_id != "") {
                    if(full.discount_type_id == "p") {
                        var discount_type = "%";    
                        var discount_amount = full.discount;
                        var discount = discount_amount + discount_type;
                    } else {
                        var discount_type = "$";   
                        var discount_amount = full.discount;
                        var discount = discount_type + discount_amount;
                    }
                }
                return discount;
            },
            visible: true,
            sortable: false,
        },
        {
            data: "tax_rate",
            visible: true,
            className: "tax_rate_sum",
            render: function (data, type, full, meta) {
                return parseFloat(full.tax_rate).toFixed(2);
            }
        },
        {
            data: "rate",
            visible: true,
        },
        {
            data: "amount",
            visible: true,
            className: "amount_sum",
            render: function (data, type, full, meta) {
                return parseFloat(full.amount).toFixed(2);
            }
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
                    that
                    .search(this.value)
                    .draw();
                }
            });
        });
    },
    "fnDrawCallback": function (row, data, start, end, display) {
        var api = this.api();
        var total_amount = api.column(7, {page: 'current'}).data().sum();
        total_amount = parseFloat(total_amount).toFixed(2);
        $(api.column(7).footer()).html(total_amount);

        var total_quantity = api.column(3, {page: 'current'}).data().sum();
        $(api.column(3).footer()).html(total_quantity);

        var total_tax = api.column(5, {page: 'current'}).data().sum();
        total_tax = parseFloat(total_tax).toFixed(2);
        $(api.column(5).footer()).html(total_tax);

        var total_rate = api.column(6, {page: 'current'}).data().sum();
        total_rate = parseFloat(total_rate).toFixed(2);
        $(api.column(6).footer()).html(total_rate);
    }
});
   
/**********************************************************
 Intitalize Service Data Table
 ***********************************************************/
var table = $('.datatable-responsive-control-right-service').dataTable({
    autoWidth: false,
    processing: true,
    serverSide: true,
    language: {
        search: '<span>Filter:</span> _INPUT_',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
        emptyTable: 'No data currently available.'
    },
    // dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    dom: 'lBfrtip',
    buttons: [
        'csv',
        { text: 'print', className: 'service_print' },
    ],
    order: [[1, "desc"]],
    lengthMenu: [[10, 20, 30, 50, -1], [10, 20, 30, 50, "All"]],
    ajax: site_url + 'reports/get_service_net_sales/'+from_date+to_date,
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
            data: "estimate_id",
            visible: true,
        },
        {
            data: "name",
            visible: true,
        },
        {
            data: "qty",
            visible: true,
        },
        {
            render: function (data, type, full, meta) {
                if(full.discount_type_id != "") {
                    if(full.discount_type_id == "p") {
                        var discount_type = "%";    
                        var discount_amount = full.discount;
                        var discount = discount_amount + discount_type;
                    } else {
                        var discount_type = "$";   
                        var discount_amount = full.discount;
                        var discount = discount_type + discount_amount;
                    }
                }
                return discount;
            },
            visible: true,
            sortable: false,
        },
        {
            data: "tax_rate",
            visible: true,
            className: "tax_rate_sum",
            render: function (data, type, full, meta) {
                return parseFloat(full.tax_rate).toFixed(2);
            }
        },
        {
            data: "rate",
            visible: true,
        },
        {
            data: "amount",
            visible: true,
            className: "amount_sum",
            render: function (data, type, full, meta) {
                return parseFloat(full.amount).toFixed(2);
            }
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
                    that
                    .search(this.value)
                    .draw();
                }
            });
        });
    },
    "fnDrawCallback": function (row, data, start, end, display) {
        var api = this.api();

        var total_quantity = api.column(3, {page: 'current'}).data().sum();
        $(api.column(3).footer()).html(total_quantity);

        var total_tax = api.column(5, {page: 'current'}).data().sum();
        total_tax = parseFloat(total_tax).toFixed(2);
        $(api.column(5).footer()).html(total_tax);

        var total_rate = api.column(6, {page: 'current'}).data().sum();
        total_rate = parseFloat(total_rate).toFixed(2);
        $(api.column(6).footer()).html(total_rate);
    
        var total_amount = api.column(7, {page: 'current'}).data().sum();
        total_amount = parseFloat(total_amount).toFixed(2);
        $(api.column(7).footer()).html(total_amount);
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

$('.part_print').addClass('btn bg-teal-400 btn-labeled ml-2 mb-3').empty().html('<b><i class="icon-printer"></i></b> Print');

$('.service_print').addClass('btn bg-teal-400 btn-labeled ml-2 mb-3').empty().html('<b><i class="icon-printer"></i></b> Print');

$('.part_net_sale_print').addClass('btn bg-teal-400 btn-labeled ml-2 mb-3').empty().html('<b><i class="icon-printer"></i></b> Print');

$('.service_net_sale_print').addClass('btn bg-teal-400 btn-labeled ml-2 mb-3').empty().html('<b><i class="icon-printer"></i></b> Print');

// Print part Invoice detail
$(document).on('click', '.part_net_sale_print', function (e) {
    $("#fakeLoader").attr('style', '').html('');
    $(document).find("#fakeLoader").fakeLoader({
        timeToHide: 5000,
        spinner: "spinner5",
        bgColor: "",
    });
    var date = from_date+to_date;
    var url = updateQueryStringParameter(site_url + 'reports/print_part_invoice_detail_net_sales', "date", date);
    window.location.href = encodeURI(url);
});

// Print service Invoice detail
$(document).on('click', '.service_net_sale_print', function (e) {
    $("#fakeLoader").attr('style', '').html('');
    $(document).find("#fakeLoader").fakeLoader({
        timeToHide: 5000,
        spinner: "spinner5",
        bgColor: "",
    });
    var date = from_date+to_date;
    var url = updateQueryStringParameter(site_url + 'reports/print_service_invoice_detail_net_sales', "date", date);
    window.location.href = encodeURI(url);
});

// Print Part Data
$(document).on('click', '.part_print', function (e) {
    $("#fakeLoader").attr('style', '').html('');
    $(document).find("#fakeLoader").fakeLoader({
        timeToHide: 5000,
        spinner: "spinner5",
        bgColor: "",
    });
    var date = from_date+to_date;
    var url = updateQueryStringParameter(site_url + 'reports/print_part_net_sales', "date", date);
    window.location.href = encodeURI(url);
});

// Print Service Data
$(document).on('click', '.service_print', function (e) {
    $("#fakeLoader").attr('style', '').html('');
    $(document).find("#fakeLoader").fakeLoader({
        timeToHide: 5000,
        spinner: "spinner5",
        bgColor: "",
    });
    var date = from_date+to_date;
    var url = updateQueryStringParameter(site_url + 'reports/print_service_net_sales', "date", date);
    window.location.href = encodeURI(url);
});

function updateQueryStringParameter(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    } else {
        return uri + separator + key + "=" + value;
    }
}