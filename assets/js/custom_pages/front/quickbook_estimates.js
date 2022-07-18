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
    buttons: [
            {
                text: 'My button',
                action: function ( e, dt, node, config ) {
                    alert( 'Button activated' );
                }
            }
        ],
    order: [[0, "desc"]],
    ajax: site_url + 'quickbook/get_estimate',
    
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
       /* {
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
                if (full.is_save_draft == 1) {
                    action = '<span class="label bg-teal-400">DRAFT</span>';
                } else {  
                    if (full.is_sent == 0) {
                        action = '<span class="label bg-orange-400">NOT SENT</span>';
                    } else if (full.is_sent == 1) {
                         action = '<span class="label bg-orange-400">NOT SENT</span>';
                    } else if (full.is_sent == 2) {
                         action = '<span class="label bg-success-400">SENT</span>';
                    }
                }
                return action;
            }
        },
        // {
        //     data: "is_sent",
        //     visible: true,
        //     render: function (data, type, full, meta) {
        //         var action = '';
        //         if (full.turn_invoiced == 1) {
        //             action = '<span class="label bg-teal-400">INVOICED</span>';
        //         } else {
        //             if (full.is_sent == 1) {
        //                 action = '<span class="label bg-success-400">SENT</span>';
        //             } else if (full.is_save_draft == 1) {
        //                 action = '<span class="label bg-blue-400">DRAFT</span>';
        //             }
        //         }
        //         return action;
        //     }

        // },
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
                action = '';
                estimate_id = full.id;
                if(session_set_status == 'yes')
                {
                    if(full.quickbooks == 1)
                    {
                        action += '<a href="' + site_url + 'estimates/add_to_quickbook/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs AddToQuickbook" title="Add To Quickbook" >Add To Quickbook</a>';
                    }
                }
                action += '<a href="' + site_url + 'estimates/view/' + btoa(full.id) + '" class="btn btn-xs custom_dt_action_button" title="View">View</a>';
                if (edit == 1) {
                    action += '<a href="' + site_url + 'estimates/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Edit</a>';
                }
                if (dlt == 1) {
                    action += '<a href="' + site_url + 'estimates/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
                }
                action += '<a href="' + site_url + 'invoices/copy_invoice?estimate=' + btoa(full.id) + '" class="btn btn-xs custom_dt_action_button" title="Copy to Invoice">Copy to Invoice</a>';
                // action += '&nbsp;&nbsp;<a href="' + site_url + 'invoices/add?estimate=' + btoa(full.id) + '" class="btn btn-xs custom_dt_action_button" title="Copy to Invoice">Copy to Invoice</a>';

                // action += '&nbsp;&nbsp;<a href="' + site_url + 'invoices/add/' + btoa(full.id) + '/1" class="btn btn-xs custom_dt_action_button" title="Copy to Invoice">Copy to Invoice</a>';
                // action += '&nbsp;&nbsp;<a href="' + site_url + 'invoices/edit/' + btoa(full.id) + '/1" class="btn btn-xs custom_dt_action_button" title="Copy to Invoice">Copy to Invoice</a>';
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
      /*  $(".dataTables_length")
        {
            this.html('<button type="button" id="any_button">Click Me!</button>');   
        }*/
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
var add_button = '<div class="text-right"><a id="add_to_estimate" class="btn btn-primary btn-labeled btn-lg ladda-button" data-style="expand-right" data-size="l"><b><i class="icon-plus-circle2"></i></b><span class="ladda-label">Add to Quickbooks</span></a></div>';
$('.datatable-header').append(add_button);

var unsync_id = $("#unsync_id").val();
if(unsync_id == '')
{
    $("#add_to_estimate").css("display", "none");
}

$("#add_to_estimate").click(function(e){
    e.preventDefault();
    if(unsync_id != '')
    {
        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: site_url + 'estimates/add_to_quickbook',
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