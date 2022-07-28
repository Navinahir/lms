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
    order: [[2, "desc"]],
    ajax: site_url + 'items/get_items_data_trash',
    responsive: {
        details: {
            type: 'column',
            target: -1
        }
    },
    columns: [
        {
            data: "id",
            render: function (data, type, full, meta) {
                var checkbox = '';
                checkbox = '<input type="checkbox" class="checkbox" id="'+btoa(full.id)+'" style="height: 20px; width: 20px;">';
                return checkbox;
            },
            visible: true,
            sortable: false,
        },
        {
            data: "start_date",
            visible: true,
            render: function (data, type, full, meta) {
                var days_left = '';
                var start_date = full.start_date;    
                var d = new Date();
                var month = d.getMonth()+1;
                var day = d.getDate();
                var current_date = d.getFullYear() + '-' +
                    ((''+month).length<2 ? '0' : '') + month + '-' +
                    ((''+day).length<2 ? '0' : '') + day;
                var days_left = daysdifference(start_date, current_date);
                                
                function daysdifference(firstDate, secondDate){
                    var startDay = new Date(firstDate);
                    var endDay = new Date(secondDate);
                    var millisBetween = startDay.getTime() - endDay.getTime();
                    var days_left = millisBetween / (1000 * 3600 * 24) + 1;
                    return Math.round(Math.abs(days_left) - 30);
                }

                var day_s = '';
                var day_color = '';
                if(Math.abs(days_left) > 1) {
                    day_s = 'Days';
                } else {
                    day_s = 'Day';
                }
                if(Math.abs(days_left) < 10) {
                    day_color = 'red';
                } else {
                    day_color = 'black';
                }
                
                var final_days = '<span style="color: '+ day_color +'; ">' + Math.abs(days_left) + day_s + '</span>';
                return final_days;
            },
            sortable: false,
        },
        {
            data: "sr_no",
            visible: true,
            sortable: false,
        },
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
                }
                return result;
            },
            sortable: false,
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
            data: "action",
            render: function (data, type, full, meta) {
                action = '';
                action += '<a href="javascript:void(0);" class="btn btn-xs custom_dt_action_button item_view_btn" title="View" id="\'' + btoa(full.id) + '\'">View</a>';
                action += '<a href="' + site_url + 'items/trash_recover_item/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_recover_alert(this)" title="Recover">Recover</a>';
                action += '<a href="' + site_url + 'items/trash_delete_item/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_delete_alert(this)" title="Delete">Delete</a>';
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
});

/**********************************************************
 Item View Popup
 ***********************************************************/
$(document).on('click', '.item_view_btn', function () {
    $('#custom_loading').removeClass('hide');
    $('#custom_loading').css('display', 'block');
    $.ajax({
        url: site_url + 'items/get_trash_item_data_ajax_by_id',
        type: "POST",
        data: {id: this.id},
        success: function (response) {
            $('#custom_loading').removeClass('hide');
            $('#custom_loading').css('display', 'none');
            $('#items_view_body').html(response);
            $('#items_view_modal').modal('show');
        }
    });
});

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});

$('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
var delete_recover_button = '<div class="text-right"><a href="javascript:void(0);" class="btn bg-teal-400 btn-labeled custom_recover_button mb-3"><b><i class="icon-plus-circle2"></i></b> Recover</a>&nbsp;<a href="javascript:void(0);" class="btn bg-danger-400 btn-labeled custom_delete_button mb-3"><b><i class="icon-trash"></i></b> Delete</a></div>';
$('.datatable-header').append(delete_recover_button);    

// Recover multiple estimate
$(document).on('click','.custom_recover_button',function(){
    var recover_id = [];
    $(".checkbox:checked").each(function(){    
        var id = $(this).attr('id');
        recover_id.push(id);
    });
    if(recover_id != "")
    {
        swal({
            title: "Are you sure?",
            text: "Are you sure you want to recover estimate?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#FF7043",
            confirmButtonText: "Yes, Recover it!"
        }, function () {
            $.ajax({
                url: 'items/recover_multiple',
                method: 'POST',
                data: {recover_id : recover_id},
                success: function(data){
                    if(data == 0) {
                        alert('Something going wrong.');
                    } else {
                        window.location.href = site_url + 'items';
                    }
                },
            });
        });
    } else {
        alert("Please select some records to recover.");
    }
});

// Delete multiple estimate
$(document).on('click','.custom_delete_button',function(){
    var delete_id = [];
    $(".checkbox:checked").each(function(){    
        var id = $(this).attr('id');
        delete_id.push(id);
    });
    if(delete_id != "")
    {
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#FF7043",
            confirmButtonText: "Yes, Delete it!"
        }, function () {
            $.ajax({
                url: 'items/delete_multiple',
                method: 'POST',
                data: {delete_id : delete_id},
                success: function(data){
                    if(data == 0) {
                        alert('Something going wrong.');
                    } else {
                        window.location.href = site_url + 'items/items_trash';
                    }
                },
            });
        });
    } else {
        alert("Please select some records to delete.");
    }
});

// Checkbox check uncheck
$(document).on('change','#check_all',function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});

/**********************************************************
 Confirm recover alert
 ***********************************************************/
function confirm_recover_alert(e) {
    swal({
        title: "Are you sure?",
        text: "Are You sure you want to recover this item?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF7043",
        confirmButtonText: "Yes, Recover it!"
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

/**********************************************************
 Confirm delete alert
 ***********************************************************/
function confirm_delete_alert(e) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF7043",
        confirmButtonText: "Yes, Delete it!"
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