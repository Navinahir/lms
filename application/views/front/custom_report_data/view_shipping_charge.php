<?php $this->session->set_userdata('referred_from', current_url()); ?>
<link href="assets/css/fakeloader.css"  rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.date.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/legacy.js"></script>
<script type="text/javascript" src="assets/js/plugins/forms/inputs/formatter.min.js"></script>
<script type="text/javascript" src="assets/js/fakeLoader.js"></script>
<script type="text/javascript" src="assets/js/fakeLoader.min.js"></script>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Gross Sales</li>
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
                <div class="panel-heading">
                    <h5 class="panel-title">Shipping Charges Detail</h5>
                    <!-- <div class="heading-elements">
                        <ul class="icons-list">
                            <li><a data-action="collapse"></a></li>
                        </ul>
                    </div> -->
                </div>
                <table class="table datatable-responsive-control-right">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th>Date</th>
                            <th>Invoice No.</th>
                            <th>Shipping Charges</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="3">Total Shipping Charges</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
</div>
<div id="fakeLoader" class="loading"></div>
<?php
if (isset($get) && !empty($get)):
    echo "<script>var get = " . json_encode($get) . "</script>";
endif;
?>

<script>
    var from_date = '<?php echo $from_date; ?>';
    var to_date = '<?php echo $to_date; ?>';
    var date_format = '<?php echo (isset($format) && !empty($format)) ? $format['name'] : 'Y/m/d' ?>';
    var signature_attachment = '';

    var authUrl = "<?= (!empty($_SESSION['authUrl'])) ? $_SESSION['authUrl'] : ""; ?>";
    var path = "<?php echo site_url('invoices/add_to_quickbook/')?>";
    var invoice_notification = "<?= ($this->session->userdata('invoice_notification') ? $this->session->userdata('invoice_notification') : '0')  ?>"; 
    var item_notification = "<?= ($this->session->userdata('item_notification') ? $this->session->userdata('item_notification') : '0')  ?>";
</script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>
<script type="text/javascript" src="assets/js/custom_pages/front/shipping_charge.js?version='<?php echo time();?>'"></script>
<style>
    .dataTables_length{
        margin-right: 20px;
        margin-left: 0px;
    }
    .dataTables_filter{
        margin-left: 0px;
    }
    .dataTables_length{ float:left; }
    .modal-open{ padding-right:3px !important; }
</style>