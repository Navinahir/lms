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
    order: [[0, "asc"]],
    ajax: site_url + 'locations/get_ajax_data',
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
            data: "name",
            visible: true,
        },
        {
            data: "description",
            visible: true,
        },
        {
            data: "is_active",
            visible: true,
            render: function (data, type, full, meta) {
                if (full.is_active == 1) {
                    action = '<span class="label bg-success-400">Active</span>';
                } else {
                    action = '<span class="label bg-danger-400">Blocked</span>';
                }
                return action;
            }
        },
        {
            data: "modified_date",
            visible: true,
        },
        {
            data: "action",
            render: function (data, type, full, meta) {
                action = '';
                if (full.is_active == 1) {
                    if (view == 1) {
                        action += '&nbsp;&nbsp;<a href="' + site_url + 'inventory_locations/view/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="View">View</a>';
                    }
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
    "fnDrawCallback": function () {
        var info = document.querySelectorAll('.switchery-info');
        $(info).each(function () {
            var switchery = new Switchery(this, {color: '#95e0eb'});
        });
    }
});

if (typeof (location_id) !== 'undefined') {
    $('.datatable-responsive-control-right').dataTable({
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
        order: [[0, "asc"]],
        ajax: site_url + 'locations/get_loc_item_ajax_data/' + location_id,
        responsive: {
            details: {
                type: 'column',
                target: -1
            }
        },
        fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            if (aData['quantity'] <= 1) {
                $('td', nRow).css('background-color', 'rgba(51, 169, 245, 0.08)');
            }
        },
        columns: [
            {
                data: "sr_no",
                visible: true,
                sortable: false
            },
            {
                data: "global_part_no",
                visible: true,
            },
            {
                data: "part_no",
                visible: true,
            },
            {
                data: "pref_vendor_name",
                visible: true,
            },
            {
                data: "quantity",
                visible: true,
            },
            {
                data: "modified_date",
                visible: true,
            },
            {
                data: "is_deleted",
                visible: false,
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
}

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});
$('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
