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
    order: [[1, "asc"]],
    "ajax": {
        "url": site_url + 'dashboard/get_chip_data',
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
        // {
        //     data: "parts_no",
        //     visible: true,
        // },
        // {
        //     data: "vendor_name",
        //     visible: true,
        // },
        {
            data: "make_name",
            visible: true,
            render: function (data, type, full, meta) {
                return "<div class='row'><div class='col-md-4'><span class='label border-left-primary label-striped mt-5 ml-5 text-bold' style='font-size: smaller;'>" + full.make_name + "</span></div>" +
                    "<div class='col-md-4'><span class='label border-left-primary label-striped mt-5 ml-5 text-bold' style='font-size: smaller;'>" + full.model_name + "</span></div>" +
                    "<div class='col-md-4'><span class='label border-left-primary label-striped mt-5 ml-5 text-bold' style='font-size: smaller;'>" + full.year_name + "</span></div></div>";
            }

        },
        {
            data: "id",
            render: function (data, type, full, meta) {
                action = '<a href="' + site_url + 'dashboard?make=' + btoa(full.make_id) + '&model=' + btoa(full.model_id) + '&year=' + btoa(full.year_id) + '" class="btn btn-xs custom_dt_action_button" title="Search">Search</a>';
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