/**
 * Validate float value upto two decimal places
 * @param {object} el
 * @param {event} evt
 * @returns {Boolean}
 * @author HGA
 */
var quickbook_customer_id = 0;
$(document).ready(function () {
    $('.date').pickadate({
        format: 'mm/dd/yy',
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

    // Phone number formatter
    $('.format-phone-number').formatter({
        pattern: '({{999}}) {{999}} - {{9999}}'
    });

    $(document).mouseup(function(e) 
    {
        var container = $("YOUR CONTAINER SELECTOR");
        if (!container.is(e.target) && container.has(e.target).length === 0) 
        {
            $(".open-div-month").hide(250);
            $(".open-div").hide(250);
        }
    });

    // avaoid special character in fax
    $('#fax').on('keypress', function (event) {
        var regex = new RegExp("^[a-zA-Z0-9]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
           event.preventDefault();
           return false;
        }
    });

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
            url: site_url + 'customers/change_order_status',
            type: "POST",
            data: data,
            success: function (response) {
                $(".datatable-responsive-control-right").dataTable().fnDestroy();
                bind();

                $('#custom_loading').removeClass('hide');
                $('#custom_loading').css('display', 'none');
            }
        });
    });

    $('#monthoverdue li').click(function(event) {
        var month = $(this).attr("value");
        var currentmonth = $(this).html();
        get_total_month_wise_invoice(month,currentmonth);
    });

    $('#yearestimate li').click(function(event) {
        var year = $(this).attr("value");
        var new_year;

        if(typeof(year) === "undefined"){
            var date = new Date();
            new_year = date.getFullYear();                
        }else{
            new_year = year;
        } 
        
        get_total_year_wise_invoice(new_year);
    });

    $('.show-cal').click(function() {
        $('.open-div').toggle();
    });

    $('.show-cal-month').click(function() {
        $('.open-div-month').toggle();
    });

    var date = new Date();
    var year = date.getFullYear();        
    var currentmonth = date.getMonth()+1; 

    get_total_year_wise_invoice(year);
    get_total_month_wise_invoice(currentmonth);


    // Check multiple email list is email or not.
    $("#multiple_email_list").on('beforeItemAdd', function(event) {
        var email = event.item;
        isEmail(email);
        if(!isEmail(email)) {
            event.cancel = true;
            $('.invalid_email_alert').fadeIn(100);
        } else {
            $('.invalid_email_alert').fadeOut(100);
        }
    });

    // Check email
    function isEmail(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }

});

/**********************************************************
 Intitalize Data Table
 ***********************************************************/
$(function () {
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
            emptyTable: 'No data currently available.'
        },
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[0, "desc"]],
        ajax: {
            url: site_url + 'customers/get_customer_data',
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
            },
            {
                data: "fullname",
                visible: true,
            },
            {
                data: "company",
                visible: true,
            },
            {
                data: "phone",
                visible: true,
                sortable: false,
            },
            {
                data: "mobile",
                visible: true,
                sortable: false,
            },
            {
                data: "email",
                render: function (data, type, full, meta) {
                    var id = btoa(full.id);
                    var customer_email = '';
                    $.ajax({
                        async: false,
                        global: false,
                        url: 'customers/email_detail',
                        method: 'POST',
                        dataType: "json",
                        data: {id: id},
                        success: function(data){
                            if(data != "" && data != null)
                            {
                                customer_email = data.customer_email;
                            } else {
                                customer_email = '';
                            }
                        },
                    });                
                    return customer_email;
                },
                sortable: false,
            },
            {
                data: "fax",
                visible: true,
                sortable: false,
            },
            {
                data: "created_date",
                visible: true,  
                sortable: false,
            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    action = '';
                    quickbook_customer_id = full.id;
                    if(session_set_status == 'yes')
                    {
                        if(full.quickbooks == 1)
                        {
                            action += '<a href="' + site_url + 'customers/add_to_quickbook/' + btoa(full.id) + '" class="btn custom_dt_action_button_quickbook btn-xs AddToQuickbook" data-customerid="' + btoa(full.id) + '" title="Add To Quickbook">add to quickbooks</a>';
                        }
                    }
                    action += '<a href="' + site_url + 'customers/view/' + btoa(full.id) + '" class="btn btn-xs custom_dt_action_button" title="View" >View</a>';

                    if (edit == 1) {
                        action += '<a href="' + site_url + 'customers/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs"title="Edit">Edit</a>';
                    }

                    if (dlt == 1) {
                        action += '<a href="' + site_url + 'customers/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
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

            $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
            if (add == 1) {
                var add_button = '<div class="text-right"><a href="' + site_url + 'customers/add" class="btn bg-teal-400 btn-labeled custom_add_button"><b><i class="icon-plus-circle2"></i></b> Add Customer</a></div>';
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

/**********************************************************
 Confirm Alert
 ***********************************************************/
function confirm_alert(e) {
    swal({
        title: "Are you sure?",
        text: "Customer  will move under the trash.",
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
var validator = $("#customer_form").validate({
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
        display_name_as: { required: true },
        first_name: {
            required: true,
            normalizer: function(value) {
                return $.trim(value);
            },
            maxlength: 250 
        },
        last_name: {
            required: true,
            normalizer: function(value) {
                return $.trim(value);
            },
            maxlength: 250
        },
        company_name: {
            noSpace: true,
            maxlength: 250,
        },
        phone: {
            minlength: 16, 
            maxlength: 16
        },
        mobile: {
            minlength: 16, 
            maxlength: 16
        },
        billing_address: {
            noSpace: true,
        },
        billing_address_city: {
            noSpace: true,
        },
        billing_address_zip: {
            noSpace: true,
        },
        billing_address_state: {
            noSpace: true,
        },
        shipping_address: {
            noSpace: true,
        },
        shipping_address_city: {
            noSpace: true,
        },
        shipping_address_zip: {
            noSpace: true,
        },
        shipping_address_state: {
            noSpace: true,
        },
        // fax: {required: true, number: true, minlength: 10, maxlength: 15},
        // fax: {required: true, number: true, minlength: 10, maxlength: 15},
        // fax: {required: true, number: true, minlength: 10, maxlength: 15},
        // fax: {required: true, number: true, minlength: 10, maxlength: 15},
        // email: {required: true, maxlength: 250},
    },
    messages: {
        display_name_as: {
            required: "Display name as is required",
        },
        first_name: {
            required: "First name is required",
        },
        last_name: {
            required: "Last name is required",
        },
        // company_name: {
        //     required: "Company name is required",
        // },
        phone: "Please enter valid phone number",
        mobile: "Please enter valid mobile number",
        // fax: {
        //     required: "Fax is required",
        // },
        // email: {
        //     required: "Email is required",
        // },
    },
    submitHandler: function (form) {
        var $form = $(form);
        form.submit();
        $('.custom_save_button').prop('disabled', true);
        $('.save_send').prop('disabled', true);
    },
    invalidHandler: function () {
        $('.custom_save_button').prop('disabled', false);
        $('.save_send').prop('disabled', false);
    }
});

// Accept only digit
$(document).on("input", "#shipping_address_zip,#billing_address_zip", function() {
    this.value = this.value.replace(/\D/g,'');
});

// Check blank space validation
jQuery.validator.addMethod("noSpace", function(value, element) { 
  return value == '' || value.trim().length != 0;  
}, "Only blank space not allow.");

/**********************************************************
 Item View Popup
 ***********************************************************/
$(document).on('click', '.customer_view_btn', function () {
    $('#custom_loading').removeClass('hide');
    $('#custom_loading').css('display', 'block');
    $.ajax({
        url: site_url + 'customers/get_customer_data_ajax_by_id',
        type: "POST",
        data: {id: this.id},
        success: function (response) {
            $('#custom_loading').removeClass('hide');
            $('#custom_loading').css('display', 'none');
            $('#customer_view_body').html(response);
            $('#customer_view_modal').modal('show');
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

// append email
$('.add_email').on('click', function () {
    var email_num = ((parseInt($('.email_tr').last().attr('data-value'))) + 1);
    email_html(email_num);
});

function email_html(email_num){
    var append_html = "<tr class='email_tr' id='tr_"+email_num+"' data-value='"+email_num+"'>"+
                        "<td><input type='text' class='form-control' name='secondary_email[]' id='secondary_email_"+email_num+"' placeholder='Secondary Email' value='' />"+
                        "</td>"+
                        "<td>"+
                            "<input type='checkbox' class='email_status' name='secondary_email_status[]' id='secondary_email_status_"+email_num+"' style='height:25px;width:50px;' checked='checked' value='1' data-attr='"+email_num+"'>"+
                        "</td>"+
                        "<td>"+
                            "<span class='remove' id='remove_"+email_num+"' data-attr='"+email_num+"'><i class='icon-trash'></i></span>"+
                        "</td>"+
                    "</tr>";
                    $('.email_div').append(append_html);
}

// Remove row
$(document).on('click', '.remove', function () {
    var count = $('.email_tr').length;
    if(count > 1) {
        var email_num = $(this).attr('data-attr');
        $('#tr_' + email_num).fadeOut(3000).remove();
    }
});

// update email status
$(document).on('change','.email_status',function(){
    var email_num = $(this).attr('data-attr');
    var status_val = $(this).val();
    var final_status = '';
    if(status_val == 1)
    {
        final_status = 0;
    } else {
        final_status = 1;
    }
    $('#secondary_email_status_'+email_num).val(final_status);
});

$('#copy_billing_address').click(function () {
    if ($(this).prop("checked") == true) {
        if ($("#billing_address").val() && $("#billing_address").val() != '') {
            $("#shipping_address").val($("#billing_address").val());
        }
        if ($("#billing_address_city").val() && $("#billing_address_city").val() != '') {
            $("#shipping_address_city").val($("#billing_address_city").val());
        }
        if ($("#billing_address_street").val() && $("#billing_address_street").val() != '') {
            $("#shipping_address_street").val($("#billing_address_street").val());
        }
        if ($("#billing_address_state").val() && $("#billing_address_state").val() != '') {
            $("#shipping_address_state").val($("#billing_address_state").val());
        }
        if ($("#billing_address_zip").val() && $("#billing_address_zip").val() != '') {
            $("#shipping_address_zip").val($("#billing_address_zip").val());
        }
    } else if ($(this).prop("checked") == false) {
        $("#shipping_address").val("");
        $("#shipping_address_city").val("");
        $("#shipping_address_street").val("");
        $("#shipping_address_state").val("");
        $("#shipping_address_zip").val("");
    }
});

$(document).on('click','#copy_billing_address',function(){
    if($("#copy_billing_address").prop('checked') == true){
        $('#copy_billing_address').val(1);
        $('#shipping_address').attr('readonly', true);
        $('#shipping_address_zip').attr('readonly', true);
        $('#shipping_address_city').attr('readonly', true);
        $('#shipping_address_state').attr('readonly', true);
    } else {
        $('#copy_billing_address').val(0);
        $('#shipping_address').attr('readonly', false);
        $('#shipping_address_zip').attr('readonly', false);
        $('#shipping_address_city').attr('readonly', false);
        $('#shipping_address_state').attr('readonly', false);
    }
    var checked_billing_address = $('#billing_address').val();
    var checked_billing_city = $('#billing_address_city').val();
    var checked_billing_state = $('#billing_address_state').val();
    var checked_billing_zip = $('#billing_address_zip').val();

    // Remover validation blank space validation if value is not blank
    if(checked_billing_address != "") {
        $('#shipping_address').valid();
    }
    if(checked_billing_city != "") {
        $('#shipping_address_city').valid();
    }
    if(checked_billing_state != "") {
        $('#shipping_address_state').valid();
    }
    if(checked_billing_zip != "") {
        $('#shipping_address_zip').valid();
    }
    
});

$(document).on('keyup','#billing_address,#billing_address_city,#billing_address_state,#billing_address_zip',function(){
    if($("#copy_billing_address").prop('checked') == true){
        var checked_billing_address = $('#billing_address').val();
        var checked_billing_city = $('#billing_address_city').val();
        var checked_billing_state = $('#billing_address_state').val();
        var checked_billing_zip = $('#billing_address_zip').val();
        $('#shipping_address').val(checked_billing_address);
        $('#shipping_address_city').val(checked_billing_city);
        $('#shipping_address_state').val(checked_billing_state);
        $('#shipping_address_zip').val(checked_billing_zip);
    }
});

// check checkbox status on update page 
if(uri_edit == 1 && uri_edit != 'undefined')
{
    if(checkbox_status == 'checked'){
        $("#copy_billing_address").prop( "checked", true );
        $('#copy_billing_address').val(1);
        $('#shipping_address').attr('readonly', true);
        $('#shipping_address_zip').attr('readonly', true);
        $('#shipping_address_city').attr('readonly', true);
        $('#shipping_address_state').attr('readonly', true);
    }
}

$('.datatable-responsive-customers-invoices').dataTable({
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
    lengthMenu: [[10, 20, 30, 50, -1], [10, 20, 30, 50, "All"]],
    order: [[1, "desc"]],
    ajax: {
        'type': 'GET',
        "url": site_url + 'customers/get_customers_invoice_data',
        "data": {
            "customer_id": customer_id
        },
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
            data: "invoice_date",
            visible: true,
            render: function (data, type, full, meta) {
                result = '<b>Date : </b>' + full.invoice_date;
                return result;
            }
        },
        {
            data: "is_invoiced",
            visible: true,
            render: function (data, type, full, meta) {
                var html = '';

                if (data == 1) {
                    html += '<span class="label bg-info-400">Invoice</span>';
                } else {
                    html += '<span class="label bg-warning-400">Estimate</span>';
                }

                return html;
            }
        },
        {
            data: "estimate_id",
            visible: true,
        },
        {
            data: "expiry_date",
            visible: true,
            render: function (data, type, full, meta) {
                var date_html = '';

                if (data && data != '') {
                    date_html += moment(data).format('MM-DD-YY');
                } else {
                    date_html += '---';
                }

                return date_html;
            }
        },
        {
            data: "invoice_balance",
            visible: true,
            className: 'balance',
            sortable: false,
            render: function (data, type, full, meta) {
                return '$' + parseFloat(data).toFixed(2);
            }
        },
        {
            data: "total",
            visible: true,
            className: 'sum',
            sortable: false,
            render: function (data, type, full, meta) {
                return '$' + parseFloat(data).toFixed(2);
            }
        },
        // {
        //     data: "is_invoiced",
        //     render: function (data, type, full, meta) {
        //         var status_label = '';

        //         if (full.is_invoiced == 1) {
        //             if (full.is_sent == 1) {
        //                 status_label = '<span class="label bg-blue-400">BILLED</span>';
        //             } else if (full.is_paid == 1) {
        //                 status_label = '<span class="label bg-info-400">PAID</span>';
        //             } else {
        //                 status_label = '<span class="label bg-orange-400">DRAFT</span>';
        //             }
        //         } else {
        //             if (full.turn_invoiced == 1) {
        //                 status_label = '<span class="label bg-teal-400">INVOICED</span>';
        //             } else {
        //                 if (full.is_sent == 1) {
        //                     status_label = '<span class="label bg-success-400">SENT</span>';
        //                 } else if (full.is_save_draft == 1) {
        //                     status_label = '<span class="label bg-blue-400">DRAFT</span>';
        //                 }
        //             }
        //         }
        //         return status_label;
        //     },
        //     sortable: false,
        // },
        {
            data: "payment_method_name",
            render: function (data, type, full, meta) {
                var status_label = '';

                if (data != '' && data != null && data != 0) {
                    status_label = '<span class="label bg-primary-400">'+data+'</span>';
                }
                else
                {
                    status_label = '<span class="label bg-warning-400">DRAFT</span>';
                }

                return status_label;
            },
            sortable: false,
        },
        {
            data: "action",
            render: function (data, type, full, meta) {
                action = '';
                if(session_set_status == 'yes')
                {
                    if(full.quickbooks == 1)
                    {
                        if(full.is_invoiced == 1)
                        {
                            var url = site_url + 'invoices/add_to_quickbook/' + btoa(full.id); 
                        }
                        else
                        {
                            var url = site_url + 'estimates/add_to_quickbook/' + btoa(full.id); 
                        }
                        action += '<a href="' + url + '" class="btn custom_dt_action_button_quickbook btn-xs AddToQuickbook" title="add to quickbooks">add to quickbooks</a>';
                    }
                }
                if (full.is_invoiced == 1) {
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'invoices/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Open</a>';
                } else {
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'estimates/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Open</a>';
                }
                if (full.is_invoiced == 0) {
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'invoices/copy_invoice?estimate=' + btoa(full.id) + '" class="btn btn-xs custom_dt_action_button" title="Copy to Invoice">Copy to Invoice</a>';
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
    "fnDrawCallback": function () {
        var info = document.querySelectorAll('.switchery-info');
        $(info).each(function () {
            var switchery = new Switchery(this, {color: '#95e0eb'});
        });
    },
    footerCallback: function (row, data, start, end, display) {
        var api = this.api();
        api.columns('.sum', {page: 'current'}).every(function () {
            var sum = this
                    .data()
                    .reduce(function (a, b) {
                        var x = parseFloat(a) || 0;
                        var y = parseFloat(b) || 0;
                        return x + y;
                    }, 0);
            $(this.footer()).html('$' + parseFloat(sum).toFixed(2));
        });

        api.columns('.balance', {page: 'current'}).every(function () {
            var sum = this
                    .data()
                    .reduce(function (a, b) {
                        var x = parseFloat(a) || 0;
                        var y = parseFloat(b) || 0;
                        return x + y;
                    }, 0);
            $(this.footer()).html('$' + parseFloat(sum).toFixed(2));
            $("#invoice-open-amount").empty().html('$' + parseFloat(sum).toFixed(2));
        });

        $("select[name=DataTables_Table_0_length]").addClass('form-control').css('width', '55%');
    }
});

$("#a_e_n_b").click(function () {
    var customer_id = $(this).attr('data-customerid');
    add_edit_note_modal(customer_id, null);
});

function add_edit_note_modal(customer_id = null, note_id = null) {
    var title, action_url;

    if (note_id && note_id != null) {
        title = 'Edit Note';
        action_url = site_url + 'customers/note/update/' + note_id;

        $.ajax({
            type: 'POST',
            url: site_url + "customers/note/get",
            data: {
                note_id: note_id
            },
            success: function (data) {
                $("#notes").empty().val(data);
            }
        });
    } else {
        title = 'Add Note'
        action_url = site_url + 'customers/note/store';
    }

    if (customer_id && customer_id != null) {
        $("#add_edit_note_form").append('<input type="hidden" name="c_id" value="' + customer_id + '" />');
    }

    $("#note_header_title").empty().text(title);
    $("#add_edit_note_form").attr('action', action_url);

    $("#add_edit_note_modal").modal('show');
}

if (customer_id && customer_id != undefined) {
    $('#customers-notes').dataTable({
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
            url: site_url + 'customers/get_customer_notes',
            type: "GET",
            data: {
                customer_id: customer_id
            }
        },
        responsive: {
            details: {
                type: 'column',
                target: -1
            }
        },
        columns: [
            {
                data: "notes",
                render: function (data, type, full, meta) {
                    notes = '';
                    notes += '<textarea style="width:100%; height:75px;"> '+full.notes+' </textarea>';
                    return notes;
                },
                visible: true,
            },
            {
                data: "created_date",
                visible: true,

            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    action = '';
                    action += '&nbsp;&nbsp;<button type="button" onclick="return add_edit_note_modal(' + customer_id + ',' + full.id + ');" class="btn custom_dt_action_button btn-xs"title="Edit">Edit</button>';
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'customers/note/delete/' + btoa(full.id) + '/' + btoa(full.customer_id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
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
            $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
        },
        "fnDrawCallback": function () {
            var info = document.querySelectorAll('.switchery-info');
            $(info).each(function () {
                var switchery = new Switchery(this, {color: '#95e0eb'});
            });
            $('.select[name=DataTables_Table_1_length]').addClass('form-control').css('width', '55%');
        }
    });
}

function get_total_month_wise_invoice(month,currentmonth){
    $.ajax({
        url: 'customers/ajax_monthoverdue',
        type: 'POST',
        data: { 'month' : month },
        success: function(data){
            data = JSON.parse(data);
            var monthrevenue = parseFloat(data.total_invoices_amount).toFixed(2);
            $('.monthrevenue').html('$'+monthrevenue); 
            $('.overdue').html(currentmonth);
        },
        error: function(error){
            alert('Error');
        }
    });
}

function get_total_year_wise_invoice(year){
    $.ajax({
        url:'customers/ajax_yearestimate',
        type:'POST',
        data: { 'year' : year },
        success: function(data){
            data = JSON.parse(data);

            var totalrevenue = parseFloat(data.total_invoices_amount).toFixed(2);
            $('.totalrevenue').html('$'+totalrevenue);
            $('.estimate').html(year);
        },
        error: function(error){
            alert('Error');
        }
    });
}

