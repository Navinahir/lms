$('.datatable-basic').dataTable({
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
    order: [[2, "asc"]],
    ajax: site_url + 'admin/subscriptions/get_ajax_data',
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
            data: "name",
            visible: true,
        },
        {
            data: "amount",
            visible: true,
        },
        {
            data: "no_of_months",
            visible: true,
        },
        {
            data: "max_redemption",
            visible: true,
        },
        {
            data: "expiry_date",
            visible: true,
        },
        {
            data: "modified_date",
            visible: true,
        },
        {
            data: "action",
            render: function (data, type, full, meta) {
                action = '';
                action += '<a href="' + site_url + 'admin/subscriptions/view/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="View">View</a>&nbsp;&nbsp;';
                if (full.duration != 'FOREVER') {
                    action += '<a href="' + site_url + 'admin/subscriptions/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Edit</a>';
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/subscriptions/action/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this,\'Delete\')" title="Delete">Delete</a>';
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
    }
});

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});
$('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
var add_button = '<div class="text-right"><a href="' + site_url + 'admin/subscriptions/add" class="btn bg-teal-400 btn-labeled custom_add_button"><b><i class="icon-plus-circle2"></i></b> Add Promo Code</a></div>';
$('.datatable-header').append(add_button);

//-- Sweet Alert Delete Popup
function confirm_alert(e, action) {
    swal({
        title: "Are you sure?",
        text: "You would like to " + action + " this Subscription!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF7043",
        confirmButtonText: "Yes, " + action + " it!"
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

//-- This function is used to validate form
$(function () {

    // Dropdown selectors
    var picker = $('.pickadate-selectors').pickadate({
        selectYears: true,
        selectMonths: true,
        min: new Date(),
        select: new Date(),
        onStart: function () {
            var date = new Date();
            this.set('select', [date.getFullYear(), date.getMonth() + 1, date.getDate()]);
        },
    });

    setTimeout(() => {
        if (selectedDate != undefined) {
            picker.val(selectedDate);
            // picker.set('select', [2018, 12, 11]);
        }
    }, 500);

    $(".switch").bootstrapSwitch('state', true);
    if (Array.prototype.forEach) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html);
        });
    } else {
        var elems = document.querySelectorAll('.switchery');
        for (var i = 0; i < elems.length; i++) {
            var switchery = new Switchery(elems[i]);
        }
    }

    //Toggle button change Amount/Percenatge input box label.
    $('#is_amount').click('switchChange.bootstrapSwitch', function (event, state) {
        var x = $(this).data('on-text');
        var y = $(this).data('off-text');
        var label = y;

        if ($("#is_amount").is(':checked')) {
            label = x;
            $("#is_amount").val(1);
            $("#txt_amount").removeAttr('max');
        } else {
            label = y;
            $("#is_amount").val(0);
            $("#txt_amount").attr('max', '100');
        }
        $("#label_amount").empty().text(label);
    });

    var validator = $("#add_subscription_form").validate({
        ignore: 'input[type=hidden], .select2-search__field, #txt_status', // ignore hidden fields
        errorClass: 'validation-error-label',
        successClass: 'validation-valid-label',
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function (element, errorClass) {
            $(element).removeClass(errorClass);
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
            $(element).parent().find('.form_success_vert_icon').remove();
            $(element).parent().append('<div class="form_success_vert_icon form-control-feedback"><i class="icon-checkmark-circle"></i></div>');
            $(element).remove();
        },
        rules: {
            txt_name: {
                required: true,
                maxlength: 150,
                remote: remoteURL
            },
            txt_months: {required: true},
            txt_redemptions: {required: true},
            expiration_date: {required: true},
            txt_coupon_duration: {required: true},
        },
        messages: {
            txt_name: {
                remote: $.validator.format("This name already exist!")
            }
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

// Preven special character
$('#txt_name').on('keypress', function (event) {
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       $(".special_car_alert").fadeIn();
       return false;
    } else {
        $(".special_car_alert").fadeOut();
    }
});

var duration = $("#txt_coupon_duration").find('option:selected').val();

if (duration == 'repeating') {
    $(".multiple_month_duration_div").removeClass('hide');
}

$("#txt_coupon_duration").change(function () {
    var duration_type = $(this).find('option:selected').val();

    if (duration_type == 'repeating') {
        $(".multiple_month_duration_div").removeClass('hide');
    } else {
        $(".multiple_month_duration_div").addClass('hide');
    }
});
