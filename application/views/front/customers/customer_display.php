<script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.date.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/legacy.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/inputs/formatter.min.js"></script>
<link type="text/css" href="<?= base_url('assets/css/customers.css') ?>" rel="stylesheet" />

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Customers</li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>
<?php
if (checkUserLogin('R') != 4) {
    $controller = $this->router->fetch_class();

    if (!empty(MY_Controller::$access_method) && array_key_exists('add', MY_Controller::$access_method[$controller])) {
        $add = 1;
    }
    if (!empty(MY_Controller::$access_method) && array_key_exists('edit', MY_Controller::$access_method[$controller])) {
        $edit = 1;
    }
    if (!empty(MY_Controller::$access_method) && array_key_exists('delete', MY_Controller::$access_method[$controller])) {
        $delete = 1;
    }
    ?>
    <script>
        add = '<?php echo (isset($add) && $add == 1) ? $add : 0; ?>';
        edit = '<?php echo (isset($edit) && $edit == 1) ? $edit : 0; ?>';
        dlt = '<?php echo (isset($delete) && $delete == 1) ? $delete : 0; ?>';
    </script>
<?php } ?>
<div class="content">
    <div class="row">
        <div class="col-sm-4 col-md-4 col-lg-4 dash-customer-card-main">
            <!-- <div>
                <select name="yearestimate" id="yearestimate">
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
            <div class="card bg-teal-400">
                <div class="card-body d-flex dash-customer-card">
                    <div class="d-inline-block">
                        <div class="d-flex">
                            <!-- <h3 class="font-weight-semibold mb-0 estimateamount">$<?= (!empty($total_estimates) && !empty($total_estimates['total_estimates_amount'])) ? number_format($total_estimates['total_estimates_amount'],2) : 0.00 ?></h3> -->

                            <h3 class="font-weight-semibold mb-0 estimateamount totalrevenue">$0.00</h3>
                        </div>

                        <div class="card-label">
                            <!-- <span class="finaltotalestimate estimate"><?= (!empty($total_estimates) && !empty($total_estimates['total_estimates'])) ? $total_estimates['total_estimates'] : 0 ?> Estimates</span> -->
                            <span class="finaltotalestimate estimate"><?php echo date('Y');?></span>
                        </div>
                    </div>
                    <div class="d-inline-block icon-container icon-default show-cal btn">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                        <div class="open-div" style="display: none;">
                            <ul name="yearestimate" id="yearestimate">
                                <!-- <li>All Year</li> -->
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
        <div class="col-sm-4 col-md-4 col-lg-4 dash-customer-card-main">
            <div class="card bg-orange-700">
                <div class="card-body d-flex dash-customer-card">
                    <div class="d-inline-block">
                        <div class="d-flex">
                            <!-- <h3 class="font-weight-semibold mb-0 totaldue monthrevenue">$<?= (!empty($due_amount) && !empty($due_amount['due_payment'])) ? number_format($due_amount['due_payment'],2) : 0.00 ?></h3> -->
                            <h3 class="font-weight-semibold mb-0 totaldue monthrevenue">$0.00   </h3>
                        </div>
                        <div class="card-label">
                           <!--  <span class="totaloverdue overdue "><?= (!empty($due_amount) && !empty($due_amount['total_due_invoices'])) ? $due_amount['total_due_invoices'] : 0 ?> Overdue</span> -->
                            <span class="totaloverdue overdue "><?php echo date('F');?></span>
                        </div>
                    </div>
                    <div class="d-inline-block icon-container icon-default show-cal-month btn">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                        <div class="open-div-month orange" style="display: none;">
                            <ul name="monthoverdue" id="monthoverdue">
                                <!-- <li value="">All Month</li> -->
                                <li value="01">January</li>
                                <li value="02">February</li>
                                <li value="03">March</li>
                                <li value="04">April</li>
                                <li value="05">May</li>
                                <li value="06">June</li>
                                <li value="07">July</li>
                                <li value="08">August</li>
                                <li value="09">September</li>
                                <li value="10">October</li>
                                <li value="11">November</li>
                                <li value="12">December</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="<?php echo site_url('customers/openinvoiceslist'); ?>">
            <div class="col-sm-4 col-md-4 col-lg-4 dash-customer-card-main">
                <div class="card bg-success-400">
                    <div class="card-body dash-customer-card">
                        <div class="d-flex">
                            <h3 class="font-weight-semibold mb-0">$<?= (!empty($total_invoices) && !empty($total_invoices['total_invoices_amount'])) ? number_format($total_invoices['total_invoices_amount'],2) : 0.00 ?></h3>
                        </div>
                        <div class="card-label">
                            <span><?= (!empty($total_invoices) && !empty($total_invoices['total_invoices'])) ? $total_invoices['total_invoices'] : 0 ?> open invoices</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>
            <div class="panel panel-flat">
                <table class="table datatable-responsive-control-right">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Company Name</th>
                            <th>Phone</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Fax</th>
                            <th>Created At</th>
                            <th>Action</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
</div>
<!-- View modal -->
<div id="customer_view_modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">Items Details</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar" id="customer_view_body" style="height: 500px;overflow: hidden;overflow-y: scroll;"></div>
        </div>
    </div>
</div>

<script>
    var date_format = '<?php echo (isset($format) && !empty($format)) ? $format['name'] : 'Y/m/d' ?>';
    var customer_id = '';
    var uri_edit = '<?php if($this->uri->segment(2) == 'edit') { echo '1'; } else { echo ''; } ?>';
    
    function changeOrderStatus(selected_status_id, order_id) {
        var select_option = "";
        html = '<select data-placeholder="-Select Status-" class="form-control select_change_order_status">';
        <?php
        if (!empty($statuses)) {
            foreach ($statuses as $order_status) {
                ?>
                var status_id = "<?= $order_status['id'] ?>";

                if (status_id == selected_status_id) {
                    select_option = 'selected';
                } else {
                    select_option = '';
                }

                html += '<option data-id="' + order_id + '" value="' + status_id + '" ' + select_option + '><?= $order_status['status_name'] ?></option>';
                <?php
            }
        }
        ?>
        html += '</select>';

        return html;
    }
</script>
<script type="text/javascript" src="assets/js/custom_pages/front/customers.js?version='<?php echo time();?>'"></script>
<style>
    .dataTables_length{ float:left; }
    .modal-open{ padding-right:3px !important; }
    .status_filter_div {
        margin-top: 10px;
    }
    .label-orange {
        border-color: orange;
        background-color: orange;
    }
</style>