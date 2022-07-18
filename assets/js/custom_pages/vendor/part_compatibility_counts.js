/**
 * Validate float value upto two decimal places
 * @param {object} el
 * @param {event} evt
 * @returns {Boolean}
 * @author HGA
 */

/**********************************************************
 Intitalize Data Table
 ***********************************************************/
$(function () {
    bind();
});

$('#filter_make').on('change', function () {
    var selected_val = $(this).find('option:selected').val();
    $.ajax({
        url: site_url + 'vendor/home/change_make_get_ajax',
        dataType: "json",
        type: "POST",
        data: {id: selected_val},
        success: function (response) {
            $('#filter_model').html(response);
            $('#filter_model').select2({containerCssClass: 'select-sm'});
        }
    });

    $(".datatable-responsive-control-right").dataTable().fnDestroy();
    bind();
});

$('#filter_model').on('change', function () {
    $(".datatable-responsive-control-right").dataTable().fnDestroy();
    bind();
});

function bind() {
    var table = $('.datatable-responsive-control-right').dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        dom: 'lBfrtip',
        buttons: [
            {
                className: 'btn btn-primary',
                extend: 'print',
                text: 'Print selected'
            },
            {
                className: 'btn btn-primary',
                extend: 'csv',
                text: 'Download Report'
            }
        ],
        select: true,
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
            emptyTable: 'No data currently available.'
        },
        order: [[0, "desc"]],
        ajax: {
            url: site_url + 'vendor/reports/get_parts_compatability_data_count',
            data: {
                make_id: $('#filter_make').find('option:selected').val(),
                model_id: $('#filter_model').find('option:selected').val(),
            },
            type: "GET"
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
                data: "make_name",
                visible: true,
            },
            {
                data: "model_name",
                visible: true,
            },
            {
                data: "year_name",
                visible: true,
            },
            {
                data: "compatible_parts ",
                render: function (data, type, full, meta) {
                    if (full.compatible_parts > 0) {
                        action = '<span class="label label-primary">' + full.compatible_parts + '</span>';
                    } else {
                        action = '<span class="label label-danger">' + full.compatible_parts + '</span>';
                    }
                    return action;
                },
                visible: true,
                sortable: true
            }
        ],
        initComplete: function () {
            $(".loader-outer").addClass('hide');
//            $("#spinner").addClass('hide');

            this.api().columns('.slct', {page: 'current'}).every(function () {
                var that = this;

                $('input', this.footer()).on('keyup change', function () {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });

            $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');

            $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 'auto'
            });
        },
        "fnDrawCallback": function () {
            $(".loader-outer").addClass('hide');
            $("#spinner").addClass('hide');
            var info = document.querySelectorAll('.switchery-info');
            $(info).each(function () {
                var switchery = new Switchery(this, {color: '#95e0eb'});
            });
        },
        "preDrawCallback": function () {
//            debugger
            // gather info to compose a message
            $("#spinner").removeClass('hide');
            return true;
        },
    });
}