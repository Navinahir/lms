$(document).ready(function () {
    $(".touchspin-empty").TouchSpin({
        min: 0,
        max: 100000,
        step: 1,
        booster: true,
        mousewheel: false
    });
});
/**
* Validate float value upto two decimal places
* @param {object} el
* @param {event} evt
* @returns {Boolean}
* @author PAV
*/
function validateFloatKeyPress(el, evt) {
    var charCode = (evt.which) ? evt.which : evt.charCode;
    var number = el.value.split('.');
    if (charCode != 46 && charCode != 0 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    //just one dot
    if (number.length > 1 && charCode == 46) {
        return false;
    }
    //get the carat position
    var caratPos = getSelectionStart(el);
    var dotPos = el.value.indexOf(".");
    if (caratPos > dotPos && dotPos > -1 && (number[1].length > 1)) {
        return false;
    }
    return true;
}

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
        paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' },
        emptyTable: 'No data currently available.'
    },
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    order: [[0, "desc"]],
    ajax: site_url + 'vendor/reports/get_items_data',
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
        // {
        //     data: "business_name",
        //     visible: true,
        //     className: 'slct',
        //     responsivePriority: 1,
        //     render: function (data, type, full, meta) {
        //         result = '<b>User Company : </b>' + full.business_name;
        //         result += '<br/><b>Email : </b>' + full.email_id;
        //         result += '<br/><b>Contact No.: </b>' + full.contact_number;
        //         return result;
        //     }
        // },
        {
            data: "global_part_no",
            visible: true,
            render: function (data, type, full, meta) {
                if (full.global_part_no != null) {
                    result = '<b>Global Part No : </b>' + full.global_part_no;
                } else {
                    result = '<b>Non Global Part</b>';
                }
                return result;
            }
        },
        {
            data: "part_no",
            visible: true,
            render: function (data, type, full, meta) {
                result = '<b>Part No : </b>' + full.part_no;
                if (full.global_part_no != null) {
                    if (full.internal_part_no != null)
                        result += '<br/><b>Alternate Part No or SKU : </b>' + full.internal_part_no;
                    if (full.item_description != null)
                        result += '</br><b>Description : </b>' + full.item_description;
                } else {
                    if (full.user_internal_part_no != null)
                        result += '<br/><b>Alternate Part No or SKU : </b>' + full.user_internal_part_no;
                    if (full.user_item_description != null)
                        result += '</br><b>Description : </b>' + full.user_item_description;
                }
                return result;
            }
        },
        {
            data: "dept_name",
            visible: true,
            render: function (data, type, full, meta) {
                return full.dept_name;
            }
        },
        // {
        //     data: "compatibility",
        //     visible: true,
        //     className: 'slct',
        //     render: function (data, type, full, meta) {
        //         var com = '';
        //         if (full.compatibility != null) {
        //             var res = full.compatibility.split(',');
        //             $.each(res, function (index, value) {
        //                 if (index > 0) {
        //                     if (index % 2 == 0)
        //                         com += "<br/><span class='label border-left-primary label-striped mt-5 text-bold' style='font-size: smaller;'>" + value + "</span>";
        //                     else
        //                         com += "<span class='label border-left-primary label-striped mt-5 ml-5 text-bold' style='font-size: smaller;'>" + value + "</span>";
        //                 } else {
        //                     com += "<span class='label border-left-primary label-striped text-bold' style='font-size: smaller;'>" + value + "</span>";
        //                 }
        //             });
        //         }
        //         return com;
        //     }
        // },
        // {
        //     data: "total_quantity",
        //     visible: true,
        //     className: 'slct',
        //     render: function (data, type, full, meta) {
        //         if (full.total_quantity > 0) {
        //             action = '<span class="label bg-success-400">' + full.total_quantity + ' - IN STOCK</span>';
        //         } else {
        //             action = '<span class="label bg-danger-400">OUT OF STOCK</span>';
        //         }
        //         return action;
        //     }
        // },
        {
            data: "action",
            render: function (data, type, full, meta) {
                action = '';
                action += '<a href="javascript:void(0);" class="btn btn-xs custom_dt_action_button item_view_btn" title="View" id="\'' + btoa(full.id) + '\'">View</a>';
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
        this.api().columns('.slct', { page: 'current' }).every(function () {
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
            var switchery = new Switchery(this, { color: '#95e0eb' });
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

/**********************************************************
                    Item View Popup
***********************************************************/
$(document).on('click', '.item_view_btn', function () {
    $('#custom_loading').removeClass('hide');
    $('#custom_loading').css('display', 'block');
    $.ajax({
        url: site_url + 'vendor/reports/get_item_data_ajax_by_id',
        type: "POST",
        data: { id: this.id },
        success: function (response) {
            $('#custom_loading').removeClass('hide');
            $('#custom_loading').css('display', 'none');
            $('#items_view_body').html(response);
            $('#items_view_modal').modal('show');
        }
    });
});
