/**********************************************************
 Intitalize Data Table
 ***********************************************************/
$(function () {
    bind();
});

function bind() {
    var table = $('.datatable-basic').dataTable({
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
            url: site_url + 'admin/Blogs/get_blogs',
            data: {
//                page_type: $('#filter_status').find('option:selected').val(),
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
                sortable: false
            },
            {
                data: "blog_title",
                visible: true,
            },
            {
                data: "is_active",
                visible: true,
                render: function (data, type, full, meta) {
                    var type_text = '';

                    if (data == 1) {
                        type_text += '<span class="label label-success">Active</span>';
                    } else {
                        type_text += '<span class="label label-danger">In-Active</span>';
                    }

                    return type_text;
                },
            },
            {
                data: "created_date",
                visible: true,
                sortable: false
            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    action = '';
                    action += '<a href="' + site_url + 'admin/blogs/view/' + btoa(full.id) + '" href="javascript:void(0);" class="btn btn-xs custom_dt_action_button" title="View">View</a>';
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/blogs/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Edit</a>';
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'admin/blogs/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
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
}
$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});
$('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
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
 Content View Popup
 ***********************************************************/
$(document).on('click', '.content_view_btn', function () {
    $('#custom_loading').removeClass('hide');
    $('#custom_loading').css('display', 'block');
    $.ajax({
        url: site_url + 'admin/TermsAndPrivacy/get_page_data_ajax_by_id',
        type: "POST",
        data: {id: this.id},
        success: function (response) {
            $('#custom_loading').removeClass('hide');
            $('#custom_loading').css('display', 'none');
            $('#content_view_body').html(response);
            $('#content_view_modal').modal('show');
        }
    });
});

$("#is_active").click(function () {
    var value = 0;
    if ($(this).prop("checked") == true) {
        value = 1;
    } else if ($(this).prop("checked") == false) {
        value = 0;
    }

    $(this).val(value);
});


// Add new element
$(".add").on('click', function () {
    // Finding total number of elements added
    var total_element = $(".element").length;

    // last <div> with element class id
    var lastid = $(".element:last").attr("id");
    var split_id = lastid.split("_");
    var nextindex = Number(split_id[1]) + 1;

    // Check total number elements
    if (total_element < max) {
        var html = '';
        var required = '';

        if (blog_id == '') {
            required = 'data-parsley-required';
        }

        // Adding new div container after last occurance of element class
        $(".element:last").after("<div class='element' id='div_" + nextindex + "'></div>");

        html += "<div class='col-md-12 pr-0'>" +
                "<div class='form-group-material has-feedback'>" +
                "<div class='row'>" +
                "<div class='col-md-3'>" +
                "<div class='form-group mr-0'>" +
                "<select id='select_content_type_" + nextindex + "' " + required + " name='content_types[]' class='form-control content-type-select' data-rowid='" + nextindex + "'>" +
                "<option value='Video'>Video</option>" +
                "<option value='Image'>Image</option>" +
                "<option value='Document'>Document</option>" +
                "</select>" +
                "</div>" +
                "</div>" +
                "<div class='col-md-4'>" +
                "<div class='form-group mr-0'>" +
                "<input type='text' class='form-control' " + required + " data-parsley-maxlength='255' placeholder='Title' name='titles[]' id='title_" + nextindex + "' />" +
                "</div>" +
                "</div>" +
                "<div class='col-md-5 pl-0'>" +
                "<div class='row select2-plus-dropdown form-group'>" +
                "<div class='col-md-10 col-sm-10 col-xs-10 select2-dropdown-custom'>" +
                "<input type='file' class='form-control' data-parsley-fileextension='mp4,3gp,flv,avi' data-parsley-max-file-size='100' " + required + " id='file_" + nextindex + "' name='content_files[]' />" +
                "</div>" +
                "<div class='col-md-2 col-sm-2 col-xs-2 select2-dropdown-plus pl-0'>" +
                "<button type='button' id='remove_" + nextindex + "' class='btn btn-danger btn-sm remove' style='padding: 7px 12px;'><i class='icon-minus3'></i></button>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>";

        // Adding element to <div>
        $("#div_" + nextindex).append(html);
    }
});

// Remove element
$('.multi-media-content').on('click', '.remove', function () {
    var id = this.id;
    var split_id = id.split("_");
    var deleteindex = split_id[1];

    // Remove <div> with id
    $("#div_" + deleteindex).remove();
});

$(document).on('change', ".content-type-select", function () {
    var row_id = $(this).attr('data-rowid');
    var type = $(this).find('option:selected').val();
    var format = '';
    var file_size = 0;

    if (type == 'Video') {
        format = 'mp4';
        file_size = 100;
    } else if (type == 'Image') {
        format = 'jpg,png,jpeg';
        file_size = 3;
    } else if (type == 'Document') {
        format = 'pdf,docx,doc';
        file_size = 5;
    }

    $("#file_" + row_id).removeAttr('data-parsley-fileextension');
    $("#file_" + row_id).attr('data-parsley-fileextension', format);
    $("#file_" + row_id).attr('data-parsley-max-file-size', file_size);
});