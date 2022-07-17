/**********************************************************
 Intitalize Data Table
 ***********************************************************/
var table = $('.datatable-responsive-control-right-invoice').dataTable({
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
        { text: 'print', className: 'invoice_print' },
    ],
    order: [[1, "desc"]],
    lengthMenu: [[10, 20, 30, 50, -1], [10, 20, 30, 50, "All"]],
    ajax: site_url + 'reports/get_gross_profit_invoice/'+from_date+to_date,
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
            data: "sub_total",
            visible: true,
            className: "sum",
            render: function (data, type, full, meta) {
                return parseFloat(full.sub_total).toFixed(2);
            }
        },
        {
            // data: "unit_cost",
            visible: true,
            sortable: false,
            className: "unit_cost",
            render: function (data, type, full, meta) {
                var invoice_id = full.id;
                var final_unit_cost = "0.00";
                $.ajax({
                    async: false,
                    global: false,
                    url: 'Reports/get_gross_profit_unit_cost',
                    type: 'POST',
                    dataType: 'json',
                    data: {from_date: from_date, to_date:to_date, invoice_id: invoice_id},
                    success: function(data){
                        if(data.id != "" && data.id != null)
                        {
                            if(invoice_id == data.id)
                            {
                               final_unit_cost = data.unit_cost;
                            }
                        } else {
                            final_unit_cost = "0.00";
                        }
                    }
                });
                return parseFloat(final_unit_cost).toFixed(2);
            }
        },
        {
            data: "shipping_charge",
            visible: true,
            className: "shipping_charge",
            render: function (data, type, full, meta) {
                return parseFloat(full.shipping_charge).toFixed(2);
            }
        },
        {
            data: "action",
            render: function (data, type, full, meta) {
                var action = '';
                invoice_id = full.id;
                action += '<a href="' + site_url + 'invoices/view/' + btoa(full.id) + '" class="btn btn-xs custom_dt_action_button" title="View">View</a>';
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
        var api = this.api();

        var total_cost = api.column(5, {page: 'current'}).data().sum();
        total_cost = parseFloat(total_cost).toFixed(2);
        $(api.column(5).footer()).html(total_cost);

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
    ajax: site_url + 'reports/get_gross_profit_part_data/'+from_date+to_date,
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
            render: function (data, type, full, meta) {
                return parseFloat(full.tax_rate).toFixed(2);
            }
        },
        {
            data: "unit_cost",
            visible: true,
            render: function (data, type, full, meta) {
                return parseFloat(full.unit_cost).toFixed(2);
            }
        },
        {
            data: "final_unit_cost",
            visible: true,
            render: function (data, type, full, meta) {
                return parseFloat(full.final_unit_cost).toFixed(2);
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
                    that
                    .search(this.value)
                    .draw();
                }
            });
        });
    },
    "fnDrawCallback": function (row, data, start, end, display) {
        var api = this.api();
        var total_cost = api.column(7, {page: 'current'}).data().sum();
        total_cost = parseFloat(total_cost).toFixed(2);
        $(api.column(7).footer()).html(total_cost);

        var total_quantity = api.column(3, {page: 'current'}).data().sum();
        $(api.column(3).footer()).html(total_quantity);

        var total_tax = api.column(5, {page: 'current'}).data().sum();
        total_tax = parseFloat(total_tax).toFixed(2);
        $(api.column(5).footer()).html(total_tax);

        var cost = api.column(6, {page: 'current'}).data().sum();
        cost = parseFloat(cost).toFixed(2);
        $(api.column(6).footer()).html(cost);
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
    ajax: site_url + 'reports/get_gross_profit_service_data/'+from_date+to_date,
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

$('.invoice_print').addClass('btn bg-teal-400 btn-labeled ml-2 mb-3').empty().html('<b><i class="icon-printer"></i></b> Print');

$('.part_print').addClass('btn bg-teal-400 btn-labeled ml-2 mb-3').empty().html('<b><i class="icon-printer"></i></b> Print');

$('.service_print').addClass('btn bg-teal-400 btn-labeled ml-2 mb-3').empty().html('<b><i class="icon-printer"></i></b> Print');

// Print Part Data
$(document).on('click', '.invoice_print', function (e) {
    $("#fakeLoader").attr('style', '').html('');
    $(document).find("#fakeLoader").fakeLoader({
        timeToHide: 5000,
        spinner: "spinner5",
        bgColor: "",
    });
    var date = from_date+to_date;
    var url = updateQueryStringParameter(site_url + 'reports/print_gross_profit_invoice_data', "date", date);
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
    var url = updateQueryStringParameter(site_url + 'reports/print_gross_profit_part_data', "date", date);
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
    var url = updateQueryStringParameter(site_url + 'reports/print_gross_profit_service_data', "date", date);
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