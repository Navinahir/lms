$('.datatable-basic').dataTable({
    autoWidth: false,
    processing: true,
    serverSide: true,
    language: {
        search: '<span>Filter:</span> _INPUT_',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
        emptyTable: 'No data currently available.'
    },
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    order: [[0, "desc"]],
    ajax: site_url + 'inventory/invoice_inventory_history_ajax_data',
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
            sortable: false
        },
        {
            data: "created_date",
            visible: true,
        },
        {
            data: "its_for",
            visible: true,
            sortable: false
        },
        {
            data: "user_name",
            visible: true,
        },
        {
            data: "part_no",
            visible: true,
        },
        {
            data: "invdesc",
            render: function (data, type, full, meta) {
                final_description = '';
                final_description = '<textarea disabled style="width: 100%;">'+ full.invdesc + ' ' + full.part_no + ' ' + full.invdesc2 + '</textarea>';
                return final_description;
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
    }
});

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});
$('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
