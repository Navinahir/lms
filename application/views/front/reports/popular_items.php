<link href="assets/css/fakeloader.css" rel="stylesheet" type="text/css"/>
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
            <li class="active">Most Popular Parts</li>
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
                <div class="col-md-5">
                    <div class="form-group has-feedback select_form_group">
                        <label class="display-block">Select Parts </label>
                        <div class="multi-select-full select-wrap">
                            <select class="multiselect-filtering" multiple="multiple" name="parts[]" id="parts" style="display:none">
                                <?php
                                if (isset($dataArr)) {
                                    foreach ($dataArr as $k => $v) {
                                        $selected = '';
                                        if ($this->input->get('parts') && $this->input->get('parts') != '') {
                                            $parts = explode(',', $this->input->get('parts'));
                                            if (in_array($v['i_id'], $parts)) {
                                                $selected = 'selected="selected"';
                                            }
                                        }
                                        if ($v['internal_part_no'] != null) {
                                            $internal = ' , Internal Part : ' . $v['internal_part_no'];
                                        } else {
                                            $internal = '';
                                        }
                                        ?>
                                        <option class='tools' value="<?php echo $v['i_id']; ?>" <?php echo $selected ?>><?php echo $v['part_no'] . ' (Vendor : ' . $v['vendor_name'] . $internal . ')'; ?></option>
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
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">Top Most Popular Parts Graph</h5>
                <!-- <div class="heading-elements">
                    <ul class="icons-list">
                        <li><a data-action="collapse"></a></li>
                    </ul>
                </div> -->
            </div>

            <div class="panel-body mt-20">
                <div class="chart-container">
                    <div class="chart has-fixed-height has-minimum-width" id="basic_donut"></div>
                </div>
            </div>
        </div>
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">Top Most Popular Parts</h5>
                <div class="heading-elements">
                    <div class="text-right popular_heading"><button type="button" class="btn bg-default btn-labeled pdf_button color-inherit"><b><i class="icon-printer"></i></b> Print</button></div>
                    <!-- <ul class="icons-list">
                        <li><a data-action="collapse"></a></li>
                    </ul> -->
                </div>
            </div>
            <div class="panel-body">
                <div class="row parts_div">
                    <?php
                    $cur = (isset($currency) && !empty($currency)) ? $currency['symbol'] : '$';
                    if (isset($ItemArr) && !empty($ItemArr)):
                        foreach ($ItemArr as $k => $v) {
                            ?>
                            <div class="col-md-6">
                                <div class="col-md-12 media mt-10">
                                    <div class="col-md-1 text-center">
                                        <h1 class="no-margin text-bold sr_no"><?php echo ++$k; ?></h1>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="thumb">
                                            <?php
                                            if($v['global_part_no'] == null)
                                            {
                                            if($image = ($v['image'] != null) ? base_url() . "uploads/items/" . $v['image'] : 'assets/images/noimage.png') ?>
                                                 <img src="<?php echo $image ?>" class="img-responsive img-rounded media-preview image_item" alt="" height="50px" width="50px">
                                            <?php } else { ?> 
                                            <?php $image = ($v['global_image'] != null) ? base_url() . "uploads/items/" . $v['global_image'] : 'assets/images/noimage.png' ?>
                                            <img src="<?php echo $image ?>" class="img-responsive img-rounded media-preview image_item" alt="" height="50px" width="50px">
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="javascript:void(0)"><h5 class="item_name"><?php echo $v['part_no'] ?></h5></a>
                                        <div class="text-muted text-size-small" style="margin-top: -10px;">
                                            <span class="status-mark border-blue position-left"></span>Global Part No : <?php echo $v['global_part_no'] ?>
                                        </div>
                                        <div class="text-muted text-size-small">
                                            <span class="status-mark border-blue position-left"></span>Vendor : <?php echo $v['pref_vendor_name'] ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-lg-3  qty_div text-size-large">
                                        <div class=""><i class="icon icon-cart5 text-blue"></i><span class="ml-5"><?php echo $v['total_quantity'] ?></span></div>
                                        <div class="mt-5"><i class="icon icon-coins text-blue"></i><span class="ml-5"><?php echo $cur . number_format((float) $v['total_amount'], 2, ".", ""); ?></span></div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    else:
                        ?>
                        <h6 class="text-small ml-5">No Data found!!</h6>
                    <?php
                    endif;
                    ?>
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
<script type="text/javascript" src="assets/js/custom_pages/front/popular_items.js?version='<?php echo time();?>'"></script>
<style>
    .dataTables_length{ float:left; }
    .select-wrap .dropdown-menu li + li {padding: 0;}
    .select-wrap .dropdown-menu li.active,
    .select-wrap .dropdown-menu li:hover{background-color:#f5f5f5;}
    .data-selection-wrap{ position:relative; padding:0 64px 0 0;    display: inline-block;vertical-align: top;}
    .data-selection-wrap button.btn-save{ width:54px; position:absolute; top:0; right:0;}
    .select-wrap .dropdown-menu li.multiselect-filter{padding:5px 12px; background-color:transparent;}
    li.multiselect-filter i {color: #333;}

    .media-preview {width: 54px !important;}
    .item_name{margin-top: 0px;font-size: large;color:black}
    .text-muted {color: #464444;}
    .text-size-large {font-size: 16px;}
    .qty_div{padding-top: 19px;}
    .image_item{height: 70px;width: 70px !important; max-height: 70px;max-width: 70px;}
    .parts_div{overflow-x: auto;height: 465px;}
    .custom_scrollbar::-webkit-scrollbar { width: 0.4em; }
    .custom_scrollbar::-webkit-scrollbar-track { -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); }
    .custom_scrollbar::-webkit-scrollbar-thumb { background-color: #33a9f5 !important; outline: 1px solid slategrey; }

    .parts_div .media {display: -webkit-box;display: -ms-flexbox;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;align-items: center;background-color: #f7f7f7;padding: 8px 0;margin-top: 0px !important;}

    .parts_div .col-md-6 {margin-top: 5px !important;}
    .parts_div .media h1 {font-size: 20px;}
    .popular_heading{color: black;margin-top: -4px;}
    .popular_heading.btn[class*=bg-]:hover{color: #000;}
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
        .qty_div{width: 100% !important;display: -webkit-box;display: -ms-flexbox;display: flex;-webkit-box-align: center;-ms-flex-align: center;
                 align-items: center;-webkit-box-pack: justify;-ms-flex-pack: justify;justify-content: space-between;}
    }
    @media(max-width:470px){
        .data-selection-wrap{ padding:0;width: 100%;}
        .data-selection-wrap button.btn-save {position: relative;top:0;display: block;}
    }

</style>