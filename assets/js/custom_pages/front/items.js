var quickbook_item_id = 0;
$(document).ready(function () {
    $(".touchspin-empty").TouchSpin({
        min: 0,
        max: 100000,
        step: 1,
        booster: true,
        mousewheel: false
    });

    // Enter degit only in low inventory point
    $("#txt_low_inventory,#txt_in_stock").keypress(function (e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });

    $('#txt_unit_cost,#txt_retail_price,#txt_unit_cost,#txt_retail_price').keypress(function(event) {
      if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
      }
    });

/**
 * Validate float value upto two decimal places
 * @param {object} el
 * @param {event} evt
 * @returns {Boolean}
 * @author PAV
 */
function validateFloatKeyPress(el, evt) {
    var charCode = (evt.which) ? evt.which : evt.charCode;
    var iber = el.value.split('.');
    if (charCode != 46 && charCode != 0 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    //just one dot
    if (iber.length > 1 && charCode == 46) {
        return false;
    }
    //get the carat position
    var caratPos = getSelectionStart(el);
    var dotPos = el.value.indexOf(".");
    if (caratPos > dotPos && dotPos > -1 && (iber[1].length > 1)) {
        return false;
    }
    return true;
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
        emptyTable: 'No items currently available.'
    },
    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    order: [[0, "desc"]],
    ajax: site_url + 'items/get_items_data',
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
            data: "dimage",
            visible: true,
            className: 'slct',
            render: function (data, type, full, meta) {
                
                if((full.dimage == null ||  full.dimage == "") && (full.image == null || full.image == ""))
                {
                    result = '<a href="uploads/items/no_image.jpg" data-popup="lightbox"><img src="uploads/items/no_image.jpg" style="height:30px"></a>';
                } 
                else if (full.image != null) 
                {
                    result = '<a href="uploads/items/'+full.image+'" data-popup="lightbox"><img src="uploads/items/'+full.image+'" style="height:30px"></a>';
                } else {
                    result = '<a href="uploads/items/'+full.dimage+'" data-popup="lightbox"><img src="uploads/items/'+full.dimage+'" style="height:30px"></a>';                 
                    // result = '<a class="img_opn" href="javascript:void(0);" data-imgpath="uploads/items/'+full.dimage+'"><img src="uploads/items/'+full.dimage+'" style="height:30px"/></a>';
                }
                return result;
            },
            sortable: false,
        },
        {
            data: "part_no",
            visible: true,
            className: 'slct',
            render: function (data, type, full, meta) {
                result = '<b>Item Part No : </b>' + full.part_no;
                if (full.internal_part_no != null)
                    result += '<br/><b>Alternate Part No or SKU : </b>' + full.internal_part_no;
                return result;
            }
        },
        {
            data: "description",
            visible: true,
            className: 'slct',
            render: function (data, type, full, meta) {
                return full.description.substring(0,30);
            }
        },
        {
            data: "part_location",
            visible: true,
            className: 'slct',
            render: function (data, type, full, meta) {
                if(full.part_location != "" && full.part_location != null)
                {
                    result = full.part_location;
                    return result;
                } else {
                    result = '<p>---</p>';
                    return result;
                }
            }
        },
        {
            data: "retail_price",
            visible: true,
            className: 'slct',
        },
        {
            data: "total_quantity",
            visible: true,
            className: 'slct',
            render: function (data, type, full, meta) {
                if (full.total_quantity > 0) {
                    action = '<span class="label bg-success-400">' + full.total_quantity + ' - IN STOCK</span>';
                } else {
                    action = '<span class="label bg-danger-400">OUT OF STOCK</span>';
                }
                return action;
            }
        },
        {
            data: "dept_name",
            visible: true,
            className: 'slct',
            render: function (data, type, full, meta) {
                return full.dept_name;
            }
        },
        {
            data: "action",
            render: function (data, type, full, meta) {
                action = '';
                quickbook_item_id = full.id;
                if(session_set_status == 'yes')
                {
                    if(full.quickbooks == 1)
                    {
                        action += '<a href="' + site_url + 'items/add_to_quickbook/' + btoa(full.id) + '" class="btn custom_dt_action_button_quickbook btn-xs AddToQuickbook" title="Add To Quickbook">add to quickbooks</a>';
                    }
                }
                action += '<a target="_blank" href="' + site_url + 'items/print-label/' + btoa(full.id) + '" class="btn btn-xs custom_dt_action_button" title="Print Label" >Print Label</a>';
                action += '<a href="javascript:void(0);" class="btn btn-xs custom_dt_action_button item_view_btn" title="View" id="\'' + btoa(full.id) + '\'">View</a>';
                if (edit == 1) {
                    action += '<a href="' + site_url + 'items/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Edit">Edit</a>';
                }
                if (dlt == 1) {
                    action += '<a href="' + site_url + 'items/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
                }

                action += '<a href="' + site_url + 'receive_inventory/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" title="Add Inventory">Add Inventory</a>';

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
    },
    "fnDrawCallback": function () {
        var info = document.querySelectorAll('.switchery-info');
        $(info).each(function () {
            var switchery = new Switchery(this, {color: '#95e0eb'});
        });
    }
});

// Setup - add a text input to each footer cell
$('.datatable-responsive-control-right tfoot th').each(function () {
    var title = $(this).text();
    if ($(this).hasClass('slct')) {
        $(this).html('<input type="text" placeholder="Search ' + title + '" />');
    }
});

$('.dataTables_length select').select2({
    minimumResultsForSearch: Infinity,
    width: 'auto'
});
$('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');


var add_button = '';
add_button += '<div class="text-right"><a class="btn btn-warning btn-labeled mb-3 ml-3" id="search-scan-item-qr-code"><b><i class="icon-camera"></i></b>Scan QR Code</a>';
if (add == 1) {
    add_button += '<a href="' + site_url + 'items/add" class="btn bg-teal-400 btn-labeled custom_add_button mb-3 ml-3"><b><i class="icon-plus-circle2"></i></b> Add Items</a>';
}
add_button += '</div>';
$('.datatable-header').append(add_button);



/**********************************************************
 Form Validation
 ***********************************************************/
var validator = $("#add_item_form").validate({
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
        txt_global_part_no: {required: true, maxlength: 150},
        txt_item_part: {
            required: true, maxlength: 150, normalizer: function (value) {
                return $.trim(value);
            }
        },
        txt_item_description: {
            required: true, normalizer: function (value) {
                return $.trim(value);
            }
        },
        txt_unit_cost: {required: true, number: true},
        txt_retail_price: {required: true, number: true},
        // txt_department: {required: true},
        // txt_pref_vendor: {required: true},
        txt_in_stock: {required: true, number: true},
        // txt_manufacturer: {required: true},
    },
    submitHandler: function (form) {
        form.submit();
        $('.custom_save_button').prop('disabled', true);
    },
    invalidHandler: function () {
        $('.custom_save_button').prop('disabled', false);
    }
});

// Check image format
$(document).on('change','#image_link',function(){
    $('#image_message_alert').html('');
    $('.image_wrapper_alert').html('');
    var files = !!this.files ? this.files : [];
    var sizeKB = files[0].size / 1024 / 1024;

    if (!files.length || !window.FileReader) {
        $('#image_message_alert').addClass('error').html("No file selected.");
        $('#image_message_alert').show();
        return; // no file selected, or no FileReader support
    }
    var i = 0;
    var file_name = files[i].name;

    if(sizeKB <= 2) {
        for (var key in files) {
            if(files[key].type != '' && files[key].type != undefined)
            {
                if (/^image/.test(files[key].type) && files[key].type != 'image/gif') { // only image file
                    var reader = new FileReader(); // instance of the FileReader
                    reader.readAsDataURL(files[key]); // read the local file
                    reader.onloadend = function (e) { // set image data as background of div
                        $('#image_message_alert').hide();
                        ++i;
                    }
                } else {
                    $('#image_message_alert').addClass('error').html("Please select proper image.");
                    $('#image_message_alert').show();
                }
            }
        }
    } else {
        $('#image_message_alert').addClass('error').html("Image size must be less than 2MB.");
        $('#image_message_alert').show();
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

if($('#txt_global_part_no').length > 0) {
    $( "#txt_global_part_no" ).autocomplete({
      source: function( request, response ) {
       // Fetch data
       $.ajax({
        url: site_url + "items/get_search_record",
        type: 'post',
        dataType: "json",

        data: {
         term: request.term
        },
        success: function( data ) {
            // console.log(data);
            var html = "";
            var item_exit = "";
            
            response( $.map( data, function( item ) {
            // console.log(item);
                var html = item.txt + ' <img src="'+item.img+'">';
                var item_exit = item.txt_exist;
                    return {
                        txt: item.txt,
                        img: item.img,
                        txt_exist: item.txt_exist,
                        description: item.description,
                        name: item.name,
                        part_no: item.part_no,
                    }  
                }));
            }
        });
    },
    html: true,
    select: function (event, ui) {
        var value = ui.item.txt;
        var result = value.split(' (');
        
        $('#txt_global_part_no_autoselected').val(1);
        $('#txt_global_part_no').prop('disabled', true);
        setTimeout(() => {
            get_global_part_details(result[0], result[1].slice(0, -1));
        }, 100);
    }, 
    appendTo: '#menu-container'
    }).autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $("<li><div class='item_exist'><div class='li-img-wrap'><img  src='"+item.img+"'/></div><div class='content'><div class='details'><div class='list'>Global Part No:<span>"+item.part_no+"</span></div><div class='list'>Vendor:<span>"+item.name+"</span></div><div class=list>Part Description:<span>"+item.description+"</span></div></div><span class='item_name_exist hidden'>"+item.txt_exist+"</span></div></div></li>").appendTo( ul );
    };

    // Alert if items is already is in inventory.
    $(document).on('click','.item_exist', function(event) {
        var item_name = $(this).closest('.item_exist').find('.item_name_exist').html();
        // var item_name = $(this).closest('.item_exist').children('.item_name_exist').html();
        $.ajax({
            url: site_url + "items/items_exist",
            type: 'POST',
            data: { item_name : item_name },
            success:function(data){  
                if(data == 1)
                {
                   $('#exits_item_warning').removeClass('hidden');
                } else {
                   $('#exits_item_warning').addClass('hidden');
                }
            }
        })        
    });
}
// $("#txt_global_part_no").autocomplete({
//     source: site_url + "items/get_search_record",
//     autoFocus: true,
//     html:true,
//     select: function (event, ui) {
//         var value = ui.item.value;
//         var result = value.split(' (');
       
//         $('#txt_global_part_no_autoselected').val(1);
//         $('#txt_global_part_no').prop('disabled', true);
//         setTimeout(() => {
//             get_global_part_details(result[0], result[1].slice(0, -1));
//         }, 100);
//     },
//     success: function(data) {
              
//                 console.log('in success');
//                 response( $.map( data, function(item) {
//                     // your operation on data
//                     console.log('123');
//                     console.log(data);
//                 }));
//             },
// });




$('#txt_global_part_no').on("keydown", function (e) {

    $("#txt_global_part_no_error2").html('');
    var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
    if (key == 13 || key == 9) {
        e.preventDefault();
        $(this).prop('disabled', true);
        /* FOCUS ELEMENT */
        var global_part_no = $(this).val();
        setTimeout(() => {
            if ($('#txt_global_part_no_autoselected').val() == 0) {
                get_global_part_details(global_part_no);
            }
        }, 100);
    }
});

// Remove validation from dropdown change
$('select').on('change', function () {
    $(this).valid();
});

function get_global_part_details(global_part_no,vendor) {
    var vendor = vendor || null;
    if (global_part_no != '') {
        $(document).find('.datatable-responsive').DataTable().clear().draw();
        $(document).find('.datatable-responsive').DataTable().destroy();
        $('.datatable-header').append('');
        $('#txt_internal_part').select2('destroy').val('').select2();
        $('#txt_internal_part').empty().trigger("change").select2();
        $.ajax({
            url: site_url + 'items/get_item_data_ajax_by_part_id',
            type: "POST",
            data: {id: global_part_no, vendor: vendor},
            success: function (response) {
                response = JSON.parse(response);
                   // console.log('response::',response);
                if (response.code == 200) {
                    var t = $('.datatable-responsive').DataTable({
                        autoWidth: false,
                        processing: true,
                        serverSide: false,
                        language: {
                            search: '<span>Filter:</span> _INPUT_',
                            lengthMenu: '<span>Show:</span> _MENU_',
                            paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'},
                            emptyTable: 'No data currently available.'
                        },
                        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                    });
                    $('.dataTables_length select').select2({
                        minimumResultsForSearch: Infinity,
                        width: 'auto'
                    });
                    $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');

                    $('.custom_clear_button').removeClass('hide');

                    data = response.data;
                    var j = 1;
                    $.each(data, function (i, item) {
                        var o = $("<option/>", {value: item.id, text: item.internal_part_no});
                        $('#txt_internal_part').append(o);
                        var selected = '<a href="javascript:void(0)" id="select_part_' + item.id + '" data-value="' + item.id + '" class="btn bg-blue select_part" title = "Select">Select</a> ';
                        t.row.add([
                            j,
                            '<b>Search Global Part : </b>' + global_part_no,
                            '<b>Alternate Part No or SKU : </b>' + item.internal_part_no + '<br/><b>Short Description : </b>' + item.description,
                            '<b>Vendor : </b>' + item.v1_name,
                            '<b>Department : </b>' + item.dept_name,
                            selected,
                            ''
                        ]).draw(false);
                        // $('#txt_internal_part').trigger('change');
                        if (item.v1_name == vendor) {
                            $('#hidden_internal_part').val(item.internal_part_no);
                            $('#txt_internal_part option[value="' + item.id + '"]').prop('selected', true);
                            $('.internal_part').removeClass('hide');
                            $('.global_part_search_result_div').removeClass('hide');
                            $('#txt_global_part_no_hidden').val(global_part_no);
                            $('#txt_referred_item_id').val(data[i].id);
                            $('#txt_item_part').val(data[i].part_no);
                            $('#txt_item_description').val(data[i].description);
                            $('#txt_unit_cost').val(0);
                            $('#txt_retail_price').val(0);
                            $('#txt_department').val(data[i].department_id).trigger('change');
                            $('#txt_manufacturer').tagsinput('add', data[i].manufacturer);
                            $('#txt_pref_vendor').val(data[i].preferred_vendor).trigger('change');
                            
                            if (data[i].image != '' && data[i].image != null) {
                                var html = '<a class="img_opn" href="javascript:void(0);" data-imgpath="' + ITEMS_IMAGE_PATH + '/' + data[i].image + '"><img src="' + ITEMS_IMAGE_PATH + '/' + data[i].image + '" style="width: 58px; height: 58px; border-radius: 2px;" alt=""></a>';
                                $('#image_preview_div').html(html);
                                $('#item_image_hidden').val(data[i].image);
                            } else {
                                // var html = '<a class="img_opn" href="javascript:void(0);" data-imgpath="assets/images/key_icon_blue.png"><img src="assets/images/key_icon_blue.png" style="width: 58px; height: 58px; border-radius: 2px;" alt=""></a>';
                                // $('#image_preview_div').html(html);
                            }
                            
                            $('.inventoy_div').removeClass('hide');
                            $('#div_no_parts').addClass('hide');
                        }
                        j++;
                    });

                    if (data.partArr && data.partArr.length > 0) {
                        var part_html = '';
                        $.each(data.partArr, function (p, part) {
                            part_html += '<div class="col-md-3">' +
                                    '<span class="label border-left-primary label-striped mt-5 text-bold compatibility">' + part.company + '&nbsp;' + part.model + '&nbsp;' + part.year + '</span>' +
                                    '</div>';
                        });

                        $("#compatibility_details_div_body").html(part_html);
                        $("#compatibility_details_div").removeClass('hide');
                    }
                } else if (response.code == 404) {
                    $('#txt_global_part_no').prop('disabled', false);
                    $('.error_message').html('No Such Data Found.');
                    $('.inventoy_div').addClass('hide');
                    $('#div_no_parts').removeClass('hide');
                }
            }
        });
    } else {
        $("#txt_global_part_no_error2").html('Please enter valid Global part iber!');
        $('#custom_loading').css('display', 'block');
        return false;
}
}

if (typeof (edit_div) != 'undefined' && edit_div == 1) {
    $('.inventoy_div').removeClass('hide');
}
if (typeof (add_item) != 'undefined' && add_item == 1) {
    $('.inventoy_div').removeClass('hide');
    var global_part_no = $('#txt_global_part_no').val();
    setTimeout(() => {
        get_global_part_details(global_part_no);
    }, 100);
}

$('.custom_clear_button').on('click', function () {
    $(".datatable-responsive").DataTable().clear().destroy();
    $('#txt_global_part_no').val('').prop('disabled', false);
    $('.inventoy_div').addClass('hide');
    $('#div_no_parts').addClass('hide');
    $('.global_part_search_result_div').addClass('hide');
    $('.custom_clear_button').addClass('hide');
    $("#add_item_form").validate().resetForm();

    $("#compatibility_details_div_body").empty();
    $("#compatibility_details_div").addClass('hide');

    setTimeout(function () {
        $('.form-control-feedback').remove();
    }, 10);
});

$('#txt_internal_part').on('change', function () {
    var inernal_id = $(this).val();
    get_part_details(inernal_id);
});

$(document).on('click', '.select_part', function () {
    var inernal_id = $(this).attr('data-value');
    $('#txt_internal_part').val(inernal_id).trigger('change');
})

function get_part_details(inernal_id) {
    $.ajax({
        url: site_url + 'items/get_item_data_ajax_by_part_id',
        type: "POST",
        data: {id: inernal_id, itsfor: 'internal'},
        success: function (response) {
            response = JSON.parse(response);
            if (response.code == 200) {
                data = response.data;
                // console.log(response.data);

                $('.internal_part').removeClass('hide');
                $('#hidden_internal_part').val(data.internal_part_no);
                $('#txt_referred_item_id').val(data.id);
                $('#txt_item_part').val(data.part_no);
                $('#txt_item_description').val(data.description);
                $('#txt_unit_cost').val(0);
                $('#txt_retail_price').val(0);
                $('#txt_department').val(data.department_id).trigger('change');
                $('#txt_pref_vendor').val(data.preferred_vendor).trigger('change');
                if (data.image != '' && data.image != null) {
                    var html = '<img src="' + ITEMS_IMAGE_PATH + '/' + data.image + '" style="width: 58px; height: 58px; border-radius: 2px;" alt="">';
                    $('#item_image_hidden').val(data.image);
                    $('#image_preview_div').html(html);
                }
            }
        }
    });
}

// $('#txt_item_id').on('change', function () {
//     $('.transfer_div').removeClass('hide');
// });
if (document.location.search.length == 0) {
    if (window.location.href.substring(window.location.href.lastIndexOf('/') + 1) == 'add') {
        introJs().start();
    }
}


$('#text_internal_part').on('blur', function () {
    console.log($(this).val());
    $('#hidden_internal_part').val($(this).val());
})
$('.tags-input').tagsinput();

$(document).on('click', '.btn_add_extra_field', function () {
    // var $nonempty = $('.additional_field_txt').filter(function () {
    //     return this.value != '';
    // });
    // if ($nonempty.length != 0) {
        var num = ((parseInt($('.tr_class').last().attr('data-value'))) + 1);
        $(this).parents('td').html('<a href="javascript:void(0)" style="color:#d66464"><i class="icon-minus-circle2 btn_remove_extra_field"></i></a>');
        var html = '<tr class="tr_class" data-value="' + num + '"><td><a href="javascript:void(0)" style="color:#009688"><i class="icon-plus-circle2 btn_add_extra_field"></i></a></td><td><select class="select select-size-sm additional_field_txt txt_make_name" data-placeholder="Select a Company..." id="txt_make_name_' + num + '" name="txt_make_name[]" required></select></td><td><select class="select select-size-sm additional_field_txt txt_model_name" data-placeholder="Select a Model..." id="txt_model_name_' + num + '" name="txt_model_name[]" required></select></td><td><select class="select select-size-sm additional_field_txt txt_year_name" data-placeholder="Select a Year..." id="txt_year_name_' + num + '" name="txt_year_name[]" required></select></td></tr>';
        setTimeout(function () {
            if (typeof (companyArr) !== 'undefined' && companyArr != '') {
                var o = $("<option/>", {value: "", text: "Select a Company..."});
                $('#txt_make_name_' + num).append(o);
                $.each(companyArr, function (key, value) {
                    var o = $("<option/>", {value: value.id, text: value.name});
                    $('#txt_make_name_' + num).append(o);
                });
                $('#txt_make_name_' + num).select2().trigger('change');
                $('#txt_model_name_' + num).select2();
            }
            if (typeof (yearArr) !== 'undefined' && yearArr != '') {
                var o = $("<option/>", {value: "", text: "Select a Year..."});
                $('#txt_year_name_' + num).append(o);
                $.each(yearArr, function (key, value) {
                    var o = $("<option/>", {value: value.id, text: value.name});
                    $('#txt_year_name_' + num).append(o);
                });
                $('#txt_year_name_' + num).select2().trigger('change');
            }
        }, 10);
        $('#tbl_additional_data tbody').append(html);
    // } else {
    //     $('#additional_data_error2').css('padding-left', '45px');
    //     $('#additional_data_error2').html('Please Fill Existing Field.');
    // }
});

$(document).on('change', '.txt_make_name', function () {
    var num = $(this).parents('.tr_class').attr('data-value');
    var selected_val = $(this).val();
    $.ajax({
        url: site_url + 'items/change_make_get_ajax',
        dataType: "json",
        type: "POST",
        data: {id: selected_val},
        success: function (response) {
            $('#txt_model_name_' + num).html(response);
            $('#txt_model_name').select2({containerCssClass: 'select-sm'});
        }
    });
});

$(document).on('click', '.btn_remove_extra_field', function () {
    $(this).parents('tr').remove();
});

//Get Model from Company
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
        }
    });
});

//Get Year from Model
$('#txt_model_name').on('change', function () {
    var make_id = $("#txt_make_name").find('option:selected').val();
    var model_id = $(this).find('option:selected').val();

    var data = {
        make_id: make_id,
        model_id: model_id
    };

    $.ajax({
        url: site_url + 'dashboard/get_transponder_item_years',
        dataType: "json",
        type: "POST",
        data: data,
        success: function (response) {
            $('#txt_year_name').html(response);
            $('#txt_year_name').select2({containerCssClass: 'select-sm'});
        }
    });
});

var validator = $("#search_transponder_form").validate({
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
        txt_make_name: {required: true},
        txt_model_name: {required: true},
        txt_year_name: {required: true}
    },
    submitHandler: function (form) {
        $('#custom_loading').removeClass('hide');
        $('#custom_loading').css('display', 'block');
        var make_name = $('#txt_make_name').val();
        var model_name = $('#txt_model_name').val();
        var year_name = $('#txt_year_name').val();
        setTimeout(function () {
            trans_details(make_name, model_name, year_name);
        }, 200);
    },
    invalidHandler: function () {
        $('.custom_save_button').prop('disabled', false);
    }
});

function trans_details(make_id, model_id, year_id, view) {
    $.ajax({
        url: site_url + 'items/get_transponder_details',
        dataType: "json",
        type: "POST",
        data: {_make_id: make_id, _model_id: model_id, _year_id: year_id},
        success: function (response) {
            if (view == 1) {
                $('#txt_make_name').val(make_id).trigger('change');
                setTimeout(function () {
                    $('#txt_model_name').val(model_id).trigger('change');
                }, 2000);
                setTimeout(function () {
                    $('#txt_year_name').val(year_id).trigger('change');
                    $('#txt_year_name').select2().trigger('change');
                }, 3500)
            }
    
            if (response.table_body2 == '') {
                $('#div_transponder_result, #div_list_of_parts, #div_my_list_of_parts').removeClass('hide');
                $('.no_data_found').removeClass('hide');
                $('.found_data').addClass('hide');

                $('.div_part_list').html(response);
                
                $('.div_part_list').html(response.div_part_list);
                $('.div_my_part_list').html(response.div_my_part_list);
                $('#div_tool_list').removeClass('hide').html(response.div_tool_list);
                $('#no_data_found').html(response.table_body);
                $('.div_recent_search').removeClass('hide');
            } else {
                $('#div_transponder_result, #div_list_of_parts , #div_my_list_of_parts').removeClass('hide');
                $('.no_data_found').addClass('hide');
                $('.found_data').removeClass('hide');
                $('#tbl_dashboard_trans').html(response.table_body);
                $('#tbl_dashboard_trans_2').html(response.table_body2);
                
                $('.div_part_list').html(response);

                $('.div_part_list').html(response.div_part_list);                
                $('.div_my_part_list').html(response.div_my_part_list);
                $('#div_tool_list').removeClass('hide').html(response.div_tool_list);
                $('.div_recent_search').addClass('hide');
            }
            $("#custom_loading").fadeOut(1000);
        }
    });
}

$(document).on('click', '.view-part-image', function () {
    var item_id = $(this).attr('data-id');
    var part_type = $(this).attr('data-parttype');

    $.ajax({
        url: site_url + 'dashboard/get_item_image',
        type: 'POST',
        data: {item_id: item_id, part_type: part_type},
        success: function (data) {
            data = jQuery.parseJSON(data);

            swal({
                title: '',
                imageUrl: data.image_path,
                imageWidth: 400,
                imageHeight: 400,
                imageAlt: 'Custom image',
                animation: true
            });
        }
    });
});


$(document).on('click', '#btn_reset', function () {
    $('#txt_make_name').val('').select2('');
    $('#txt_model_name').val('').select2('');
    $('#txt_year_name').val('').select2('');
    $('.form-control-feedback').remove();
    $('#div_transponder_result').addClass('hide');
    $('#div_list_of_parts').addClass('hide');
    $('#div_tool_list').addClass('hide');
    $('.div_recent_search').removeClass('hide');
    $('#div_my_list_of_parts').addClass('hide');
    $("#search_transponder_form").validate().resetForm();
});

$(document).on('click', '.btn_home_item_view', function () {
    var make_id = $('#txt_make_name').val();
    var model_id = $('#txt_model_name').val();
    var year_id = $('#txt_year_name').val();
    $('#custom_loading').removeClass('hide');
    $('#custom_loading').css('display', 'block');
    $.ajax({
        url: site_url + 'items/get_item_data_ajax_by_id',
        type: "POST",
        data: {id: this.id, make_id: make_id, model_id: model_id, year_id: year_id},
        success: function (response) {
            $('#dash_view_body1').html(response);
            $('#dash_view_modal1').modal('show');
            $("#custom_loading").fadeOut(1000);
        }
    });
});
$(document).on('click', '.btn_non_global_item_view', function () {
    var make_id = $('#txt_make_name').val();
    var model_id = $('#txt_model_name').val();
    var year_id = $('#txt_year_name').val();
    $('#custom_loading').removeClass('hide');
    $('#custom_loading').css('display', 'block');
    $.ajax({
        url: site_url + 'items/get_item_data_ajax_by_id',
        type: "POST",
        data: {id: this.id, make_id: make_id, model_id: model_id, year_id: year_id, part_type: 'non_global'},
        success: function (response) {
            $('#dash_view_body1').html(response);
            $('#dash_view_modal1').modal('show');
            $("#custom_loading").fadeOut(1000);
        }
    });
});
$(document).on('click', '.btn_global_item_view', function () {
    $('#custom_loading').removeClass('hide');
    $('#custom_loading').css('display', 'block');
    $.ajax({
        url: site_url + 'items/get_global_item_data_ajax_by_id',
        type: "POST",
        data: {id: this.id},
        success: function (response) {
            $('#dash_view_body1').html(response);
            $('#dash_view_modal1').modal('show');
            $("#custom_loading").fadeOut(1000);
        }
    });
});

/**
 * @author : Hardik Gadhiya
 * @description Open webcam and scan qr code, if qr code will match with user's items then part will be add in part dropdown.
 * @date: 24-04-2019
 **/
$(document).on('click', "#scan-item-qr-code", function () {
    let scanner = new Instascan.Scanner({video: document.getElementById('webcam-preview')});

    scanner.addListener('scan', function (part_no) {
        scanner.stop();
        try {
            if (part_no) {
                $.ajax({
                    type: 'POST',
                    url: site_url + "get_global_item_data",
                    data: {part_no: part_no},
                    success: function (response) {
                        var value = jQuery.parseJSON(response)[0];
                        var result = value.split(' (');
                        $('#txt_global_part_no').val(value);
                        $('#txt_global_part_no_autoselected').val(1);
                        $('#txt_global_part_no').prop('disabled', true);

                        setTimeout(() => {
                            get_global_part_details(result[0], result[1].slice(0, -1));
                        }, 100);
                    }
                });
            } else {
                swal({
                    title: "Error!",
                    text: 'Part Number is not matching with system.',
                    icon: "error",
                });
            }
            $("#partno_scan_webcam_modal").modal('hide');
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

/**
 * @author : Hardik Gadhiya
 * @description Open webcam and scan qr code, if qr code will match with user's items then part will be add in part dropdown.
 * @date: 24-04-2019
 **/
$(document).on('click', "#search-scan-item-qr-code", function () {
    let scanner = new Instascan.Scanner({video: document.getElementById('webcam-preview')});

    scanner.addListener('scan', function (part_no) {
        scanner.stop();
        try {
            if (part_no) {
                $("#DataTables_Table_0_filter input").val(part_no).trigger('keyup');
            } else {
                swal({
                    title: "Error!",
                    text: 'Part Number is not matching with system.',
                    icon: "error",
                });
            }
            $("#partno_scan_webcam_modal").modal('hide');
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

});

$(document).on('click', '.img_opn', function () {
var imgpath = $(this).attr('data-imgpath');
swal({
        title: '',
        imageUrl: imgpath,
        imageWidth: 300,
        imageHeight: 300,
        imageAlt: 'Item image',
        animation: true
    });
});

/**********************************************************
 Confirm Alert
 ***********************************************************/
function confirm_alert(e) {
    swal({
        title: "Are you sure?",
        text: "Item will move under the trash.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#FF7043",
        confirmButtonText: "Yes, delete it!"
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
