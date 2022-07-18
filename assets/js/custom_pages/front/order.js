/**
 * Validate float value upto two decimal places
 * @param {object} el
 * @param {event} evt
 * @returns {Boolean}
 * @author HGA
 */


$(document).ready(function () {
    $('.date').pickadate({
        // format: 'mm/dd/yy',
        format: date_format,
        formatSubmit: 'yyyy/mm/dd',
        today: 'Today',
        clear: 'Clear',
        close: 'Close',
        selectMonths: true,
        selectYears: true
    });

    $('#ordered_from_id').on('change', function () {
        var part_no = $(this).find('option:selected').attr('data-part-no');
        var item_id = $(this).find('option:selected').attr('data-item-id');

        if (part_no) {
            $('#vendor_part').val(part_no);
            $('#item_id').val(item_id);
        }
    });
})

/**********************************************************
 Intitalize Data Table
 ***********************************************************/
$(function () {
    bind();
});

$('#filter_status').on('change', function () {
    $(".datatable-responsive-control-right").dataTable().fnDestroy();
    bind();
});

function bind() {
    var table = $('.datatable-responsive-control-right').dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
            emptyTable: 'No order currently available.'
        },
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[0, "desc"]],
        ajax: {
            url: site_url + 'orders/get_order_data',
            data: {
                status_id: $('#filter_status').find('option:selected').val(),
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
                sortable: false,
            },
            {
                data: "ordered_date",
                visible: true,
            },
            {
                data: "order_no",
                visible: true,
            },
            {
                data: "customer_name",
                visible: true,
            },
            {
                data: "vendor_part_no",
                visible: true,
            },
            {
                data: "status_name",
                visible: true,
                render: function (data, type, full, meta) {
                    action = '<span class="label label-' + full.status_color + '">' + full.status_name + '</span>';
                    return action;
                }
            },
            {
                data: "total_receipt_amount",
                visible: false,
                render: function (data, type, full, meta) {
                    return parseFloat(full.total_receipt_amount).toFixed(2);
                }

            },
            {
                data: "change_status",
                visible: true,
                sortable: false,
                render: function (data, type, full, meta) {
                    return changeOrderStatus(full.status_id, full.id);
                }
            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    action = '';
                    action += '<a href="javascript:void(0);" class="btn btn-xs custom_dt_action_button order_view_btn" title="View" id="\'' + btoa(full.id) + '\'">View</a>';
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'orders/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs"title="Edit">Edit</a>';
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'orders/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
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

            $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
            if (add == 1) {
                var add_button = '<div class="text-right"><a href="' + site_url + 'orders/add" class="btn bg-teal-400 btn-labeled custom_add_button"><b><i class="icon-plus-circle2"></i></b> Add Order</a></div>';
                $('.datatable-header').append(add_button);
            }

            $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 'auto'
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

$(document).ready(function () {
    // Setup - add a text input to each footer cell
    $('.datatable-responsive-control-right tfoot th').each(function () {
        var title = $(this).text();
        if ($(this).hasClass('slct')) {
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        }
    });

    $(document).on('change', '.select_change_order_status', function () {
        $('#custom_loading').removeClass('hide');
        $('#custom_loading').css('display', 'block');

        var status_id = $(this).find('option:selected').val();
        var order_id = $(this).find('option:selected').attr('data-id');

        var data = {
            status_id: status_id,
            order_id: order_id
        };

        $.ajax({
            url: site_url + 'orders/change_order_status',
            type: "POST",
            data: data,
            success: function (response) {
                $(".datatable-responsive-control-right").dataTable().fnDestroy();
                bind();

                $('#custom_loading').removeClass('hide');
                $('#custom_loading').css('display', 'none');
                success_alert();
            }
        });
    });
});

/**********************************************************
 Success Alert
***********************************************************/
function success_alert(){
    swal("Success!", "Status changed successfully!", "success");
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
 Form Validation
 ***********************************************************/
var validator = $("#add_order_form").validate({
    onkeyup: false,
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
//        current_date: {required: true},
//        ordered_date: {required: true},
        ordered_taken_user_id: {required: true},
//        ordered_given_user_id: {required: true},
        customer_name: {
            required: true,
            normalizer: function(value) {
                return $.trim(value);
            },
            maxlength: 150
        },
        quoted_price: {number: true, maxlength: 10},
        customer_phone: {required: true, minlength: 16, maxlength: 16},
//        paid_for: {required: true},
        description: {
            required: true,
            normalizer: function(value) {
                return $.trim(value);
            },
        },
        total_receipt_amount: { number: true },
//        receipt_number: {required: true},
//        vendor_part: {
//            remote: {
//                url: "orders/check_part",
//                type: "post",
//                dataType: "json",
//                async: false,
//                dataFilter: function (data) {
//                    data = jQuery.parseJSON(data);
//                    if (jQuery.isEmptyObject(data) == false) {
//                        var item_id = data.id;
//                        $("#item_id").val(item_id);
//                        return true;
//                    } else {
//                        $("#item_id").val("");
//                        return false;
//                    }
//                },
//            },
//        },
//        payment_method_id: {required: true},
        status: {required: true},
//        payment_notes: {required: true},
    },
    messages: {
//        vendor_part: {
//            remote: 'Vendor part details not match with our records'
//        },
        customer_phone: "Please enter validate phone number."
    },
    submitHandler: function (form) {
        var $form = $(form);
        var sub_total = $form.find('input.sub_total').val()
        if (sub_total == '0.00' || sub_total == 0.00) {
            $('#hidden_part_id_error2').text('Enter the valid part name or description.').show();
            return false;
        } else {
            form.submit();
            $('.custom_save_button').prop('disabled', true);
            $('.save_send').prop('disabled', true);
        }
    },
    invalidHandler: function () {
        $('.custom_save_button').prop('disabled', false);
        $('.save_send').prop('disabled', false);
    }
});

// remove validation on the dropdown id value is not empty
$('#ordered_taken_user_id,#status').change(function(){
    $(this).valid()
});

$('.format-phone-number').formatter({
    pattern: '({{999}}) {{999}} - {{9999}}'
});

// Allow number only
$(document).on("input", "#quoted_price,#total_receipt_amount", function() {
    this.value = this.value.replace(/\D/g,'');
});

/**********************************************************
 Item View Popup
 ***********************************************************/
$(document).on('click', '.order_view_btn', function () {
    $('#custom_loading').removeClass('hide');
    $('#custom_loading').css('display', 'block');
    $.ajax({
        url: site_url + 'orders/get_order_data_ajax_by_id',
        type: "POST",
        data: {id: this.id},
        success: function (response) {
            $('#custom_loading').removeClass('hide');
            $('#custom_loading').css('display', 'none');
            $('#order_view_body').html(response);
            $('#order_view_modal').modal('show');
        }
    });
});

if (typeof (edit_div) != 'undefined' && edit_div == 1) {
    $('.inventoy_div').removeClass('hide');
}

// Format displayed data
function formatRepo(repo) {
    if (repo.loading)
        return repo.text;
    var markup = "<div class='select2-result-repository clearfix'>";
    if (jQuery.isEmptyObject(repo.image)) {
        markup += "<div class='select2-result-repository__avatar'><img src='" + ITEMS_IMAGE_PATH + "/no_image.jpg' /></div>";
    } else {
        markup += "<div class='select2-result-repository__avatar'><img src='" + ITEMS_IMAGE_PATH + "/" + repo.image + "' /></div>";
    }
    markup += "<div class='select2-result-repository__meta'>" +
            "<div class='select2-result-repository__title'>" + repo.part_no + " (Vendor : " + repo.v1_name + " )</div>";

    if (repo.description) {
        markup += "<div class='select2-result-repository__description'>Description : " + repo.description + "</div>";
    }
    total_quantity = (jQuery.isEmptyObject(repo.total_quantity)) ? 0 : repo.total_quantity;
    markup += "<div class='select2-result-repository__statistics'>" +
            "<div class='select2-result-repository__quantity'>Quantity : " + repo.location_quantity + "</div>" +
            "<div class='select2-result-repository__retail'>Department : " + repo.dept_name + "</div>" +
            "<div class='select2-result-repository__cost'>Price : $" + repo.retail_price + "</div>" +
            "</div>" +
            "</div></div>";
    return markup;
}

// Format selection
function formatRepoSelection(repo, num) {
    if (repo.id != '' && repo.selected == false) {
        total_quantity = (jQuery.isEmptyObject(repo.total_quantity)) ? 0 : repo.total_quantity;
        var html = "<div class='row'>" +
                "<div class='col-md-11'>" +
                "<input type='hidden' name='hidden_part_id[]' value='" + repo.id + "' id='part_no_" + num + "' />" +
                "<div class='select2-result-title text-left'>" + repo.part_no + " (Vendor : " + repo.v1_name + " ) </div>" +
                "<div class='select2-result-description mt-10'> <input type='text' class='form-control' name='description[]' value='" + repo.description + "'/></div></div>" +
                "<div class='col-md-1'>" +
                "<span class='text-right cancel-part'><i class='icon-cancel-square text-danger'></i></span>" +
                "</div></div>";
        $("#td_part_no_" + num).html(html).removeClass('select_part');
        $("#span_quantity_" + num).html(repo.location_quantity);
        // $("#quantity_" + num).val(repo.location_quantity);
        $("#td_rate_" + num).html(repo.retail_price);
        $("#td_amount_" + num).html(repo.retail_price).addClass('td_amount');
        $("#rate_" + num).val(repo.retail_price);
        $("#amount_" + num).val(repo.retail_price);
        $('#hidden_part_id_error2').text('').hide();
        var count = $('.' + estimate_id).children('.select_part').length;
        if (count == 0) {
            add_html((parseInt($('.' + estimate_id).last().attr('data-value'))) + 1);
        }
        setTimeout(() => {
            sum_sub_total();
            check_location_inventory(repo.id, num);
        }, 20);
        return repo.part_no;
    }
}

$(document).on('click', '.cancel-part', function () {
    var num = $(this).parents('.' + estimate_id).attr('data-value');
    var html = "<select class='select-part-data' id='txt_part_no_" + num + "' name='txt_part_no[]'>" +
            "<option value='' selected='selected'>Type to select Part</option>" +
            "</select>";
    $("#td_part_no_" + num).html(html).addClass('select_part');
    $("#div_quantity_" + num).html('1');
    $("#td_rate_" + num).html('0.00');
    $("#td_amount_" + num).html('0.00');
    $("#quantity_" + num).val('1');
    $("#rate_" + num).val('0.00');
    $("#amount_" + num).val('0.00');
    $("#discount_type_id_" + num).val('p');
    $("#div_discount_" + num).html('0.00');
    $("#discount_" + num).val('0.00');
    $("#span_quantity_" + num).html('');
    $("#tax_" + num).val('0.00');
    $("#span_tax_rate_" + num).html('');
    $("#span_discount_rate_" + num).html('');
    $('#txt_location_id_' + num).select2('destroy').select2();
    $('#tax_id_' + num).val('').select2('destroy').select2();
    setTimeout(() => {
        set_select(num);
        sum_sub_total();
    }, 20);

});

function set_select(num = 1) {
    $("#txt_part_no_" + num).select2({
        ajax: {
            url: site_url + 'invoices/get_item_data_ajax_by_part_id',
            dataType: 'json',
            delay: 250,
            type: 'POST',
            data: function (params) {
                return {
                    id: params.term, // search term
                    page: params.page,
                    location_id: $('#txt_location_id_' + num).val()
                };
            },
            processResults: function (response, params) {
                data = response.data;
                if (response.code == 200) {
                    params.page = params.page || 1;
                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < response.count
                        }
                    };
                } else {
                    return {
                        results: ''
                    }
                }
            },
            cache: true,
        },
        placeholder: 'Search for a Part',
        escapeMarkup: function (markup) {
            return markup;
        }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: formatRepo,
        templateSelection: function (repo) {
            return formatRepoSelection(repo, num)
        },
    });
}

$('.add_line').on('click', function () {
    var num = ((parseInt($('.' + estimate_id).last().attr('data-value'))) + 1);
    add_html(num);
});

set_select();

function add_html(num) {
    var html = "<tr class='" + estimate_id + "' id='tr_" + num + "' data-value='" + num + "'>" +
            "<td class='location' id='td_location_id_" + num + "'>" +
            "<select class='select select-location-data' id='txt_location_id_" + num + "' name='location_id[]'>" +
            " </select>" +
            "</td>" +
            "<td class='select_part' id='td_part_no_" + num + "'>" +
            "<select class='select-part-data' id='txt_part_no_" + num + "' name='txt_part_no[]'>" +
            "<option value='' selected='selected'>Type to select Part</option>" +
            "</select>" +
            "</td>" +
            "<td id='td_quantity_" + num + "'><input type='hidden' value='1' name='quantity[]' id='quantity_" + num + "'/>" +
            "<div class='row mt-3'>" +
            "<div class='col-md-8 text-left'>" +
            "<span class='plus' id='plus_" + num + "'><i class='icon-plus3 text-primary'></i></span>" +
            "<span class='minus' id='minus_" + num + "'><i class='icon-minus3 text-primary'></i></span>" +
            "</div>" +
            "<div class='col-md-4 mt-3'><span id='div_quantity_" + num + "'>1</span>" +
            "<br/><span id='span_quantity_" + num + "' class='span_quantity'></span>" +
            "</div>" +
            "</div>" +
            "</td>" +
            "<td><input type='hidden' value='0.00' name='rate[]' id='rate_" + num + "'/><span id='td_rate_" + num + "' class='td_rate'>0.00</span></td>" +
            "<td id='td_discount_" + num + "'><input type='hidden' value='0.00' name='discount[]' id='discount_" + num + "'/><input type='hidden' value='0.00' name='discount_rate[]' id='discount_rate_" + num + "'/>" +
            "<div class='row'>" +
            "<div class='col-md-6 mt-3 div_discount text-center' id='div_discount_" + num + "'>0.00</div>" +
            "<div class='col-md-6'>" +
            "<select name='discount_type_id[]' id='discount_type_id_" + num + "' class='discount_type_id'>" +
            "<option value='p' selected=''>%</option>" +
            "<option value'r'>" + currency + "</option>" +
            "</select>" +
            "</div>" +
            "</div>" +
            "<div class='row discount_rate'>" +
            "<span id='span_discount_rate_" + num + "' class='span_discount_rate'></span>" +
            "</div>" +
            "</td>" +
            "<td id='td_tax_" + num + "'>" +
            "<input type='hidden' value='0.00' name='tax[]' id='tax_" + num + "'/>" +
            "<div class='row'>" +
            "<select data-placeholder='Select a Tax...' class='select select-size-sm select-tax-data' id='tax_id_" + num + "' name='tax_id[]'>" +
            "</select>" +
            "</div>" +
            "<div class='col-md-12 tax_rate'>" +
            "<span id='span_tax_rate_" + num + "' class='span_tax_rate'></span>" +
            "</div>" +
            "</td>" +
            "<td><input type='hidden' value='0.00' name='amount[]' id='amount_" + num + "'/><span id='td_amount_" + num + "'>0.00</span></td>" +
            "<td id='td_remove_" + num + "'><span class='remove' id='remove_" + num + "'><i class='icon-trash'></i></span></td>" +
            "</tr>";
    setTimeout(function () {
        set_select(num);
        if (typeof (locations) != undefined && locations != '') {
            $.each(locations, function (key, value) {
                var o = $("<option/>", {value: value.id, text: value.name});
                $('#txt_location_id_' + num).append(o);
            });
            $('#txt_location_id_' + num).select2().trigger('change');
        }
        if (typeof (taxes) != undefined && taxes != '') {
            var o = $("<option/>", {value: "", text: "Select a Tax..."});
            $('#tax_id_' + num).append(o);
            $.each(taxes, function (key, value) {
                var o = $("<option/>", {value: value.id, text: value.name + " (" + value.rate + "%)"});
                $('#tax_id_' + num).append(o);
            });
            $('#tax_id_' + num).select2().trigger('change');
        }
    }, 10);
    $('.part_div').append(html);
}

$(window).keydown(function (event) {
    if (event.keyCode == 13) {
        event.preventDefault();
        return false;
    }
});

// Prevent special character
$('#customer_name').on('keypress', function (event) {
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
});