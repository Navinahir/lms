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
            url: site_url + 'quickbook/get_customer',
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
            //     data: "id",
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
                data: "display_name_as",
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
                data: "created_date",
                visible: true,  
                sortable: false,
            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    action = '';
                    quickbook_customer_id = full.id;
                    if(session_set_status == 'yes')
                    {
                        if(full.quickbooks == 1)
                        {
                            action += '<a href="' + site_url + 'customers/add_to_quickbook/' + btoa(full.id) + '" class="btn custom_dt_action_button_quickbook btn-xs AddToQuickbook" data-customerid="' + btoa(full.id) + '" title="Add To Quickbook">add to quickbooks</a>';
                        }
                    }
                    action += '<a href="' + site_url + 'customers/view/' + btoa(full.id) + '" class="btn btn-xs custom_dt_action_button" title="View" >View</a>';

                    if (edit == 1) {
                        action += '<a href="' + site_url + 'customers/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs"title="Edit">Edit</a>';
                    }

                    if (dlt == 1) {
                        action += '<a href="' + site_url + 'customers/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
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
    var add_button = '<div class="text-right"><a id="add_to_customer" class="btn bg-teal-400 btn-labeled custom_add_button ladda-button" data-style="expand-right" data-size="l"><b><i class="icon-plus-circle2"></i></b> <span class="ladda-label">Add To Quicknbook</span></a></div>';
    $('.datatable-header').append(add_button); 
}

$(document).ready(function () {
    // Setup - add a text input to each footer cell
    $('.datatable-responsive-control-right tfoot th').each(function () {
        var title = $(this).text();
        if ($(this).hasClass('slct')) {
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        }
    });

    var unsync_id = $("#unsync_id").val();
    if(unsync_id == '')
    {
        $("#add_to_customer").css("display", "none");
    }
    $("#add_to_customer").click(function(e){
        e.preventDefault();
        if(unsync_id != '')
        {
            var l = Ladda.create(this);
            l.start();
            $.ajax({
                url: site_url + 'customers/add_to_quickbook',
                type: 'POST',
                dataType : "json",
                data: {unsync_id: unsync_id},
                success: function(data)
                {
                    l.stop();
                    if(data.error != null && data.error == "error")
                    {
                        location.reload(true);
                    }
                    if(data.session != null && data.session == "exprired")
                    {
                        location.reload(true);
                    }
                    if(data.success != null && data.success == "success")
                    {
                        location.reload(true);
                    }
                }
            });
        }
        else
        {
            alert("No record found");
        }
    });
});

/**********************************************************
 Confirm Alert
 ***********************************************************/
function confirm_alert(e) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF7043",
        confirmButtonText: "Yes, delete it!"
    }, function (isConfirm) {
        if (isConfirm) {
            window.location.href = $(e).attr('href');
            return true;
        } else {
            return false;
        }
    });
    return false;
}

$(window).keydown(function (event) {
    if (event.keyCode == 13) {
        event.preventDefault();
        return false;
    }
});

if (customer_id && customer_id != undefined) {
    $('#customers-notes').dataTable({
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
            url: site_url + 'customers/get_customer_notes',
            type: "GET",
            data: {
                customer_id: customer_id
            }
        },
        responsive: {
            details: {
                type: 'column',
                target: -1
            }
        },
        columns: [
            {
                data: "notes",
                visible: true,
            },
            {
                data: "created_date",
                visible: true,

            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    action = '';
                    action += '&nbsp;&nbsp;<button type="button" onclick="return add_edit_note_modal(' + customer_id + ',' + full.id + ');" class="btn custom_dt_action_button btn-xs"title="Edit">Edit</button>';
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'customers/note/delete/' + btoa(full.id) + '/' + btoa(full.customer_id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
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
            $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
        },
        "fnDrawCallback": function () {
            var info = document.querySelectorAll('.switchery-info');
            $(info).each(function () {
                var switchery = new Switchery(this, {color: '#95e0eb'});
            });
            $('.select[name=DataTables_Table_1_length]').addClass('form-control').css('width', '55%');
        }
    });
}