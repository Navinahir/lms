/**
 * Validate float value upto two decimal places
 * @param {object} el
 * @param {event} evt
 * @returns {Boolean}
 * @author KBH
 */
var invoice_id = 0;

$(document).ready(function () {
    $('.estimate_date').pickadate({
        format: date_format,
        formatSubmit: 'yyyy/mm/dd',
        today: 'Today',
        clear: 'Clear',
        close: 'Close',
        selectMonths: true,
        selectYears: true,
        // onRender: function() {
        //    $('.picker__table tr td').find('.picker__day').removeClass('picker__day--highlighted').removeClass('picker__day--selected');
        // }
    });
    $('.expiry_date').pickadate({
        format: date_format,
        formatSubmit: 'yyyy/mm/dd',
        today: 'Today',
        clear: 'Clear',
        close: 'Close',
        selectMonths: true,
        selectYears: true
    });
});


// Display the preview of image on image upload
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var html = '<img src="' + e.target.result + '" style="width: 58px; height: 58px; border-radius: 2px;" alt="">';
            $('#image_preview_div').html(html);
            $('#item_image_hidden').val('');
        }   
        reader.readAsDataURL(input.files[0]);
    }
}
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
        paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
        emptyTable: 'No data currently available.'
    },
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    order: [[0, "desc"]],
    ajax: site_url + 'quickbook/get_invoice',
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
     /*   {
            data: "id",
            visible: true,
            sortable: false,
        },*/
        {
            data: "estimate_date",
            visible: true,
        },
        {
            data: "estimate_id",
            visible: true,
        },
        {
            data: "cust_name",
            visible: true,
        },
        {
            data: "full_name",
            visible: true,
        },
        {
            data: "is_sent",
            visible: true,
            render: function (data, type, full, meta) {
                var action = '';
                if (full.is_sent == 1) {
                    action = '<span class="label bg-blue-400">BILLED</span>';
                } else if (full.is_paid == 1) {
                    action = '<span class="label bg-success-400">PAID</span>';
                } else {
                    action = '<span class="label bg-orange-400">DRAFT</span>';
                }
                return action;
            }

        },
        {
            data: "total",
            visible: true,
            render: function (data, type, full, meta) {
                return parseFloat(full.total).toFixed(2);
            }

        },
        {
            data: "action",
            render: function (data, type, full, meta) {
                var action = '';
                invoice_id = full.id;
                if(session_set_status == 'yes')
                {
                    if(full.quickbooks == 1)
                    {
                        action += '<a href="' + site_url + 'invoices/add_to_quickbook/' + btoa(full.id) + '" class="btn custom_dt_action_button_quickbook btn-xs AddToQuickbook" title="add to quickbooks">add to quickbooks</a>';
                    }
                }
                action += '<a href="' + site_url + 'invoices/view/' + btoa(full.id) + '" class="btn btn-xs custom_dt_action_button" title="View">View</a>';
                if (edit == 1) {
//                    if (full.is_deducted == 0 && full.is_sent == 0) {
                    action += '<a href="' + site_url + 'invoices/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Edit</a>';
//                    }
                }
                if (dlt == 1) {
                    action += '<a href="' + site_url + 'invoices/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
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
var add_button = '<div class="text-right"><a id="add_to_invoice" class="btn bg-teal-400 btn-labeled custom_add_button ladda-button" data-style="expand-right" data-size="l"><b><i class="icon-plus-circle2"></i></b> <span class="ladda-label">Add To Quicknbook</span></a></div>';
$('.datatable-header').append(add_button);

var unsync_id = $("#unsync_id").val();
if(unsync_id == '')
{
    $("#add_to_invoice").css("display", "none");
}

$("#add_to_invoice").click(function(e){
    e.preventDefault();
    if(unsync_id != '')
    {
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: site_url + 'invoices/add_to_quickbook',
            type: 'POST',
            dataType : "json",
            data: {unsync_id: unsync_id},
            success: function(data)
            {
                l.stop();
                if(data.success != null && data.success == "success")
                {
                    window.location.reload(true)
                }
                if(data.customer != null && data.customer == "not available")
                {
                    console.log("if success");
                    location.reload(true);
                }
                if(data.parts != null && data.parts == "not available")
                {
                    location.reload(true);
                }
                if(data.services != null && data.services == "not available")
                {
                    location.reload(true);
                }
                if(data.error != null && data.error == "error")
                {
                    location.reload(true);
                }
                if(data.session != null && data.session == "exprired")
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