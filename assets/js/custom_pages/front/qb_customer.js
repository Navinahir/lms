/**
 * Validate float value upto two decimal places
 * @param {object} el
 * @param {event} evt
 * @returns {Boolean}
 * @author KBH
 */
var quickbook_customer_id = 0;
$(document).ready(function () {
    $('.date').pickadate({
        format: 'mm/dd/yy',
        formatSubmit: 'yyyy/mm/dd',
        today: 'Today',
        clear: 'Clear',
        close: 'Close',
        selectMonths: true,
        selectYears: true
    });

    $('#ordered_from_id').on('change', function () {
        var part_no = $(this).find('option:selected').attr('data-part-no');
        var item_id = $(this).find('option:selected').attr('data-item-id');

        if (part_no) {
            $('#vendor_part').val(part_no);
            $('#item_id').val(item_id);
        }
    });
})

/**********************************************************
 Intitalize Data Table
 ***********************************************************/
$(function () {
    bind();
});

function bind() {

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
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[0, "desc"]],
        ajax: {
            url: site_url + 'quickbook/qb_customer_ajax',
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
            }, 
            // {
            //     data: "qb_id",
            //     visible: true,
            // }, 
            {
                data: "first_name",
                visible: true,
                render: function (data, type, full, meta) {
                    return full.first_name + ' ' + full.last_name;
                }
            },
            {
                data: "company",
                visible: true,
            },
            {
                data: "phone",
                visible: true,
                sortable: false,
            },
            {
                data: "mobile",
                visible: true,
                sortable: false,
            },
            {
                data: "email",
                visible: true,
                sortable: false,
            },
            {
                data: "fax",
                visible: true,
                sortable: false,
            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    action = '';
                    if(full.quickbook == 1)
                    {
                        action += '<a class="btn custom_dt_action_button_quickbook btn-xs addtoark" data-customerid="' + btoa(full.qb_id) + '" title="Add To Quickbook">add to ARK</a>';
                        action += '<a class="btn custom_dt_action_button_quickbook btn-xs loaderview-btn" data-customerid="' + btoa(full.qb_id) + '" title="Add To Quickbook"><div class="loader"></div></a>';
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
            var info = document.querySelectorAll('.switchery-info');
            $(info).each(function () {
                var switchery = new Switchery(this, {color: '#95e0eb'});
            });
        }
    });
}

$(document).on('click', '.addtoark', function(e) {
    e.preventDefault();
    let qb_customer_id = $(this).attr('data-customerid');
    $(this).hide();
    $(this).next('.loaderview-btn').show();
    $.ajax({
        url: site_url + 'customers/add_customer_to_ark',
        type: 'POST',
        dataType: 'json',
        data: {
            qb_customer_id: qb_customer_id
        },
        success: function(data){
            $('.loader').hide();
            if(data.success == true){
                swal(data.message, 'success');
            }else{
                $('.addtoark').show();
                swal(data.message, 'error');
            }
        }
    });
});
