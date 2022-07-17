<?php $this->session->set_userdata('referred_from', current_url()); ?>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/ui/moment/moment.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/pickers/pickadate/picker.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/pickers/pickadate/picker.date.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/pickers/pickadate/legacy.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/inputs/formatter.min.js') ?>"></script>
<link type="text/css" href="<?= base_url('assets/css/customers.css') ?>" rel="stylesheet" />

<!-- <i class="icon-cash3"></i> -->
<!-- <i class="icon-file-text2"></i>  -->
<!-- <i class="icon-car"></i> -->

<?php
if (checkUserLogin('R') != 4) {
    $controller = $this->router->fetch_class();
    if (!empty(MY_Controller::$access_method) && array_key_exists('edit', MY_Controller::$access_method[$controller])) {
        $edit = 1;
    }
} 
?>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('customers') ?>">Customers</a></li>
            <li class="active">View</li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>

            <div class="panel panel-flat">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-6">
                            <div class="ark-container">
                                <h2 class="m-t-0">
                                    <?= (!empty($result['display_name_as'])) ? $result['display_name_as'] : '---' ?>
                                    <!-- <?= (!empty($result['first_name']) && !empty($result['last_name'])) ? $result['first_name'] . ' ' . $result['last_name'] : '---' ?> -->
                                    &nbsp;
                                    <a title="<?= $result['phone'] ?>"><i class="icon-phone2 font-blue"></i></a>
                                    <!-- <a href = "mailto: <?= $result['email'] ?>" title="<?= $result['email'] ?>"><i class="icon-envelop font-blue"></i></a> -->
                                </h2>
                                <?php foreach ($emailArr as $key => $value) { ?>
                                    <a href = "mailto: <?= $value['customer_email'] ?>" title="<?= $value['customer_email'] ?>">
                                        <?php echo $value['customer_email'].'<br>'; ?>
                                    </a>
                                <?php } ?>
                                <br/>
                                <ul class="ark-lists">
                                    <?php if (!empty($result['company'])) { ?>
                                        <li><?= $result['company'] ?></li>
                                    <?php } ?>
                                    <li>
                                        <?= (!empty($result['billing_address'])) ? $result['billing_address'] : '' ?>
                                        <?= (!empty($result['billing_address_street'])) ? ', ' . $result['billing_address_street'] : '' ?>
                                        <?= (!empty($result['billing_address_city'])) ? ', ' . $result['billing_address_city'] : '' ?>
                                        <?= (!empty($result['billing_address_state'])) ? ', ' . $result['billing_address_state'] : '' ?>
                                        <?= (!empty($result['billing_address_zip'])) ? ' ' . $result['billing_address_zip'] : '' ?>
                                    </li>
                                </ul>
                                <button type="button" id="a_e_n_b" data-customerid="<?= $result['id'] ?>" class="btn btn-primary"><i class="icon-plus3"></i>&nbsp;Add Note</button>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-6 ark-container">
                            <?php if(isset($edit) && $edit == 1) { ?>
                                <div class="col-sm-12">
                                    <a href="<?= site_url('customers/edit/' . base64_encode($result['id'])) ?>" class="btn btn-default pull-right customer-editbtn" id="btn_reset"><i class="icon-pencil"></i>&nbsp; Edit</a>
                                </div> 
                            <?php } ?>
                            <div class="col-sm-12 text-right set-relative m-t-20">
                                <div class="row">
                                    <div class="col-sm-12 pr-0">
                                        <a href="<?php echo site_url('estimates/add'); ?>" class="btn btn-primary mb-3"><i class="icon-file-text2"></i> &nbsp; <span> Estimate</span></a>
                                        <a href="<?php echo site_url('invoices/add'); ?>" class="btn btn-primary mb-3"><i class="icon-cash3"></i> &nbsp; <span> Invoice</span></a>
                                        <a href="<?php echo site_url('orders/add'); ?>" class="btn btn-primary mb-3"> <i class="icon-car"></i>&nbsp; <span> Order</span></a>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-sm-12 text-right set-relative m-t-20">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Add New<span class="caret"></span></button>
                                <ul class="dropdown-menu nohover">
                                    <li class=""><a href="<?php echo site_url('invoices/add'); ?>"> <span> Invoices</span></a></li>
                                    <li class=""><a href="<?php echo site_url('estimates/add'); ?>"><span> Estimates</span></a></li>
                                    <li class=""><a href="<?php echo site_url('orders/add'); ?>"> <span> Orders</span></a></li>
                                </ul>
                            </div> -->

                        <div class="col-sm-12 pl-0 pr-0">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="bs-callout bs-callout-info">
                                        <h4 id="invoice-open-amount">$0.00</h4>
                                        <!-- <h4 id="invoice-open-amount" class="openamt">$0.00</h4> -->
                                        <h6>OPEN</h6>
                                    </div>
                                </div>
                               <!--  <div>
                                    <select name="yeardue" id="yeardue">
                                        <option value="">All Year</option>
                                        <?php 
                                        for($i =0; $i <= 5 ;$i++)
                                        {
                                            $year = date('Y') - 5 + $i;
                                            echo '<option value='.$year.'>'.$year.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div> -->
                                <div class="col-sm-6">
                                    <div class="bs-callout bs-callout-warning d-flex">
                                        <div class="d-inline-block w-100 text-xs-center">
                                            <h4 class="pl-xs-10" id="dueamount">$<?= $due_amount ?></h4>
                                            <!-- DUE -->
                                            <h6 class="due pl-xs-10"><?php echo date('Y');?></h6>
                                        </div>
                                        <div class="d-inline-block icon-container icon-default show-cal1 btn">
                                            <i class="fas fa-calendar-alt fa-2x diff"></i>
                                            <div class="open-div orange" style="display: none;">
                                                <ul name="yeardue" id="yeardue">
                                                    <li>All Year</li>
                                                    <?php 
                                                        for($i =0; $i <= 5 ;$i++)
                                                        {
                                                            $year = date('Y') - 5 + $i;
                                                            echo '<li value='.$year.'>'.$year.'</li>';
                                                        }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="tabbable tab-content-bordered">
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="active"><a href="#bordered-tab1" data-toggle="tab"><b>Transactions List</b></a></li>
                        <li><a href="#bordered-tab4" data-toggle="tab"><b>Orders</b></a></li>
                        <li><a href="#bordered-tab2" data-toggle="tab"><b>Customer Details</b></a></li>
                        <li><a href="#bordered-tab3" data-toggle="tab"><b>Notes</b></a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane has-padding active" id="bordered-tab1">
                            <div class="row">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"><span class="text-semibold">Customer Transactions</span></h6>
                                    </div>

                                    <table class="table datatable-responsive-customers-invoices">
                                        <thead>
                                            <tr>
                                                <th style="width:5%">#</th>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>No</th>
                                                <th>Due Date</th>
                                                <th>Balance</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th style="width:18%">Action</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th>Total</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane has-padding" id="bordered-tab2">
                            <div class="row">
                                <div class="col-lg-12">
                                    <table class="table table-hover table-condensed">
                                        <tbody>
                                            <tr class="heading_tr">
                                                <th colspan="2"><strong>Customer Informations</strong></th>
                                            </tr>
                                            <tr>
                                                <th>Disaplay Name As</th>
                                                <td><?= (!empty($result['display_name_as'])) ? $result['display_name_as'] : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th>Customer Name</th>
                                                <td><?= (!empty($result['first_name']) && !empty($result['last_name'])) ? $result['first_name'] . ' ' . $result['last_name'] : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th>Company</th>
                                                <td><?= (!empty($result['company'])) ? $result['company'] : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th>Phone</th>
                                                <td><?= (!empty($result['phone'])) ? $result['phone'] : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th>Mobile</th>
                                                <td><?= (!empty($result['mobile'])) ? $result['mobile'] : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th>Fax</th>
                                                <td><?= (!empty($result['fax'])) ? $result['fax'] : '---' ?></td>
                                            </tr>
                                            <!-- <tr>
                                                <th>Email</th>
                                                <td><?= (!empty($result['email'])) ? $result['email'] : '---' ?></td>
                                            </tr> -->
                                            <?php if($emailArr != "" && !empty($emailArr)) { ?>
                                                <tr class="heading_tr">
                                                    <th colspan="2"><strong>Email</strong></th>
                                                </tr>
                                                <?php foreach ($emailArr as $key => $value) { ?>
                                                    <tr>
                                                        <td colspan="2"><?php echo $value['customer_email'];?></td>
                                                    </tr>
                                                <?php 
                                                    } 
                                                } 
                                            ?>
                                            <tr class="heading_tr">
                                                <th colspan="2"><strong>Billing Address</strong></th>
                                            </tr>
                                            <tr>
                                                <th>Address</th>
                                                <td><?= (!empty($result['billing_address'])) ? $result['billing_address'] : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th>City</th>
                                                <td><?= (!empty($result['billing_address_city'])) ? $result['billing_address_city'] : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th>State</th>
                                                <td><?= (!empty($result['billing_address_state'])) ? $result['billing_address_state'] : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th>Zip Code</th>
                                                <td><?= (!empty($result['billing_address_zip'])) ? $result['billing_address_zip'] : '---' ?></td>
                                            </tr>
                                            <tr class="heading_tr">
                                                <th colspan="2"><strong>Shipping Address</strong></th>
                                            </tr>
                                            <tr>
                                                <th>Address</th>
                                                <td><?= (!empty($result['shipping_address'])) ? $result['shipping_address'] : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th>City</th>
                                                <td><?= (!empty($result['shipping_address_city'])) ? $result['shipping_address_city'] : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th>State</th>
                                                <td><?= (!empty($result['shipping_address_state'])) ? $result['shipping_address_state'] : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th>Zip Code</th>
                                                <td><?= (!empty($result['shipping_address_zip'])) ? $result['shipping_address_zip'] : '---' ?></td>
                                            </tr>
                                            <tr>
                                                <th>Created Date</th>
                                                <td><?= (!empty($result['created_date'])) ? $result['created_date'] : '---' ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane has-padding" id="bordered-tab3">
                            <div class="row">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"><span class="text-semibold">Customer Notes</span></h6>
                                    </div>

                                    <table class="table" id="customers-notes">
                                        <thead>
                                            <tr>
                                                <th>Note</th>
                                                <th>Created At</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane has-padding" id="bordered-tab4">
                            <div class="row">
                                <div class="panel panel-flat">
                                    <div class="panel-heading">
                                        <h6 class="panel-title"><span class="text-semibold">Order List</span></h6>
                                    </div>

                                    <table class="table datatable-responsive-control-right-order">
                                        <thead>
                                            <tr>
                                                <th style="width:5%">#</th>
                                                <th>Date</th>
                                                <th>Order No.</th>
                                                <th>Customer Name</th>
                                                <th>Vendor Part #</th>
                                                <th>Status</th>
                                                <th>Total Receipt Amount</th>
                                                <!-- <th>Change Status</th> -->
                                                <th>Action</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
</div>
<?php $this->load->view('Templates/footer.php'); ?>
</div>

<!-- View modal -->
<div id="add_edit_note_modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center" id="note_header_title">Add Note</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar" id="add_edit_note_body">
                <form method="post" id="add_edit_note_form">
                    <div class="form-group">
                        <label for="notes" class="col-form-label">Notes:</label>
                        <textarea class="form-control" id="notes" name="notes" required></textarea>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div> -->
        </div>
    </div>
</div>

<!-- View order modal -->
<div id="order_view_modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">Order Details</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar" id="order_view_body" style="height: 500px;overflow: hidden;overflow-y: scroll;"></div>
        </div>
    </div>
</div>

<style type="text/css">
    .heading_tr{
        background-color: #33a9f5;
        color: white;
    }

    .heading_tr:hover{
        background-color: white;
        color: #33a9f5;
    }
</style>

<script>
    var date_format = '<?php echo (isset($format) && !empty($format)) ? $format['name'] : 'Y/m/d' ?>';
    var customer_id = '<?= $result['id']; ?>';
    var cname = '<?php echo $result['display_name_as']; ?>';
    // var cname = '<?php echo $result['first_name'].' '. $result['last_name']?>';
    var uri_edit = '<?php if($this->uri->segment(2) == 'edit') { echo '1'; } else { echo ''; } ?>';
    
</script>
<script type="text/javascript" src="assets/js/custom_pages/front/customers.js?version='<?php echo time(); ?>'"></script>

<!--  **********************************************************
  Intitalize Data Table For Order
 ********************************************************** -->
<script type="text/javascript">
    $(function () {
    bind1();
});

$('#filter_status').on('change', function () {
    $(".datatable-responsive-control-right-order").dataTable().fnDestroy();
    bind1();
});

function bind1() {
    
    var table = $('.datatable-responsive-control-right-order').dataTable({
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
            url: site_url + 'orders/get_customer_order_data/'+cname,
            data: {
                status_id: $('#filter_status').find('option:selected').val(),
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
                sortable: false,
            },
            {
                data: "ordered_date",
                visible: true,
            },
            {
                data: "order_no",
                visible: true,
            },
            {
                data: "customer_name",
                visible: true,
            },
            {
                data: "vendor_part_no",
                visible: true,
            },
            {
                data: "status_name",
                visible: true,
                render: function (data, type, full, meta) {
                    action = '<span class="label label-' + full.status_color + '">' + full.status_name + '</span>';
                    return action;
                }
            },
            {
                data: "total_receipt_amount",
                visible: true,
                render: function (data, type, full, meta) {
                    return parseFloat(full.total_receipt_amount).toFixed(2);
                }

            },
            // {
            //     data: "change_status",
            //     visible: true,
            //     sortable: false,
            //     render: function (data, type, full, meta) {
            //         return changeOrderStatus(full.status_id, full.id);
            //     }
            // },
            {
                data: "action",
                render: function (data, type, full, meta) {
                    action = '';
                    action += '<a href="javascript:void(0);" class="btn btn-xs custom_dt_action_button order_view_btn" title="View" id="\'' + btoa(full.id) + '\'">View</a>';
                    action += '&nbsp;&nbsp;<a href="' + site_url + 'orders/edit/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs"title="Edit">Edit</a>';
                    // action += '&nbsp;&nbsp;<a href="' + site_url + 'orders/delete/' + btoa(full.id) + '" class="btn custom_dt_action_button btn-xs" onclick="return confirm_alert(this)" title="Delete">Delete</a>';
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

            $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to filter...');
            // if (add == 1) {
                // var add_button = '<div class="text-right"><a href="' + site_url + 'orders/add" class="btn bg-teal-400 btn-labeled custom_add_button"><b><i class="icon-plus-circle2"></i></b> Add Order</a></div>';
            //     $('.datatable-header').append(add_button);
            // }

            $('.dataTables_length select').select2({
                minimumResultsForSearch: Infinity,
                width: 'auto'
            });
        },
        "fnDrawCallback": function () {
            var info = document.querySelectorAll('.switchery-info');
            $(info).each(function () {
                var switchery = new Switchery(this, {color: '#95e0eb'});
            });
        }
    });
}
// ================================================================================
    
$(document).ready(function(){

    // **********************************************************
    //  Intitalize Data Table For Order
    // **********************************************************

    // Setup - add a text input to each footer cell
    $('.datatable-responsive-control-right-order tfoot th').each(function () {
        var title = $(this).text();
        if ($(this).hasClass('slct')) {
            $(this).html('<input type="text" placeholder="Search ' + title + '" />');
        }
    });

    $(document).on('change', '.select_change_order_status', function () {
        $('#custom_loading').removeClass('hide');
        $('#custom_loading').css('display', 'block');

        var status_id = $(this).find('option:selected').val();
        var order_id = $(this).find('option:selected').attr('data-id');

        var data = {
            status_id: status_id,
            order_id: order_id
        };

        $.ajax({
            url: site_url + 'orders/change_order_status',
            type: "POST",
            data: data,
            success: function (response) {
                $(".datatable-responsive-control-right-order").dataTable().fnDestroy();
                bind1();

                $('#custom_loading').removeClass('hide');
                $('#custom_loading').css('display', 'none');
            }
        });
    });
    
    /**********************************************************
     Item View Popup
     ***********************************************************/
    $(document).on('click', '.order_view_btn', function () {
        $('#custom_loading').removeClass('hide');
        $('#custom_loading').css('display', 'block');
        $.ajax({
            url: site_url + 'orders/get_order_data_ajax_by_id',
            type: "POST",
            data: {id: this.id},
            success: function (response) {
                $('#custom_loading').removeClass('hide');
                $('#custom_loading').css('display', 'none');
                $('#order_view_body').html(response);
                $('#order_view_modal').modal('show');
            }
        });
    });

    $('.show-cal1').click(function() {
        $('.open-div').toggle();
    });

    $('#yeardue li').click(function(){
        // alert('ok');
        var customer_id = '<?= $result['id']; ?>';

        var year = $(this).attr("value");

        // if(typeof(year) === "undefined"){
        //     new_yer = "DUE";                
        // }else{
        //     new_yer = year;
        // }
        sales_by_year(year);
    });

    var date = new Date();
    var year = date.getFullYear(); 
    // alert(year);

    sales_by_year(year);
});

function sales_by_year(year){   
// alert(year);
    $.ajax({
        url:'customers/ajax_yeardue/'+customer_id+'',
        method:'POST',
        data:{ 'year' : year },
        success: function(data){
            data = JSON.parse(data);
            finaldue = '$'+''+data; 
            // alert(data);
            $('#dueamount').html(finaldue);
            $('.due').html(year);

        },
        error:function(error){
            alert('error');
        }
    });
}

        
</script>