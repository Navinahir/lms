<link href="assets/css/fakeloader.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="assets/js/plugins/notifications/jgrowl.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/daterangepicker.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/anytime.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/pickers/pickadate/picker.js"></script>
<script type="text/javascript" src="assets/js/plugins/visualization/echarts/echarts.js"></script>
<script type="text/javascript" src="assets/js/charts/echarts/timeline_option.js"></script>
<script type="text/javascript" src="assets/js/fakeLoader.js"></script>
<script type="text/javascript" src="assets/js/fakeLoader.min.js"></script>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Reports</li>
            <li class="active">Sales</li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>
<div class="content">
    <div>
        <?php $this->load->view('alert_view'); ?>
        <div class="row">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Filter</h5>
                </div>
                <div class="panel-body mt-20">
                    <div class="">
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
        </div>
        <div class="row">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Sales Report</h5>
                    <!-- <div class="heading-elements">
                        <ul class="icons-list">
                            <li><a data-action="collapse"></a></li>
                        </ul>
                    </div> -->
                </div>

                <div class="panel-body mt-20">
                    <div class="chart-container">
                        <div class="chart has-fixed-height" id="basic_columns"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title"><span class="text-semibold">Total Sales</span></h6>
                </div>

                <table class="table datatable-responsive-control-right">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th>Date</th>
                            <th>Sales</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="2">
                                Total Sales
                            </th>
                            <th>
                            </th>
                            <th>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h6 class="panel-title"><span class="text-semibold">Daily Sales</span></h6>
                </div>

                <table class="table datatable-responsive-daily-invoice-sales">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th>Date</th>
                            <th>Invoice No</th>
                            <th>Sales</th>
                            <th>Action</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th colspan="2">Total Sales</th>
                            <th></th>
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
<script type="text/javascript" src="assets/js/custom_pages/front/sales.js?version='<?php echo time();?>'"></script>
<style>
    .dataTables_length{
        margin-right: 20px;
        margin-left: 0px;
    }
    .dataTables_filter{
        margin-left: 0px;
    }
    .dataTables_length{ float:left; }
    .ranges ul li:hover, .ranges ul li:focus{color: #333333 !important;}
    .ranges ul li.active {color: #33a9f5 !important;background-color: #324f61;}
    .daterangepicker td.active, .daterangepicker td.active:hover, .daterangepicker td.active:focus {background-color: #324f61;}
    .daterangepicker.dropdown-menu {max-height: unset;overflow-y: unset;}

    @media(max-width:767px){
        .panel { margin: 0 20px 20px 20px;}
    }

    @media(max-width:480px){
        .data-selection-wrap button#date_range {white-space: normal;margin-bottom: 10px;width: 100%;  display: -webkit-box;display: -ms-flexbox;display: flex;-webkit-box-align: center;-ms-flex-align: center;align-items: center;-webkit-box-pack: center;-ms-flex-pack: center;justify-content: center;}
        .data-selection-wrap .bg-blue{float: right;}
        .data-selection-wrap button#date_range .position-left {margin-right: 20px;}
        .data-selection-wrap button#date_range span{text-align: left;}
    }
</style>