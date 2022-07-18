<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script> -->
<link href="assets/css/ladda-themeless.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script type="text/javascript" src="assets/js/plugins/forms/tags/tagsinput.min.js"></script>
<script src="assets/js/intro.js"></script>
<script type="text/javascript" src="assets/js/jquery_ui.js"></script>
<script type="text/javascript" src="assets/js/spin.min.js"></script>
<script type="text/javascript" src="assets/js/ladda.min.js"></script>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Items</li>
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
                            <th>Image</th>
                            <th>Parts</th>
                            <th>Description</th>
                            <th>Vendor</th>
                            <th>Price</th>
                            <th>Availability</th>
                            <th>Department</th>
                            <th>Action</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th class='slct'>Image</th>
                            <th class='slct'>Parts</th>
                            <th class='slct'>Description</th>
                            <th class='slct'>Vendor</th>
                            <th class='slct'>Price</th>
                            <th class='slct'>Availability</th>
                            <th class='slct'>Department</th>
                            <th class='slct'></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
</div>
<!-- View modal -->
<div id="items_view_modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">Items Details</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar" id="items_view_body" style="height: 500px;overflow: hidden;overflow-y: scroll;"></div>
        </div>
    </div>
</div>

<!-- View modal -->
<div id="partno_scan_webcam_modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">Scan QR Code</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar">
                <video id="webcam-preview" width="100%"></video>
            </div>
        </div>
    </div>
</div>
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script type="text/javascript" src="assets/js/custom_pages/front/quickbook_unsync_items.js"></script>
<style>
    .dataTables_length{ float:left; }
    .modal-open{ padding-right:3px !important; }
    .table > tfoot > tr > th input[type="text"]{padding: 0 10px;}

    .location_main .col-lg-4.td_location {padding: 0;}

</style>