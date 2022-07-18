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
            <div class="panel panel-flat position-relative">
                <div class="datatable-aligndiv">
                <!-- <div class="text-center"> -->
                    <a id="sync_all" class="btn btn-primary btn-labeled btn-lg ladda-button" data-style="expand-right" data-size="l">
                    <b>
                        <i class="icon-plus-circle2"></i>
                    </b>
                    <span class="ladda-label">Sync all</span></a>
                <!-- </div> -->
                <div class="get_item_type">
                    <select name="get_type" id="get_type">
                        <option value="0">All Items</option>
                        <option value="1">Out of sync</option>
                    </select>
                </div>
             </div>
                <table class="table datatable-responsive-control-right">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th>Parts</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>QtyOnHand ARK</th>
                            <th>QtyOnHand QBO</th>
                            <th tyle="width:18%">Action</th>
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
<script type="text/javascript" src="assets/js/custom_pages/front/quickbook_item.js"></script>
<style>
    .dataTables_wrapper{box-shadow: none;}
    .dataTables_length{ float:left; }
    .modal-open{ padding-right:3px !important; }
    .table > tfoot > tr > th input[type="text"]{padding: 0 10px;}

    .location_main .col-lg-4.td_location {padding: 0;}

    .ladda-button{padding: 7px 15px;margin-right: 10px;}

    .ladda-button > b{padding: 10px !important;}

    .datatable-aligndiv{
        display: flex;
        align-items: center;
        float: right;
        position: relative;
        top: 5rem;
        height: 0;
        right: 20px;
    }

    @media screen and (max-width:1199px){
        .datatable-aligndiv{
            display: flex;
            align-items: center;
            float: right;
            position: relative;
            top: 15px;
            right: 20px;
            flex-wrap: wrap;
            height: auto;
        }
    }

</style>