/**********************************************************
                Intitalize Data Table
***********************************************************/
    $('.datatable-responsive-control-right').dataTable({
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
        order: [[1, "asc"]],
        ajax: site_url + 'admin/inventory/get_departments_data',
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
                data: "description",
                visible: true,
            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    action = '';
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/inventory/departments/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Edit</a>';
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/inventory/departments/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
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
    var add_button = '<div class="text-right"><a href="' + site_url + 'admin/inventory/departments/add" class="btn bg-teal-400 btn-labeled custom_add_button"><b><i class="icon-plus-circle2"></i></b> Add Department</a></div>';
    $('.datatable-header').append(add_button);

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
            }
            else {
                return false;
            }
        });
        return false;
    }

/**********************************************************
                    Form Validation
***********************************************************/
    var validator = $("#add_department_form").validate({
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
            txt_name: { required: true },
            txt_desc: { required: true, maxlength:500 },
        },
        submitHandler: function (form) {
            form.submit();
            $('.custom_save_button').prop('disabled', true);
        },
        invalidHandler: function () {
            $('.custom_save_button').prop('disabled', false);
        }
    });

    $(document).on('click', '.transponder_view_btn', function () {
        $.ajax({
            url: site_url + 'admin/product/view_transponder_ajax',
            type: "POST",
            data: {id: this.id},
            success: function (response) {
                $('#transponder_view_body').html(response);
                $('#transponder_view_modal').modal('show');
            }
        });
    });