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
            <li class="active">Tax</li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>
<div class="content">
    <div>
        <?php $this->load->view('alert_view'); ?>

        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">Filter</h5>
            </div>
            <div class="panel-body mt-20">
                <div class="col-md-4">
                    <div class="form-group has-feedback select_form_group">
                        <label class="display-block">Select Tax </label>
                        <div class="multi-select-full select-wrap">
                            <select class="multiselect-filtering" multiple="multiple" name="tax[]" id="tax" style="display:none">
                                <?php
                                if (isset($dataArr) && !empty($dataArr)) {
                                    foreach ($dataArr as $k => $v) {
                                        ?>
                                        <option class='tools' value="<?php echo $v['id']; ?>"><?php echo $v['name'] . " (" . $v['rate'] . "%)"; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="display-block">Date Selection </label>
                        <div class="data-selection-wrap">
                            <button type="button" class="btn btn-default daterange-predefined" id="date_range" name="date">
                                <i class="icon-calendar22 position-left"></i>
                                <span></span>
                                <b class="caret"></b>
                            </button>
                            <button type="button" class="btn bg-blue btn-save">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-20 chart-div-wrap">
            <div class="col-md-7">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Tax Graph</h5>
                        <!-- <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                            </ul>
                        </div> -->
                    </div>

                    <div class="panel-body chart-div">
                        <div class="chart-container mt-20">
                            <div class="chart has-fixed-height has-minimum-width" id="multiple_donuts" style="height: 450px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /nightingale roses width hidden labels -->
            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Tax Details</h5>
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
                                <th style="width:30%">Tax</th>
                                <th>Completed Invoice</th>
                                <th>Total Tax</th>
                                <!-- <th>Action</th> -->
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total Tax</th>
                                <th></th>
                                <!-- <th></th> -->
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Part Tax Details</h5>
                        <!-- <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                            </ul>
                        </div> -->
                    </div>
                    <table class="table datatable-responsive-invoices-tax">
                        <thead>
                            <tr>
                                <th style="width:5%">#</th>
                                <th>Date</th>
                                <th>Invoice No.</th>
                                <th>Part Tax Amount</th>
                                <th>Invoice Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total Part Tax</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Service Tax Details</h5>
                        <!-- <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                            </ul>
                        </div> -->
                    </div>
                    <table class="table datatable-responsive-service-tax">
                        <thead>
                            <tr>
                                <th style="width:5%">#</th>
                                <th>Date</th>
                                <th>Invoice No.</th>
                                <th>Service Tax Amount</th>
                                <th>Invoice Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total Service Tax</th>
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
<div id="fakeLoader" class="loading"></div>
<?php
if (isset($get) && !empty($get)):
    echo "<script>var get = " . json_encode($get) . "</script>";
endif;
?>

<!-- View modal -->
<div id="invoice_view_modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">Invoice Details</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar" id="invoice_view_body" style="height: 500px;overflow: hidden;overflow-y: scroll;"></div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.19/api/sum().js"></script>
<script type="text/javascript" src="assets/js/custom_pages/front/tax_reports.js?version='<?php echo time();?>'"></script>
<style>
    .dataTables_length{ float:left; }
    .select-wrap .dropdown-menu li + li {padding: 0;}
    .select-wrap .dropdown-menu li.active,
    .select-wrap .dropdown-menu li:hover{background-color:#f5f5f5;}
    .data-selection-wrap{ position:relative; padding:0 64px 0 0;    display: inline-block;vertical-align: top;}
    .data-selection-wrap button.btn-save{ width:54px; position:absolute; top:0; right:0;}
    .select-wrap .dropdown-menu li.multiselect-filter{padding:5px 12px; background-color:transparent;}
    li.multiselect-filter i {color: #333;}
    .daterangepicker.dropdown-menu {max-height: unset;overflow-y: unset;}
    .table > tfoot > tr > th:last-child{border-top:none !important;}
    .ranges ul li:hover, .ranges ul li:focus{    color: #333333 !important;}
    .ranges ul li.active {color: #33a9f5 !important;background-color: #324f61;}
    .daterangepicker td.active, .daterangepicker td.active:hover, .daterangepicker td.active:focus {background-color: #324f61;}
    @media(max-width:1200px){
        .chart-div-wrap .col-md-7{width: 100%;}
        .chart-div-wrap .col-md-5{width: 100%;}
    }
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