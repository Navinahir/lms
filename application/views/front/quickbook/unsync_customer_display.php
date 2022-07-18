<link type="text/css" href="<?= base_url('assets/css/customers.css') ?>" rel="stylesheet" />
<link href="assets/css/ladda-themeless.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.date.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/legacy.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/inputs/formatter.min.js"></script>
<script type="text/javascript" src="assets/js/spin.min.js"></script>
<script type="text/javascript" src="assets/js/ladda.min.js"></script>

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
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>
            <div class="panel panel-flat">
                <input type="hidden" id="unsync_id" value="<?php if($unsync_id != '') { echo $unsync_id; }?>">
                <table class="table datatable-responsive-control-right">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Company Name</th>
                            <th>Display Name As</th>
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
<script type="text/javascript" src="assets/js/custom_pages/front/quickbook_unsync_customer.js"></script>
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
