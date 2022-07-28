/**
 * Validate float value upto two decimal places
 * @param {object} el
 * @param {event} evt
 * @returns {Boolean}
 * @author PAV
 */

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
    "ajax": {
        "url": site_url + 'dashboard/get_cust_data',
        "type": "GET",
        "data": { q: q },
        beforeSend: function () {
            $('#custom_loading').removeClass('hide');
            $('#custom_loading').css('display', 'block');
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
            data: "is_sent",
            visible: true,
            render: function (data, type, full, meta) {
                action = '';
                if (full.is_invoiced == 0) {
                    action += '<span class="label bg-violet-400">Estimate</span>&nbsp;&nbsp;';
                    if (full.turn_invoiced == 1) {
                        action += '<span class="label bg-teal-400">INVOICED</span>';
                    } else {
                        if (full.is_sent == 1) {
                            action += '<span class="label bg-success-400">SENT</span>';
                        } else if (full.is_save_draft == 1) {
                            action += '<span class="label bg-blue-400">DRAFT</span>';
                        }
                    }
                } else {
                    action += '<span class="label bg-pink-400">Invoice</span>&nbsp;&nbsp;';
                    if (full.is_sent == 1) {
                        action += '<span class="label bg-success-400">Completed</span>';
                    } else {
                        action += '<span class="label bg-orange-400">DRAFT</span>';
                    }
                }
                return action;
            }

        },
        {
            data: "total",
            visible: true,
            render: function (data, type, full, meta) {
                return parseFloat(full.total).toFixed(2);
            }

        },
        {
            data: "action",
            render: function (data, type, full, meta) {
                action = '';
                if (full.is_invoiced == 0) {
                    action += '<a href="' + site_url + 'estimates/view/' + btoa(full.id) + '" class="btn btn-xs custom_dt_action_button" title="View">View</a>';
                } else {
                    action += '<a href="' + site_url + 'invoices/view/' + btoa(full.id) + '" class="btn btn-xs custom_dt_action_button" title="View">View</a>';
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
            var switchery = new Switchery(this, { color: '#95e0eb' });
        });
        $("#custom_loading").fadeOut(500);
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
var add = '<div class="text-right"><span><i class="text-grey">Your Search:</i> <b>' + search_type + '</b> - <span class="text-blue">' + q + '</span></span></div>';
$('.datatable-header').append(add);

var validator = $("#search_all").validate({
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
        search_type: { required: true },
        q: {
            required: true, normalizer: function (value) {
                return $.trim(value);
            }
        },
    },
    submitHandler: function (form) {
        $('#custom_loading').removeClass('hide');
        $('#custom_loading').css('display', 'block');
        $("#custom_loading").fadeOut(1000);
        $('.custom_search_button').prop('disabled', true);
        form.submit();
    },
    invalidHandler: function () {
        $('.custom_search_button').prop('disabled', false);
    }
});