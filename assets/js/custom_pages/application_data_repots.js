/**********************************************************
 Intitalize Data Table
 ***********************************************************/
$(function () {
    bind();
});

$(document).on('click', '#btn_search', function(event) {
    $(".datatable-basic").dataTable().fnDestroy();
    bind();
});

// Year Dropdown
    if($("#txt_make_name").val() == "" && $("#txt_model_name").val() == "" && $("#txt_year_name").val() == "")
    {
        for (i = new Date().getFullYear(); i > 1990; i--)
        {
            $('#filter_year_name').append($('<option />').val(i).html(i));
        }
    }

// Year filter result
$('#filter_year_name').change(function(event) {
    var year_id = $(this).find('option:selected').val();
    $('#txt_make_name').val('').select2('');
    $('#txt_model_name').val('').select2('');
    $('#txt_year_name').val('').select2('');
    $(".datatable-basic").dataTable().fnDestroy();
    $('.datatable-basic').dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        // stateSave: true,
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
            emptyTable: 'No data currently available.'
        },
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[1, "asc"]],
        ajax: {
            url: site_url + 'admin/reports/get_transponder_year_filter',
            data: {
                make_id: $('#txt_make_name').val(),
                model_id: $('#txt_model_name').val(),
                year_id: year_id,
                percentage: $('#application_filter').val(),
            },
            type: "GET"
        },
        columns: [
            {
                data: "sr_no",
                visible: true,
                sortable: false,
            },
            {
                data: "make_name",
                visible: true,
            },
            {
                data: "model_name",
                visible: true,
                render: function (data, type, full, meta) {
                    result = '<b>Model : </b>' + full.model_name;
                    return result;
                }
            },
            {
                data: "year_name",
                visible: true,
                render: function (data, type, full, meta) {
                    result = '<b>Year : </b>' + full.year_name;
                    return result;
                }
            },
            {
                data: "percentage",
                visible: true,
                render: function (data, type, full, meta) {
                    result = '<p align="center"><b>'+ full.percentage +'</b></p>';
                    return result;
                },
                sortable: false,
            },
            {
                data: "total_part_attached_id",
                visible: true,
                render: function (data, type, full, meta) {
                    result = '<p align="center">'+ full.total_part_attached_id +'</p>';
                    return result;
                },
                sortable: false,
            },
            {
                data: "total_tools",
                visible: true,
                render: function (data, type, full, meta) {
                    if(full.total_tools !== "" && full.total_tools !== null)
                    {
                        result = '<p align="center">'+ full.total_tools +'</p>';
                    } else {
                        result = '<p align="center">'+ 0 +'</p>';
                    }
                    return result;
                },
                sortable: false,
            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    action = '';
                    action += '<a href="javascript:void(0);" class="btn btn-xs custom_dt_action_button transponder_view_btn" title="View" id="\'' + btoa(full.id) + '\'">View</a>';
                    if (user_type != 2) {
                        action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/product/transponder/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Edit</a>';
                    }
                    return action;
                },
                sortable: false,
            }
        ],
        "fnDrawCallback": function () {
            var info = document.querySelectorAll('.switchery-info');
            $(info).each(function () {
                var switchery = new Switchery(this, {color: '#95e0eb'});
            });

            $(".styled, .multiselect-container input").uniform({
                radioClass: 'choice'
            });
        }
    });

    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
    $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
});

// Get model list
$('#txt_make_name').change(function () {
    $('#filter_year_name').val('').select2('');
    $('#txt_model_name').val('').select2('');
    $('#txt_year_name').val('').select2('');
    var selected_val = $(this).val();
    $.ajax({
        url: site_url + 'admin/dashboard/change_make_get_ajax',
        dataType: "json",
        type: "POST",
        data: {id: selected_val},
        success: function (response) {
            $('#txt_model_name').html(response);
            $('#txt_model_name').select2({containerCssClass: 'select-sm'});
        }
    });
    $(".datatable-basic").dataTable().fnDestroy();
    bind();
});

// Get year list
$('#txt_model_name').on('change', function () {
    $('#filter_year_name').val('').select2('');
    $('#txt_year_name').val('').select2('');
    var make_id = $("#txt_make_name").find('option:selected').val();
    var model_id = $(this).find('option:selected').val();
    var data = {
        make_id: make_id,
        model_id: model_id
    };
    $.ajax({
        url: site_url + 'admin/dashboard/get_transponder_item_years',
        dataType: "json",
        type: "POST",
        data: data,
        success: function (response) {
            $('#txt_year_name').html(response);
            $('#txt_year_name').select2({containerCssClass: 'select-sm'});
        }
    });
    $(".datatable-basic").dataTable().fnDestroy();
    bind();
});

// Get filter result year wish
$('#txt_year_name').change(function(event) {
    $('#filter_year_name').val('').select2('');
    $(".datatable-basic").dataTable().fnDestroy();
    bind(); 
});

// Reset
$(document).on('click', '#btn_reset', function () {
    $('#txt_make_name').val('').select2('');
    $('#txt_model_name').val('').select2('');
    $('#txt_year_name').val('').select2('');
    $('#filter_year_name').val('').select2('');
    $(".datatable-basic").dataTable().fnDestroy();
    bind();
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

function bind(){
    $('.datatable-basic').dataTable({
        autoWidth: false,
        processing: true,
        serverSide: true,
        // stateSave: true,
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
            emptyTable: 'No data currently available.'
        },
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        order: [[1, "asc"]],
        ajax: {
            url: site_url + 'admin/reports/get_transponder',
            data: {
                make_id: $('#txt_make_name').val(),
                model_id: $('#txt_model_name').val(),
                year_id: $('#txt_year_name').val(),
                percentage: $('#application_filter').val(),
            },
            type: "GET"
        },
        columns: [
            {
                data: "sr_no",
                visible: true,
                sortable: false,
            },
            {
                data: "make_name",
                visible: true,
            },
            {
                data: "model_name",
                visible: true,
                render: function (data, type, full, meta) {
                    result = '<b>Model : </b>' + full.model_name;
                    return result;
                }
            },
            {
                data: "year_name",
                visible: true,
                render: function (data, type, full, meta) {
                    result = '<b>Year : </b>' + full.year_name;
                    return result;
                }
            },
            {
                data: "percentage",
                visible: true,
                render: function (data, type, full, meta) {
                    result = '<p align="center"><b>'+ full.percentage +'</b></p>';
                    return result;
                },
                sortable: false,
            },
            {
                data: "total_part_attached_id",
                visible: true,
                render: function (data, type, full, meta) {
                    result = '<p align="center">'+ full.total_part_attached_id +'</p>';
                    return result;
                },
                sortable: false,
            },
            {
                data: "total_tools",
                visible: true,
                render: function (data, type, full, meta) {
                    if(full.total_tools !== "" && full.total_tools !== null)
                    {
                        result = '<p align="center">'+ full.total_tools +'</p>';
                    } else {
                        result = '<p align="center">'+ 0 +'</p>';
                    }
                    return result;
                },
                sortable: false,
            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    action = '';
                    action += '<a href="javascript:void(0);" class="btn btn-xs custom_dt_action_button transponder_view_btn" title="View" id="\'' + btoa(full.id) + '\'">View</a>';
                    if (user_type != 2) {
                        action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/product/transponder/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Edit</a>';
                    }
                    return action;
                },
                sortable: false,
            }
        ],
        "fnDrawCallback": function () {
            var info = document.querySelectorAll('.switchery-info');
            $(info).each(function () {
                var switchery = new Switchery(this, {color: '#95e0eb'});
            });

            $(".styled, .multiselect-container input").uniform({
                radioClass: 'choice'
            });
        }
    });

    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
    $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
}