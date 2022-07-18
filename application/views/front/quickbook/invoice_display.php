<?php $this->session->set_userdata('referred_from', current_url()); ?>
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
            <li class="active">Invoices</li>
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
                            <th style="width:5%">#</th>
                            <th>Date</th>
                            <th>Invoice No.</th>
                            <th>Customer Name</th>
                            <th>Representative</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th style="width:18%">Action</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
</div>
<script>
    var date_format = '<?php echo (isset($format) && !empty($format)) ? $format['name'] : 'Y/m/d' ?>';
    var signature_attachment = '';

    var authUrl = "<?= (!empty($_SESSION['authUrl'])) ? $_SESSION['authUrl'] : ""; ?>";
</script>
<script type="text/javascript" src="assets/js/custom_pages/front/quickbook_invoice.js"></script>
<style>
    .dataTables_length{ float:left; }
    .modal-open{ padding-right:3px !important; }
</style>