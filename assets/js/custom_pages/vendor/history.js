/**********************************************************
                Intitalize Data Table
***********************************************************/
var table = $('.datatable-responsive-control-right').dataTable({
    autoWidth: false,
    processing: true,
    serverSide: true,
    language: {
        search: '<span>Filter:</span> _INPUT_',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' },
        emptyTable: 'No data currently available.'
    },
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    order: [[0, "desc"]],
    ajax: site_url + 'vendor/history/get_history_data',
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
            data: "created_date",
            visible: true,
        },
        {
            data: "part_no",
            visible: true,
        },
        {
            data: "action",
            visible: true,
            className: 'slct',
            render: function (data, type, full, meta) {
                if (full.action == 'added') {
                    var lable_class = 'bg-blue';
                } else if (full.action == 'edited') {
                    var lable_class = 'bg-success';
                } else if (full.action == 'deleted') {
                    var lable_class = 'bg-danger';
                }
                return '<span class="label ' + lable_class + '">' + full.action + '</span>';
            }
        },
        {
            data: "vendor_id",
            visible: true,
            className: 'slct',
            render: function (data, type, full, meta) {
                return 'Item ' + full.part_no + ' was ' + full.action + ' at ' + full.date;
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
        this.api().columns('.slct', { page: 'current' }).every(function () {
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
            var switchery = new Switchery(this, { color: '#95e0eb' });
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