/**********************************************************
 Intitalize Data Table
 ***********************************************************/

if (typeof (get) !== "undefined" && get != "") {
    var start = moment(get.from_date);
    ;
    var end = moment(get.to_date);
} else {
    var start = moment().subtract(1, 'days');
    var end = moment();
}

setTimeout(function () {
    var date = start.format('YYYY-MM-DD') + ':=:' + end.format('YYYY-MM-DD');
    var user = $('#user').val();

    setDataTable(date, user);
}, 500);

function setDataTable(date, user) {
    $(document).find('.datatable-responsive-control-right').DataTable().clear().draw();
    $(document).find('.datatable-responsive-control-right').DataTable().destroy();
    $('.datatable-header').append('');
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
        // dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        dom: 'lBfrtip',
        buttons: [
            'csv',
        ],
        lengthMenu: [[10, 20, 30, 50, -1], [10, 20, 30, 50, 'All']],
        order: [[0, "desc"]],
        ajax: site_url + 'reports/get_inventory_investment_items_data',
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
                        result = '<a href="uploads/items/no_image.jpg" data-popup="lightbox"><img src="uploads/items/no_image.jpg" style="height:25px; width:25px;"></a>';
                    } 
                    else if (full.image != null) 
                    {
                        result = '<a href="uploads/items/'+full.image+'" data-popup="lightbox"><img src="uploads/items/'+full.image+'" style="height:25px; width:25px;"></a>';
                    } else {
                        result = '<a href="uploads/items/'+full.dimage+'" data-popup="lightbox"><img src="uploads/items/'+full.dimage+'" style="height:25px; width:25px;"></a>';                 
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
                },
                sortable: false,
            },
            {
                data: "unit_cost",
                visible: true,
                className: 'inventory_price',
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
                data: "inventory_investment",
                visible: true,
                className: 'inventory_sum',
                render: function (data, type, full, meta) {
                    if(full.inventory_investment > 0)
                    {
                        var inv_val = parseFloat(full.inventory_investment).toFixed(2);
                        x=inv_val.toString();
                        var afterPoint = '';
                        if(x.indexOf('.') > 0)
                           afterPoint = x.substring(x.indexOf('.'),x.length);
                        x = Math.floor(x);
                        x=x.toString();
                        var lastThree = x.substring(x.length-3);
                        var otherNumbers = x.substring(0,x.length-3);
                        if(otherNumbers != '')
                            lastThree = ',' + lastThree;
                        var inventory_investment = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree + afterPoint;
                    } else {
                        var inventory_investment = 0.00;
                    }

                    return inventory_investment;
                },
                sortable: false,
            },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    action = '';
                    action += '<a href="javascript:void(0);" class="btn btn-xs custom_dt_action_button item_view_btn" title="View" id="\'' + btoa(full.id) + '\'">View</a>';
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
            api.columns('.inventory_sum', {page: 'current'}).every(function () {
                var inventory_sum = this
                        .data()
                        .reduce(function (a, b) {
                            var x = parseFloat(a) || 0;
                            var y = parseFloat(b) || 0;
                            return x + y;
                        }, 0);
                $(this.footer()).html(parseFloat(inventory_sum).toFixed(2));
            });
            var api = this.api();
            api.columns('.inventory_price', {page: 'current'}).every(function () {
                var inventory_price = this
                        .data()
                        .reduce(function (a, b) {
                            var x = parseFloat(a) || 0;
                            var y = parseFloat(b) || 0;
                            return x + y;
                        }, 0);
                $(this.footer()).html(parseFloat(inventory_price).toFixed(2));
            });
        }
    });

    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
    
    $('.buttons-csv').addClass('btn bg-teal-400 btn-labeled mb-3').empty().html('<b><i class="icon-download"></i></b> Download Reports');
    
    var pdf_button = '&nbsp;<button type="button" class="btn bg-teal-400 btn-labeled pdf_button mb-3 ml-2"><b><i class="icon-printer"></i></b> Print</button>';
    $('.dt-buttons').append(pdf_button);
}

$(document).on('click', '.pdf_button', function (e) {
    $("#fakeLoader").attr('style', '').html('');
    $(document).find("#fakeLoader").fakeLoader({
        timeToHide: 30000,
        spinner: "spinner5",
        bgColor: "",
    });
    var date = $('#date_range').val();
    var user = $('#user').val();
    var url = site_url + 'reports/print_inventory_investment_data';
    if (user != null) {
        url = updateQueryStringParameter(url, "user", user);
    }
    window.location.href = encodeURI(url);
});
//----------------------------- v! Change URL ---------------------------------------------

function updateQueryStringParameter(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    } else {
        return uri + separator + key + "=" + value;
    }
}
//-----------------------------------------------------------------------------------------

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
