$(document).ready(function () {
    $(".touchspin-empty").TouchSpin({
        min: 0,
        max: 100000,
        step: 1,
        booster: true,
        mousewheel: false
    });

    $('#txt_adjust_item_id').on('change', function () {
        var item_id = $(this).val();
        $('.adjust_inventory').each(function (i) {
            var id = $(this).data('id');
            $('#txt_in_stock_' + id).val(0);
            // $('#txt_in_stock_' + id).append('<option value="0">0</option>').trigger('change').prop('selected', true);
        });
        setTimeout(function () {
            $.ajax({
                type: 'POST',
                url: site_url + 'inventory/get_item_location_ajax_data',
                async: false,
                dataType: 'JSON',
                data: {item_id: item_id},
                success: function (data) {
                    $.each(data, function (i, item) {
                        if (typeof (item.location_id) !== 'undefined') {
                            var id = item.location_id;
                            $('#txt_in_stock_' + id).val(item.quantity);
                        }
                    });
                    $('.adjust_div').removeClass('hide');
                }
            });
        }, 500);
    });

    // Update dropdown on page load 
    $('#txt_adjust_item_id').trigger('change');

    $('#txt_item_id').on('change', function () {
        var item_id = $(this).val();
        // alert(item_id);
        $('#txt_from_location_id option').attr('disabled', 'disabled');
        $('#txt_from_location_id').select2('destroy').val('').select2();
        $('#txt_to_location_id').select2('destroy').val('').select2();
        $('#txt_quantity').empty().trigger("change").select2();
        $('#txt_quantity_error2').html('');
        $.ajax({
            type: 'POST',
            url: site_url + 'inventory/get_item_location_ajax_data',
            async: false,
            dataType: 'JSON',
            data: {item_id: item_id},
            success: function (data) {
                $.each(data, function (key, value) {
                    $('#txt_from_location_id option[value="' + value.location_id + '"]').removeAttr('disabled', 'disabled');
                });
                $('.total_quantity').html(data.total_quantity);
                $('#txt_from_location_id').select2('destroy').select2();
                $('.total_qty').removeClass('hide');
                $('.transfer_div').removeClass('hide');
                $('.current_qty_amount').html('0');
                $('.current_qty').addClass('hide');
                $('.to_current_qty_amount').html('0');
                $('.to_current_qty').addClass('hide');
            }
        });
    });

    // Update dropdown on page load 
    $('#txt_item_id').trigger('change');

    $('#txt_from_location_id').on('change', function () {
        var location_id = $(this).val();
        var item_id = $('#txt_item_id').val();
        $('#txt_quantity').empty().trigger("change").select2();
        $('#txt_to_location_id').select2('destroy').val('').select2();
        $('#txt_to_location_id option').removeAttr('disabled', 'disabled');
        $('.to_current_qty').addClass('hide');
        if ($('#txt_from_location_id option[value="' + location_id + '"]').attr("selected", 'selected')) {
            $('#txt_to_location_id option[value="' + location_id + '"]').attr('disabled', 'disabled');
        }
        $('#txt_to_location_id').select2('destroy').select2();
        $.ajax({
            type: 'POST',
            url: site_url + 'inventory/get_item_location_ajax_data/' + location_id,
            async: false,
            dataType: 'JSON',
            data: {item_id: item_id},
            success: function (data) {
                if (data.quantity != 0) {
                    for (var i = 1; i <= data.quantity; i++) {
                        $('#txt_quantity').append('<option value="' + i + '">' + i + '</option>').trigger('change');
                        $('#txt_to_location_id').select2('destroy').select2();
                    }
                    $('#txt_quantity option[value="1"]').prop('selected', true);
                    $('.current_qty_amount').html(data.quantity);
                    $('.current_qty').removeClass('hide');
                } else {
                    $('#txt_quantity_error2').html('There is no quantity to move. Please try another location!!');
                }
            }
        });

    });

    $('#txt_to_location_id').on('change', function () {
        var location_id = $(this).val();
        var item_id = $('#txt_item_id').val();
        $.ajax({
            type: 'POST',
            url: site_url + 'inventory/get_item_location_ajax_data/' + location_id,
            async: false,
            dataType: 'JSON',
            data: {item_id: item_id},
            success: function (data) {
                if (data.quantity != 0 && typeof data.quantity !== "undefined") {
                    $('.to_current_qty_amount').html(data.quantity);
                    $('.to_current_qty').removeClass('hide');
                } else {
                    $('.to_current_qty_amount').html(0);
                }
            }
        });
    });

    var validator = $("#new_inventory_form").validate({
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
            txt_item_part: {required: true},
            txt_new_inventory: {required: true},
            notes: {
                required: true, normalizer: function (value) {
                    return $.trim(value);
                }
            },
        },
        submitHandler: function (form) {
            var $form = $(form),
                    txt_new_inventory = $form.find('.txt_new_inventory');
            var values = [];
            $.each(txt_new_inventory, function (i, item) {
                if ($(item).val() != 0) {
                    values.push($(item).val());
                }
            });
            if (values.length === 0) {
                $('.error_message').html('Please enter inventory in any location!!').removeClass('hide');
            } else {
                form.submit();
                $('.custom_save_button').prop('disabled', true);
            }
        },
        invalidHandler: function () {
            $('.custom_save_button').prop('disabled', false);
        }
    });

    var validator = $("#transfer_inventory_form").validate({
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
            txt_from_location_id: {required: true},
            txt_to_location_id: {required: true},
            notes: {
                required: true, normalizer: function (value) {
                    return $.trim(value);
                }
            },
        },
        submitHandler: function (form) {
            form.submit();
            $('.custom_save_button').prop('disabled', true);
        },
        invalidHandler: function () {
            $('.custom_save_button').prop('disabled', false);
        }
    });
    var validator = $("#adjust_inventory_form").validate({
        ignore: '.select2-search__field, #txt_status, .adjust_inventory', // ignore hidden fields
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
            notes: {
                required: true, normalizer: function (value) {
                    return $.trim(value);
                }
            },
        },
        submitHandler: function (form) {
            form.submit();
            $('.custom_save_button').prop('disabled', true);
        },
        invalidHandler: function () {
            $('.custom_save_button').prop('disabled', false);
        }
    });
});

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
    ajax: site_url + 'inventory/get_inventory_data',
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
            data: "created_date",
            visible: true,
        },
        {
            data: "its_for",
            visible: true,
            sortable: false,
            render: function (data, type, full, meta) {
                if (full.its_for == 'receive') {
                    action = 'Received';
                } else if (full.its_for == 'adjust') {
                    action = 'Adjusted';
                } else if (full.its_for == 'invoice') {
                    action = 'Invoice';
                } else {
                    action = 'Moved';
                }
                return action;
            }

        },
        {
            data: "full_name",
            visible: true,
        },
        {
            data: "part_no",
            visible: true,
        },
        {
            data: "description",
            visible: true,
            sortable: false,
        },
        {
            data: "notes",
            visible: true,
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
$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});
$('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');

function cancel_click() {
    $('.total_quantity').html('0');
    $('.total_qty').addClass('hide');
    $('.transfer_div').addClass('hide');
    $('.current_qty_amount').html('0');
    $('.current_qty').addClass('hide');
    $('.to_current_qty_amount').html('0');
    $('.to_current_qty').addClass('hide');
    $('.adjust_div').addClass('hide');
    $('[name="notes"]').val('');
    $('.txt_new_inventory').val(0);
    $('.adjust_inventory').val(0);
    $('#txt_item_id').select2('destroy').val('').select2();
    $('#txt_adjust_item_id').select2('destroy').val('').select2();
    $('.form-control-feedback').remove();
    $("#new_inventory_form").validate().resetForm();
}

/**
 * @author : Hardik Gadhiya
 * @description Open webcam and scan qr code, if qr code will match with user's items then part will be add in part dropdown.
 * @date: 24-04-2019
 **/
$(document).on('click', "#scan-item-qr-code", function () {
    let scanner = new Instascan.Scanner({video: document.getElementById('webcam-preview')});

    scanner.addListener('scan', function (part_no) {
        scanner.stop();
        try {
            if (part_no) {
                $.ajax({
                    type: 'POST',
                    url: site_url + 'get_item_data',
                    async: false,
                    dataType: 'JSON',
                    data: {part_no: part_no},
                    success: function (response) {
                        if (response.status == "success") {
                            var itemId = response.data.item_id;
                            $("#txt_item_id").val(itemId).trigger('change');
                            $("#txt_adjust_item_id").val(itemId).trigger('change');
                        } else if(response.status == "error") {
                            swal({
                                title: "Error!",
                                text: response.message,
                                icon: "error",
                            });
                        }
                    }
                });
            } else {
                swal({
                    title: "Error!",
                    text: 'Part Number is not matching with system.',
                    icon: "error",
                });
            }
            $("#partno_scan_webcam_modal").modal('hide');
        } catch (e) {
            swal({
                title: "Error!",
                text: "Something went wrong.",
                icon: "error",
            });
        }
    });

    Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
            var camera_settings = check_device();
            scanner.start(cameras[camera_settings]);
        } else {
            swal({
                title: "Error!",
                text: "Camera not found.",
                icon: "error",
            });
        }
    }).catch(function (e) {
        swal({
            title: "Error!",
            text: "Camera not found.",
            icon: "error",
        });
    });

    $("#partno_scan_webcam_modal").modal('show');
});