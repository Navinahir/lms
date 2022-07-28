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
    ajax: site_url + 'reports/get_taxable_service_sales/'+from_date+to_date,
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
            data: "tax_rate",
            visible: true,
            className: "tax_rate_sum",
            render: function (data, type, full, meta) {
                return parseFloat(full.tax_rate).toFixed(2);
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

        var total_tax = api.column(4, {page: 'current'}).data().sum();
        total_tax = parseFloat(total_tax).toFixed(2);
        $(api.column(4).footer()).html(total_tax);
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

$('.buttons-csv').addClass('btn bg-teal-400 btn-labeled mb-2').empty().html('<b><i class="icon-download"></i></b> Download Reports');

$('.service_print').addClass('btn bg-teal-400 btn-labeled ml-2 mb-2').empty().html('<b><i class="icon-printer"></i></b> Print');

// var service_pdf_button = '&nbsp;<button type="button" class="btn bg-teal-400 btn-labeled service_pdf_button"><b><i class="icon-printer"></i></b> Print</button>';
// $('.dt-buttons').append(service_pdf_button);

// Print Service Data
$(document).on('click', '.service_print', function (e) {
    $("#fakeLoader").attr('style', '').html('');
    $(document).find("#fakeLoader").fakeLoader({
        timeToHide: 5000,
        spinner: "spinner5",
        bgColor: "",
    });
    var date = from_date+to_date;
    var url = updateQueryStringParameter(site_url + 'reports/print_total_taxable_service_sales', "date", date);
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