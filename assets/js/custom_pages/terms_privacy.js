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
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var html = '<img src="' + e.target.result + '" style="width: 58px; height: 58px; border-radius: 2px;" alt="">';
            $('#image_preview_div').html(html);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
/**********************************************************
 Intitalize Data Table
 ***********************************************************/
$(function () {
    bind();
});

$('#filter_status').on('change', function () {
    $(".datatable-basic").dataTable().fnDestroy();
    bind();
});

function bind() {
    var table = $('.datatable-basic').dataTable({
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
            url: site_url + 'admin/TermsAndPrivacy/get_pages',
            data: {
                page_type: $('#filter_status').find('option:selected').val(),
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
                sortable: false
            },
            {
                data: "page_title",
                visible: true,
            },
            {
                data: "page_type",
                visible: true,
                render: function (data, type, full, meta) {
                    var type_text = '';

                    if (data == 'Privacy') {
                        type_text += '<span class="label label-primary">Privacy Policy</span>';
                    } else if (data == 'Terms') {
                        type_text += '<span class="label label-info">Terms of Service</span>';
                    }

                    return type_text;
                },
            },
            {
                data: "created_date",
                visible: true,
                sortable: false
            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    action = '';
                    action += '<a href="javascript:void(0);" class="btn btn-xs custom_dt_action_button content_view_btn" title="View" id="\'' + btoa(full.id) + '\'">View</a>';
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/terms/privacy/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Edit</a>';
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/terms/privacy//delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
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
                var switchery = new Switchery(this, {color: '#95e0eb'});
            });
        }
    });
}
$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});
$('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
/**********************************************************
 Form Validation
 ***********************************************************/
var validator = $("#add_content_form").validate({
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
    ignore: ":hidden:not(textarea)",
    rules: {
        page_name: {required: true, maxlength: 250},
        page_description: {required: true},
        page_type: {required: true}
    },
    submitHandler: function (form) {
        form.submit();
        $('.custom_save_button').prop('disabled', true);
    },
    invalidHandler: function () {
        $('.custom_save_button').prop('disabled', false);
    }
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

/**********************************************************
 Content View Popup
 ***********************************************************/
$(document).on('click', '.content_view_btn', function () {
    $('#custom_loading').removeClass('hide');
    $('#custom_loading').css('display', 'block');
    $.ajax({
        url: site_url + 'admin/TermsAndPrivacy/get_page_data_ajax_by_id',
        type: "POST",
        data: {id: this.id},
        success: function (response) {
            $('#custom_loading').removeClass('hide');
            $('#custom_loading').css('display', 'none');
            $('#content_view_body').html(response);
            $('#content_view_modal').modal('show');
        }
    });
});

$(function () {
    $('.wysihtml5-default').wysihtml5({
        parserRules: wysihtml5ParserRules,
    });
});

$("#is_default").click(function () {
    var value = 0;
    if ($(this).prop("checked") == true) {
        value = 1;
    } else if ($(this).prop("checked") == false) {
        value = 0;
    }

    $(this).val(value);
});