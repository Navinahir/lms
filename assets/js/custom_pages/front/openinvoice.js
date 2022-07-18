/**
 * Validate float value upto two decimal places
 * @param {object} el
 * @param {event} evt
 * @returns {Boolean}
 * @author PAV
 */




// $(document).ready(function () {
//     $('.estimate_date').pickadate({
//         format: date_format,
//         formatSubmit: 'yyyy/mm/dd',
//         today: 'Today',
//         clear: 'Clear',
//         close: 'Close',
//         selectMonths: true,
//         selectYears: true,
//         // onRender: function() {
//         //    $('.picker__table tr td').find('.picker__day').removeClass('picker__day--highlighted').removeClass('picker__day--selected');
//         // }
//     });
//     $('.expiry_date').pickadate({
//         format: date_format,
//         formatSubmit: 'yyyy/mm/dd',
//         today: 'Today',
//         clear: 'Clear',
//         close: 'Close',
//         selectMonths: true,
//         selectYears: true
//     });
     

// });


// Display the preview of image on image upload
// function readURL(input) {
//     if (input.files && input.files[0]) {
//         var reader = new FileReader();
//         reader.onload = function (e) {
//             var html = '<img src="' + e.target.result + '" style="width: 58px; height: 58px; border-radius: 2px;" alt="">';
//             $('#image_preview_div').html(html);
//             $('#item_image_hidden').val('');
//         }   
//         reader.readAsDataURL(input.files[0]);
//     }
// }
/**********************************************************
 Intitalize Data Table
 ***********************************************************/

var table = $('.customer-open-invoice-datatable').dataTable({
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
    ajax: site_url + 'customers/openinvoices',
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
            data: "cust_name",
            visible: true,
        }, 
        {
            data: "estimate_date",
            visible: true,
        },      
        {
            data: "estimate_id",
            visible: true,
        },
        {
            data: "payment_method_name",
            visible: true,
            render: function (data, type, full, meta) {
                var action = '';
                action = '<span class="label bg-blue-400">'+data+'</span>';
                return action;
            }

        },
        {
            data: "total",
            visible: true,
            className: 'sum',
            sortable: false,
            render: function (data, type, full, meta) {
                return parseFloat(full.total).toFixed(2);
            }

        },
        {
            data: 'responsive',
            className: 'control',
            orderable: false,
            targets: -1,
        },
        {
            data: "action",
            render: function (data, type, full, meta) {
                var action = '';
                action += '&nbsp;&nbsp;<a href="' + site_url + 'invoices/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Open</a>';
                // action += '<a href="' + site_url + 'invoices/view/' + btoa(full.id) + '" class="btn btn-xs custom_dt_action_button" title="View">Open</a>';
                // if (edit == 1) {
                //    if (full.is_deducted == 0 && full.is_sent == 0) {
                //     action += '&nbsp;&nbsp;<a href="' + site_url + 'invoices/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Edit</a>';
                //     }
                // }
                // if (dlt == 1) {
                //     action += '&nbsp;&nbsp;<a href="' + site_url + 'invoices/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
                // }
                return action;
            },
            sortable: false,
        },
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

        $("select[name=DataTables_Table_0_length]").addClass('form-control').css('width', '55%');
    }
    // initComplete: function () {
    //     this.api().columns('.slct', {page: 'current'}).every(function () {
    //         var that = this;

    //         $('input', this.footer()).on('keyup change', function () {
    //             if (that.search() !== this.value) {
    //                 that
    //                         .search(this.value)
    //                         .draw();
    //             }
    //         });
    //     });
    // },
    // "fnDrawCallback": function () {
    //     var info = document.querySelectorAll('.switchery-info');
    //     $(info).each(function () {
    //         var switchery = new Switchery(this, {color: '#95e0eb'});
    //     });
    // }
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
 Confirm Alert
 ***********************************************************/
// function confirm_alert(e) {
//     swal({
//         title: "Are you sure?",
//         text: "You will not be able to recover this!",
//         type: "warning",
//         showCancelButton: true,
//         confirmButtonColor: "#FF7043",
//         confirmButtonText: "Yes, delete it!"
//     }, function (isConfirm) {
//         if (isConfirm) {
//             window.location.href = $(e).attr('href');
//             return true;
//         } else {
//             return false;
//         }
//     });
//     return false;
// }

/**********************************************************
 Form Validation
 ***********************************************************/
// $(document).ready(function () {
//     $("#add_invoice_form").validate({
//         ignore: '.select2-search__field, #txt_status', // ignore hidden fields
//         errorClass: 'validation-error-label',
//         successClass: 'validation-valid-label',
//         highlight: function (element, errorClass, validClass) {
//             var elem = $(element);
//             if (elem.hasClass("select2-offscreen")) {
//                 $("#s2id_" + elem.attr("id") + " ul").removeClass(errorClass);
//             } else {
//                 elem.removeClass(errorClass);
//             }
//         },
//         unhighlight: function (element, errorClass, validClass) {
//             var elem = $(element);
//             if (elem.hasClass("select2-offscreen")) {
//                 $("#s2id_" + elem.attr("id") + " ul").removeClass(errorClass);
//             } else {
//                 elem.removeClass(errorClass);
//             }
//         },
//         errorPlacement: function (error, element) {
//             $(element).parent().find('.form_success_vert_icon').remove();
//             if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container')) {
//                 if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
//                     error.appendTo(element.parent().parent().parent().parent());
//                 } else {
//                     error.appendTo(element.parent().parent().parent().parent().parent());
//                 }
//             } else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
//                 error.appendTo(element.parent().parent().parent());
//             } else if (element.parents('div').hasClass('has-feedback') || element.hasClass('select2-hidden-accessible')) {
//                 error.appendTo(element.parent());
//             } else if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
//                 error.appendTo(element.parent().parent());
//             } else if (element.parent().hasClass('uploader') || element.parents().hasClass('input-group')) {
//                 error.appendTo(element.parent().parent());
//             } else {
//                 error.insertAfter(element);
//             }
//         },
//         validClass: "validation-valid-label",
//         success: function (element) {
//             if ($(element).parent('div').hasClass('media-body')) {
//                 $(element).parent().find('.form_success_vert_icon').remove();
//                 $(element).remove();
//             } else {
//                 $(element).parent().find('.form_success_vert_icon').remove();
//                 $(element).parent().append('<div class="form_success_vert_icon form-control-feedback"><i class="icon-checkmark-circle"></i></div>');
//                 $(element).remove();
//             }
//         },
//         rules: {
//             txt_cust_name: {required: true, maxlength: 150},
//             estimate_date: {required: true},
//             sales_person: {required: true},
//             // txt_make_name: { required: true },
//             // txt_model_name: { required: true, number: true },
//             // txt_year_name: { required: true },
//             hidden_part_id: {required: true},
//             txt_email: {email: true},
//             txt_phone_number: {
//                 minlength: 16,
//                 maxlength: 16,
//             }
//         },
//         messages: {
//             hidden_part_id: "Please add one or more part!",
//             txt_phone_number: 'Please enter valid Phone number!'
//         },
//         submitHandler: function (form) {
//             if (signature_attachment == '') {
//                 var signature_image = $("#signature-pad-img").attr('src');

//                 if (signature_image != '' && signature_image != undefined) {
//                     $("#signature_attachment").val(signature_image);
//                 }
//             }

//             var $form = $(form);
//             var sub_total = $form.find('input.sub_total').val()
//             if (sub_total == '0.00' || sub_total == 0.00) {
//                 $('#hidden_part_id_error2').text('Enter the valid part name or description.').show();
//                 return false;
//             } else {
//                 form.submit();
//                 $('.custom_save_button').prop('disabled', true);
//                 $('.save_send').prop('disabled', true);
//             }
//         },
//         invalidHandler: function () {
//             $('.custom_save_button').prop('disabled', false);
//             $('.save_send').prop('disabled', false);
//         }
//     });
// });


// /**********************************************************
//  Item View Popup
//  ***********************************************************/
// $(document).on('click', '.item_view_btn', function () {
//     $('#custom_loading').removeClass('hide');
//     $('#custom_loading').css('display', 'block');
//     $.ajax({
//         url: site_url + 'items/get_item_data_ajax_by_id',
//         type: "POST",
//         data: {id: this.id},
//         success: function (response) {
//             $('#custom_loading').removeClass('hide');
//             $('#custom_loading').css('display', 'none');
//             $('#items_view_body').html(response);
//             $('#items_view_modal').modal('show');
//         }
//     });
// });

// if (typeof (edit_div) != 'undefined' && edit_div == 1) {
//     $('.inventoy_div').removeClass('hide');
// }

// // Format displayed data
// function formatRepo(repo) {
//     if (repo.loading)
//         return repo.text;
//     var markup = "<div class='select2-result-repository clearfix'>";
//     if (jQuery.isEmptyObject(repo.global_image)) {
//         markup += "<div class='select2-result-repository__avatar'><img src='" + ITEMS_IMAGE_PATH + "/no_image.jpg' /></div>";
//     } else {
//         markup += "<div class='select2-result-repository__avatar'><img src='" + ITEMS_IMAGE_PATH + "/" + repo.global_image + "' /></div>";
//     }
//     markup += "<div class='select2-result-repository__meta'>" +
//             "<div class='select2-result-repository__title'>" + repo.part_no + " (Vendor : " + repo.v1_name + " )</div>";

//     if (repo.description) {
//         markup += "<div class='select2-result-repository__description'>Description : " + repo.description + "</div>";
//     }
//     total_quantity = (jQuery.isEmptyObject(repo.total_quantity)) ? 0 : repo.total_quantity;
//     markup += "<div class='select2-result-repository__statistics'>" +
//             "<div class='select2-result-repository__quantity'>Quantity : " + repo.location_quantity + "</div>" +
//             "<div class='select2-result-repository__retail'>Department : " + repo.dept_name + "</div>" +
//             "<div class='select2-result-repository__cost'>Price : $" + repo.retail_price + "</div>" +
//             "</div>" +
//             "</div></div>";
//     return markup;
// }

// // Format selection
// function formatRepoSelection(repo, num, selected = null) {
//     if (repo.id != '' && (repo.selected == false || selected == false)) {
//         total_quantity = (jQuery.isEmptyObject(repo.total_quantity)) ? 0 : repo.total_quantity;
//         var html = "<div class='row'>" +
//                 "<div class='col-md-11'>" +
//                 "<input type='hidden' name='hidden_part_id[]' value='" + repo.id + "' id='part_no_" + num + "' />" +
//                 "<div class='select2-result-title text-left'>" + repo.part_no + " (Vendor : " + repo.v1_name + " ) </div>" +
//                 "<div class='select2-result-description mt-10'> <input type='text' class='form-control' name='description[]' value='" + repo.description + "'/></div></div>" +
//                 "<div class='col-md-1'>" +
//                 "<span class='text-right cancel-part'><i class='icon-cancel-square text-danger'></i></span>" +
//                 "</div></div>";
//         $("#td_part_no_" + num).html(html).removeClass('select_part');
//         $("#span_quantity_" + num).html(repo.location_quantity);
//         // $("#quantity_" + num).val(repo.location_quantity);
//         $("#td_rate_" + num).html(repo.retail_price);
//         $("#td_amount_" + num).html(repo.retail_price).addClass('td_amount');
//         $("#rate_" + num).val(repo.retail_price);
//         $("#amount_" + num).val(repo.retail_price);
//         $('#hidden_part_id_error2').text('').hide();
//         var count = $('.' + estimate_id).children('.select_part').length;
//         if (count == 0) {
//             add_html((parseInt($('.' + estimate_id).last().attr('data-value'))) + 1);
//         }
//         setTimeout(() => {
//             sum_sub_total();
//             check_location_inventory(repo.id, num);
//         }, 20);
//         return repo.part_no;
// }
// }

// $(document).on('click', '.cancel-part', function () {
//     var num = $(this).parents('.' + estimate_id).attr('data-value');
//     var html = "<select class='select-part-data' id='txt_part_no_" + num + "' name='txt_part_no[]'>" +
//             "<option value='' selected='selected'>Type to select Part</option>" +
//             "</select>";
//     $("#td_part_no_" + num).html(html).addClass('select_part');
//     $("#div_quantity_" + num).html('1');
//     $("#td_rate_" + num).html('0.00');
//     $("#td_amount_" + num).html('0.00');
//     $("#quantity_" + num).val('1');
//     $("#rate_" + num).val('0.00');
//     $("#amount_" + num).val('0.00');
//     $("#discount_type_id_" + num).val('p');
//     $("#div_discount_" + num).html('0.00');
//     $("#discount_" + num).val('0.00');
//     $("#span_quantity_" + num).html('');
//     $("#tax_" + num).val('0.00');
//     $("#span_tax_rate_" + num).html('');
//     $("#span_discount_rate_" + num).html('');
//     $('#txt_location_id_' + num).select2('destroy').select2();
//     $('#tax_id_' + num).val('').select2('destroy').select2();
//     setTimeout(() => {
//         set_select(num);
//         sum_sub_total();
//     }, 20);

// });

// function set_select(num = 1) {
//     $("#txt_part_no_" + num).select2({
//         ajax: {
//             url: site_url + 'invoices/get_item_data_ajax_by_part_id',
//             dataType: 'json',
//             delay: 250,
//             type: 'POST',
//             data: function (params) {
//                 return {
//                     id: params.term, // search term
//                     page: params.page,
//                     location_id: $('#txt_location_id_' + num).val()
//                 };
//             },
//             processResults: function (response, params) {
//                 data = response.data;
//                 if (response.code == 200) {
//                     params.page = params.page || 1;
//                     return {
//                         results: data,
//                         pagination: {
//                             more: (params.page * 30) < response.count
//                         }
//                     };
//                 } else {
//                     return {
//                         results: ''
//                     }
//                 }
//             },
//             cache: true,
//         },
//         placeholder: 'Search for a Part',
//         escapeMarkup: function (markup) {
//             return markup;
//         }, // let our custom formatter work
//         minimumInputLength: 1,
//         templateResult: formatRepo,
//         templateSelection: function (repo) {
//             return formatRepoSelection(repo, num)
//         },
//     });
// }

// $('.add_line').on('click', function () {
//     var num = ((parseInt($('.' + estimate_id).last().attr('data-value'))) + 1);
//     add_html(num);
// });

// set_select();

// function add_html(num) {
//     var html = "<tr class='" + estimate_id + "' id='tr_" + num + "' data-value='" + num + "'>" +
//             "<td class='location' id='td_location_id_" + num + "'>" +
//             "<select class='select select-location-data' id='txt_location_id_" + num + "' name='location_id[]'>" +
//             " </select>" +
//             "</td>" +
//             "<td class='select_part' id='td_part_no_" + num + "'>" +
//             "<select class='select-part-data' id='txt_part_no_" + num + "' name='txt_part_no[]'>" +
//             "<option value='' selected='selected'>Type to select Part</option>" +
//             "</select>" +
//             "</td>" +
//             "<td id='td_quantity_" + num + "'><input type='hidden' value='1' name='quantity[]' id='quantity_" + num + "'/>" +
//             "<div class='row mt-3'>" +
//             "<div class='col-md-8 text-left'>" +
//             "<span class='plus' id='plus_" + num + "'><i class='icon-plus3 text-primary'></i></span>" +
//             "<span class='minus' id='minus_" + num + "'><i class='icon-minus3 text-primary'></i></span>" +
//             "</div>" +
//             "<div class='col-md-4 mt-3'><span id='div_quantity_" + num + "'>1</span>" +
//             "<br/><span id='span_quantity_" + num + "' class='span_quantity'></span>" +
//             "</div>" +
//             "</div>" +
//             "</td>" +
//             "<td><input type='hidden' value='0.00' name='rate[]' id='rate_" + num + "'/><span id='td_rate_" + num + "' class='td_rate'>0.00</span></td>" +
//             "<td id='td_discount_" + num + "'><input type='hidden' value='0.00' name='discount[]' id='discount_" + num + "'/><input type='hidden' value='0.00' name='discount_rate[]' id='discount_rate_" + num + "'/>" +
//             "<div class='row'>" +
//             "<div class='col-md-6 mt-3 div_discount text-center' id='div_discount_" + num + "'>0.00</div>" +
//             "<div class='col-md-6'>" +
//             "<select name='discount_type_id[]' id='discount_type_id_" + num + "' class='discount_type_id'>" +
//             "<option value='p' selected=''>%</option>" +
//             "<option value'r'>" + currency + "</option>" +
//             "</select>" +
//             "</div>" +
//             "</div>" +
//             "<div class='row discount_rate'>" +
//             "<span id='span_discount_rate_" + num + "' class='span_discount_rate'></span>" +
//             "</div>" +
//             "</td>" +
//             "<td id='td_tax_" + num + "'>" +
//             "<input type='hidden' value='0.00' name='tax[]' id='tax_" + num + "'/>" +
//             "<div class='row'>" +
//             "<select data-placeholder='Select a Tax...' class='select select-size-sm select-tax-data' id='tax_id_" + num + "' name='tax_id[]'>" +
//             "</select>" +
//             "</div>" +
//             "<div class='col-md-12 tax_rate'>" +
//             "<span id='span_tax_rate_" + num + "' class='span_tax_rate'></span>" +
//             "</div>" +
//             "</td>" +
//             "<td><input type='hidden' value='0.00' name='amount[]' id='amount_" + num + "'/><span id='td_amount_" + num + "'>0.00</span></td>" +
//             "<td id='td_remove_" + num + "'>" +
//             "<span class='scan-item-qr-code' id='scan-item-qr-code_1' data-rowid='" + num + "' title='Scan QR Code'><i class='icon-camera'></i></span>&nbsp;&nbsp;" +
//             "<span class='remove' id='remove_" + num + "'><i class='icon-trash'></i></span></td>" +
//             "</tr>";
//     setTimeout(function () {
//         set_select(num);
//         if (typeof (locations) != undefined && locations != '') {
//             $.each(locations, function (key, value) {
//                 var o = $("<option/>", {value: value.id, text: value.name});
//                 $('#txt_location_id_' + num).append(o);
//             });
//             $('#txt_location_id_' + num).select2().trigger('change');
//         }
//         if (typeof (taxes) != undefined && taxes != '') {
//             var o = $("<option/>", {value: "", text: "Select a Tax..."});
//             $('#tax_id_' + num).append(o);
//             $.each(taxes, function (key, value) {
//                 var o = $("<option/>", {value: value.id, text: value.name + " (" + value.rate + "%)"});
//                 $('#tax_id_' + num).append(o);
//             });
//             $('#tax_id_' + num).select2().trigger('change');
//         }
//     }, 10);
//     $('.part_div').append(html);
// }

// $(document).on('click', '.remove', function () {
//     var count = $('.' + estimate_id).length;
//     if (count > 1) {
//         var num = $(this).parents('.' + estimate_id).attr('data-value');
//         $('#tr_' + num).fadeOut(3000).remove();
//     }
//     setTimeout(() => {
//         sum_sub_total();
//     }, 20);
// });
// $(document).on('click', '.plus', function () {
//     var num = $(this).parents('.' + estimate_id).attr('data-value');
//     if ($('#td_part_no_' + num).hasClass('select_part') == false) {
//         var total = parseInt($('#span_quantity_' + num).html());
//         var qty = parseInt($('#div_quantity_' + num).html());
//         var amount = parseFloat($('#td_rate_' + num).html()).toFixed(2);
//         if (isNaN(amount))
//             amount = '0.00';
//         var discount = parseFloat($('#div_discount_' + num).html()).toFixed(2);
//         if (isNaN(discount))
//             amount = '0.00';
//         if (qty < total) {
//             var tax_id = $('#tax_id_' + num).val();
//             var new_qty = (qty + 1);
//             var new_amount = (new_qty * amount).toFixed(2);
//             $('#div_quantity_' + num).html(new_qty);
//             $('#quantity_' + num).val(new_qty);
//             if (tax_id != '') {
//                 setTimeout(() => {
//                     check_tax(num, tax_id, new_amount);
//                 }, 20);
//             } else {
//                 setTimeout(() => {
//                     calculate_discount(num, discount, new_amount);
//                     sum_sub_total();
//                 }, 20);
//             }
//         }
//     }

// });
// $(document).on('click', '.minus', function () {
//     var num = $(this).parents('.' + estimate_id).attr('data-value');
//     if ($('#td_part_no_' + num).hasClass('select_part') == false) {
//         var total = parseInt($('#span_quantity_' + num).html());
//         var qty = parseInt($('#div_quantity_' + num).html());
//         var amount = parseFloat($('#td_rate_' + num).html()).toFixed(2);
//         if (isNaN(amount))
//             amount = '0.00';
//         var discount = parseFloat($('#div_discount_' + num).html()).toFixed(2);
//         if (isNaN(discount))
//             discount = '0.00';
//         if (total >= qty && qty > 1) {
//             var tax_id = $('#tax_id_' + num).val();
//             var new_qty = (qty - 1);
//             var new_amount = (new_qty * amount).toFixed(2);
//             $('#div_quantity_' + num).html(new_qty);
//             $('#quantity_' + num).val(new_qty);
//             if (tax_id != '') {
//                 setTimeout(() => {
//                     check_tax(num, tax_id, new_amount);
//                 }, 20);
//             } else {
//                 setTimeout(() => {
//                     calculate_discount(num, discount, new_amount);
//                     sum_sub_total();
//                 }, 20);
//             }
//         }
//     }
// });
// $(document).on('click', '.div_discount', function () {
//     var num = $(this).parents('.' + estimate_id).attr('data-value');
//     if ($('#td_part_no_' + num).hasClass('select_part') == false) {
//         if ($('#div_discount_' + num).children('.input_discount').length < 1) {
//             var discount = parseFloat($('#div_discount_' + num).html()).toFixed(2);
//             if (isNaN(discount))
//                 discount = '0.00';
//             var html = '<input type="text" value="' + discount + '" id="input_discount_' + num + '" class="input_discount form-control input-xs"/>';
//             $('#div_discount_' + num).removeClass('div_discount');
//             $('#div_discount_' + num).html(html);
//         }
//     }
// });
// $(document).on('focusout', '.input_discount', function () {
//     var num = $(this).parents('.' + estimate_id).attr('data-value');
//     var tax_id = $('#tax_id_' + num).val();
//     var discount = parseFloat($('#input_discount_' + num).val()).toFixed(2);
//     if (isNaN(discount))
//         discount = '0.00';
//     var rate = parseFloat($('#td_rate_' + num).html()).toFixed(2);
//     if (isNaN(rate))
//         rate = '0.00';
//     var qty = parseInt($('#div_quantity_' + num).html());
//     var amount = (qty * rate).toFixed(2);
//     $('#div_discount_' + num).addClass('div_discount');
//     $('#div_discount_' + num).html(discount);
//     $('#discount_' + num).val(discount);
//     if (tax_id != '') {
//         setTimeout(() => {
//             check_tax(num, tax_id, amount);
//         }, 20);
//     } else {
//         setTimeout(() => {
//             calculate_discount(num, discount, amount);
//             sum_sub_total();
//         }, 50);
//     }
// });

// $(document).on('click', '.td_rate', function () {
//     var num = $(this).parents('.' + estimate_id).attr('data-value');
//     var rate = parseFloat($('#rate_' + num).val()).toFixed(2);
//     if (isNaN(rate))
//         rate = '0.00';
//     if ($('#td_part_no_' + num).hasClass('select_part') == false) {
//         var html = '<input type="text" value="' + rate + '" id="input_rate_' + num + '" class="input_rate form-control input-xs"/>';
//         $('#td_rate_' + num).removeClass('td_rate');
//         $('#td_rate_' + num).html(html);
//     }
// });
// $(document).on('focusout', '.input_rate', function () {
//     var num = $(this).parents('.' + estimate_id).attr('data-value');
//     var tax_id = $('#tax_id_' + num).val();
//     var discount = parseFloat($('#div_discount_' + num).html()).toFixed(2);
//     if (isNaN(discount)) {
//         discount = '0.00';
//     }
//     var rate = parseFloat($('#input_rate_' + num).val()).toFixed(2);
//     if (isNaN(rate)) {
//         rate = '0.00';
//     }
//     var qty = parseInt($('#div_quantity_' + num).html());
//     var amount = (qty * rate).toFixed(2);
//     $('#td_rate_' + num).addClass('td_rate');
//     $('#td_rate_' + num).html(rate);
//     $('#rate_' + num).val(rate);
//     if (tax_id != '') {
//         setTimeout(() => {
//             check_tax(num, tax_id, amount);
//         }, 20);
//     } else {
//         setTimeout(() => {
//             calculate_discount(num, discount, amount);
//             sum_sub_total();
//         }, 50);
//     }
// });

// function sum_sub_total() {
//     var sub_total = '0.00';
//     var sub_part_total = '0.00';
//     var sub_service_total = '0.00';
//     var shipping_charge = $(document).find('#shipping_charge').val();
//     if ($(document).find('.td_amount').length > 0) {
//         var all = $(document).find('.td_amount');
//         var total = 0.00;
//         $.each(all, function (i, v) {
//             sub_part_total = (parseFloat(sub_part_total) + parseFloat($(v).text()));
//             sub_part_total = sub_part_total.toFixed(2);
//         });
//     }
//     if ($(document).find('.td_service_amount').length > 0) {
//         var all = $(document).find('.td_service_amount');
//         var total = 0.00;
//         $.each(all, function (i, v) {
//             sub_service_total = (parseFloat(sub_service_total) + parseFloat($(v).text()));
//             sub_service_total = sub_service_total.toFixed(2);
//         });
//     }
//     sub_total = (parseFloat(sub_part_total) + parseFloat(sub_service_total));
//     sub_total = sub_total.toFixed(2);

//     $('#sub_total').html(sub_total);
//     $('.sub_total').val(sub_total);
//     setTimeout(() => {
//         check_total_tax();
//         check_total(sub_total, shipping_charge);
//     }, 20);
// }

// function check_total_tax() {
//     var total_tax = '0.00';
//     if ($(document).find('.span_tax_rate').length > 0) {
//         var all = $(document).find('.span_tax_rate');
//         var tax = '0.00';
//         $.each(all, function (i, v) {
//             if ($(v).html() != '') {
//                 tax = (parseFloat(tax) + parseFloat($(v).html()));
//                 tax = tax.toFixed(2);
//             }
//         });
//         if (isNaN(tax))
//             tax = '0.00';
//     }
//     if ($(document).find('.span_service_tax_rate').length > 0) {
//         var all = $(document).find('.span_service_tax_rate');
//         var stax = '0.00';
//         $.each(all, function (i, v) {
//             if ($(v).html() != '') {
//                 stax = (parseFloat(stax) + parseFloat($(v).html()));
//                 stax = stax.toFixed(2);
//             }
//         });
//         if (isNaN(stax))
//             stax = '0.00';
//     }
//     total_tax = (parseFloat(tax) + parseFloat(stax));
//     total_tax = total_tax.toFixed(2);
//     if (isNaN(total_tax))
//         total_tax = '0.00';
//     $('#span_total_tax').html(total_tax);
// }

// function calculate_discount(num, discount = 0, amount) {
//     var rate = parseFloat($('#td_rate_' + num).html()).toFixed(2);
//     if (isNaN(rate))
//         rate = '0.00';
//     var dis_type = $('#discount_type_id_' + num).val();
//     if (dis_type == 'p') {
//         if (discount > 100) {
//             discount = '0.00';
//             $('#div_discount_' + num).html(discount);
//             $('#discount_' + num).val(discount);
//         } else {
//             var calcPerc = (amount * discount / 100);
//             amount = parseFloat(amount).toFixed(2) - parseFloat(calcPerc).toFixed(2);
//             amount = amount.toFixed(2);
//             $('#discount_rate_' + num).val(parseFloat(calcPerc).toFixed(2));
//             $('#span_discount_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
//         }
//     } else {
//         if (parseInt(discount) > parseInt(rate)) {
//             discount = '0.00';
//             $('#div_discount_' + num).html(discount);
//             $('#discount_' + num).val(discount);
//         } else {
//             amount = parseFloat(amount).toFixed(2) - parseFloat(discount).toFixed(2);
//             amount = amount.toFixed(2);
//             $('#discount_rate_' + num).val(parseFloat(discount).toFixed(2));
//             $('#span_discount_rate_' + num).html(parseFloat(discount).toFixed(2));
//         }
//     }
//     $('#td_amount_' + num).html(amount);
//     $('#amount_' + num).val(amount);
// }

// function check_tax(num, tax_id, rate) {
//     var amount = rate;
//     var tax_rate = $("#tax_hidden_" + tax_id).val();
//     var discount = parseFloat($('#div_discount_' + num).html()).toFixed(2);
//     if (isNaN(discount))
//         discount = '0.00';
//     var calcPerc = (parseFloat(amount) * parseFloat(tax_rate) / 100);
//     // amount = parseFloat(amount) + parseFloat(calcPerc);
//     amount = parseFloat(amount).toFixed(2);
//     $('#tax_' + num).val(parseFloat(calcPerc).toFixed(2));
//     $('#span_tax_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
//     setTimeout(() => {
//         calculate_discount(num, discount, amount);
//         sum_sub_total();
//     }, 40);
// }

// $(document).on('change', '.discount_type_id', function () {
//     var num = $(this).parents('.' + estimate_id).attr('data-value');
//     var tax_id = $('#tax_id_' + num).val();
//     if ($('#td_part_no_' + num).hasClass('select_part') == false) {
//         var discount = parseFloat($('#div_discount_' + num).html()).toFixed(2);
//         if (isNaN(discount))
//             discount = '0.00';
//         var rate = parseFloat($('#td_rate_' + num).html()).toFixed(2);
//         if (isNaN(rate))
//             rate = '0.00';
//         var qty = parseInt($('#div_quantity_' + num).html());
//         var amount = (qty * rate).toFixed(2);
//         if (tax_id != '') {
//             setTimeout(() => {
//                 check_tax(num, tax_id, amount);
//             }, 20);
//         } else {
//             setTimeout(() => {
//                 calculate_discount(num, discount, amount);
//                 sum_sub_total();
//             }, 50);
//         }
//     }
// });

// $(document).on('change', '.select-location-data', function () {
//     var location_id = $(this).val();
//     var num = $(this).parents('.' + estimate_id).attr('data-value');
//     var item_id = $("#part_no_" + num).val();
//     if ($('#td_part_no_' + num).hasClass('select_part') == false) {
//         $.ajax({
//             url: site_url + 'estimates/get_item_data_ajax_by_part_id',
//             dataType: "json",
//             type: "POST",
//             data: {
//                 id: item_id, // search term
//                 location_id: location_id,
//                 itsfor: 'internal'
//             },
//             success: function (response) {
//                 data = response.data;
//                 if (response.code == 200) {
//                     $("#span_quantity_" + num).html(data.location_quantity);
//                     if (parseInt($("#div_quantity_" + num).html()) > parseInt(data.location_quantity)) {
//                         $("#div_quantity_" + num).html('1');
//                         $("#discount_" + num).val('1');
//                         $("#td_amount_" + num).html(data.retail_price);
//                         $("#amount_" + num).val(data.retail_price);
//                         var discount = parseFloat($('#div_discount_' + num).html()).toFixed(2);
//                         if (isNaN(discount))
//                             discount = '0.00';
//                         setTimeout(() => {
//                             calculate_discount(num, discount, data.retail_price);
//                             sum_sub_total();
//                         }, 50);
//                     }
//                 }
//             }
//         });
//     }
// });

// $(document).on('change', '.select-tax-data', function () {
//     var num = $(this).parents('.' + estimate_id).attr('data-value');
//     if ($('#td_part_no_' + num).hasClass('select_part') == false) {
//         var tax_id = $(this).val();
//         var rate = parseFloat($('#td_rate_' + num).html()).toFixed(2);
//         if (isNaN(rate))
//             rate = '0.00';
//         var qty = parseInt($('#div_quantity_' + num).html());
//         var amount = (qty * rate).toFixed(2);
//         if (tax_id != '') {
//             setTimeout(() => {
//                 check_tax(num, tax_id, amount);
//             }, 20);
//         }
//     }
// });

// $(document).on('blur', '#shipping_charge', function () {
//     var shipping_charge = $(this).val();
//     if (isNaN(shipping_charge) || shipping_charge == '')
//         shipping_charge = '0.00';

//     var sub_total = $('.sub_total').val();
//     if (isNaN(sub_total))
//         sub_total = '0.00';

//     $('#shipping_charge').val(parseFloat(shipping_charge).toFixed(2));
//     $('#span_shipping_charge').html(parseFloat(shipping_charge).toFixed(2));
//     setTimeout(() => {
//         check_total(sub_total, shipping_charge);
//     }, 10);
// });


// function check_total(sub_total = 0.00, shipping_charge = 0.00) {
//     var total = 0.00;
//     var total_tax = $('#span_total_tax').html();
//     total = (parseFloat(shipping_charge) + parseFloat(sub_total) + parseFloat(total_tax));
//     total = total.toFixed(2);
//     $('#total').html(total);
//     $('.total').val(total);
// }

// $('#txt_make_name').change(function () {
//     var selected_val = $(this).val();
//     $.ajax({
//         url: site_url + 'dashboard/change_make_get_ajax',
//         dataType: "json",
//         type: "POST",
//         data: {id: selected_val},
//         success: function (response) {
//             $('#txt_model_name').html(response);
//             $('#txt_model_name').select2({containerCssClass: 'select-sm'});
//             setTimeout(() => {
//                 check_part_details();
//             }, 50);
//         }
//     });
// });

// $('.format-phone-number').formatter({
//     pattern: '({{999}}) {{999}} - {{9999}}'
// });

// if (typeof (make_id) !== 'undefined' && make_id != '') {
//     $('#txt_make_name').val(make_id).trigger('change');
//     setTimeout(function () {
//         if (typeof (modal_id) !== 'undefined' && modal_id != '') {
//             $('#txt_model_name').val(modal_id).trigger('change');
//         }
//     }, 1000);
// }

// if (typeof (item_make_id) !== 'undefined' && item_make_id != '') {
//     $('#txt_make_name').val(item_make_id).trigger('change');
//     setTimeout(function () {
//         if (typeof (item_modal_id) !== 'undefined' && item_modal_id != '') {
//             $('#txt_model_name').val(item_modal_id).trigger('change');
//         }
//     }, 1000);
// }

// function check_location_inventory(item_id, num = 1) {
//     $('#txt_location_id_' + num + ' option').attr('disabled', 'disabled');
//     $.ajax({
//         type: 'POST',
//         url: site_url + 'estimates/get_item_location_ajax_data',
//         dataType: 'JSON',
//         data: {item_id: item_id},
//         success: function (data) {
//             $.each(data, function (key, value) {
//                 $('#txt_location_id_' + num + ' option[value="' + value.location_id + '"]').removeAttr('disabled', 'disabled');
//             });
//             $('#txt_location_id_' + num).select2('destroy').select2();
//         }
//     });
// }

// if (typeof (item_arr) !== 'undefined' && item_arr != '') {
//     setTimeout(function () {
//         check_location_inventory(item_id);
//     }, 1000);
// }

// $('.part_list').on('click', function () {
//     $('#part_list_modal').modal('show');
// });

// $('#txt_model_name').change(function () {
//     setTimeout(() => {
//         check_part_details();
//     }, 10);
// });
// $('#txt_year_name').change(function () {
//     setTimeout(() => {
//         check_part_details();
//     }, 10);
// });
// $('.part_list').addClass('hide');
// $('#part_list_view').html('');

// function check_part_details() {
//     var make_id = $('#txt_make_name').val();
//     var model_id = $('#txt_model_name').val();
//     var year_id = $('#txt_year_name').val();
//     if ((typeof (make_id) != undefined && make_id != '') && (typeof (model_id) != undefined && model_id != '') && (typeof (year_id) != undefined && year_id != '')) {
//         $.ajax({
//             url: site_url + 'estimates/get_part_data',
//             dataType: "json",
//             type: "POST",
//             data: {make_id: make_id, model_id: model_id, year_id: year_id},
//             success: function (response) {
//                 if (response.code == 200) {
//                     if (response.AllParts.length > 0) {
//                         var html = '';
//                         $.each(response.AllParts, function (key, value) {
//                             html += '<div class="col-sm-6">' +
//                                     '<div class="panel invoice-grid">' +
//                                     '<div class="panel-body border-top-primary text-center">' +
//                                     '<div class="row">' +
//                                     '<div class="col-sm-5 text-left">' +
//                                     '<h6 class="text-semibold no-margin-top"><a href="javascript:void(0)" class="btn_home_item_view" id="' + btoa(value.id) + '">' + value.part_no + '</a></h6>' +
//                                     '<ul class="list list-unstyled">' +
//                                     '<li>Global Part:' + value.global_part + '</li>' +
//                                     '</ul>' +
//                                     '</div>' +
//                                     '<div class="col-sm-7">' +
//                                     '<h6 class="text-semibold text-right no-margin-top">' + currency + '' + value.retail_price + '</h6>' +
//                                     '<ul class="list list-unstyled text-right">' +
//                                     '<li>Department: <span class="text-semibold">' + value.dept_name + '</span></li>' +
//                                     '</ul>' +
//                                     '</div>' +
//                                     '</div>' +
//                                     '</div>' +
//                                     '<div class="panel-footer panel-footer-condensed">' +
//                                     '<div class="heading-elements">' +
//                                     '<span class="heading-text">' +
//                                     '<span class="status-mark border-blue position-left"></span> Vendor: <span class="text-semibold">' + value.name + '</span>' +
//                                     '</span>' +
//                                     '<ul class="list-inline list-inline-condensed heading-text pull-right">' +
//                                     '<li class="dropdown">' +
//                                     '</li>' +
//                                     '</ul>' +
//                                     '</div>' +
//                                     '</div>' +
//                                     '</div>' +
//                                     '</div>';
//                         });
//                         $('.part_list').removeClass('hide');
//                         $('#part_list_view').html(html);
//                     } else {
//                         $('.part_list').addClass('hide');
//                         $('#part_list_view').html('');
//                     }
//                 }
//             }
//         });
//     }
// }
// $(document).on('click', '.btn_home_item_view', function () {
//     $.ajax({
//         url: site_url + 'items/get_item_data_ajax_by_id',
//         type: "POST",
//         data: {id: this.id},
//         success: function (response) {
//             $('#dash_view_body1').html(response);
//             $('#dash_view_modal1').modal('show');
//         }
//     });
// });

// $(window).keydown(function (event) {
//     if (event.keyCode == 13) {
//         event.preventDefault();
//         return false;
//     }
// });


// //for service:

// function add_service_html(num) {
//     var html = "<tr class='service_" + estimate_id + "' id='service_tr_" + num + "' data-value='" + num + "'>" + "<td class='select_service' id='td_service_no_" + num + "'>" +
//             "<select class='select select-size-sm select-service-data' id='service_id_" + num + "' name='service_id[]' data-placeholder='Select a Service...'>" +
//             " </select>" +
//             "</td>" +
//             "<td><input type='hidden' value='0.00' name='service_rate[]' id='service_rate_" + num + "'/><span id='td_service_rate_" + num + "' class='td_service_rate'>0.00</span></td>" +
//             "<td id='td_service_discount_" + num + "'><input type='hidden' value='0.00' name='service_discount[]' id='service_discount_" + num + "'/><input type='hidden' value='0.00' name='service_discount_rate[]' id='service_discount_rate_" + num + "'/>" +
//             "<div class='row'>" +
//             "<div class='col-md-6 mt-3 div_service_discount text-center' id='div_service_discount_" + num + "'>0.00</div>" +
//             "<div class='col-md-6'>" +
//             "<select name='service_discount_type_id[]' id='service_discount_type_id_" + num + "' class='service_discount_type_id'>" +
//             "<option value='p' selected=''>%</option>" +
//             "<option value'r'>" + currency + "</option>" +
//             "</select>" +
//             "</div>" +
//             "</div>" +
//             "<div class='row service_discount_rate'>" +
//             "<span id='span_service_discount_rate_" + num + "' class='span_service_discount_rate'></span>" +
//             "</div>" +
//             "</td>" +
//             "<td id='td_service_tax_" + num + "'>" +
//             "<input type='hidden' value='0.00' name='service_tax[]' id='service_tax_" + num + "'/>" +
//             "<div class='row'>" +
//             "<select data-placeholder='Select a Tax...' class='select select-size-sm select-service-tax-data' id='service_tax_id_" + num + "' name='service_tax_id[]'>" +
//             "</select>" +
//             "</div>" +
//             "<div class='col-md-12 service_tax_rate'>" +
//             "<span id='span_service_tax_rate_" + num + "' class='span_service_tax_rate'></span>" +
//             "</div>" +
//             "</td>" +
//             "<td><input type='hidden' value='0.00' name='service_amount[]' id='service_amount_" + num + "'/><span id='td_service_amount_" + num + "' class='td_service_amount'>0.00</span></td>" +
//             "<td id='td_service_remove_" + num + "'>" +
//             "<span class='service_remove' id='service_remove_" + num + "'><i class='icon-trash'></i></span></td>" +
//             "</tr>";
//     setTimeout(function () {
//         var o = $("<option/>", {value: "", text: "Select a Service..."});
//         $('#service_id_' + num).append(o);
//         if (typeof (services) !== 'undefined' && services != '') {
//             $.each(services, function (key, value) {
//                 var o = $("<option/>", {value: value.id, text: value.name + " : " + currency + "" + value.rate});
//                 $('#service_id_' + num).append(o);
//             });
//         }
//         $('#service_id_' + num).select2().trigger('change');
//         if (typeof (taxes) !== 'undefined' && taxes != '') {
//             var o = $("<option/>", {value: "", text: "Select a Tax..."});
//             $('#service_tax_id_' + num).append(o);
//             $.each(taxes, function (key, value) {
//                 var o = $("<option/>", {value: value.id, text: value.name + " (" + value.rate + "%)"});
//                 $('#service_tax_id_' + num).append(o);
//             });
//             $('#service_tax_id_' + num).select2().trigger('change');
//         }
//     }, 10);
//     $('.service_div').append(html);
// }

// $('.add_service_line').on('click', function () {
//     var num = ((parseInt($('.service_' + estimate_id).last().attr('data-value'))) + 1);
//     if (isNaN(num))
//         num = 1;
//     add_service_html(num);
// });

// $(document).on('click', '.service_remove', function () {
//     var count = $('.service_' + estimate_id).length;
//     if (count > 1) {
//         var num = $(this).parents('.service_' + estimate_id).attr('data-value');
//         $('#service_tr_' + num).fadeOut(3000).remove();
//     } else if (count == 1) {
//         var num = $(this).parents('.service_' + estimate_id).attr('data-value');
//         $("#service_id_" + num).select2('destroy').val('').select2();
//         $("#td_service_no_" + num).addClass('select_service');
//         $("#td_service_rate_" + num).html('0.00');
//         $("#td_service_amount_" + num).html('0.00');
//         $("#service_rate_" + num).val('0.00');
//         $("#service_amount_" + num).val('0.00');
//         $("#service_discount_type_id_" + num).val('p');
//         $("#div_service_discount_" + num).html('0.00');
//         $("#service_discount_" + num).val('0.00');
//         $("#service_discount_rate_" + num).val('0.00');
//         $("#span_service_quantity_" + num).html('');
//         $("#service_tax_" + num).val('0.00');
//         $("#span_service_tax_rate_" + num).html('');
//         $("#span_service_discount_rate_" + num).html('');
//         $('#service_tax_id_' + num).val('').select2('destroy').val('').select2();
//     }
//     setTimeout(() => {
//         sum_sub_total();
//     }, 20);
// });

// $(document).on('change', '.select-service-data', function () {
//     var service_id = $(this).val();
//     var num = $(this).parents('.service_' + estimate_id).attr('data-value');
//     $('#td_service_no_' + num).removeClass('select_service');
//     var rate = parseFloat($("#service_hidden_" + service_id).val()).toFixed(2);
//     if (isNaN(rate))
//         rate = '0.00';
//     $("#td_service_amount_" + num).html(rate);
//     $("#service_amount_" + num).val(rate);
//     $("#td_service_rate_" + num).html(rate);
//     $("#service_rate_" + num).val(rate);
//     var discount = parseFloat($('#div_service_discount_' + num).html()).toFixed(2);
//     if (isNaN(discount))
//         discount = '0.00';
//     setTimeout(() => {
//         calculate_service_discount(num, discount, rate);
//         sum_sub_total();
//     }, 50);
// });

// function calculate_service_discount(num, discount = 0, amount) {
//     var rate = parseFloat($('#td_service_rate_' + num).html()).toFixed(2);
//     if (isNaN(rate))
//         rate = '0.00';
//     var dis_type = $('#service_discount_type_id_' + num).val();
//     if (dis_type == 'p') {
//         if (discount > 100) {
//             discount = '0.00';
//             $('#div_service_discount_' + num).html(discount);
//             $('#service_discount_' + num).val(discount);
//         } else {
//             var calcPerc = (amount * discount / 100);
//             amount = parseFloat(amount).toFixed(2) - parseFloat(calcPerc).toFixed(2);
//             amount = amount.toFixed(2);
//             $('#service_discount_rate_' + num).val(parseFloat(calcPerc).toFixed(2));
//             $('#span_service_discount_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
//         }
//     } else {
//         if (parseInt(discount) > parseInt(rate)) {
//             discount = '0.00';
//             $('#div_service_discount_' + num).html(discount);
//             $('#service_discount_' + num).val(discount);
//         } else {
//             amount = parseFloat(amount).toFixed(2) - parseFloat(discount).toFixed(2);
//             amount = amount.toFixed(2);
//             $('#service_discount_rate_' + num).val(parseFloat(discount).toFixed(2));
//             $('#span_service_discount_rate_' + num).html(parseFloat(discount).toFixed(2));
//         }
//     }
//     $('#td_service_amount_' + num).html(amount);
//     $('#service_amount_' + num).val(amount);
// }

// $(document).on('click', '.div_service_discount', function () {
//     var num = $(this).parents('.service_' + estimate_id).attr('data-value');
//     if ($('#td_service_no_' + num).hasClass('select_service') == false) {
//         if ($('#div_service_discount_' + num).children('.input_service_discount').length < 1) {
//             var discount = parseFloat($('#div_service_discount_' + num).html()).toFixed(2);
//             if (isNaN(discount))
//                 discount = '0.00';
//             var html = '<input type="text" value="' + discount + '" id="input_service_discount_' + num + '" class="input_service_discount form-control input-xs"/>';
//             $('#div_service_discount_' + num).html(html);
//         }
//     }
// });

// $(document).on('focusout', '.input_service_discount', function () {
//     var num = $(this).parents('.service_' + estimate_id).attr('data-value');
//     var tax_id = $('#service_tax_id_' + num).val();
//     var discount = parseFloat($('#input_service_discount_' + num).val()).toFixed(2);
//     var rate = parseFloat($('#td_service_rate_' + num).html()).toFixed(2);
//     if (isNaN(rate))
//         rate = '0.00';
//     if (isNaN(discount)) {
//         discount = '0.00';
//     }
//     $('#div_service_discount_' + num).html(discount);
//     $('#service_discount_' + num).val(discount);
//     if (tax_id != '') {
//         setTimeout(() => {
//             check_service_tax(num, tax_id, rate);
//         }, 20);
//     } else {
//         setTimeout(() => {
//             calculate_service_discount(num, discount, rate);
//             sum_sub_total();
//         }, 50);
//     }
// });

// $(document).on('click', '.td_service_rate', function () {
//     var num = $(this).parents('.service_' + estimate_id).attr('data-value');
//     var rate = parseFloat($('#service_rate_' + num).val()).toFixed(2);
//     if ($('#td_service_no_' + num).hasClass('select_service') == false) {
//         var html = '<input type="text" value="' + rate + '" id="input_service_rate_' + num + '" class="input_service_rate form-control input-xs"/>';
//         $('#td_service_rate_' + num).text('').append(html);
//     }
// });

// $(document).on('focusout', '.input_service_rate', function () {
//     var num = $(this).parents('.service_' + estimate_id).attr('data-value');
//     var tax_id = $('#service_tax_id_' + num).val();
//     var discount = parseFloat($('#div_service_discount_' + num).html()).toFixed(2);
//     if (isNaN(discount))
//         discount = '0.00';
//     var rate = parseFloat($('#input_service_rate_' + num).val()).toFixed(2);
//     if (isNaN(rate)) {
//         rate = '0.00';
//     }
//     $('#td_service_rate_' + num).html(rate);
//     $('#service_rate_' + num).val(rate);
//     if (tax_id != '') {
//         setTimeout(() => {
//             check_service_tax(num, tax_id, rate);
//         }, 20);
//     } else {
//         setTimeout(() => {
//             calculate_service_discount(num, discount, rate);
//             sum_sub_total();
//         }, 50);
//     }
// });

// function check_service_tax(num, tax_id, rate) {
//     var amount = rate;
//     var tax_rate = $("#tax_hidden_" + tax_id).val();
//     var discount = parseFloat($('#div_service_discount_' + num).html()).toFixed(2);
//     if (isNaN(discount))
//         discount = '0.00';
//     var calcPerc = (parseFloat(amount) * parseFloat(tax_rate) / 100);
//     // amount = parseFloat(amount) + parseFloat(calcPerc);
//     amount = parseFloat(amount).toFixed(2);
//     $('#service_tax_' + num).val(parseFloat(calcPerc).toFixed(2));
//     $('#span_service_tax_rate_' + num).html(parseFloat(calcPerc).toFixed(2));
//     setTimeout(() => {
//         calculate_service_discount(num, discount, amount);
//         sum_sub_total();
//     }, 40);
// }

// $(document).on('change', '.service_discount_type_id', function () {
//     var num = $(this).parents('.service_' + estimate_id).attr('data-value');
//     var tax_id = $('#service_tax_id_' + num).val();
//     if ($('#td_service_no_' + num).hasClass('select_service') == false) {
//         var discount = parseFloat($('#div_service_discount_' + num).html()).toFixed(2);
//         if (isNaN(discount))
//             discount = '0.00';
//         var rate = parseFloat($('#td_service_rate_' + num).html()).toFixed(2);
//         if (isNaN(rate))
//             rate = '0.00';
//         if (tax_id != '') {
//             setTimeout(() => {
//                 check_service_tax(num, tax_id, rate);
//             }, 20);
//         } else {
//             setTimeout(() => {
//                 calculate_service_discount(num, discount, rate);
//                 sum_sub_total();
//             }, 50);
//         }
//     }
// });

// $(document).on('change', '.select-service-tax-data', function () {
//     var num = $(this).parents('.service_' + estimate_id).attr('data-value');
//     if ($('#td_service_no_' + num).hasClass('select_service') == false) {
//         var tax_id = $(this).val();
//         var rate = parseFloat($('#td_service_rate_' + num).html()).toFixed(2);
//         if (isNaN(rate))
//             rate = '0.00';
//         if (tax_id != '') {
//             setTimeout(() => {
//                 check_service_tax(num, tax_id, rate);
//             }, 20);
//         }
//     }
// });

// /**
//  * @author : Hardik Gadhiya
//  * @description Open webcam and scan qr code, if qr code will match with user's items then part will be add in invoice.
//  * @date: 22-04-2019
//  **/
// $(document).on('click', ".scan-item-qr-code", function () {
//     var row_no = $(this).attr('data-rowid');
//     let scanner = new Instascan.Scanner({video: document.getElementById('webcam-preview')});

//     scanner.addListener('scan', function (part_no) {
//         scanner.stop();
//         try {
//             if (part_no) {
//                 $.ajax({
//                     type: 'POST',
//                     url: site_url + 'estimates/get_item_data_id',
//                     dataType: 'JSON',
//                     data: {part_no: part_no},
//                     success: function (data) {
//                         if (data.item_id != '' && data.location_item) {
//                             var item_id = data.item_id;

//                             $.each(data, function (key, value) {
//                                 $('#txt_location_id_' + row_no + ' option[value="' + value.location_id + '"]').removeAttr('disabled', 'disabled');
//                             });

//                             $('#txt_location_id_' + row_no).select2('destroy').select2();
//                             $("#partno_scan_webcam_modal").modal('hide');

//                             var item_data = {
//                                 id: part_no, // search term
//                                 location_id: $('#txt_location_id_' + row_no).val(),
//                             };

//                             $.ajax({
//                                 type: 'POST',
//                                 url: site_url + 'invoices/get_item_data_ajax_by_part_id',
//                                 data: item_data,
//                                 dataType: 'json',
//                                 success: function (response) {
//                                     if (response.data != null && response.data != '') {
//                                         var response_data = response.data[0];
//                                         formatRepoSelection(response_data, row_no, false);
//                                         $("#partno_scan_webcam_modal").modal('hide');
//                                     } else {
//                                         swal({
//                                             title: "Error!",
//                                             text: "Product location details has not been found.",
//                                             icon: "error",
//                                             timer: 2000
//                                         });

//                                         $("#partno_scan_webcam_modal").modal('hide');
//                                     }
//                                 }
//                             });

//                             $("#partno_scan_webcam_modal").modal('hide');
//                         } else {
//                             swal({
//                                 title: "Error!",
//                                 text: "Product has not been found.",
//                                 icon: "error",
//                                 timer: 2000
//                             });

//                             $("#partno_scan_webcam_modal").modal('hide');
//                         }
//                     }
//                 });
//             }
//         } catch (e) {
//             swal({
//                 title: "Error!",
//                 text: "Something went wrong.",
//                 icon: "error",
//             });
//         }
//     });

//     Instascan.Camera.getCameras().then(function (cameras) {
//         if (cameras.length > 0) {
//             var camera_settings = check_device();
//             scanner.start(cameras[camera_settings]);
//         } else {
//             swal({
//                 title: "Error!",
//                 text: "Camera not found.",
//                 icon: "error",
//             });
//         }
//     }).catch(function (e) {
//         swal({
//             title: "Error!",
//             text: "Camera not found.",
//             icon: "error",
//         });
//     });

//     $("#partno_scan_webcam_modal").modal('show');
// });

// $(".estimate_attachments").on("change", function () {
//     var order_no = $(this).attr('data-no');

//     $('#message_' + order_no).html();
//     $('.image_wrapper_' + order_no).html('');
//     var files = !!this.files ? this.files : [];

//     if (!files.length || !window.FileReader) {
//         $('#message_' + order_no).addClass('error').html("No file selected.");
//         $('#message_' + order_no).show();
//         return; // no file selected, or no FileReader support
//     }

//     var i = 0;
//     var file_name = files[i].name;

//     for (var key in files) {
//         if (/^image/.test(files[key].type)) { // only image file
//             var reader = new FileReader(); // instance of the FileReader
//             reader.readAsDataURL(files[key]); // read the local file
//             reader.onloadend = function (e) { // set image data as background of div
//                 $('#message_' + order_no).removeClass('error').html(file_name);
//                 $('#message_' + order_no).show();

//                 var html = '<img src="' + e.target.result + '" style="width: 50px; border-radius: 2px;" alt="">';
//                 html += '<input type="hidden" name="order_ids[]" value="' + order_no + '">';
//                 $('.image_wrapper_' + order_no).html(html);
//                 ++i;
//             }
//         } else if (files[key].type == 'application/pdf') {
//             $('#message_' + order_no).removeClass('error').html(file_name);
//             $('#message_' + order_no).show();

//             var html = '<img src="' + site_url + 'assets/images/pdf_icon.png" style="width: 50px; border-radius: 2px;" alt="">';
//             html += '<input type="hidden" name="order_ids[]" value="' + order_no + '">';
//             $('.image_wrapper_' + order_no).html(html);
//             ++i;
//         } else {
//             $('#message_' + order_no).addClass('error').html("Please select proper image");
//             $('#message_' + order_no).show();
//         }
//     }
// });

// if (signature_attachment && signature_attachment != '') {
//     var FR = new FileReader(signature_attachment);

//     toDataUrl(signature_attachment, function (myBase64) {
//         $("#signature_attachment").val(myBase64);
//     });
// }

// function toDataUrl(url, callback) {
//     var xhr = new XMLHttpRequest();
//     xhr.onload = function () {
//         var reader = new FileReader();
//         reader.onloadend = function () {
//             callback(reader.result);
//         }
//         reader.readAsDataURL(xhr.response);
//     };
//     xhr.open('GET', url);
//     xhr.responseType = 'blob';
//     xhr.send();
// }

// $(".remove-attachement").click(function () {
//     var attachment_id = $(this).attr('data-id');
//     var div_no = $(this).attr('data-divno');

//     $(this).empty().off("click").html('<i class="icon-spinner3 spinner"></i>');

//     $.ajax({
//         url: site_url + "remove_attchment/" + attachment_id,
//         success: function (data) {
//             if (data == 1) {
//                 $("#attachment_div_" + div_no).remove();

//                 swal({
//                     type: 'success',
//                     title: 'Invoice Attachments',
//                     text: 'Attachment has been deleted successfully!',
//                 });
//             } else {
//                 swal({
//                     type: 'error',
//                     title: 'Invoice Attachments',
//                     text: 'Attachment has not been deleted!',
//                 });
//                 $(this).empty().on("click").html('&times;');
//             }
//         }, error: function (jqXHR, textStatus, errorThrown) {
//             Swal({
//                 type: 'error',
//                 title: 'Estimation Attachments',
//                 text: 'Something went wrong!',
//             });
//         }
//     });
// });

// $("#customer_id").change(function () {
//     var customer_id = $(this).find('option:selected').val();
//     var address = '';

//     $.ajax({
//         type: 'POST',
//         url: site_url + "invoices/get_customer_details",
//         data: {
//             customer_id: customer_id
//         },
//         success: function (data) {
//             if (data) {
//                 data = JSON.parse(data);

//                 var customer_name = data.first_name + ' ' + data.last_name;
//                 $("#txt_cust_name").val(customer_name);
//                 $("#txt_email").val(data.email);

//                 var phone = data.phone;
//                 $("#txt_phone_number").val(phone).trigger('keyup');

//                 if (data.billing_address && data.billing_address != '') {
//                     address += data.billing_address + ', ';
//                 }

//                 if (data.billing_address_street && data.billing_address_street != '') {
//                     address += data.billing_address_street + ', ';
//                 }

//                 if (data.billing_address_city && data.billing_address_city != '') {
//                     address += data.billing_address_city + ', ';
//                 }

//                 if (data.billing_address_state && data.billing_address_state != '') {
//                     address += data.billing_address_state + ', ';
//                 }

//                 if (data.billing_address_zip && data.billing_address_zip != '') {
//                     address += data.billing_address_zip;
//                 }

//                 $("#txt_address").val(address);
//             }
//         }
//     });
// });