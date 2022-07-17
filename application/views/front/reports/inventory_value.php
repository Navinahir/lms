<link href="assets/css/fakeloader.css"  rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="assets/js/plugins/notifications/jgrowl.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/daterangepicker.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/anytime.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.js"></script>
<script type="text/javascript" src="assets/js/plugins/visualization/echarts/echarts.js"></script>
<script type="text/javascript" src="assets/js/fakeLoader.js"></script>
<script type="text/javascript" src="assets/js/fakeLoader.min.js"></script>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Reports</li>
            <li class="active">Inventory Value</li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>
<div class="content">
    <?php $this->load->view('alert_view'); ?>
    <div class="row mt-20">
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Inventory Value Details</h5>
                    </div>
                    <table class="table datatable-responsive-control-right">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Parts</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Availability</th>
                                <th>Inventory Value</th>
                                <th>Action</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="3">Inventory Amount</th>
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

<div id="fakeLoader" class="loading"></div>
<?php
if (isset($get) && !empty($get)):
    echo "<script>var get = " . json_encode($get) . "</script>";
endif;
?>
<script type="text/javascript" src="assets/js/custom_pages/front/inventory_value.js?version='<?php echo time();?>'"></script>
<style>
    .dataTables_length{
        margin-right: 20px;
        margin-left: 0px;
    }
    .dataTables_filter{
        margin-left: 0px;
    } 
    .dataTables_length{ float:left; }
    .select-wrap .dropdown-menu li + li {padding: 0;}
    .select-wrap .dropdown-menu li.active,
    .select-wrap .dropdown-menu li:hover{background-color:#f5f5f5;}
    .data-selection-wrap{ position:relative; padding:0 64px 0 0;    display: inline-block;vertical-align: top;}
    .data-selection-wrap button.btn-save{ width:54px; position:absolute; top:0; right:0;}
    .select-wrap .dropdown-menu li.multiselect-filter{padding:5px 12px; background-color:transparent;}
    li.multiselect-filter i {color: #333;}
    .daterangepicker.dropdown-menu {max-height: unset;overflow-y: unset;}
    .ranges ul li:hover, .ranges ul li:focus{    color: #333333 !important;}
    .ranges ul li.active {color: #33a9f5 !important;background-color: #324f61;}
    .daterangepicker td.active, .daterangepicker td.active:hover, .daterangepicker td.active:focus {background-color: #324f61;}
    @media(max-width:480px){
        .data-selection-wrap button#date_range {white-space: normal;margin-bottom: 10px;width: 100%;  display: -webkit-box;
                                                display: -ms-flexbox;display: flex;-webkit-box-align: center;-ms-flex-align: center;align-items: center;-webkit-box-pack: center;
                                                -ms-flex-pack: center;justify-content: center;}
        .data-selection-wrap .bg-blue{float: right;}
        .data-selection-wrap button#date_range .position-left {margin-right: 20px;}
        .data-selection-wrap button#date_range span{text-align: left;}
    }
    @media(max-width:470px){
        .data-selection-wrap{ padding:0;width: 100%;}
        .data-selection-wrap button.btn-save {position: relative;top:0;display: block;}
    }
</style>