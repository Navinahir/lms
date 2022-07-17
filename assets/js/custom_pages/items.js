$(document).ready(function () {
    $(".touchspin-empty").TouchSpin({
        min: 0,
        max: 1000000000,
        step: 1,
        booster: true
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
// function readURL(input) {
//     if (input.files && input.files[0]) {
//         var reader = new FileReader();
//         reader.onload = function (e) {
//             var html = '<img src="' + e.target.result + '" style="width: 58px; height: 58px; border-radius: 2px;" alt="">';
//             $('#image_preview_div').html(html);
//         }
//         reader.readAsDataURL(input.files[0]);
//     }
// }

/**********************************************************
                Intitalize Data Table
***********************************************************/
$('.datatable-responsive-control-right').dataTable({
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
    ajax: site_url + 'admin/inventory/get_items_data',
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
            data: "part_no",
            visible: true,
            render: function (data, type, full, meta) {
                result = '<b>Part No : </b>' + full.part_no;
                result += '<br><b>Alternate Part No or SKU : </b>' + full.internal_part_no;
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
        {
            data: "vendor_name",
            visible: true,
            render: function (data, type, full, meta) {
                result = full.pref_vendor_name;
                // result+='<br><b>Preferred Vendor Part : </b>'+full.preferred_vendor_part;
                return result;
            }
        },
        // {   
        //     data: "new_unit_cost",
        //     visible: true,
        // },
        // {   
        //     data: "qty_on_hand",
        //     visible: true,
        //     render: function (data, type, full, meta) {
        //         if(full.qty_on_hand>0){
        //             action = '<span class="label bg-success-400">'+full.qty_on_hand+' - IN STOCK</span>';
        //         }else{
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
                action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/inventory/items/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Edit</a>';
                action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/inventory/items/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
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
            var switchery = new Switchery(this, { color: '#95e0eb' });
        });
    }
});

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});
$('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
var add_button = '<div class="text-right"><a href="' + site_url + 'admin/inventory/items/add" class="btn bg-teal-400 btn-labeled custom_add_button"><b><i class="icon-plus-circle2"></i></b> Add Items</a></div>';
$('.datatable-header').append(add_button);

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
    },
        function (isConfirm) {
            if (isConfirm) {
                window.location.href = $(e).attr('href');
                return true;
            }
            else {
                return false;
            }
        });
    return false;
}

/**********************************************************
                    Form Validation
***********************************************************/
var validator = $("#add_item_form").validate({
    ignore: '.select2-search__field, #txt_status', // ignore hidden fields
    errorClass: 'validation-error-label',
    successClass: 'validation-valid-label',
    highlight: function (element, errorClass, validClass) {
        var elem = $(element);
        if (elem.hasClass("select2-offscreen")) {
            $("#s2id_" + elem.attr("id") + " ul").removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        var elem = $(element);
        if (elem.hasClass("select2-offscreen")) {
            $("#s2id_" + elem.attr("id") + " ul").removeClass(errorClass);
        } else {
            elem.removeClass(errorClass);
        }
    },
    errorPlacement: function (error, element) {
        $(element).parent().find('.form_success_vert_icon').remove();
        if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container')) {
            if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                error.appendTo(element.parent().parent().parent().parent());
            } else {
                error.appendTo(element.parent().parent().parent().parent().parent());
            }
        } else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
            error.appendTo(element.parent().parent().parent());
        } else if (element.parents('div').hasClass('has-feedback') || element.hasClass('select2-hidden-accessible')) {
            error.appendTo(element.parent());
        } else if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
            error.appendTo(element.parent().parent());
        } else if (element.parent().hasClass('uploader') || element.parents().hasClass('input-group')) {
            error.appendTo(element.parent().parent());
        } else {
            error.insertAfter(element);
        }
    },
    validClass: "validation-valid-label",
    success: function (element) {
        if ($(element).parent('div').hasClass('media-body')) {
            $(element).parent().find('.form_success_vert_icon').remove();
            $(element).remove();
        } else {
            $(element).parent().find('.form_success_vert_icon').remove();
            $(element).parent().append('<div class="form_success_vert_icon form-control-feedback"><i class="icon-checkmark-circle"></i></div>');
            $(element).remove();
        }
    },
    rules: {
        txt_item_part: { required: true, maxlength: 150 },
        txt_internal_part: { required: true, maxlength: 150 },
        txt_department: { required: true },
        txt_pref_vendor: { required: true },
        txt_manufacturer: { required: true },
        // txt_pref_vendor_part: { required: true },
        // txt_pref_vendor_cost_new: { required: true, number:true },
        // txt_pref_vendor_cost_refurbished: { required: true, number:true },
        txt_item_description: { required: true },
        // txt_sec_vendor_cost_new: { number:true },
        // txt_sec_vendor_cost_refurbished: { number:true },
    },
    submitHandler: function (form) {
        setTimeout(function () {
            if ($('#exists_part').html() == '') {
                var res = check_unique_part();
                form.submit();
                $('.custom_save_button').prop('disabled', true);
            } else {
                return false;
            }
        }, 250);
    },
    invalidHandler: function () {
        $('.custom_save_button').prop('disabled', false);
    }
});

/**********************************************************
                    Item View Popup
***********************************************************/
$(document).on('click', '.item_view_btn', function () {
    $('#custom_loading').removeClass('hide');
    $('#custom_loading').css('display', 'block');
    $.ajax({
        url: site_url + 'admin/inventory/get_item_data_ajax_by_id',
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

// Basic initialization
$('.tags-input').tagsinput();

$('#txt_item_part').on('focusout', function () {
    check_unique_part();
});
$('#txt_pref_vendor').on('change', function () {
    check_unique_part();
});
function check_unique_part(id = null) {
    var id = $('#txt_item_hidden').val();
    $('.custom_save_button').prop('disabled', false);
    var txt_item_part = $('#txt_item_part').val();
    var txt_pref_vendor = $('#txt_pref_vendor').val();
    if (txt_item_part != '' && txt_pref_vendor != '') {
        $.ajax({
            url: site_url + 'admin/inventory/check_unique_item_data',
            type: "POST",
            data: { part_no: txt_item_part, preferred_vendor: txt_pref_vendor, id: id },
            success: function (response) {
                response = JSON.parse(response);
                $('#exists_part').html('');
                if (response.code == 200) {
                    $('#exists_part').html('Part is exists with same vendor.');
                    return false;
                } else {
                    return true;
                }
            }
        });
    }
}

$("#image_link").on("change", function () {
    $('#image_preview_div').html('');
    var files = !!this.files ? this.files : [];
    var sizeKB = files[0].size / 1024 / 1024;
    if (!files.length || !window.FileReader) {
        return; // no file selected, or no FileReader support
    }
    // append attachment name
    $('.filename').html(files[0].name);
    if(sizeKB <= 2) {
        var i = 0;
        for (var key in files) {
            if (/^image/.test(files[key].type) && files[key].type != 'image/gif') { // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[key]); // read the local file
                reader.onloadend = function (e) { // set image data as background of div
                    var html = '<img src="' + e.target.result + '" style="width: 80px; height: 60px; border-radius: 2px;" alt="">';
                    $('#image_preview_div').html(html);
                    $('#image_message_alert').hide();
                    ++i;
                }
            } else {
                var html = '<img src="'+ site_url + 'uploads/common_images/block_image.png" style="width: 60px; height: 60px; border-radius: 2px;" alt="">';
                $('#image_preview_div').html(html);
                $('#image_message_alert').addClass('error').html("Please select proper image.");
                $('#image_message_alert').show();
            }
        }
    } else {
        var html = '<img src="'+ site_url + 'uploads/common_images/block_image.png" style="width: 60px; height: 60px; border-radius: 2px;" alt="">';
        $('#image_preview_div').html(html);
        $('#image_message_alert').addClass('error').html("Image size must be less than 2MB.");
        $('#image_message_alert').show();
    }
});