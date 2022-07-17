/**********************************************************
 Intitalize Data Table
 ***********************************************************/
var table = $('.datatable-responsive-control-right').dataTable({
    autoWidth: false,
    processing: true,
    serverSide: true,
    stateSave: true,
    language: {
        search: '<span>Filter:</span> _INPUT_',
        lengthMenu: '<span>Show:</span> _MENU_',
        paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
        emptyTable: 'No data currently available.'
    },
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    order: [[0, "desc"]],
    ajax: site_url + 'vendor/products/get_items_data',
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
                result = '<b>Part No : </b>' + full.part_no;
                return result;
            }
        },
        {
            data: "description",
            visible: true,
            className: 'slct',
            render: function (data, type, full, meta) {
                result = '<b>Alternate Part No or SKU : </b>' + full.internal_part_no;
                result += '</br><b>Description : </b>' + full.description;
                return result;
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
                action += '<a href="javascript:void(0);" class="btn btn-xs custom_dt_action_button item_view_btn" title="View" id="\'' + btoa(full.id) + '\'">View</a>';
                if (edit == 1) {
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'vendor/products/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Edit</a>';
                }

                if (dlt == 1) {
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'vendor/products/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
                }
                // if (full.total_quantity <= 1) {
                //     action += '&nbsp;&nbsp;<a href="' + site_url + 'receive_inventory/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Add Inventory">Add Inventory</a>';
                // }
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

if (add == 1) {
    var add_button = '';

    if (role == 5) {
        add_button += '<div class="text-right"><a href="' + site_url + 'vendor/products/export" class="btn bg-teal-400 btn-labeled custom_add_button mb-2"><b><i class="icon-download"></i></b> Export Products</a>&nbsp;';
    }
    
    add_button += '<a href="' + site_url + 'vendor/products/add" class="btn bg-teal-400 btn-labeled custom_add_button mb-2"><b><i class="icon-plus-circle2"></i></b> Add Products</a></div>';
    $('.datatable-header').append(add_button);
}

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
                } else {
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
        txt_global_part_no: {required: true, maxlength: 150},
        txt_item_part: {required: true, maxlength: 150},
        txt_internal_part: {required: true, maxlength: 150},
        txt_department: {required: true},
        txt_item_description: {required: true},
        txt_pref_vendor: {required: true},
        txt_manufacturer: {required: true},
        item_link: {url: true},
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
        url: site_url + 'vendor/products/get_item_data_ajax_by_id',
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

// $('#txt_item_id').on('change', function () {
//     $('.transfer_div').removeClass('hide');
// });
// Basic initialization
//$('.tags-input').tagsinput();

$('#txt_item_part').on('focusout', function () {
    check_unique_part();
});
$('#txt_pref_vendor').on('change', function () {
    check_unique_part();
});
function check_unique_part() {
    var id = $('#txt_item_hidden').val();
    $('.custom_save_button').prop('disabled', false);
    var txt_item_part = $('#txt_item_part').val();
    var txt_pref_vendor = $('#txt_pref_vendor').val();
    if (txt_item_part != '' && txt_pref_vendor != '') {
        $.ajax({
            url: site_url + 'vendor/products/check_unique_item_data',
            type: "POST",
            data: {part_no: txt_item_part, preferred_vendor: txt_pref_vendor, id: id},
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
$(document).on('click', '.btn_add_extra_field', function () {
    var $nonempty = $('.additional_field_txt').filter(function () {
        return this.value != '';
    });

    if ($nonempty.length != 0) {
        var last_row_num = parseInt($('.tr_class').last().attr('data-value'));
        var num = ((parseInt($('.tr_class').last().attr('data-value'))) + 1);
        var year_key_name = ((parseInt($('.tr_class').last().attr('data-value'))) + 1);


        if (product_id != '') {
            year_key_name = (parseInt($("#txt_year_name_" + last_row_num).attr('data-keyid')) + 1);

            if (isNaN(year_key_name)) {
                year_key_name = ((parseInt($('.tr_class').last().attr('data-value'))) + 1);
            }
        }

        $(this).parents('td').html('<a href="javascript:void(0)" style="color:#d66464"><i class="icon-minus-circle2 btn_remove_extra_field"></i></a>');
        var html = '<tr class="tr_class" data-value="' + num + '"><td><a href="javascript:void(0)" style="color:#009688"><i class="icon-plus-circle2 btn_add_extra_field"></i></a></td><td><select class="select select-size-sm additional_field_txt txt_make_name" data-placeholder="Select a Company..." id="txt_make_name_' + num + '" name="txt_make_name[]" required></select></td><td><select class="select select-size-sm additional_field_txt txt_model_name" data-placeholder="Select a Model..." id="txt_model_name_' + num + '" name="txt_model_name[]" required></select></td>';
        html += '<td><select class="additional_field_txt txt_year_name multiselect-ui" multiple="multiple" data-placeholder="Select a Year..." id="txt_year_name_' + num + '" data-keyid="' + year_key_name + '" name="txt_year_name[' + year_key_name + '][]" required></select>';
        html += '</td></tr>';

        setTimeout(function () {
            if (typeof (companyArr) !== 'undefined' && companyArr != '') {
                var o = $("<option/>", {value: "", text: "Select a Company..."});
                $('#txt_make_name_' + num).append(o);
                $.each(companyArr, function (key, value) {
                    var o = $("<option/>", {value: value.id, text: value.name});
                    $('#txt_make_name_' + num).append(o);
                });
                $('#txt_make_name_' + num).select2().trigger('change');
                $('#txt_model_name_' + num).select2();
            }
            if (typeof (yearArr) !== 'undefined' && yearArr != '') {
                var o = $("<option/>", {value: "", text: "Select a Year..."});
                $('#txt_year_name_' + num).append(o);
//                $.each(yearArr, function (key, value) {
//                    var o = $("<option/>", {value: value.id, text: value.name});
//                    $('#txt_year_name_' + num).append(o);
//                });
                $('#txt_year_name_' + num).select2().trigger('change');
            }
        }, 10);

        $('#tbl_additional_data tbody').append(html);
    } else {
        $('#additional_data_error2').css('padding-left', '45px');
        $('#additional_data_error2').html('Please Fill Existing Field.');
    }
});

$(document).on('change', '.txt_make_name', function () {
    var num = $(this).parents('.tr_class').attr('data-value');
    var selected_val = $(this).val();

    $('#txt_year_name_' + num).empty();

    $.ajax({
        url: site_url + 'vendor/products/change_make_get_ajax',
        dataType: "json",
        type: "POST",
        data: {id: selected_val},
        success: function (response) {
            $('#txt_model_name_' + num).html(response);
            $('#txt_model_name').select2({containerCssClass: 'select-sm'});
        }
    });
});

$(document).on('change', '.txt_model_name', function () {
    var num = $(this).parents('.tr_class').attr('data-value');
    var make_id = $("#txt_make_name_" + num).find('option:selected').val();
    var model_id = $(this).find('option:selected').val();

    var data = {
        make_id: make_id,
        model_id: model_id
    };

    $.ajax({
        url: site_url + 'vendor/products/get_transponder_item_years',
        dataType: "json",
        type: "POST",
        data: data,
        success: function (response) {
            $('#txt_year_name_' + num).html(response);
            $('.txt_year_name').select2({containerCssClass: 'select-sm'});
        }
    });
});

$(document).on('click', '.btn_remove_extra_field', function () {
    $(this).parents('tr').remove();
});
