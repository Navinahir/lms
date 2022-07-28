/**
 * @author  KBH
 */
get_type_value = 0;
$(document).ready(function () {
    var url = site_url + 'quickbook/get_items';

    datatble(url);
});

function datatble(url){
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
        ajax: url,
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
                data: "part_no",
                visible: true,
                className: 'slct',
                render: function (data, type, full, meta) {
                    result = '<b>Item Part No : </b>' + full.part_no;
                    if (full.sku != null)
                        result += '<br/><b>Alternate Part No or SKU : </b>' + full.sku;
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
                data: "retail_price",
                visible: true,
                className: 'slct',
                render: function (data) {
                    return "$" + parseFloat(data).toFixed(2);
                }
            },
            {
                data: "qty_on_hand",
                visible: true,
                className: 'slct',
            }, 
            {
                data: "qtyOnHand",
                visible: true,
                className: 'slct',
            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    action = '';
                    quickbook_item_id = full.id;
                    action += '<a href="' + site_url + 'quickbook/update_qty/' + btoa(full.qtyOnHand) + '/' + btoa(full.ark_id) +'" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Edit">Update</a>';
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


               /* $('input', this.footer()).on('keyup change', function () {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });*/
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

$('.dataTables_filter input').keyup(function(e) {
    table.search( this.value ).draw();
});

/**********************************************************
 Confirm Alert
 ***********************************************************/
function confirm_alert(e) {
    swal({
        title: "Are you sure?",
        text: "Are you sure you want to update your record!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF7043",
        confirmButtonText: "Yes, update it!"
    },
            function (isConfirm) {
                if (isConfirm) {
                    window.location.href = $(e).attr('href');
                    return true;
                } else {
                    return false;
                }
            });
    return false;
}

$(document).on("change", "#get_type", function (e) {
    e.preventDefault();
    var get_type_value =  $(this).val();
     if(get_type_value == '1' || get_type_value == 1){
        url = site_url + 'quickbook/out_sync_get_items';
    }else{
        url = site_url + 'quickbook/get_items';
    }
    $('.datatable-responsive-control-right').dataTable().fnDestroy();
    datatble(url);
});

$(document).on("click","#sync_all", function (e){
    var l = Ladda.create(this);
    l.start();
    $.ajax({
        url: site_url + 'quickbook/sync_all_items_qty',
        type: 'POST',
        dataType : "json",
        success: function(data){
            l.stop();
            if(data.session != null && data.session == "session")
            {
                location.reload(true);
            }
            if(data.success != null && data.success == "success")
            {
                location.reload(true);
            }
        }
    });
});
