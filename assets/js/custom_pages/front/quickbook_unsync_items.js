var quickbook_item_id = 0;
$(document).ready(function () {
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
        ajax: site_url + 'quickbook/get_item',
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
          /*  {
                data: "id",
                visible: true,
                sortable: false,
            },*/
            {
                data: "dimage",
                visible: true,
                className: 'slct',
                render: function (data, type, full, meta) {
                    
                    if((full.dimage == null ||  full.dimage == "") && (full.image == null || full.image == ""))
                    {
                        result = '<a href="uploads/items/no_image.jpg" data-popup="lightbox"><img src="uploads/items/no_image.jpg" style="height:30px"></a>';
                    } 
                    else if (full.image != null) 
                    {
                        result = '<a href="uploads/items/'+full.image+'" data-popup="lightbox"><img src="uploads/items/'+full.image+'" style="height:30px"></a>';
                    } else {
                        result = '<a href="uploads/items/'+full.dimage+'" data-popup="lightbox"><img src="uploads/items/'+full.dimage+'" style="height:30px"></a>';                 
                        // result = '<a class="img_opn" href="javascript:void(0);" data-imgpath="uploads/items/'+full.dimage+'"><img src="uploads/items/'+full.dimage+'" style="height:30px"/></a>';
                    }
                    return result;

                }
            },
            {
                data: "part_no",
                visible: true,
                className: 'slct',
                render: function (data, type, full, meta) {
                    result = '<b>Item Part No : </b>' + full.part_no;
                    if (full.internal_part_no != null)
                        result += '<br/><b>Alternate Part No or SKU : </b>' + full.internal_part_no;
                    return result;
                }
            },
            {
                data: "description",
                visible: true,
                className: 'slct',
                render: function (data, type, full, meta) {
                    return full.description.substring(0,30);
                }
            },
            {
                data: "pref_vendor_name",
                visible: true,
                className: 'slct',
                render: function (data, type, full, meta) {
                    result = full.pref_vendor_name;
                    return result;
                }
            },
            {
                data: "retail_price",
                visible: true,
                className: 'slct',
            },
            {
                data: "total_quantity",
                visible: true,
                className: 'slct',
                render: function (data, type, full, meta) {
                    if (full.total_quantity > 0) {
                        action = '<span class="label bg-success-400">' + full.total_quantity + ' - IN STOCK</span>';
                    } else {
                        action = '<span class="label bg-danger-400">OUT OF STOCK</span>';
                    }
                    return action;
                }
            },
            {
                data: "dept_name",
                visible: true,
                className: 'slct',
                render: function (data, type, full, meta) {
                    return full.dept_name;
                }
            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    action = '';
                    quickbook_item_id = full.id;
                    if(session_set_status == 'yes')
                    {
                        if(full.quickbooks == 1)
                        {
                            action += '<a href="' + site_url + 'items/add_to_quickbook/' + btoa(full.id) + '" class="btn custom_dt_action_button_quickbook btn-xs AddToQuickbook" title="Add To Quickbook">add to quickbooks</a>';
                        }
                    }
                    action += '<a target="_blank" href="' + site_url + 'items/print-label/' + btoa(full.id) + '" class="btn btn-xs custom_dt_action_button" title="Print Label" >Print Label</a>';
                    action += '<a href="javascript:void(0);" class="btn btn-xs custom_dt_action_button item_view_btn" title="View" id="\'' + btoa(full.id) + '\'">View</a>';
                    if (edit == 1) {
                        action += '<a href="' + site_url + 'items/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Edit</a>';
                    }
                    if (dlt == 1) {
                        action += '<a href="' + site_url + 'items/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
                    }
                    if (full.total_quantity <= 1) {
                        action += '<a href="' + site_url + 'receive_inventory/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Add Inventory">Add Inventory</a>';
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
        },
        "fnDrawCallback": function () {
            var info = document.querySelectorAll('.switchery-info');
            $(info).each(function () {
                var switchery = new Switchery(this, {color: '#95e0eb'});
            });
        }
    });

    var add_button = '<div class="text-right"><a id="add_to_item" class="btn bg-teal-400 btn-labeled custom_add_button ladda-button" data-style="expand-right" data-size="l"><b><i class="icon-plus-circle2"></i></b> <span class="ladda-label">Add To Quicknbook</span></a></div>';
    $('.datatable-header').append(add_button);  
    var unsync_id = $("#unsync_id").val();
    if(unsync_id == '')
    {
        $("#add_to_item").css("display", "none");
    }
    $("#add_to_item").click(function(e){
        e.preventDefault();
        if(unsync_id != '')
        {
            var l = Ladda.create(this);
            l.start();
            $.ajax({
                url: site_url + 'items/add_to_quickbook',
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

