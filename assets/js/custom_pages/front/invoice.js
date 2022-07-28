/**
 * Validate float value upto two decimal places
 * @param {object} el
 * @param {event} evt
 * @returns {Boolean}
 * @author PAV
 */
$(document).ready(function () {
    var invoice_id = 0;
    
    // Open preview PDF file
    if(edit_pdf_preview != "" && edit_pdf_preview != null && edit_pdf_preview != undefined)
    {
        $('#preview_model').modal('show');
    }
    
    $('.estimate_date').pickadate({
        format: date_format,
        formatSubmit: 'yyyy/mm/dd',
        today: 'Today',
        clear: 'Clear',
        close: 'Close',
        selectMonths: true,
        selectYears: true,    
    });

    $('.expiry_date').pickadate({
        format: date_format,
        formatSubmit: 'yyyy/mm/dd',
        today: 'Today',
        clear: 'Clear',
        close: 'Close',
        selectMonths: true,
        selectYears: true
    });
    
    if(invoice_notification == 1 || item_notification == 1)
    {
        notification("Invoice added" ,"Khyati Mehta", "04-11-2019", "1");
        $.post(site_url + 'home/invoice_notification', function(data) {
        });
    }

    if($("#part_dis_checkbox_1").prop('checked') == true){
        $("#discount_part_hide_1").show();
    } else {
        $("#discount_part_hide_1").hide();
    }

    if($("#part_tax_checkbox_1").prop('checked') == true){
        $("#tax_part_hide_1").show();
    } else {
        $("#tax_part_hide_1").hide();
    }

    if($("#service_dis_checkbox_1").prop('checked') == true){
        $("#discount_service_hide_1").show();
    } else {
        $("#discount_service_hide_1").hide();
    }

    if($("#service_tax_checkbox_1").prop('checked') == true){
        $("#tax_service_hide_1").show();
    } else {
        $("#tax_service_hide_1").hide();
    }

    // Setup - add a text input to each footer cell
    $('.datatable-responsive-control-right tfoot th').each(function () {
        var title = $(this).text();
        if ($(this).hasClass('slct')) {
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        }
    });

    // Due date calender alert
    $(document).on('change', '.estimate_date,.expiry_date', function(event) {
        var est_date = $('[name="estimate_date_submit"]').val();
        var exp_date = $('[name="expiry_date_submit"]').val();
        if(exp_date != ""){
            if(new Date(est_date) > new Date(exp_date))
            {
                $('[name="expiry_date_submit"]').val('');
                $('[name="expiry_date"]').val('');
                $('.expiry_date_alert').fadeOut(500).fadeIn(500);

                // Uncheck radio button
                $("#dueon").prop("checked", false);
                $("#net15").prop("checked", false);
                $("#net30").prop("checked", false);
                $('#dueon').parents('#span1').removeClass('checked');
                $('#net15').parents('#span2').removeClass('checked');
                $('#net30').parents('#span3').removeClass('checked');
            } else {
                $('.expiry_date_alert').fadeOut(500);
            }
        }
    });

    // Check multiple email list is email or not.
    $("#multiple_email_list").on('beforeItemAdd', function(event) {
        var email = event.item;
        isEmail(email);
        if(!isEmail(email)) {
            event.cancel = true;
            $('.invalid_email_alert').fadeIn(100);
        } else {
            $('.invalid_email_alert').fadeOut(100);
            $('label[for=multiple_email_list]').remove();
        }
    });

    $(document).on('click', '.invoice-action', function () {
        var button_val = $(this).attr('data-value');
        var button_text = 'save_send';

        // Remove invalid email alert
        $('.invalid_email_alert').hide();
        
        // add blank email validation
        var email_id = $('#multiple_email_list').val();
        if(button_val == 1 && email_id == "")
        {   
            $("#multiple_email_list").prop('required',true);
            $('.email_required').addClass('required');
            $('html, body').animate({
                scrollTop: ($('#multiple_email_list').offset().top - 2100)
            }, 10);
        } else {
            $('.email_required').removeClass('required');
            $("#multiple_email_list").prop('required',false);
        }

        $("#hidden_submit_value").empty().html('<input type="hidden" name="' + button_text + '" value="' + button_val + '" />');
        $(this).trigger('submit');
    });

    // Remove email required validation on save, print, preview
    $(document).on('click', '.custom_save_button', function () {
        $('.email_required').removeClass('required');
        $("#multiple_email_list").prop('required',false);
        // Remove invalid email alert
        $('.invalid_email_alert').hide();
    });
    
    $(document).on('click', '#custom_submit_save', function () {
        $("#hidden_submit_value").empty().html('<input type="hidden" name="save" />');
        $(this).trigger('submit');
    });

    $(document).on('click', '#submit_save_draft', function () {
        $("#hidden_submit_value").empty().html('<input type="hidden" name="save_draft" value="1" />');
        $(this).trigger('submit');
    });

    $("#txt_phone_number").attr('minLength', 10);
    $("#txt_phone_number").attr('maxLength', 10);
    $("#txt_phone_number").keyup(function () {
        var text = $(this).val();
        text = text.replace(/(\d\d\d)(\d\d\d)(\d\d\d\d)/, "($1) $2 - $3");
        $(this).val(text);
    });

    // Manage button toggle
    if($('.btntoggle').prop('checked')){
        $('.payment-toggle').show();
    } else {
        $('.payment-toggle').hide();
    }

    $(".btntoggle").change(function() {
        if(this.checked) {
            $('.payment-toggle').show();
        }else{
            $('.payment-toggle').hide();
        }
    });

    $("#invoice_type").change(function() {
        var checked_val;

        if(this.checked) {
            checked_val = 1;
        }else{
            checked_val = 0;
        }

        $("#invoice_type").val(checked_val);
    });

    // Set same due date
    $('.dueon').click(function(){
        // For display in calendar
        var estimatedate = $('.estimate_date').val();
        $('.expiry_date').val(estimatedate);

        // For submit date in DB
        var estimatedate_submit_dueon = $("input[name=estimate_date_submit]").val();
        $("input[name=expiry_date_submit]").val(estimatedate_submit_dueon);
    });

    // Add +15 days and +30 days in due date
    $(document).on('click','.net15,.net30',function(){
        var date_type = $(this).data('val');
        var inv_date = $("input[name=estimate_date_submit]").val();
        var date = new Date(inv_date);
        var newdate = new Date(date);
        
        if(date_type == 15) {
            newdate.setDate(newdate.getDate() + 15);
        }
        if(date_type == 30) {
            newdate.setDate(newdate.getDate() + 30);
        }
        
        var dd = newdate.getDate();
        var mm = newdate.getMonth()+1;
        var yy = newdate.getFullYear();
        var short_month = newdate.toLocaleString('default', { month: 'short' });
        var full_month = newdate.toLocaleString('default', { month: 'long' });
        var short_day =  moment(newdate).format('ddd');
        var full_day =  moment(newdate).format('dddd');
        
        if (mm < 10) {
            mm = '0' + mm; 
        }
        if (dd < 10) {
            dd = '0' + dd;
        }

        switch(date_format) {
            case 'mm/dd/yy':
                var someFormattedDate = mm + '/' + dd + '/' + yy.toString().substring(2);
            break;

            case 'dd/mm/yy':
                var someFormattedDate = dd + '/' + mm + '/' + yy.toString().substring(2);
            break;

            case 'yy/mm/dd':
                var someFormattedDate = yy.toString().substring(2) + '/' + mm + '/' + dd;
            break;

            case 'mm/dd/yyyy':
                var someFormattedDate = mm + '/' + dd + '/' + yy;
            break;

            case 'dd/mm/yyyy':
                var someFormattedDate = dd + '/' + mm + '/' + yy;
            break;

            case 'yyyy/mm/dd':
                var someFormattedDate = yy + '/' + mm + '/' + dd;
            break;

            case 'dd mmm yyyy':
                var someFormattedDate = dd + ' ' + short_month + ' ' + yy;
            break;

            case 'dd mmmm yyyy':
                var someFormattedDate = dd + ' ' + full_month + ' ' + yy;
            break;

            case 'mmmm dd, yyyy':
                var someFormattedDate = full_month + ' ' + dd + ', ' + yy;
            break;

            case 'ddd, mmmm dd, yyyy':
                var someFormattedDate = short_day + ', ' + full_month + ' ' + dd + ', ' + yy;
            break;

            case 'dddd, mmmm dd, yyyy':
                var someFormattedDate = full_day + ', ' + full_month + ' ' + dd + ', ' + yy;
            break;

            case 'mmm dd, yyyy':
                var someFormattedDate = short_month + ' ' + dd + ', ' + yy;
            break;

            case 'yyyy mm dd':
                var someFormattedDate = yy + ' ' + mm + ' ' + dd;
            break;
        }

        $('.expiry_date').val(someFormattedDate);

        // For submit date in DB
        var final_due_date = yy + '/' + mm + '/' + dd;
        $("input[name=expiry_date_submit]").val(final_due_date);
    });

    // Add +30 days in due date
    // $('.net30').click(function() {
    //     // For display in calendar
    //     var inv_date = $("input[name=estimate_date_submit]").val();
    //     var date = new Date(inv_date);
    //     var newdate = new Date(date);
    //     newdate.setDate(newdate.getDate() + 30);
    //     var dd = newdate.getDate();
    //     var mm = newdate.getMonth()+1;
    //     var yy = newdate.getFullYear();
    //     var short_month = newdate.toLocaleString('default', { month: 'short' });
    //     var full_month = newdate.toLocaleString('default', { month: 'long' });
    //     var short_day =  moment(newdate).format('ddd');
    //     var full_day =  moment(newdate).format('dddd');

    //     if (mm <10){
    //         mm = '0' + mm; 
    //     }
    //     if (dd < 10){
    //         dd = '0' + dd;
    //     }

    //     if(date_format == 'mm/dd/yy'){
    //         var someFormattedDate = mm + '/' + dd + '/' + yy.toString().substring(2);
    //     }
    //     if(date_format == 'dd/mm/yy'){
    //         var someFormattedDate = dd + '/' + mm + '/' + yy.toString().substring(2);
    //     }
    //     if(date_format == 'yy/mm/dd'){
    //         var someFormattedDate = yy.toString().substring(2) + '/' + mm + '/' + dd;
    //     }
    //     if(date_format == 'mm/dd/yyyy'){
    //         var someFormattedDate = mm + '/' + dd + '/' + yy;
    //     }
    //     if(date_format == 'dd/mm/yyyy'){
    //         var someFormattedDate = dd + '/' + mm + '/' + yy;
    //     }
    //     if(date_format == 'yyyy/mm/dd'){
    //         var someFormattedDate = yy + '/' + mm + '/' + dd;
    //     }
    //     if(date_format == 'dd mmm yyyy'){
    //         var someFormattedDate = dd + ' ' + short_month + ' ' + yy;
    //     }
    //     if(date_format == 'dd mmmm yyyy'){
    //         var someFormattedDate = dd + ' ' + full_month + ' ' + yy;
    //     }
    //     if(date_format == 'mmmm dd, yyyy'){
    //         var someFormattedDate = full_month + ' ' + dd + ', ' + yy;
    //     }
    //     if(date_format == 'ddd, mmmm dd, yyyy'){
    //         var someFormattedDate = short_day + ', ' + full_month + ' ' + dd + ', ' + yy;
    //     }
    //     if(date_format == 'dddd, mmmm dd, yyyy'){
    //         var someFormattedDate = full_day + ', ' + full_month + ' ' + dd + ', ' + yy;
    //     }
    //     if(date_format == 'mmm dd, yyyy'){
    //         var someFormattedDate = short_month + ' ' + dd + ', ' + yy;
    //     }
    //     if(date_format == 'yyyy mm dd'){
    //         var someFormattedDate = yy + ' ' + mm + ' ' + dd;
    //     }

    //     $('.expiry_date').val(someFormattedDate);
        
    //     // For submit date in DB
    //     var estimatedate_submit_net_thirty = yy + '/' + mm + '/' + dd;
    //     $("input[name=expiry_date_submit]").val(estimatedate_submit_net_thirty);
    // });

    // Redirect on edit page on print preview
    var redirect_status_invoice = print_preview_url_invoice;
    if(redirect_status_invoice != "" && redirect_status_invoice != null)
    {
        window.location.replace(redirect_status_invoice); 
    }

    $(function(){
      $('input[type="radio"]').click(function(){
        if ($('#dueon').is(':checked'))
        {
           $('#span1').addClass('checked');
           $('#span2').removeClass('checked');
           $('#span3').removeClass('checked');
        }
        if ($('#net15').is(':checked'))
        {
           $('#span1').removeClass('checked');
           $('#span2').addClass('checked');
           $('#span3').removeClass('checked');
        }
        if ($('#net30').is(':checked'))
        {
           $('#span1').removeClass('checked');
           $('#span2').removeClass('checked');
           $('#span3').addClass('checked');
        }
      });
    });

    // remove validation on the dropdown id value is not empty
    $('#sales_person').change(function(){
        $(this).valid()
    });

});

// Check email
function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
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
        paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
        emptyTable: 'No data currently available.'
    },
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    order: [[0, "desc"]],
    ajax: site_url + 'invoices/get_invoice_data',
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
            data: "estimate_date",
            visible: true,
        },
        {
            data: "estimate_id",
            visible: true,
        },
        {
            data: "cust_name",
            visible: true,
        },
        {
            data: "full_name",
            visible: true,
        },
        {
            data: "is_sent",
            visible: true,
            render: function (data, type, full, meta) {
                var action = '';
                if (full.is_sent == 1) {
                    action = '<span class="label bg-blue-400">BILLED</span>';
                } else if (full.is_paid == 1) {
                    action = '<span class="label bg-success-400">PAID</span>';
                } else {
                    action = '<span class="label bg-orange-400">DRAFT</span>';
                }
                return action;
            }

        },
        {
            data: "total",
            className: 'invoice_sum',
            visible: true,
            render: function (data, type, full, meta) {
                return parseFloat(full.total).toFixed(2);
            }
        },
        {
            data: "action",
            render: function (data, type, full, meta) {
                var action = '';
                invoice_id = full.id;
                if(session_set_status == 'yes')
                {
                    if(full.quickbooks == 1)
                    {
                        action += '<a href="' + site_url + 'invoices/add_to_quickbook/' + btoa(full.id) + '" class="btn custom_dt_action_button_quickbook btn-xs AddToQuickbook" title="add to quickbooks">add to quickbooks</a>';
                    }
                }
                action += '<a href="' + site_url + 'invoices/view/' + btoa(full.id) + '" class="btn btn-xs custom_dt_action_button" title="View">View</a>';
                if (edit == 1) {
//                    if (full.is_deducted == 0 && full.is_sent == 0) {
                    action += '<a href="' + site_url + 'invoices/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Edit</a>';
//                    }
                }
                if (dlt == 1) {
                    action += '<a href="' + site_url + 'invoices/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
                }
                action += '<a target="_BLANK" href="' + site_url + 'invoices/print_pdf/' + btoa(full.id) + '" class="btn btn-xs custom_dt_action_button" title="Print">Print</a>';
                
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
    },
    footerCallback: function (row, data, start, end, display) {
        var api = this.api();
        api.columns('.invoice_sum', {page: 'current'}).every(function () {
            var sum = this
                    .data()
                    .reduce(function (a, b) {
                        var x = parseFloat(a) || 0;
                        var y = parseFloat(b) || 0;
                        return x + y;
                    }, 0);
            $(this.footer()).html('$' + parseFloat(sum).toFixed(2));
        });
    }
});

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});

$('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
if (add == 1) {
    var add_button = '<div class="text-right"><a href="' + site_url + 'invoices/add" class="btn bg-teal-400 btn-labeled custom_add_button"><b><i class="icon-plus-circle2"></i></b> Add Invoice</a></div>';
    $('.datatable-header').append(add_button);
}

/**********************************************************
 Confirm Alert
 ***********************************************************/
function confirm_alert(e) {
    swal({
        title: "Are you sure?",
        text: "Invoice will move under the trash.",
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
$("#add_invoice_form").validate({
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
        txt_cust_name: {
            required: true,
            normalizer: function(value) {
                return $.trim(value);
            },
            maxlength: 150
        },
        estimate_date: {required: true},
        sales_person: {required: true},
        // txt_make_name: { required: true },
        // txt_model_name: { required: true, number: true },
        // txt_year_name: { required: true },
        hidden_part_id: {required: true},
        // txt_email: {email: true},
        txt_phone_number: {
            minlength: 16,
            maxlength: 16,
        }
    },
    messages: {
        hidden_part_id: "Please add one or more part!",
        txt_phone_number: 'Please enter valid Phone number!'
    },
    submitHandler: function (form) {
        if (signature_attachment == '') {
            var signature_image = $("#signature-pad-img").attr('src');

            if (signature_image != '' && signature_image != undefined) {
                $("#signature_attachment").val(signature_image);
            }
        }

        var $form = $(form);
        var sub_total = $form.find('input.sub_total').val()
        if (sub_total == '0.00' || sub_total == 0.00) {
            $('#hidden_part_id_error2').text('Enter the valid part name or description.').show();
            $('html, body').animate({
                scrollTop: ($('#multiple_email_list').offset().top - 750)
            }, 10);
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

/**********************************************************
 Item View Popup
 ***********************************************************/
$(document).on('click', '.item_view_btn', function () {
    $('#custom_loading').removeClass('hide');
    $('#custom_loading').css('display', 'block');
    $.ajax({
        url: site_url + 'items/get_item_data_ajax_by_id',
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

if (typeof (edit_div) != 'undefined' && edit_div == 1) {
    $('.inventoy_div').removeClass('hide');
}

// Format displayed data
function formatRepo(repo) {
    if (repo.loading)
    return repo.text;
    var markup = "<div class='select2-result-repository clearfix'>";
    
    if (jQuery.isEmptyObject(repo.global_image) && jQuery.isEmptyObject(repo.image)) {
        markup += "<div class='select2-result-repository__avatar'><img src='" + ITEMS_IMAGE_PATH + "/no_image.jpg' /></div>";
    } 
    else 
    {
        if(jQuery.isEmptyObject(repo.referred_item_id)) {
            markup += "<div class='select2-result-repository__avatar'><img src='" + ITEMS_IMAGE_PATH + "/" + repo.image + "' /></div>";
        }else{
            markup += "<div class='select2-result-repository__avatar'><img src='" + ITEMS_IMAGE_PATH + "/" + repo.global_image + "' /></div>";
        }
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
            "<div class='select2-result-repository__cost'>UPC Barcode: " + repo.upc_barcode + "</div>" +
            "</div>" +
            "</div></div>";
    return markup;
}

// Format selection
function formatRepoSelection(repo, num, selected = null) {
    if (repo.id != '' && (repo.selected == false || selected == false)) {
        total_quantity = (jQuery.isEmptyObject(repo.total_quantity)) ? 0 : repo.total_quantity;
        var html = "<div class='row'>" +
                "<div class='col-md-11'>" +
                "<input type='hidden' name='hidden_part_id[]' value='" + repo.id + "' id='part_no_" + num + "' />" +
                "<div class='select2-result-title text-left white-space-nowrap'>" + repo.part_no + " (Vendor : " + repo.v1_name + " ) </div>" +
                "<div class='select2-result-description mt-10'> <input type='text' class='form-control' name='description[]' value='" + repo.description + "'/><input type='text' class='form-control item_note' name='item_note[]' value='' placeholder = 'Item note' autocomplete='off' /></div></div>" +
                "<div class='col-md-1 mt-2'>" +
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
    var get_this_index = $(this).parents('.' + estimate_id).attr('data-part_tax_id');
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
    $("#hidden_part_tax_id_" + get_this_index).val('');
    $("#hidden_part_tax_amount_" + get_this_index).val('');
    $("#hidden_pname_tax_id_" + get_this_index).val('');

    setTimeout(() => {
        set_select(num);
        sum_sub_total();
        caculateTotaltx();
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
    var loc_id = $('#session_val').val();
    var html = "<tr class='" + estimate_id + "' id='tr_" + num + "' data-value='" + num + "' data-part_tax_id='" + num + "'>" +
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
            "<div class='col-xs-4 col-sm-4 col-md-4 text-left'>" +
            "<span class='plus' id='plus_" + num + "' data-part_tax_id ='"+ num + "'><i class='icon-plus3 text-primary'></i></span>" +
            "<span class='minus' id='minus_" + num + "' data-part_tax_id ='"+ num + "'><i class='icon-minus3 text-primary'></i></span>" +
            "</div>" +
            "<div class='col-xs-8 col-sm-8 col-md-8'><span id='div_quantity_" + num + "'>1</span>" +
            "<br/><span id='span_quantity_" + num + "' class='span_quantity'></span>" +
            "</div>" +
            "</div>" +
            "</td>" +
            "<td><input type='hidden' value='0.00' name='rate[]' id='rate_" + num + "'/><span id='td_rate_" + num + "' class='td_rate'>0.00</span></td>" +
            "<td id='td_discount_" + num + "'><input type='hidden' value='0.00' name='discount[]' id='discount_" + num + "'/><input type='hidden' value='0.00' name='discount_rate[]' id='discount_rate_" + num + "'/><label class='chk-box-container custom-check'><input type='checkbox' name='' id='part_dis_checkbox_"+ num +"' class='part_dis_checkbox'><span class='checkmark'></span></label>" +
            "<div id='discount_part_hide_"+ num +"'  >" +
            "<div class='row' >" +
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
            "</div>" +
            "</td>" +
            "<td id='td_tax_" + num + "'>" +
            "<input type='hidden' value='0.00' name='tax[]' id='tax_" + num + "'/>" +
            "<label class='chk-box-container custom-check'>"+
            "<input type='checkbox' name='' id='part_tax_checkbox_" + num + "' class='part_tax_checkbox'/>" +
            "<span class='checkmark'></span>"+
            "</label>"+
            "<div id='tax_part_hide_" + num + "'>" +
            "<div class='row'>" +
            // "<select data-placeholder='Select a Tax...' class='select select-size-sm select-tax-data' id='tax_id_" + num + "' name='tax_id[]'>" +
            "<select data-placeholder='Select a Tax...' class='tax_multiple select select-size-sm select-tax-data tax_select_multiple' multiple='multiple' id='tax_id_" + num + "' data-part_tax_id ='"+ num + "' name='tax_id[]'>" +
            "</select>" +
            "<input type='hidden' id='hidden_part_tax_id_" + num + "' name='hidden_part_tax_id[hidden_part_tax_id_" + num + "][]' value=''>" +
            "<input type='hidden' id='hidden_part_tax_amount_" + num + "' name='hidden_part_tax_amount[hidden_part_tax_amount_" + num + "][]' value=''>" +
            "<input type='hidden' id='hidden_pname_tax_id_" + num + "' name='hidden_pname_tax_id[hidden_pname_tax_id_" + num + "][]' value=''>" +
            "</div>" +
            "<div class='col-md-12 tax_rate'>" +
            "<span id='span_tax_rate_" + num + "' class='span_tax_rate'></span>" +
            "</div>" +
            "</div>" +
            "</td>" +
            "<td><input type='hidden' value='0.00' name='amount[]' id='amount_" + num + "'/><span id='td_amount_" + num + "'>0.00</span></td>" +
            "<td class='white-space-nowrap' id='td_remove_" + num + "'>" +
            "<span class='scan-item-qr-code' id='scan-item-qr-code_1' data-rowid='" + num + "' title='Scan QR Code'><i class='icon-camera'></i></span>&nbsp;&nbsp;" +
            "<span class='remove ml-3' id='remove_" + num + "'><i class='icon-trash'></i></span></td>" +
            "</tr>";
    setTimeout(function () {
        set_select(num);
        if (typeof (locations) != undefined && locations != '') {
            $.each(locations, function (key, value) {
                if(loc_id !== "" && loc_id !== null)
                {
                    if(loc_id === value.id)
                    {
                        var o = $("<option/>", {value: value.id, text: value.name});
                        $('#txt_location_id_' + num).append(o);
                    }
                } else {
                    var o = $("<option/>", {value: value.id, text: value.name});
                    $('#txt_location_id_' + num).append(o);
                }
            });
            $('#txt_location_id_' + num).select2().trigger('change');
        }
        if (typeof (taxes) != undefined && taxes != '') {
            // var o = $("<option/>", {value: "", text: "Select a Tax..."});
            // $('#tax_id_' + num).append(o);
            $.each(taxes, function (key, value) {
                var o = $("<option/>", {value: value.id, text: value.name + " (" + value.rate + "%)"});
                $('#tax_id_' + num).append(o);
            });
            $('#tax_id_' + num).select2().trigger('change');
        }
    }, 10);
    $('.part_div').append(html);

    $('#discount_part_hide_'+ num +'').hide();
    $('#tax_part_hide_'+ num +'').hide();

    check_part_section();
}

function check_part_section(){
    if($('.tax_multiple option:selected').length == 0) {
        $('.hide_show_part_tax_amount').hide();
    } else {
        $('.hide_show_part_tax_amount').show();
    }
}

$(document).on('change','.part_dis_checkbox',function(){
    var num = $(this).parents('.' + estimate_id).attr('data-value');
    var get_this_index = $(this).parents('.' + estimate_id).attr('data-part_tax_id');
    var qty = parseInt($('#div_quantity_' + num).html());
    var rate = parseFloat($('#td_rate_' + num).html()).toFixed(2);
    var amount = parseFloat(qty * rate).toFixed(2);
    var tax_id = $('#tax_id_' + num).val();

    if($(this).prop('checked') == true){
        $('#discount_part_hide_'+ num).show();
        $('#discount_part_hide_'+ num).removeClass('hide');
        $('#span_discount_rate_'+ num).show();
    } else {
        $('#discount_part_hide_' + num).hide();
        $('#span_discount_rate_' + num).hide();
        $('#div_discount_'+ num).html('0.00');
        $('#discount_'+ num).val('0.00');
        $('#span_discount_rate_'+ num).html('0.00');
        $('#discount_rate_'+ num).val('0.00');

        var select_arr_amount = [];
        if(tax_id){
            $.each(tax_id,function(i,v) {
                calcPercpart = (parseFloat(amount) * parseFloat($("#tax_hidden_"+ v).val()) / 100);
                select_arr_amount.push(parseFloat(calcPercpart).toFixed(2));
                $('#hidden_part_tax_amount_'+get_this_index).val(select_arr_amount);
            });
        }
        $('#td_amount_'+num).html(amount);
        check_tax(num, tax_id, amount);
    }
});

$(document).on('change','.part_tax_checkbox',function(){
    var num = $(this).parents('.' + estimate_id).attr('data-value');
    if($(this).prop('checked') == true){
        $('#tax_part_hide_'+ num).show();
        $('#tax_part_hide_'+ num).removeClass('hide');
        $('#span_tax_rate_'+ num).show();
    } else {
        $('#tax_part_hide_' + num).hide();
        $('#span_tax_rate_' + num).hide();
        $('#hidden_pname_tax_id_'+num).val('');
        $('#hidden_part_tax_amount_'+num).val('');
        $('#span_tax_rate_'+num).html('0.00');
        $('#tax_id_'+num).val(null).trigger('change');
    }
});

$(document).on('change','.tax_multiple',function(){
    check_part_section();
    $('.hide_show_part_tax_amount').removeClass('hide');
    var get_this_index = $(this).attr('data-part_tax_id');
    var tax_name_list = $("option:selected",this).text();
    var select_arr = [];
    var select_arr_amount = [];

    $('#hidden_part_tax_amount_'+get_this_index).val('');
    $(this).each(function() {
        select_arr.push($(this).val());
        dta = $(this).val();
        amount_val = parseFloat($(this).parent('div').parent('div').parent('td').parent('tr').find('.td_amount').html()).toFixed(2);
        if(dta){
            $.each(dta,function(i,v) {
                calcPercpart = (parseFloat(amount_val) * parseFloat($("#tax_hidden_"+ v).val()) / 100);
                if(isNaN(calcPercpart))
                { 
                    calcPercpart = 0.00;
                }
                select_arr_amount.push(parseFloat(calcPercpart).toFixed(2));
                $('#hidden_part_tax_amount_'+get_this_index).val(select_arr_amount);
            });
        }
    });

    $('#hidden_part_tax_id_'+get_this_index).val(select_arr);
    $('#hidden_pname_tax_id_'+get_this_index).val(tax_name_list); 

    // amount_val = parseFloat($('#td_amount_'+num).html()).toFixed(2);
    // console.log('amount_val',amount_val);
});

$(document).on('click', '.remove', function () {
    var count = $('.' + estimate_id).length;
    if (count > 1) {
        var num = $(this).parents('.' + estimate_id).attr('data-value');
        $('#tr_' + num).fadeOut(3000).remove();
    }
    setTimeout(() => {
        sum_sub_total();
        caculateTotaltx();
    }, 20);
});

$(document).on('click', '.plus', function () {
    var num = $(this).parents('.' + estimate_id).attr('data-value');
    var get_this_index = $(this).parents('.' + estimate_id).attr('data-part_tax_id');
    var rate = parseFloat($('#td_rate_' + num).html()).toFixed(2);
    if (isNaN(rate))
        rate = '0.00';
    if ($('#td_part_no_' + num).hasClass('select_part') == false) {
        var total = parseInt($('#span_quantity_' + num).html());
        var qty = parseInt($('#div_quantity_' + num).html());
        var amount = parseFloat($('#td_rate_' + num).html()).toFixed(2);
        if (isNaN(amount))
            amount = '0.00';
        var discount = parseFloat($('#div_discount_' + num).html()).toFixed(2);
        if (isNaN(discount))
            amount = '0.00';
        if (qty < total) {
            var tax_id = $('#tax_id_' + num).val();
            var new_qty = (qty + 1);
            var new_amount = (new_qty * amount).toFixed(2);
            $('#div_quantity_' + num).html(new_qty);
            $('#quantity_' + num).val(new_qty);

            var amount_val = new_amount;
            var dis_type = $('#discount_type_id_' + num).val();
            if (dis_type == 'p') {
                if (discount > 100) {
                    discount = '0.00';
                    $('#div_discount_' + num).html(discount);
                    $('#discount_' + num).val(discount);
                } else {
                    var calcPerc = (amount_val * discount / 100);
                    amount_val = parseFloat(amount_val).toFixed(2) - parseFloat(calcPerc).toFixed(2);
                    amount_val = amount_val.toFixed(2);
                    $('#discount_rate_' + num).val(parseFloat(calcPerc).toFixed(2));
                    $('#span_discount_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
                }
            } else {
                if (parseInt(discount) > parseInt(rate)) {
                    discount = '0.00';
                    $('#div_discount_' + num).html(discount);
                    $('#discount_' + num).val(discount);
                } else {
                    amount_val = parseFloat(amount_val).toFixed(2) - parseFloat(discount).toFixed(2);
                    amount_val = amount_val.toFixed(2);
                    $('#discount_rate_' + num).val(parseFloat(discount).toFixed(2));
                    $('#span_discount_rate_' + num).html(parseFloat(discount).toFixed(2));
                }
            }

            var select_arr_amount = [];
            if(tax_id){
                $.each(tax_id,function(i,v) {
                    calcPercpart = (parseFloat(amount_val) * parseFloat($("#tax_hidden_"+ v).val()) / 100);
                    select_arr_amount.push(parseFloat(calcPercpart).toFixed(2));
                    $('#hidden_part_tax_amount_'+get_this_index).val(select_arr_amount);
                });
            }

            if (tax_id != '') {
                setTimeout(() => {
                    check_tax(num, tax_id, new_amount);
                }, 20);
            } else {
                setTimeout(() => {
                    calculate_discount(num, discount, new_amount);
                    sum_sub_total();
                }, 20);
            }
        }
    }

});
$(document).on('click', '.minus', function () {
    var num = $(this).parents('.' + estimate_id).attr('data-value');
    var get_this_index = $(this).parents('.' + estimate_id).attr('data-part_tax_id');
    var rate = parseFloat($('#td_rate_' + num).html()).toFixed(2);
    if (isNaN(rate))
        rate = '0.00';
    if ($('#td_part_no_' + num).hasClass('select_part') == false) {
        var total = parseInt($('#span_quantity_' + num).html());
        var qty = parseInt($('#div_quantity_' + num).html());
        var amount = parseFloat($('#td_rate_' + num).html()).toFixed(2);
        if (isNaN(amount))
            amount = '0.00';
        var discount = parseFloat($('#div_discount_' + num).html()).toFixed(2);
        if (isNaN(discount))
            discount = '0.00';
        if (total >= qty && qty > 1) {
            var tax_id = $('#tax_id_' + num).val();
            var new_qty = (qty - 1);
            var new_amount = (new_qty * amount).toFixed(2);
            $('#div_quantity_' + num).html(new_qty);
            $('#quantity_' + num).val(new_qty);

            var amount_val = new_amount;
            var dis_type = $('#discount_type_id_' + num).val();
            if (dis_type == 'p') {
                if (discount > 100) {
                    discount = '0.00';
                    $('#div_discount_' + num).html(discount);
                    $('#discount_' + num).val(discount);
                } else {
                    var calcPerc = (amount_val * discount / 100);
                    amount_val = parseFloat(amount_val).toFixed(2) - parseFloat(calcPerc).toFixed(2);
                    amount_val = amount_val.toFixed(2);
                    $('#discount_rate_' + num).val(parseFloat(calcPerc).toFixed(2));
                    $('#span_discount_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
                }
            } else {
                if (parseInt(discount) > parseInt(rate)) {
                    discount = '0.00';
                    $('#div_discount_' + num).html(discount);
                    $('#discount_' + num).val(discount);
                } else {
                    amount_val = parseFloat(amount_val).toFixed(2) - parseFloat(discount).toFixed(2);
                    amount_val = amount_val.toFixed(2);
                    $('#discount_rate_' + num).val(parseFloat(discount).toFixed(2));
                    $('#span_discount_rate_' + num).html(parseFloat(discount).toFixed(2));
                }
            }

            var select_arr_amount = [];
            if(tax_id){
                $.each(tax_id,function(i,v) {
                    calcPercpart = (parseFloat(amount_val) * parseFloat($("#tax_hidden_"+ v).val()) / 100);
                    select_arr_amount.push(parseFloat(calcPercpart).toFixed(2));
                    $('#hidden_part_tax_amount_'+get_this_index).val(select_arr_amount);
                });
            }

            if (tax_id != '') {
                setTimeout(() => {
                    check_tax(num, tax_id, new_amount);
                }, 20);
            } else {
                setTimeout(() => {
                    calculate_discount(num, discount, new_amount);
                    sum_sub_total();
                }, 20);
            }
        }
    }
});
$(document).on('click', '.div_discount', function () {
    var num = $(this).parents('.' + estimate_id).attr('data-value');
    if ($('#td_part_no_' + num).hasClass('select_part') == false) {
        if ($('#div_discount_' + num).children('.input_discount').length < 1) {
            var discount = parseFloat($('#div_discount_' + num).html()).toFixed(2);
            if (isNaN(discount))
                discount = '0.00';
            var html = '<input type="text" value="' + discount + '" id="input_discount_' + num + '" class="input_discount form-control input-xs"/>';
            $('#div_discount_' + num).removeClass('div_discount');
            $('#div_discount_' + num).html(html);
        }
    }
    $('#input_discount_' + num + '').keydown(function(rate) {
        if(!((rate.keyCode > 95 && rate.keyCode < 106)
          || (rate.keyCode > 47 && rate.keyCode < 58)
          || (rate.keyCode > 36 && rate.keyCode < 41) 
          || rate.keyCode == 8
          || rate.keyCode == 46
          || rate.keyCode == 110)) 
        {
            return false;
        }
    });
});
$(document).on('focusout', '.input_discount', function () {
    var num = $(this).parents('.' + estimate_id).attr('data-value');
    var get_this_index = $(this).parents('.' + estimate_id).attr('data-part_tax_id');
    var tax_id = $('#tax_id_' + num).val();
    var discount = parseFloat($('#input_discount_' + num).val()).toFixed(2);
    if (isNaN(discount))
        discount = '0.00';
    var rate = parseFloat($('#td_rate_' + num).html()).toFixed(2);
    if (isNaN(rate))
        rate = '0.00';
    var qty = parseInt($('#div_quantity_' + num).html());
    var amount = (qty * rate).toFixed(2);
    $('#div_discount_' + num).addClass('div_discount');
    $('#div_discount_' + num).html(discount);
    $('#discount_' + num).val(discount);

    var amount_val = amount;
    var dis_type = $('#discount_type_id_' + num).val();
    if (dis_type == 'p') {
        if (discount > 100) {
            discount = '0.00';
            $('#div_discount_' + num).html(discount);
            $('#discount_' + num).val(discount);
        } else {
            var calcPerc = (amount_val * discount / 100);
            amount_val = parseFloat(amount_val).toFixed(2) - parseFloat(calcPerc).toFixed(2);
            amount_val = amount_val.toFixed(2);
            $('#discount_rate_' + num).val(parseFloat(calcPerc).toFixed(2));
            $('#span_discount_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
        }
    } else {
        if (parseInt(discount) > parseInt(rate)) {
            discount = '0.00';
            $('#div_discount_' + num).html(discount);
            $('#discount_' + num).val(discount);
        } else {
            amount_val = parseFloat(amount_val).toFixed(2) - parseFloat(discount).toFixed(2);
            amount_val = amount_val.toFixed(2);
            $('#discount_rate_' + num).val(parseFloat(discount).toFixed(2));
            $('#span_discount_rate_' + num).html(parseFloat(discount).toFixed(2));
        }
    }

    var select_arr_amount = [];
    if(tax_id){
        $.each(tax_id,function(i,v) {
            calcPercpart = (parseFloat(amount_val) * parseFloat($("#tax_hidden_"+ v).val()) / 100);
            select_arr_amount.push(parseFloat(calcPercpart).toFixed(2));
            $('#hidden_part_tax_amount_'+get_this_index).val(select_arr_amount);
        });
    }

    if (tax_id != '') {
        setTimeout(() => {
            check_tax(num, tax_id, amount);
        }, 20);
    } else {
        setTimeout(() => {
            calculate_discount(num, discount, amount);
            sum_sub_total();
        }, 50);
    }
});

$(document).on('click', '.td_rate', function () {
    var num = $(this).parents('.' + estimate_id).attr('data-value');
    var rate = parseFloat($('#rate_' + num).val()).toFixed(2);
    if (isNaN(rate))
        rate = '0.00';
    if ($('#td_part_no_' + num).hasClass('select_part') == false) {
        var html = '<input type="text" value="' + rate + '" id="input_rate_' + num + '" class="input_rate form-control input-xs"/>';
        $('#td_rate_' + num).removeClass('td_rate');
        $('#td_rate_' + num).html(html);
    }
    $('#input_rate_' + num + '').keydown(function(rate) {
        if(!((rate.keyCode > 95 && rate.keyCode < 106)
          || (rate.keyCode > 47 && rate.keyCode < 58)
          || (rate.keyCode > 36 && rate.keyCode < 41) 
          || rate.keyCode == 8
          || rate.keyCode == 46
          || rate.keyCode == 110)) 
        {
            return false;
        }
    });
});

$(document).on('focusout', '.input_rate', function () {
    var num = $(this).parents('.' + estimate_id).attr('data-value');
    var get_this_index = $(this).parents('.' + estimate_id).attr('data-part_tax_id');
    var tax_id = $('#tax_id_' + num).val();
    var discount = parseFloat($('#div_discount_' + num).html()).toFixed(2);
    if (isNaN(discount)) {
        discount = '0.00';
    }
    var rate = parseFloat($('#input_rate_' + num).val()).toFixed(2);
    if (isNaN(rate)) {
        rate = '0.00';
    }
    var qty = parseInt($('#div_quantity_' + num).html());
    var amount = (qty * rate).toFixed(2);
    $('#td_rate_' + num).addClass('td_rate');
    $('#td_rate_' + num).html(rate);
    $('#rate_' + num).val(rate);

    var amount_val = amount;
    var dis_type = $('#discount_type_id_' + num).val();
    if (dis_type == 'p') {
        if (discount > 100) {
            discount = '0.00';
            $('#div_discount_' + num).html(discount);
            $('#discount_' + num).val(discount);
        } else {
            var calcPerc = (amount_val * discount / 100);
            amount_val = parseFloat(amount_val).toFixed(2) - parseFloat(calcPerc).toFixed(2);
            amount_val = amount_val.toFixed(2);
            $('#discount_rate_' + num).val(parseFloat(calcPerc).toFixed(2));
            $('#span_discount_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
        }
    } else {
        if (parseInt(discount) > parseInt(rate)) {
            discount = '0.00';
            $('#div_discount_' + num).html(discount);
            $('#discount_' + num).val(discount);
        } else {
            amount_val = parseFloat(amount_val).toFixed(2) - parseFloat(discount).toFixed(2);
            amount_val = amount_val.toFixed(2);
            $('#discount_rate_' + num).val(parseFloat(discount).toFixed(2));
            $('#span_discount_rate_' + num).html(parseFloat(discount).toFixed(2));
        }
    }

    var select_arr_amount = [];
    if(tax_id){
        $.each(tax_id,function(i,v) {
            calcPercpart = (parseFloat(amount_val) * parseFloat($("#tax_hidden_"+ v).val()) / 100);
            select_arr_amount.push(parseFloat(calcPercpart).toFixed(2));
            $('#hidden_part_tax_amount_'+get_this_index).val(select_arr_amount);
        });
    }

    if (tax_id != '') {
        setTimeout(() => {
            check_tax(num, tax_id, amount);
        }, 20);
    } else {
        setTimeout(() => {
            calculate_discount(num, discount, amount);
            sum_sub_total();
        }, 50);
    }
});

function sum_sub_total() {
    var sub_total = '0.00';
    var sub_part_total = '0.00';
    var sub_service_total = '0.00';
    var shipping_charge = $(document).find('#shipping_charge').val();
    if ($(document).find('.td_amount').length > 0) {
        var all = $(document).find('.td_amount');
        var total = 0.00;
        $.each(all, function (i, v) {
            sub_part_total = (parseFloat(sub_part_total) + parseFloat($(v).text()));
            sub_part_total = sub_part_total.toFixed(2);
        });
    }
    if ($(document).find('.td_service_amount').length > 0) {
        var all = $(document).find('.td_service_amount');
        var total = 0.00;
        $.each(all, function (i, v) {
            sub_service_total = (parseFloat(sub_service_total) + parseFloat($(v).text()));
            sub_service_total = sub_service_total.toFixed(2);
        });
    }
    sub_total = (parseFloat(sub_part_total) + parseFloat(sub_service_total));
    sub_total = sub_total.toFixed(2);

    $('#sub_total').html(sub_total);
    $('.sub_total').val(sub_total);
    setTimeout(() => {
        check_total_tax();
        check_total(sub_total, shipping_charge);
    }, 50);
}

function check_total_tax() {
    var total_tax = '0.00';
    if ($(document).find('.span_tax_rate').length > 0) {
        var all = $(document).find('.span_tax_rate');
        var tax = '0.00';
        $.each(all, function (i, v) {
            if ($(v).html() != '') {
                tax = (parseFloat(tax) + parseFloat($(v).html()));
                tax = tax.toFixed(2);
            }
        });
        if (isNaN(tax))
            tax = '0.00';
    }
    if ($(document).find('.span_service_tax_rate').length > 0) {
        var all = $(document).find('.span_service_tax_rate');
        var stax = '0.00';
        $.each(all, function (i, v) {
            if ($(v).html() != '') {
                stax = (parseFloat(stax) + parseFloat($(v).html()));
                stax = stax.toFixed(2);
            }
        });
        if (isNaN(stax))
            stax = '0.00';
    }
    total_tax = (parseFloat(tax) + parseFloat(stax));
    total_tax = total_tax.toFixed(2);
    if (isNaN(total_tax))
        total_tax = '0.00';
    $('#span_total_tax').html(total_tax);
    $('#final_tax_rate').val(total_tax);
}

function calculate_discount(num, discount = 0, amount) {
    var rate = parseFloat($('#td_rate_' + num).html()).toFixed(2);
    if (isNaN(rate))
        rate = '0.00';
    var dis_type = $('#discount_type_id_' + num).val();
    if (dis_type == 'p') {
        if (discount > 100) {
            discount = '0.00';
            $('#div_discount_' + num).html(discount);
            $('#discount_' + num).val(discount);
        } else {
            var calcPerc = (amount * discount / 100);
            amount = parseFloat(amount).toFixed(2) - parseFloat(calcPerc).toFixed(2);
            amount = amount.toFixed(2);
            $('#discount_rate_' + num).val(parseFloat(calcPerc).toFixed(2));
            $('#span_discount_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
        }
    } else {
        if (parseInt(discount) > parseInt(rate)) {
            discount = '0.00';
            $('#div_discount_' + num).html(discount);
            $('#discount_' + num).val(discount);
        } else {
            amount = parseFloat(amount).toFixed(2) - parseFloat(discount).toFixed(2);
            amount = amount.toFixed(2);
            $('#discount_rate_' + num).val(parseFloat(discount).toFixed(2));
            $('#span_discount_rate_' + num).html(parseFloat(discount).toFixed(2));
        }
    }
    $('#td_amount_' + num).html(amount);
    $('#amount_' + num).val(amount);
}

function caculateTotaltx(){
    $('.total_tax_part').val(parseFloat(0.00).toFixed(2));
    $('.tax_select_multiple').each(function(index, el) {
        dta = $(this).val();
        amount_val = parseFloat($(this).parent('div').parent('div').parent('td').parent('tr').find('.td_amount').html()).toFixed(2);
        if(isNaN(amount_val))
        {
            amount_val = 0.00;
        }
        if(dta){
            $.each(dta,function(i,v) {
                $("#span_tax_hidden_parttotal_service" + v).removeClass('hidden');
                $("#tax_hidden_total_" + v).removeClass('hidden');
                calcPercpart = (parseFloat(amount_val) * parseFloat($("#tax_hidden_"+ v).val()) / 100);
                tax_hidden_total = parseFloat($('#tax_hidden_total_'+ v).val());
                tax_hidden_total += calcPercpart;
                $('#tax_hidden_total_'+ v).val(parseFloat(tax_hidden_total).toFixed(2)); 
            });
        }
    });
}

$(document).on('change', '.tax_select_multiple', function(event) {
    caculateTotaltx();
});

function check_tax(num, tax_id, rate) {
    var tax_total_rate = 0 ;
    var calcPercpart = 0;
    var amount = rate;
    var tax_rate = $("#tax_hidden_" + tax_id).val();
    if(tax_id)
    {
        $.each(tax_id,function(i,v) {
            tax_total_rate += parseFloat($('#tax_hidden_'+ v).val());
        });
    }

    var discount = parseFloat($('#div_discount_' + num).html()).toFixed(2);
    if (isNaN(discount))
        discount = '0.00';

    var amount_val = amount;
    var dis_type = $('#discount_type_id_' + num).val();
    if (dis_type == 'p') {
        if (discount > 100) {
            discount = '0.00';
            $('#div_discount_' + num).html(discount);
            $('#discount_' + num).val(discount);
        } else {
            var calcPerc = (amount_val * discount / 100);
            amount_val = parseFloat(amount_val).toFixed(2) - parseFloat(calcPerc).toFixed(2);
            amount_val = amount_val.toFixed(2);
            $('#discount_rate_' + num).val(parseFloat(calcPerc).toFixed(2));
            $('#span_discount_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
        }
    } else {
        if (parseInt(discount) > parseInt(rate)) {
            discount = '0.00';
            $('#div_discount_' + num).html(discount);
            $('#discount_' + num).val(discount);
        } else {
            amount_val = parseFloat(amount_val).toFixed(2) - parseFloat(discount).toFixed(2);
            amount_val = amount_val.toFixed(2);
            $('#discount_rate_' + num).val(parseFloat(discount).toFixed(2));
            $('#span_discount_rate_' + num).html(parseFloat(discount).toFixed(2));
        }
    }

    var calcPerc = (parseFloat(amount_val) * parseFloat(tax_total_rate) / 100);
    // amount = parseFloat(amount) + parseFloat(calcPerc);
    amount = parseFloat(amount).toFixed(2);
    $('#tax_' + num).val(parseFloat(calcPerc).toFixed(2));
    $('#span_tax_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
    setTimeout(() => {
        calculate_discount(num, discount, amount);
        sum_sub_total();
        caculateTotaltx();
    }, 40);
}


$(document).on('change', '.discount_type_id', function () {
    var num = $(this).parents('.' + estimate_id).attr('data-value');
    var get_this_index = $(this).parents('.' + estimate_id).attr('data-part_tax_id');
    var tax_id = $('#tax_id_' + num).val();
    if ($('#td_part_no_' + num).hasClass('select_part') == false) {
        var discount = parseFloat($('#div_discount_' + num).html()).toFixed(2);
        if (isNaN(discount))
            discount = '0.00';
        var rate = parseFloat($('#td_rate_' + num).html()).toFixed(2);
        if (isNaN(rate))
            rate = '0.00';
        var qty = parseInt($('#div_quantity_' + num).html());
        var amount = (qty * rate).toFixed(2);

        var amount_val = amount;
        var dis_type = $('#discount_type_id_' + num).val();
        if (dis_type == 'p') {
            if (discount > 100) {
                discount = '0.00';
                $('#div_discount_' + num).html(discount);
                $('#discount_' + num).val(discount);
            } else {
                var calcPerc = (amount_val * discount / 100);
                amount_val = parseFloat(amount_val).toFixed(2) - parseFloat(calcPerc).toFixed(2);
                amount_val = amount_val.toFixed(2);
                $('#discount_rate_' + num).val(parseFloat(calcPerc).toFixed(2));
                $('#span_discount_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
            }
        } else {
            if (parseInt(discount) > parseInt(rate)) {
                discount = '0.00';
                $('#div_discount_' + num).html(discount);
                $('#discount_' + num).val(discount);
            } else {
                amount_val = parseFloat(amount_val).toFixed(2) - parseFloat(discount).toFixed(2);
                amount_val = amount_val.toFixed(2);
                $('#discount_rate_' + num).val(parseFloat(discount).toFixed(2));
                $('#span_discount_rate_' + num).html(parseFloat(discount).toFixed(2));
            }
        }

        var select_arr_amount = [];
        if(tax_id){
            $.each(tax_id,function(i,v) {
                calcPercpart = (parseFloat(amount_val) * parseFloat($("#tax_hidden_"+ v).val()) / 100);
                select_arr_amount.push(parseFloat(calcPercpart).toFixed(2));
                $('#hidden_part_tax_amount_'+get_this_index).val(select_arr_amount);
            });
        }

        if (tax_id != '') {
            setTimeout(() => {
                check_tax(num, tax_id, amount);
            }, 20);
        } else {
            setTimeout(() => {
                calculate_discount(num, discount, amount);
                sum_sub_total();
            }, 50);
        }
    }
});

$(document).on('change', '.select-location-data', function () {
    var location_id = $(this).val();
    var num = $(this).parents('.' + estimate_id).attr('data-value');
    var item_id = $("#part_no_" + num).val();
    if ($('#td_part_no_' + num).hasClass('select_part') == false) {
        $.ajax({
            url: site_url + 'estimates/get_item_data_ajax_by_part_id',
            dataType: "json",
            type: "POST",
            data: {
                id: item_id, // search term
                location_id: location_id,
                itsfor: 'internal'
            },
            success: function (response) {
                data = response.data;
                if (response.code == 200) {
                    $("#span_quantity_" + num).html(data.location_quantity);
                    if (parseInt($("#div_quantity_" + num).html()) > parseInt(data.location_quantity)) {
                        $("#div_quantity_" + num).html('1');
                        $("#discount_" + num).val('1');
                        $("#td_amount_" + num).html(data.retail_price);
                        $("#amount_" + num).val(data.retail_price);
                        var discount = parseFloat($('#div_discount_' + num).html()).toFixed(2);
                        if (isNaN(discount))
                            discount = '0.00';
                        setTimeout(() => {
                            calculate_discount(num, discount, data.retail_price);
                            sum_sub_total();
                        }, 50);
                    }
                }
            }
        });
    }
});

$(document).on('change', '.select-tax-data', function () {
    var num = $(this).parents('.' + estimate_id).attr('data-value');
    if ($('#td_part_no_' + num).hasClass('select_part') == false) {
        var tax_id = $(this).val();
        var rate = parseFloat($('#td_rate_' + num).html()).toFixed(2);
        if (isNaN(rate))
            rate = '0.00';
        var qty = parseInt($('#div_quantity_' + num).html());
        var amount = (qty * rate).toFixed(2);
        if (tax_id != '') {
            setTimeout(() => {
                check_tax(num, tax_id, amount);
            }, 20);
        }
    }
});

$('#shipping_charge').keydown(function(rate) {
    if(!((rate.keyCode > 95 && rate.keyCode < 106)
      || (rate.keyCode > 47 && rate.keyCode < 58)
      || (rate.keyCode > 36 && rate.keyCode < 41) 
      || rate.keyCode == 8
      || rate.keyCode == 46
      || rate.keyCode == 110)) 
    {
        return false;
    }
});

// Check/uncheck shipping checkbox
shipping_checkbox_status();

$(document).on('blur', '#shipping_charge', function () {
    shipping_checkbox_status();
});

function shipping_checkbox_status(){
    var shipping_charge = Number($('#shipping_charge').val());
    var cal_sub_total = Number($('#sub_total').html());
    var total_tax = Number($('#span_total_tax').html());
    var final_amount = Number(cal_sub_total) + Number(total_tax);

    if(shipping_charge <= final_amount)
    {
        $('.shipping_alert').addClass('hide');

        if (isNaN(shipping_charge) || shipping_charge == '')
            shipping_charge = '0.00';

        if (isNaN(sub_total))
            sub_total = '0.00';

        var sub_total = $('.sub_total').val();
        $('#shipping_charge').val(parseFloat(shipping_charge).toFixed(2));
        $('#span_shipping_charge').html(parseFloat(shipping_charge).toFixed(2));
        if(shipping_charge > 0)
        {
            $(".change_shipping_status").prop("checked", true);
            if($('.change_shipping_status').prop("checked") == true)
            {
                $(".shipping_status").val(1); 
            }
        } else {
            $(".change_shipping_status").prop("checked", false);
            if($('.change_shipping_status').prop("checked") == false)
            {
                $(".shipping_status").val(0); 
            }
        }
        setTimeout(() => {
            check_total(sub_total, shipping_charge);
        }, 10);
    } else {
        $('.shipping_alert').removeClass('hide');  
    }
}

// shipping charge higer alert
$('#shipping_charge').keyup(function(event) {
    var shipping_charge = Number($('#shipping_charge').val());
    var cal_sub_total = Number($('#sub_total').html());
    var total_tax = Number($('#span_total_tax').html());
    var final_amount = Number(cal_sub_total) + Number(total_tax);

    if(shipping_charge <= final_amount){
        $('.shipping_alert').addClass('hide');
    } else {
        $('.shipping_alert').removeClass('hide');
    }

});

function check_total(sub_total = 0.00, shipping_charge = 0.00) {
    var total = 0.00;
    var total_tax = $('#span_total_tax').html();
    total = (parseFloat(shipping_charge) + parseFloat(sub_total) + parseFloat(total_tax));
    total = total.toFixed(2);
    $('#total').html(total);
    $('.total').val(total);
}

$('#txt_make_name').change(function () {
    var selected_val = $(this).val();
    $.ajax({
        url: site_url + 'dashboard/change_make_get_ajax',
        dataType: "json",
        type: "POST",
        data: {id: selected_val},
        success: function (response) {
            $('#txt_model_name').html(response);
            $('#txt_model_name').select2({containerCssClass: 'select-sm'});
            setTimeout(() => {
                check_part_details();
            }, 50);
        }
    });
});

$('.format-phone-number').formatter({
    pattern: '({{999}}) {{999}} - {{9999}}'
});

if (typeof (make_id) !== 'undefined' && make_id != '') {
    $('#txt_make_name').val(make_id).trigger('change');
    setTimeout(function () {
        if (typeof (modal_id) !== 'undefined' && modal_id != '') {
            $('#txt_model_name').val(modal_id).trigger('change');
        }
    }, 1000);
}

var drop_status = $('.drop_status').val();
if(drop_status == 0){
    if (typeof (item_make_id) !== 'undefined' && item_make_id != '') {
        $('#txt_make_name').val(item_make_id).trigger('change');
        setTimeout(function () {
            if (typeof (item_modal_id) !== 'undefined' && item_modal_id != '') {
                $('#txt_model_name').val(item_modal_id).trigger('change');
            }
        }, 1000);
    }
}

// For Multiple items id
if (typeof (item_make_id_multiple) !== 'undefined' && item_make_id_multiple != '') {
    $('#txt_make_name').val(item_make_id_multiple).trigger('change');
    setTimeout(function () {
        if (typeof (item_modal_id_multiple) !== 'undefined' && item_modal_id_multiple != '') {
            $('#txt_model_name').val(item_modal_id_multiple).trigger('change');
        }
    }, 1000);
}

function check_location_inventory(item_id, num = 1) {
    $('#txt_location_id_' + num + ' option').attr('disabled', 'disabled');
    $.ajax({
        type: 'POST',
        url: site_url + 'estimates/get_item_location_ajax_data',
        dataType: 'JSON',
        data: {item_id: item_id},
        success: function (data) {
            $.each(data, function (key, value) {
                $('#txt_location_id_' + num + ' option[value="' + value.location_id + '"]').removeAttr('disabled', 'disabled');
            });
            $('#txt_location_id_' + num).select2('destroy').select2();
        }
    });
}

if (typeof (item_arr) !== 'undefined' && item_arr != '') {
    setTimeout(function () {
        check_location_inventory(item_id);
    }, 1000);
}

$('.part_list').on('click', function () {
    $('#part_list_modal').modal('show');
});

$('#txt_model_name').change(function () {
    setTimeout(() => {
        check_part_details();
    }, 10);
});
$('#txt_year_name').change(function () {
    setTimeout(() => {
        check_part_details();
    }, 10);
});
$('.part_list').addClass('hide');
$('#part_list_view').html('');

function check_part_details() {
    var make_id = $('#txt_make_name').val();
    var model_id = $('#txt_model_name').val();
    var year_id = $('#txt_year_name').val();
    if ((typeof (make_id) != undefined && make_id != '') && (typeof (model_id) != undefined && model_id != '') && (typeof (year_id) != undefined && year_id != '')) {
        $.ajax({
            url: site_url + 'estimates/get_part_data',
            dataType: "json",
            type: "POST",
            data: {make_id: make_id, model_id: model_id, year_id: year_id},
            success: function (response) {
                if (response.code == 200) {
                    if (response.AllParts.length > 0) {
                        var html = '';
                        $.each(response.AllParts, function (key, value) {
                        if(value.item_image != "" && value.item_image != null)
                        {
                            var part_img = '<img src="uploads/items/'+value.item_image+'" />';
                        } else {
                            var part_img = '<img src="uploads/items/no_image.jpg" />';
                        }
                        html += 
                            '<div class="col-md-6">' +
                                '<div class="n-ite-content">'+
                                    '<div class="n-product">'+
                                        '<div class="n-item">'+
                                            '<div class="left">'+
                                                '<div class="image-wrap">'+
                                                    part_img+
                                                '</div>'+
                                            '</div>'+
                                            '<div class="right">'+
                                                '<div class="pd-15">'+
                                                    '<h5><a href="javascript:void(0)" class="btn_home_item_view" id="' + btoa(value.id) + '">' + value.part_no + '</a></h5>'+
                                                    '<button class=" copy-btn" id="myTooltip_'+value.id+'" onclick="myFunction('+value.id+')" onmouseout="outFunc('+value.id+')">Copy to clipboard</button>'+    
                                                    '<ul>'+
                                                        '<li>'+
                                                            '<strong>GLobal Part</strong>'+
                                                            '<input type="text" value='+value.global_part+' id="myInput_'+value.id+'" />'+
                                                        '</li>'+
                                                        '<li>'+
                                                            '<strong>Department</strong>'+
                                                            '<span>' + value.dept_name + '</span>'+
                                                        '</li>'+
                                                        '<li>'+
                                                            '<strong>Quantity</strong>'+
                                                            '<span>' + value.total_quantity + '</span>'+
                                                        '</li>'+
                                                    '</ul>'+
                                                    '<h4 class="price">' + currency + '' + value.retail_price + '</h4>'+
                                                '</div>'+
                                                '<div class="vendor-details">'+
                                                    '<ul>'+
                                                        '<li>'+
                                                            '<strong>Vendor</strong>'+
                                                            '<span>' + value.name + '</span>'+
                                                        '</li>'+
                                                    '</ul>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';
                        });
                        $('.part_list').removeClass('hide');
                        $('#part_list_view').html(html);
                    } else {
                        $('.part_list').addClass('hide');
                        $('#part_list_view').html('');
                    }
                }
            }
        });
    }
}

// Copy To Clipboard
function myFunction(id) {
  var copyText = document.getElementById("myInput_"+id);
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  document.execCommand("copy");
  
  var tooltip = document.getElementById("myTooltip_"+id);
  tooltip.innerHTML = "Copied: " + copyText.value;
}

// Copy To Clipboard
function outFunc(id) {
  var tooltip = document.getElementById("myTooltip_"+id);
  tooltip.innerHTML = "Copy to clipboard";
}

$(document).on('click', '.btn_home_item_view', function () {
    $.ajax({
        url: site_url + 'items/get_item_data_ajax_by_id',
        type: "POST",
        data: {id: this.id},
        success: function (response) {
            $('#dash_view_body1').html(response);
            $('#dash_view_modal1').modal('show');
        }
    });
});

$(window).keydown(function (event) {
    if (event.keyCode == 13) {
        event.preventDefault();
        return false;
    }
});


//for service:

function add_service_html(num) {
    var html = "<tr class='service_" + estimate_id + "' id='service_tr_" + num + "' data-value='" + num + "' data-service_tax_id ='"+ num + "'>" + "<td class='select_service' id='td_service_no_" + num + "'>" +
            "<select class='select select-size-sm select-service-data' id='service_id_" + num + "' name='service_id[]' data-placeholder='Select a Service...'>" +
            " </select>" +
            "</td>" +
            "<td class='' id='td_service_note_" + num + "'>"+
                "<input type='text' class='form-control service_note min-w-150' name='service_note[]' value='' placeholder = 'Service note' autocomplete='off' />"+
            "</td>"+
            "<td id='td_srv_quantity_" + num + "'><input type='hidden' value='1' name='srvquantity[]' id='srvquantity_" + num + "'/>" +
            "<div class='row mt-3'>" +
            "<div class='col-md-8 text-left'>" +
            "<span class='srvplus' id='srvplus_" + num + "' data-service_tax_id ='"+ num + "'><i class='icon-plus3 text-primary'></i></span>" +
            "<span class='srvminus' id='srvminus_" + num + "' data-service_tax_id ='"+ num + "'><i class='icon-minus3 text-primary'></i></span>" +
            "</div>" +
            "<div class='col-md-4 mt-3'><span class='srvquantity' id='div_srv_quantity_" + num + "'>1</span>" +
            "<br/><span id='span_srvquantity_" + num + "' class='span_srvquantity'></span>" +
            "</div>" +
            "</div>" +
            "</td>" +
            "<td><input type='hidden' value='0.00' name='service_rate[]' id='service_rate_" + num + "'/><span id='td_service_rate_" + num + "' class='td_service_rate'>0.00</span></td>" +
            "<td id='td_service_discount_" + num + "'><input type='hidden' value='0.00' name='service_discount[]' id='service_discount_" + num + "'/><input type='hidden' value='0.00' name='service_discount_rate[]' id='service_discount_rate_" + num + "'/><label class='chk-box-container custom-check'><input type='checkbox' name='' id='service_dis_checkbox_"+ num +"' class='service_dis_checkbox'><span class='checkmark'></span></label>" +
            "<div id='discount_service_hide_"+ num +"'  >" +
            "<div class='row'>" +
            "<div class='col-md-6 mt-3 div_service_discount text-center' id='div_service_discount_" + num + "'>0.00</div>" +
            "<div class='col-md-6'>" +
            "<select name='service_discount_type_id[]' id='service_discount_type_id_" + num + "' class='service_discount_type_id'>" +
            "<option value='p' selected=''>%</option>" +
            "<option value'r'>" + currency + "</option>" +
            "</select>" +
            "</div>" +
            "</div>" +
            "<div class='row service_discount_rate'>" +
            "<span id='span_service_discount_rate_" + num + "' class='span_service_discount_rate'></span>" +
            "</div>" +
            "</div>" +
            "</td>" +
            "<td id='td_service_tax_" + num + "'>" +
            "<input type='hidden' value='0.00' name='service_tax[]' id='service_tax_" + num + "'/>" +
            "<label class='chk-box-container custom-check'>"+
            "<input type='checkbox' name='' id='service_tax_checkbox_" + num + "' class='service_tax_checkbox'/>" +
            "<span class='checkmark'></span>"+
            "</label>"+
            "<div id='tax_service_hide_" + num + "'>" +
            "<div class='row'>" +
            // "<select data-placeholder='Select a Tax...' class='select select-size-sm select-service-tax-data' id='service_tax_id_" + num + "' name='service_tax_id[]'>" +
            "<select data-placeholder='Select a Tax...' class='service_tax_multiple select select-size-sm select-service-tax-data service_select_multiple' multiple='multiple' id='service_tax_id_" + num + "' data-service_tax_id ='"+ num + "' name='service_tax_id[]'>" +
            "</select>" +
            "<input type='hidden' id='hidden_tax_id_" + num + "' name='hidden_tax_id[hidden_tax_id_" + num + "][]' value=''>" +
            "<input type='hidden' id='hidden_service_tax_amount_" + num + "' name='hidden_service_tax_amount[hidden_service_tax_amount_" + num + "][]' value=''>" +
            "<input type='hidden' id='hidden_sname_tax_id_" + num + "' name='hidden_sname_tax_id[hidden_sname_tax_id_" + num + "][]' value=''>" +
            "</div>" +
            "<div class='col-md-12 service_tax_rate'>" +
            "<span id='span_service_tax_rate_" + num + "' class='span_service_tax_rate'></span>" +
            "</div>" +
            "</div>" +
            "</td>" +
            "<td><input type='hidden' value='0.00' name='service_amount[]' id='service_amount_" + num + "'/><span id='td_service_amount_" + num + "' class='td_service_amount'>0.00</span></td>" +
            "<td id='td_service_remove_" + num + "'>" +
            "<span class='service_remove' id='service_remove_" + num + "'><i class='icon-trash'></i></span></td>" +
            "</tr>";
    setTimeout(function () {
        var o = $("<option/>", {value: "", text: "Select a Service..."});
        $('#service_id_' + num).append(o);
        if (typeof (services) !== 'undefined' && services != '') {
            $.each(services, function (key, value) {
                var o = $("<option/>", {value: value.id, text: value.name + " : " + currency + "" + value.rate});
                $('#service_id_' + num).append(o);
            });
        }
        $('#service_id_' + num).select2().trigger('change');
        if (typeof (taxes) !== 'undefined' && taxes != '') {
            // var o = $("<option/>", {value: "", text: "Select a Tax..."});
            // $('#service_tax_id_' + num).append(o);
            $.each(taxes, function (key, value) {
                var o = $("<option/>", {value: value.id, text: value.name + " (" + value.rate + "%)"});
                $('#service_tax_id_' + num).append(o);
            });
            $('#service_tax_id_' + num).select2().trigger('change');
        }
    }, 10);
    $('.service_div').append(html);
    
    $('#discount_service_hide_'+ num +'').hide();
    $('#tax_service_hide_'+ num +'').hide();

    check_service_section();
}

function check_service_section(){
    if($('.service_tax_multiple option:selected').length == 0) {
        $('.hide_service_part_tax_amount').hide();
    } else {
        $('.hide_service_part_tax_amount').show();
    }
}

$(document).on('change','.service_dis_checkbox',function(){
    var num = $(this).parents('.service_' + estimate_id).attr('data-value');
    if($(this).prop('checked') == true){
        $('#discount_service_hide_'+ num).show();
        $('#discount_service_hide_'+ num).removeClass('hide');
        $('#span_service_discount_rate'+ num).show();
    } else {
        $('#discount_service_hide_' + num).hide();
        $('#span_service_discount_rate' + num).hide();
        $('#span_service_discount_rate_'+num).html('0.00');
        $('#service_discount_rate_'+num).val('0.00');
        $('#div_service_discount_'+num).html('0.00');
        $('#service_discount_'+num).val('0.00');

        var get_this_index = $(this).parents('.service_' + estimate_id).attr('data-service_tax_id');
        var qty = $('#div_srv_quantity_' + num).html();
        var rate = $('#td_service_rate_' + num).html();
        var amount = parseFloat(qty * rate).toFixed(2);
        var tax_id = $('#service_tax_id_' + num).val();

        var select_arr_amount = [];
        if(tax_id){
            $.each(tax_id,function(i,v) {
                calcPercpart = (parseFloat(amount) * parseFloat($("#tax_hidden_"+ v).val()) / 100);
                select_arr_amount.push(parseFloat(calcPercpart).toFixed(2));
                $('#hidden_service_tax_amount_'+get_this_index).val(select_arr_amount);
            });
        }
        $('#td_service_amount_'+num).html(amount);
        check_service_tax(num, tax_id, amount);
    }
});

$(document).on('change','.service_tax_checkbox',function(){
    var num = $(this).parents('.service_' + estimate_id).attr('data-value');
    if($(this).prop('checked') == true){
        $('#tax_service_hide_'+ num).show();
        $('#tax_service_hide_'+ num).removeClass('hide')
        $('#span_service_tax_rate'+ num).show();
    } else {
        $('#tax_service_hide_' + num).hide();
        $('#span_service_tax_rate' + num).hide();
        $('#hidden_tax_id_'+num).val('');
        $('#hidden_sname_tax_id_'+num).val('');
        $('#hidden_service_tax_amount_'+num).val('');
        $('#span_service_tax_rate_'+num).html('0.00');
        $('#service_tax_id_'+num).val(null).trigger('change');
    }
});

$(document).on('change','.service_tax_multiple', function() {
    check_service_section();
    $('.hide_service_part_tax_amount').removeClass('hide');
    var tax_name_list = $("option:selected",this).text();
    var get_this_index = $(this).attr('data-service_tax_id');
    var select_arr = [];
    var select_arr_amount = [];

    $('#hidden_service_tax_amount_'+get_this_index).val('');
    $(this).each(function() {
        select_arr.push($(this).val());
        dta = $(this).val();
        amount_val = parseFloat($(this).parent('div').parent('div').parent('td').parent('tr').find('.td_service_amount').html()).toFixed(2);
        if(dta){
            $.each(dta,function(i,v) {
                calcPercpart = (parseFloat(amount_val) * parseFloat($("#tax_hidden_"+ v).val()) / 100);
                select_arr_amount.push(parseFloat(calcPercpart).toFixed(2));
                $('#hidden_service_tax_amount_'+get_this_index).val(select_arr_amount);
            });
        }
    });

    $('#hidden_tax_id_'+get_this_index).val(select_arr);
    $('#hidden_sname_tax_id_'+get_this_index).val(tax_name_list);
});

$('.add_service_line').on('click', function () {
    var num = ((parseInt($('.service_' + estimate_id).last().attr('data-value'))) + 1);
    if (isNaN(num))
        num = 1;
    add_service_html(num);
});

$(document).on('click', '.service_remove', function () {
    var count = $('.service_' + estimate_id).length;
    if (count > 1) {
        var num = $(this).parents('.service_' + estimate_id).attr('data-value');
        $('#service_tr_' + num).fadeOut(3000).remove();
    } else if (count == 1) {
        var num = $(this).parents('.service_' + estimate_id).attr('data-value');
        var get_this_index = $(this).parents('.service_' + estimate_id).attr('data-service_tax_id');
        $("#service_id_" + num).select2('destroy').val('').select2();
        $("#td_service_no_" + num).addClass('select_service');
        $("#td_service_rate_" + num).html('0.00');
        $("#td_service_amount_" + num).html('0.00');
        $("#service_rate_" + num).val('0.00');
        $("#service_amount_" + num).val('0.00');
        $("#service_discount_type_id_" + num).val('p');
        $("#div_service_discount_" + num).html('0.00');
        $("#service_discount_" + num).val('0.00');
        $("#service_discount_rate_" + num).val('0.00');
        $("#span_service_quantity_" + num).html('');
        $("#service_tax_" + num).val('0.00');
        $("#span_service_tax_rate_" + num).html('');
        $("#span_service_discount_rate_" + num).html('');
        $('#service_tax_id_' + num).val('').select2('destroy').val('').select2();
        $("#hidden_tax_id_" + get_this_index).val('');
        $("#hidden_service_tax_amount_" + get_this_index).val('');
        $("#hidden_sname_tax_id_" + get_this_index).val('');
    }
    setTimeout(() => {
        sum_sub_total();
        caculateserviceTotaltx();
    }, 20);
});

$(document).on('change', '.select-service-data', function () {
    var service_id = $(this).val();
    var num = $(this).parents('.service_' + estimate_id).attr('data-value');
    var tax_id = $('#service_tax_id_' + num).val();
    var get_this_index = $(this).parents('.service_' + estimate_id).attr('data-service_tax_id');

    $('#td_service_no_' + num).removeClass('select_service');
    var rate = parseFloat($("#service_hidden_" + service_id).val()).toFixed(2);
    if (isNaN(rate))
        rate = '0.00';
    $("#td_service_amount_" + num).html(rate);
    $("#service_amount_" + num).val(rate);
    $("#td_service_rate_" + num).html(rate);
    $("#service_rate_" + num).val(rate);
    var discount = parseFloat($('#div_service_discount_' + num).html()).toFixed(2);

    var curval = parseInt($('#div_srv_quantity_' + num).html());
    var amount =  parseFloat($('#td_service_rate_' + num).html());
    var srvtotalamt = parseFloat(amount*curval).toFixed(2);
    
    var srv_rate = parseFloat($('#td_service_rate_' + num).html()).toFixed(2);
    var qty = parseFloat($('#div_srv_quantity_' + num).html());
    var final_amount = srv_rate * qty; 

    if (isNaN(srv_rate))
        srv_rate = '0.00';
    var dis_type = $('#service_discount_type_id_' + num).val();
    if (dis_type == 'p') {
        if (discount > 100) {
            discount = '0.00';
            $('#div_service_discount_' + num).html(discount);
            $('#service_discount_' + num).val(discount);
        } else {
            var calcPerc = (final_amount * discount / 100);
            final_amount = parseFloat(final_amount).toFixed(2) - parseFloat(calcPerc).toFixed(2);
            final_amount = final_amount.toFixed(2);
            $('#service_discount_rate_' + num).val(parseFloat(calcPerc).toFixed(2));
            $('#span_service_discount_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
        }
    } else {
        if (parseInt(discount) > parseInt(srv_rate)) {
            discount = '0.00';
            $('#div_service_discount_' + num).html(discount);
            $('#service_discount_' + num).val(discount);
        } else {
            final_amount = parseFloat(final_amount).toFixed(2) - parseFloat(discount).toFixed(2);
            final_amount = final_amount.toFixed(2);
            $('#service_discount_rate_' + num).val(parseFloat(discount).toFixed(2));
            $('#span_service_discount_rate_' + num).html(parseFloat(discount).toFixed(2));
        }
    }
    
    var get_this_index = $(this).parents('.service_' + estimate_id).attr('data-service_tax_id');
    var select_arr_amount = [];

    if(tax_id){
        $.each(tax_id,function(i,v) {
            calcPercpart = (parseFloat(final_amount) * parseFloat($("#tax_hidden_"+ v).val()) / 100);
            select_arr_amount.push(parseFloat(calcPercpart).toFixed(2));
            $('#hidden_service_tax_amount_'+get_this_index).val(select_arr_amount);
        });
    }

    if (isNaN(discount))
        discount = '0.00';
    setTimeout(() => {
        calculate_service_discount(num, discount, rate);
        sum_sub_total();
        check_service_tax(num, tax_id, final_amount);
        caculateserviceTotaltx();
    }, 50);

});

function calculate_service_discount(num, discount = 0, amount) {
    var rate = parseFloat($('#td_service_rate_' + num).html()).toFixed(2);

    var qty = parseFloat($('#div_srv_quantity_' + num).html());
    var amount = rate * qty;

    if (isNaN(rate))
        rate = '0.00';
    var dis_type = $('#service_discount_type_id_' + num).val();
    if (dis_type == 'p') {
        if (discount > 100) {
            discount = '0.00';
            $('#div_service_discount_' + num).html(discount);
            $('#service_discount_' + num).val(discount);
        } else {
            var calcPerc = (amount * discount / 100);
            amount = parseFloat(amount).toFixed(2) - parseFloat(calcPerc).toFixed(2);
            amount = amount.toFixed(2);
            $('#service_discount_rate_' + num).val(parseFloat(calcPerc).toFixed(2));
            $('#span_service_discount_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
        }
    } else {
        if (parseInt(discount) > parseInt(rate)) {
            discount = '0.00';
            $('#div_service_discount_' + num).html(discount);
            $('#service_discount_' + num).val(discount);
        } else {
            amount = parseFloat(amount).toFixed(2) - parseFloat(discount).toFixed(2);
            amount = amount.toFixed(2);
            $('#service_discount_rate_' + num).val(parseFloat(discount).toFixed(2));
            $('#span_service_discount_rate_' + num).html(parseFloat(discount).toFixed(2));
        }
    }
    $('#td_service_amount_' + num).html(amount);
    $('#service_amount_' + num).val(amount);
}

$(document).on('click', '.div_service_discount', function () {
    var num = $(this).parents('.service_' + estimate_id).attr('data-value');
    if ($('#td_service_no_' + num).hasClass('select_service') == false) {
        if ($('#div_service_discount_' + num).children('.input_service_discount').length < 1) {
            var discount = parseFloat($('#div_service_discount_' + num).html()).toFixed(2);
            if (isNaN(discount))
                discount = '0.00';
            var html = '<input type="text" value="' + discount + '" id="input_service_discount_' + num + '" class="input_service_discount form-control input-xs"/>';
            $('#div_service_discount_' + num).html(html);
        }
    }
    $('#div_service_discount_' + num + '').keydown(function(rate) {
        if(!((rate.keyCode > 95 && rate.keyCode < 106)
          || (rate.keyCode > 47 && rate.keyCode < 58)
          || (rate.keyCode > 36 && rate.keyCode < 41) 
          || rate.keyCode == 8
          || rate.keyCode == 46
          || rate.keyCode == 110)) 
        {
            return false;
        }
    });
});

$(document).on('focusout', '.input_service_discount', function () {
    var num = $(this).parents('.service_' + estimate_id).attr('data-value');
    var tax_id = $('#service_tax_id_' + num).val();
    var discount = parseFloat($('#input_service_discount_' + num).val()).toFixed(2);
    // var rate = parseFloat($('#td_service_rate_' + num).html()).toFixed(2);
    var rate = parseFloat($('#td_service_amount_' + num).html()).toFixed(2);
    if (isNaN(rate))
        rate = '0.00';
    if (isNaN(discount)) {
        discount = '0.00';
    }

    var srv_rate = parseFloat($('#td_service_rate_' + num).html()).toFixed(2);
    var qty = parseFloat($('#div_srv_quantity_' + num).html());
    var amount = srv_rate * qty; 

    if (isNaN(srv_rate))
        srv_rate = '0.00';
    var dis_type = $('#service_discount_type_id_' + num).val();
    if (dis_type == 'p') {
        if (discount > 100) {
            discount = '0.00';
            $('#div_service_discount_' + num).html(discount);
            $('#service_discount_' + num).val(discount);
        } else {
            var calcPerc = (amount * discount / 100);
            amount = parseFloat(amount).toFixed(2) - parseFloat(calcPerc).toFixed(2);
            amount = amount.toFixed(2);
            $('#service_discount_rate_' + num).val(parseFloat(calcPerc).toFixed(2));
            $('#span_service_discount_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
        }
    } else {
        if (parseInt(discount) > parseInt(srv_rate)) {
            discount = '0.00';
            $('#div_service_discount_' + num).html(discount);
            $('#service_discount_' + num).val(discount);
        } else {
            amount = parseFloat(amount).toFixed(2) - parseFloat(discount).toFixed(2);
            amount = amount.toFixed(2);
            $('#service_discount_rate_' + num).val(parseFloat(discount).toFixed(2));
            $('#span_service_discount_rate_' + num).html(parseFloat(discount).toFixed(2));
        }
    }
    
    var get_this_index = $(this).parents('.service_' + estimate_id).attr('data-service_tax_id');
    qty = $('#div_srv_quantity_' + num).html();
    // amount = qty * rate;
    var select_arr_amount = [];
    if(tax_id){
        $.each(tax_id,function(i,v) {
            calcPercpart = (parseFloat(amount) * parseFloat($("#tax_hidden_"+ v).val()) / 100);
            select_arr_amount.push(parseFloat(calcPercpart).toFixed(2));
            $('#hidden_service_tax_amount_'+get_this_index).val(select_arr_amount);
        });
    }

    $('#div_service_discount_' + num).html(discount);
    $('#service_discount_' + num).val(discount);
    if (tax_id != '') {
        setTimeout(() => {
            check_service_tax(num, tax_id, amount);
            caculateserviceTotaltx();
        }, 20);
    } else {
        setTimeout(() => {
            calculate_service_discount(num, discount, rate);
            sum_sub_total();
        }, 50);
    }
});

$(document).on('click', '.td_service_rate', function () {
    var num = $(this).parents('.service_' + estimate_id).attr('data-value');
    var rate = parseFloat($('#service_rate_' + num).val()).toFixed(2);
    if (isNaN(rate))
        rate = '0.00';
    if ($('#td_service_no_' + num).hasClass('select_service') == false) {
        var html = '<input type="text" value="' + rate + '" id="input_service_rate_' + num + '" class="input_service_rate form-control input-xs" data-service_tax_id ="0"/>';
        // $('#td_service_rate_' + num).text('').append(html);
        $('#td_service_rate_' + num).removeClass('td_service_rate');
        $('#td_service_rate_' + num).html(html);
    }
    $('#input_service_rate_' + num + '').keydown(function(rate) {
        if(!((rate.keyCode > 95 && rate.keyCode < 106)
          || (rate.keyCode > 47 && rate.keyCode < 58)
          || (rate.keyCode > 36 && rate.keyCode < 41) 
          || rate.keyCode == 8
          || rate.keyCode == 46
          || rate.keyCode == 110)) 
        {
            return false;
        }
    });
});

$(document).on('focusout', '.input_service_rate', function () {
    var num = $(this).parents('.service_' + estimate_id).attr('data-value');
    var get_this_index = $(this).parents('.service_' + estimate_id).attr('data-service_tax_id');
    var tax_id = $('#service_tax_id_' + num).val();
    var discount = parseFloat($('#div_service_discount_' + num).html()).toFixed(2);
    if (isNaN(discount))
        discount = '0.00';
    var rate = parseFloat($('#input_service_rate_' + num).val()).toFixed(2);
    if (isNaN(rate)) {
        rate = '0.00';
    }
    $('#td_service_rate_' + num).addClass('td_service_rate');
    $('#td_service_rate_' + num).html(rate);
    $('#service_rate_' + num).val(rate);
    
    var srv_rate = parseFloat($('#td_service_rate_' + num).html()).toFixed(2);
    var qty = parseFloat($('#div_srv_quantity_' + num).html());
    var amount = srv_rate * qty; 

    if (isNaN(srv_rate))
        srv_rate = '0.00';
    var dis_type = $('#service_discount_type_id_' + num).val();
    if (dis_type == 'p') {
        if (discount > 100) {
            discount = '0.00';
            $('#div_service_discount_' + num).html(discount);
            $('#service_discount_' + num).val(discount);
        } else {
            var calcPerc = (amount * discount / 100);
            amount = parseFloat(amount).toFixed(2) - parseFloat(calcPerc).toFixed(2);
            amount = amount.toFixed(2);
            $('#service_discount_rate_' + num).val(parseFloat(calcPerc).toFixed(2));
            $('#span_service_discount_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
        }
    } else {
        if (parseInt(discount) > parseInt(srv_rate)) {
            discount = '0.00';
            $('#div_service_discount_' + num).html(discount);
            $('#service_discount_' + num).val(discount);
        } else {
            amount = parseFloat(amount).toFixed(2) - parseFloat(discount).toFixed(2);
            amount = amount.toFixed(2);
            $('#service_discount_rate_' + num).val(parseFloat(discount).toFixed(2));
            $('#span_service_discount_rate_' + num).html(parseFloat(discount).toFixed(2));
        }
    }

    qty = $('#div_srv_quantity_' + num).html();
    // amount = qty * rate;
    var select_arr_amount = [];
    if(tax_id){
        $.each(tax_id,function(i,v) {
            calcPercpart = (parseFloat(amount) * parseFloat($("#tax_hidden_"+ v).val()) / 100);
            select_arr_amount.push(parseFloat(calcPercpart).toFixed(2));
            $('#hidden_service_tax_amount_'+get_this_index).val(select_arr_amount);
        });
    }

    if (tax_id != '') {
        setTimeout(() => {
            check_service_tax(num, tax_id, amount);
            caculateserviceTotaltx();
        }, 20);
    } else {
        setTimeout(() => {
            calculate_service_discount(num, discount, rate);
            sum_sub_total();
        }, 50);
    }
});

function caculateserviceTotaltx(){
    $('.total_tax_service').val(parseFloat(0).toFixed(2));
    $('.service_select_multiple').each(function(index,el){
        dta = $(this).val();
        amount_val = parseFloat($(this).parent('div').parent('div').parent('td').parent('tr').find('.td_service_amount').html()).toFixed(2);
        if(dta)
        {
            $.each(dta,function(i,v){
                $("#span_tax_hidden_total_service" + v).removeClass('hidden');
                $("#tax_hidden_total_service" + v).removeClass('hidden');
                calcPercpart = (parseFloat(amount_val) * parseFloat($("#tax_hidden_" + v).val()) / 100);
                tax_hidden_total = parseFloat($('#tax_hidden_total_service'+ v).val());
                tax_hidden_total += calcPercpart;
                $('#tax_hidden_total_service'+ v).val(parseFloat(tax_hidden_total).toFixed(2));
            });
        }
    });  
} 

$(document).on('change', '.service_select_multiple', function(event) {
    caculateserviceTotaltx();
});

function check_service_tax(num, tax_id, rate) {
    var tax_total_rate = 0 ;
    var calcPercpart = 0;
    var amount = rate;
    var tax_rate = $("#tax_hidden_" + tax_id).val();
    if(tax_id)
    {
        $.each(tax_id,function(i,v){
            tax_total_rate += parseFloat($("#tax_hidden_" + v).val());
        });
    }
    var discount = parseFloat($('#div_service_discount_' + num).html()).toFixed(2);
    if (isNaN(discount))
        discount = '0.00';
    
    var calcPerc = (parseFloat(amount) * parseFloat(tax_total_rate) / 100);
    // amount = parseFloat(amount) + parseFloat(calcPerc);
    amount = parseFloat(amount).toFixed(2);
    $('#service_tax_' + num).val(parseFloat(calcPerc).toFixed(2));
    $('#span_service_tax_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
    setTimeout(() => {
        calculate_service_discount(num, discount, amount);
        sum_sub_total();
        caculateserviceTotaltx();
    }, 40);
}

$(document).on('change', '.service_discount_type_id', function () {
    var num = $(this).parents('.service_' + estimate_id).attr('data-value');
    var tax_id = $('#service_tax_id_' + num).val();
    if ($('#td_service_no_' + num).hasClass('select_service') == false) {
        var discount = parseFloat($('#div_service_discount_' + num).html()).toFixed(2);
        if (isNaN(discount))
            discount = '0.00';
        var rate = parseFloat($('#td_service_rate_' + num).html()).toFixed(2);
        var qty = parseFloat($('.srvquantity').html());
        var srvamt = rate * qty;
        $('#td_service_amount_' + num).html(srvamt);
        if (isNaN(rate))
            rate = '0.00';

        var srv_rate = parseFloat($('#td_service_rate_' + num).html()).toFixed(2);
        var qty = parseFloat($('#div_srv_quantity_' + num).html());
        var amount = srv_rate * qty; 

        if (isNaN(srv_rate))
            srv_rate = '0.00';
        var dis_type = $('#service_discount_type_id_' + num).val();
        if (dis_type == 'p') {
            if (discount > 100) {
                discount = '0.00';
                $('#div_service_discount_' + num).html(discount);
                $('#service_discount_' + num).val(discount);
            } else {
                var calcPerc = (amount * discount / 100);
                amount = parseFloat(amount).toFixed(2) - parseFloat(calcPerc).toFixed(2);
                amount = amount.toFixed(2);
                $('#service_discount_rate_' + num).val(parseFloat(calcPerc).toFixed(2));
                $('#span_service_discount_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
            }
        } else {
            if (parseInt(discount) > parseInt(srv_rate)) {
                discount = '0.00';
                $('#div_service_discount_' + num).html(discount);
                $('#service_discount_' + num).val(discount);
            } else {
                amount = parseFloat(amount).toFixed(2) - parseFloat(discount).toFixed(2);
                amount = amount.toFixed(2);
                $('#service_discount_rate_' + num).val(parseFloat(discount).toFixed(2));
                $('#span_service_discount_rate_' + num).html(parseFloat(discount).toFixed(2));
            }
        }
        
        var get_this_index = $(this).parents('.service_' + estimate_id).attr('data-service_tax_id');
        qty = $('#div_srv_quantity_' + num).html();
        // amount = qty * rate;
        var select_arr_amount = [];
        if(tax_id){
            $.each(tax_id,function(i,v) {
                calcPercpart = (parseFloat(amount) * parseFloat($("#tax_hidden_"+ v).val()) / 100);
                select_arr_amount.push(parseFloat(calcPercpart).toFixed(2));
                $('#hidden_service_tax_amount_'+get_this_index).val(select_arr_amount);
            });
        }

        if (tax_id != '') {
            setTimeout(() => {
                check_service_tax(num, tax_id, amount);
            }, 20);
        } else {
            setTimeout(() => {
                calculate_service_discount(num, discount, rate);
                sum_sub_total();
            }, 50);
        }
    }
});

$(document).on('change', '.select-service-tax-data', function () {
    var num = $(this).parents('.service_' + estimate_id).attr('data-value');
    if ($('#td_service_no_' + num).hasClass('select_service') == false) {
        var tax_id = $(this).val();
        var rate = parseFloat($('#td_service_amount_' + num).html()).toFixed(2);
        // var rate = parseFloat($('#td_service_rate_' + num).html()).toFixed(2);
        if (isNaN(rate))
            rate = '0.00';
        if (tax_id != '') {
            setTimeout(() => {
                check_service_tax(num, tax_id, rate);
            }, 20);
        }
    }
});

/**
 * @author : Hardik Gadhiya
 * @description Open webcam and scan qr code, if qr code will match with user's items then part will be add in invoice.
 * @date: 22-04-2019
 **/
$(document).on('click', ".scan-item-qr-code", function () {
    var row_no = $(this).attr('data-rowid');
    let scanner = new Instascan.Scanner({video: document.getElementById('webcam-preview')});

    scanner.addListener('scan', function (part_no) {
        scanner.stop();
        try {
            if (part_no) {
                $.ajax({
                    type: 'POST',
                    url: site_url + 'estimates/get_item_data_id',
                    dataType: 'JSON',
                    data: {part_no: part_no},
                    success: function (data) {
                        if (data.item_id != '' && data.location_item) {
                            var item_id = data.item_id;

                            $.each(data, function (key, value) {
                                $('#txt_location_id_' + row_no + ' option[value="' + value.location_id + '"]').removeAttr('disabled', 'disabled');
                            });

                            $('#txt_location_id_' + row_no).select2('destroy').select2();
                            $("#partno_scan_webcam_modal").modal('hide');

                            var item_data = {
                                id: part_no, // search term
                                location_id: $('#txt_location_id_' + row_no).val(),
                            };

                            $.ajax({
                                type: 'POST',
                                url: site_url + 'invoices/get_item_data_ajax_by_part_id',
                                data: item_data,
                                dataType: 'json',
                                success: function (response) {
                                    if (response.data != null && response.data != '') {
                                        var response_data = response.data[0];
                                        formatRepoSelection(response_data, row_no, false);
                                        $("#partno_scan_webcam_modal").modal('hide');
                                    } else {
                                        swal({
                                            title: "Error!",
                                            text: "Product location details has not been found.",
                                            icon: "error",
                                            timer: 2000
                                        });

                                        $("#partno_scan_webcam_modal").modal('hide');
                                    }
                                }
                            });

                            $("#partno_scan_webcam_modal").modal('hide');
                        } else {
                            swal({
                                title: "Error!",
                                text: "Product has not been found.",
                                icon: "error",
                                timer: 2000
                            });

                            $("#partno_scan_webcam_modal").modal('hide');
                        }
                    }
                });
            }
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

$(".estimate_attachments").on("change", function () {
    var order_no = $(this).attr('data-no');

    $('#message_' + order_no).html();
    $('.image_wrapper_' + order_no).html('');
    var files = !!this.files ? this.files : [];

    if (!files.length || !window.FileReader) {
        $('#message_' + order_no).addClass('error').html("No file selected.");
        $('#message_' + order_no).show();
        return; // no file selected, or no FileReader support
    }

    var i = 0;
    var file_name = files[i].name;
    var notification_status = 0;
    
    for (var key in files) {
        if(files[key].type != '' && files[key].type != undefined && files[key].type != 'image/gif')
        {
            notification_status = 1;
            if (/^image/.test(files[key].type)) { // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[key]); // read the local file
                reader.onloadend = function (e) { // set image data as background of div
                    $('#message_' + order_no).removeClass('error').html(file_name);
                    $('#message_' + order_no).show();

                    var html = '<img src="' + e.target.result + '" style="border-radius: 2px;height: 50px;width:50px;object-fit: cover;object-position: center;" alt="">';
                    html += '<input type="hidden" name="order_ids[]" value="' + order_no + '">';
                    $('.image_wrapper_' + order_no).html(html);
                    ++i;
                }
            } else if (files[key].type == 'application/pdf') {
                $('#message_' + order_no).removeClass('error').html(file_name);
                $('#message_' + order_no).show();

                var html = '<img src="' + site_url + 'assets/images/pdf_icon.png" style="border-radius: 2px;height: 50px;object-fit: cover;object-position: center;" alt="">';
                html += '<input type="hidden" name="order_ids[]" value="' + order_no + '">';
                $('.image_wrapper_' + order_no).html(html);
                ++i;
            } else {
                $('#message_' + order_no).addClass('error').html("Please select proper attachment.");
                $('#message_' + order_no).show();
            }
        } else {
            if(notification_status != 1) {       
                $('#message_' + order_no).addClass('error').html("Please select proper attachment.");
                $('#message_' + order_no).show();
            }
        }
    }
});

if (signature_attachment && signature_attachment != '') {
    var FR = new FileReader(signature_attachment);

    toDataUrl(signature_attachment, function (myBase64) {
        $("#signature_attachment").val(myBase64);
    });
}

function toDataUrl(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onload = function () {
        var reader = new FileReader();
        reader.onloadend = function () {
            callback(reader.result);
        }
        reader.readAsDataURL(xhr.response);
    };
    xhr.open('GET', url);
    xhr.responseType = 'blob';
    xhr.send();
}

$(".remove-attachement").click(function () {
    var attachment_id = $(this).attr('data-id');
    var div_no = $(this).attr('data-divno');

    $(this).empty().off("click").html('<i class="icon-spinner3 spinner"></i>');

    $.ajax({
        url: site_url + "remove_attchment/" + attachment_id,
        success: function (data) {
            if (data == 1) {
                $("#attachment_div_" + div_no).remove();

                swal({
                    type: 'success',
                    title: 'Invoice Attachments',
                    text: 'Attachment has been deleted successfully!',
                });
            } else {
                swal({
                    type: 'error',
                    title: 'Invoice Attachments',
                    text: 'Attachment has not been deleted!',
                });
                $(this).empty().on("click").html('&times;');
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            Swal({
                type: 'error',
                title: 'Estimation Attachments',
                text: 'Something went wrong!',
            });
        }
    });
});

$("#customer_id").change(function () {  
    var customer_id = $(this).find('option:selected').val();
    $.ajax({
        url: site_url + "invoices/get_email_list",
        type: 'POST',
        dataType: 'JSON',
        data: {customer_id: customer_id},
        success: function(data){
            $("#multiple_email_list").tagsinput('removeAll');            
            $.each(data, function(key, value) {   
                $('#multiple_email_list').tagsinput('add', value.customer_email);
            });
        }
    });    
});

$("#customer_id").change(function () {
    var customer_id = $(this).find('option:selected').val();
    var address = '';
    $.ajax({
        type: 'POST',
        url: site_url + "invoices/get_customer_details",
        data: {
            customer_id: customer_id
        },
        success: function (data) {
            if (data) {
                data = JSON.parse(data);
                var customer_name = data.first_name + ' ' + data.last_name;
                var phone = data.phone;
                
                $("#txt_cust_name").val(customer_name);
                $("#txt_email").val(data.email);
                $("#txt_phone_number").val(phone).trigger('keyup');

                if(customer_name != "" && customer_name != null && customer_name != undefined)
                {
                    $('label[for=txt_cust_name]').remove();
                }
                
                if (data.billing_address && data.billing_address != '') {
                    address += data.billing_address + ', ';
                }

                if (data.billing_address_street && data.billing_address_street != '') {
                    address += data.billing_address_street + ', ';
                }

                if (data.billing_address_city && data.billing_address_city != '') {
                    address += data.billing_address_city + ', ';
                }

                if (data.billing_address_state && data.billing_address_state != '') {
                    address += data.billing_address_state + ', ';
                }

                if (data.billing_address_zip && data.billing_address_zip != '') {
                    address += data.billing_address_zip;
                }

                $("#txt_address").val(address);
            }
        }
    });
});


//Service Quantity +
$(document).on("click",".srvplus",function() {
    var num = $(this).parents('.service_' + estimate_id).attr('data-value');
    var curval = parseInt($('#div_srv_quantity_' + num).html());
    var srvqty = (curval + 1);
    var amount =  parseFloat($('#td_service_rate_' + num).html());
    var srvtotalamt = parseFloat(amount*srvqty).toFixed(2);

    if(amount > 0)
    {
        $('#td_service_amount_' + num).html(srvtotalamt);
        $('#div_srv_quantity_' + num).html(srvqty); 
        $('#srvquantity_' + num).val(srvqty);
        $('#service_amount_' + num).val(srvtotalamt);
    }
    
    var tax_id = $('#service_tax_id_' + num).val();
    var new_qty = srvqty;
    var new_amount = (new_qty * amount).toFixed(2);    

    var discount = parseFloat($('#div_service_discount_' + num).html()).toFixed(2);
    var srv_rate = parseFloat($('#td_service_rate_' + num).html()).toFixed(2);
    var qty = parseFloat($('#div_srv_quantity_' + num).html());
    var amount = srv_rate * qty; 

    if (isNaN(srv_rate))
    srv_rate = '0.00';
    var dis_type = $('#service_discount_type_id_' + num).val();
    if (dis_type == 'p') {
        if (discount > 100) {
            discount = '0.00';
            $('#div_service_discount_' + num).html(discount);
            $('#service_discount_' + num).val(discount);
        } else {
            var calcPerc = (amount * discount / 100);
            amount = parseFloat(amount).toFixed(2) - parseFloat(calcPerc).toFixed(2);
            amount = amount.toFixed(2);
            $('#service_discount_rate_' + num).val(parseFloat(calcPerc).toFixed(2));
            $('#span_service_discount_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
        }
    } else {
        if (parseInt(discount) > parseInt(srv_rate)) {
            discount = '0.00';
            $('#div_service_discount_' + num).html(discount);
            $('#service_discount_' + num).val(discount);
        } else {
            amount = parseFloat(amount).toFixed(2) - parseFloat(discount).toFixed(2);
            amount = amount.toFixed(2);
            $('#service_discount_rate_' + num).val(parseFloat(discount).toFixed(2));
            $('#span_service_discount_rate_' + num).html(parseFloat(discount).toFixed(2));
        }
    }
    
    var get_this_index = $(this).parents('.service_' + estimate_id).attr('data-service_tax_id');
    qty = $('#div_srv_quantity_' + num).html();
    // amount = qty * rate;
    var select_arr_amount = [];
    if(tax_id){
        $.each(tax_id,function(i,v) {
            calcPercpart = (parseFloat(amount) * parseFloat($("#tax_hidden_"+ v).val()) / 100);
            select_arr_amount.push(parseFloat(calcPercpart).toFixed(2));
            $('#hidden_service_tax_amount_'+get_this_index).val(select_arr_amount);
        });
    }

    check_service_tax(num, tax_id, amount);
    sum_sub_total();
    caculateserviceTotaltx();
});

//Service Quantity -
$(document).on("click",".srvminus",function() {
    var num = $(this).parents('.service_' + estimate_id).attr('data-value');
    var curval = parseInt($('#div_srv_quantity_' + num).html());
    if(curval > 1)
    {
        var srvqty = (curval - 1);
        $('#div_srv_quantity_' + num).html(srvqty);
        var amount =  parseFloat($('#td_service_rate_' + num).html());
        var srvtotalamt = parseFloat(amount*srvqty).toFixed(2);
        $('#td_service_amount_' + num).html(srvtotalamt);
        $('#srvquantity_' + num).val(srvqty);
        $('#service_amount_' + num).val(srvtotalamt);
        
        var tax_id = $('#service_tax_id_' + num).val();
        var new_qty = srvqty;
        var new_amount = (new_qty * amount).toFixed(2);
        
        var discount = parseFloat($('#div_service_discount_' + num).html()).toFixed(2);
        var srv_rate = parseFloat($('#td_service_rate_' + num).html()).toFixed(2);
        var qty = parseFloat($('#div_srv_quantity_' + num).html());
        var amount = srv_rate * qty; 

        if (isNaN(srv_rate))
            srv_rate = '0.00';
        var dis_type = $('#service_discount_type_id_' + num).val();
        if (dis_type == 'p') {
            if (discount > 100) {
                discount = '0.00';
                $('#div_service_discount_' + num).html(discount);
                $('#service_discount_' + num).val(discount);
            } else {
                var calcPerc = (amount * discount / 100);
                amount = parseFloat(amount).toFixed(2) - parseFloat(calcPerc).toFixed(2);
                amount = amount.toFixed(2);
                $('#service_discount_rate_' + num).val(parseFloat(calcPerc).toFixed(2));
                $('#span_service_discount_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
            }
        } else {
            if (parseInt(discount) > parseInt(srv_rate)) {
                discount = '0.00';
                $('#div_service_discount_' + num).html(discount);
                $('#service_discount_' + num).val(discount);
            } else {
                amount = parseFloat(amount).toFixed(2) - parseFloat(discount).toFixed(2);
                amount = amount.toFixed(2);
                $('#service_discount_rate_' + num).val(parseFloat(discount).toFixed(2));
                $('#span_service_discount_rate_' + num).html(parseFloat(discount).toFixed(2));
            }
        }
        
        var get_this_index = $(this).parents('.service_' + estimate_id).attr('data-service_tax_id');
        qty = $('#div_srv_quantity_' + num).html();
        // amount = qty * rate;
        var select_arr_amount = [];
        if(tax_id){
            $.each(tax_id,function(i,v) {
                calcPercpart = (parseFloat(amount) * parseFloat($("#tax_hidden_"+ v).val()) / 100);
                select_arr_amount.push(parseFloat(calcPercpart).toFixed(2));
                $('#hidden_service_tax_amount_'+get_this_index).val(select_arr_amount);
            });
        }

        check_service_tax(num, tax_id, amount);
        sum_sub_total();
        caculateserviceTotaltx();
    }
});

// Change shipping status
$(document).on("change",".change_shipping_status",function() {
    var shipping_status;
    if(this.checked) {
        shipping_status = 1;
        var shipping_charge = Number($('#shipping_charge').val());
        var sub_total = Number($('#sub_total').html());
        
        check_total(sub_total, shipping_charge);
    }else{
        shipping_status = 0;
        sub_total = $('#sub_total').html();
        shipping_charge = '0.00';

        $('#shipping_charge').val('0.00');
        $('#span_shipping_charge').html('0.00');
        check_total(sub_total, shipping_charge);
    }
    $(".shipping_status").val(shipping_status);
});

// Bottom to top scroll 
var btn = $('#scroll-top-button');

$(window).scroll(function() {
  if ($(window).scrollTop() > 300) {
    btn.addClass('show');
  } else {
    btn.removeClass('show');
  }
});

btn.on('click', function(e) {
  e.preventDefault();
  $('html, body').animate({scrollTop:0}, '300');
});
// Bottom to top scroll end

// Accept only number
$(document).on("input", "#shipping_charge", function(evt) {
    var self = $(this);
    self.val(self.val().replace(/[^0-9\.]/g, ''));
    if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
    {
      evt.preventDefault();
    }
});