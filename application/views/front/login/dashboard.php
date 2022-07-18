<script type="text/javascript" src="assets/js/fakeLoader.js"></script>
<script type="text/javascript" src="assets/js/fakeLoader.min.js"></script>
<link href="assets/css/fakeloader.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
<link href="assets/css/accordion.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
<?php
$estimate_edit = "";
$invoice_edit = "";

if (checkUserLogin('R') != 4) {
    $controller = $this->router->fetch_class();
    // For estimate permission
    if (!empty(MY_Controller::$access_method) && array_key_exists('edit', MY_Controller::$access_method['estimates'])) {
        $estimate_edit = 1;
    }

    // For invoice permission
    if (!empty(MY_Controller::$access_method) && array_key_exists('edit', MY_Controller::$access_method['invoices'])) {
        $invoice_edit = 1;
    }
}
?>
<div class="page-header page-header-default">
    <div class="breadcrumb-line col-md-12">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12 ni_recent-search-list mt-20">
            <?php $this->load->view('alert_view'); ?>
            <div class="row ">
                <div class="col-md-6">
                    <div class="panel panel-flat">
                        <div class="panel-heading">
                            <h6 class="panel-title">Vehicle Search</h6>
                        </div>
                        <div class="panel-body pb-3">
                            <form method="post" class="form-validate-jquery" id="search_transponder_form" name="search_transponder_form">
                                <div class="row mt-20">
                                    <div class="col-md-12">
                                        <div class="form-group has-feedback select_form_group">
                                            <label class="required">Make</label>
                                            <select data-placeholder="Select a Vehicle Make..." class="select select-size-sm" id="txt_make_name" name="txt_make_name">
                                                <option></option>
                                                <?php
                                                foreach ($companyArr as $k => $v) {
                                                    $selected = '';
                                                    if (isset($searchData) && !empty($searchData)):
                                                        if ($v['id'] == $searchData['make_id']):
                                                            $selected = 'selected="selected"';
                                                        endif;
                                                    endif;
                                                    ?>
                                                    <option value="<?php echo $v['id']; ?>" <?php echo $selected ?>><?php echo $v['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group has-feedback select_form_group">
                                            <label class="required">Model</label>
                                            <select data-placeholder="Select a Model..." class="select select-size-sm" id="txt_model_name" name="txt_model_name">
                                                <option></option>
                                                <?php
                                                if ((isset($searchData) && !empty($searchData)) && (isset($modelArr) && !empty($modelArr))):
                                                    foreach ($modelArr as $k => $v) {
                                                        $selected = '';
                                                        if ($v['id'] == $searchData['model_id']):
                                                            $selected = 'selected="selected"';
                                                        endif;
                                                        ?>
                                                        <option value="<?php echo $v['id']; ?>" <?php echo $selected ?>><?php echo $v['name']; ?></option>
                                                        <?php
                                                    }
                                                endif;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group has-feedback select_form_group">
                                            <label class="required">Year</label>
                                            <select data-placeholder="Select a Year..." class="select select-size-sm" id="txt_year_name" name="txt_year_name">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn bg-teal custom_save_button" id="btn_search">Search</button>
                                        <button type="button" class="btn btn-default custom_cancel_button" id="btn_reset">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="panel-body pt-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>VIN Search:</label>
                                </div>
                                <div class="form-group col-sm-9 col-md-9 col-lg-9">
                                    <input type="text" class="form-control" name="txt_vin_no" placeholder="VIN: (For Ex. 1N6AA07C68N321943)" maxlength="17" />
                                    <span class="error hide" id="txt_vin_no_error"></span>
                                </div>
                                <div class="form-group col-sm-3 col-md-3 col-lg-3">
                                    <button type="button" id="search-vin-for-make-model" class="btn bg-teal">Search</button>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body quick-action-wrap">
                            <div class="row">
                                <div class="col-md-12">
                                    <label><b>Quick action:</b></label>
                                </div>
                                <div class="quick-action-btn">
                                    <div class="form-group">
                                        <a href="<?php echo base_url('items/add'); ?>" class="btn bg-teal-400 btn-labeled custom_add_button"><b><i class="icon-plus-circle2"></i></b> Add Items</a>
                                    </div>
                                    <div class="form-group">
                                        <a href="<?php echo base_url('estimates/add'); ?>" class="btn bg-pink-300 bg-pink-plus"><b><i class="icon-plus-circle2"></i></b> Add Estimate</a>
                                    </div>
                                    <div class="form-group">
                                        <a href="<?php echo base_url('invoices/add'); ?>" class="btn bg-indigo-300 bg-indigo-plus"><b><i class="icon-plus-circle2"></i></b> Add Invoice</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (isset($searchArr) && !empty($searchArr)): ?>
                    <div class="col-md-6 div_recent_search" id="div_recent_search">
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title">Recent Searches</h6>
                            </div>
                            <div class="panel-body">
                                <?php foreach ($searchArr as $v): ?>
                                    <div class="search_div mt-5 row">
                                        <div class="col-md-3 col-sm-4 col-xs-4">
                                            <span class='label border-left-primary label-striped mt-5 text-bold span_name' id="make_id_<?php echo $v['id'] ?>" data-id="<?php echo $v['make_id'] ?>"><?php echo $v['make_name'] ?></span>
                                        </div>
                                        <div class="col-md-4 col-xs-4">
                                            <span class='label border-left-primary label-striped mt-5 text-bold span_name' id="model_id_<?php echo $v['id'] ?>" data-id="<?php echo $v['model_id'] ?>"><?php echo $v['model_name'] ?></span>
                                        </div>
                                        <div class="col-md-2 col-xs-2">
                                            <span class='label border-left-primary label-striped mt-5 text-bold span_name' id="year_id_<?php echo $v['id'] ?>" data-id="<?php echo $v['year_id'] ?>"><?php echo $v['year_name'] ?></span>
                                        </div>
                                        <div class="col-md-2 col-xs-1" style="MARGIN-TOP: -2px;">
                                            <a href="javascript:void(0)" class="label bg-blue btn_view" id="<?php echo $v['id'] ?>">View</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-12 hide" id="div_vehical_details">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h6 class="panel-title">Vehicle Information</h6>
                    </div>
                    <div class="panel-body">
                        <div class="view_vehical_info">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 hide" id="div_list_of_parts">
                <div class="row">
                   <!--  <div class="panel-heading">
                        <h6 class="panel-title">List Of Parts</h6>
                        
                    </div> -->
                    <!-- <div class="stock_legend_div">
                        <span class="status-mark border-orange position-left"></span>Global Part&nbsp;&nbsp;
                        <span class="status-mark border-success position-left"></span>In Stock&nbsp;&nbsp;
                        <span class="status-mark border-danger position-left"></span>Out Of Stock
                    </div> -->
                    <div class="panel-body p-0">
                        <div class="div_part_list p-0">
                        </div>                                
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-12 hide" id="div_my_list_of_parts">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h6 class="panel-title">List Of My Parts</h6>
                    </div>
                    <div class="stock_legend_div">
                        <span class="status-mark border-success position-left"></span>In Stock&nbsp;&nbsp;
                        <span class="status-mark border-danger position-left"></span>Out Of Stock
                    </div>
                    <div class="panel-body">
                        <div class="div_my_part_list">
                        </div>                 
                    </div>
                </div>
            </div> -->
            <!-- <div class="col-md-12 hide" id="div_transponder_result">
                <div class="panel panel-flat">
                    <div class="panel-heading text-center" style="color: #fff;background-color: #009688;padding:10px 20px">
                        <h6 class="panel-title">Details</h6>
                    </div>
                    <div class="panel-body" style="padding:0px">
                        <div class="table-responsive custom_scrollbar found_data mt-10" style="padding-bottom: 10px;">
                            <div class="col-lg-6 found_left_table">
                                <table class="table table-bordered table-striped" id="tbl_dashboard_trans">

                                </table>
                            </div>
                            <div class="col-lg-6 found_right_table">
                                <table class="table table-bordered table-striped" id="tbl_dashboard_trans_2">

                                </table>
                            </div>
                        </div>
                        <div class="table-responsive no_data_found hide" style="padding: 10px;height: 500px;">
                            <table class="table table-bordered table-striped " id="no_data_found">

                            </table>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="div_estimate_invoice row">
                <div class="col-md-6">
                    <?php if (isset($estimateArr) && !empty($estimateArr)): ?>
                        <div class="div_recent_search" id="div_recent_estimate_search">
                            <div class="panel panel-flat">
                                <div class="panel-heading">
                                    <h6 class="panel-title">Recent Estimates</h6>
                                </div>
                                <div class="panel-body">
                                    <?php foreach ($estimateArr as $v): ?>
                                        <div class="row search_div mt-5">
                                            <div class="col-md-4 col-xs-4">
                                                <span class='label border-left-primary label-striped mt-5 mr-5 text-bold span_name'> 
                                                    <?php
//                                                    $cur = ($_COOKIE['currentOffset']) ? '+ ' + $_COOKIE['currentOffset'] : '';
//                                                    echo date($format['format'], strtotime($v['estimate_date']) . $cur);
                                                    echo $v['estimate_date'];
                                                    ?>
                                                </span>
                                            </div>
                                            <div class="col-md-3 col-xs-3">
                                                <span class='label border-left-primary label-striped mt-5 mr-5 text-bold span_name'> 
                                                    <?php echo $v['estimate_id'] ?>
                                                </span>
                                            </div>
                                            <div class="col-md-3 col-xs-3">
                                                <span class='label border-left-primary label-striped mt-5 mr-5 text-bold span_name'> 
                                                    <?php echo $v['cust_name'] ?>
                                                </span>
                                            </div>
                                            <div class="col-md-2 col-xs-1">
                                                <span><a href="<?php echo base_url() . 'estimates/view/' . base64_encode($v['id']) ?>" class="label bg-blue btn_view" id="<?php echo $v['id'] ?>">View</a></span>
                                                <?php if($estimate_edit == 1) { ?>
                                                    <span><a href="<?php echo base_url() . 'estimates/edit/' . base64_encode($v['id']) ?>" class="label bg-blue btn_edit">Edit</a></span>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <?php if (isset($invoiceArr) && !empty($invoiceArr)): ?>
                        <div class="div_recent_search" id="div_recent_invoice_search">
                            <div class="panel panel-flat">
                                <div class="panel-heading">
                                    <h6 class="panel-title">Recent Invoices</h6>
                                </div>
                                <div class="panel-body">
                                    <?php foreach ($invoiceArr as $v): ?>
                                        <div class="row search_div mt-5">
                                            <div class="col-md-4 col-xs-4">
                                                <span class='label border-left-primary label-striped mt-5 mr-5 text-bold span_name'> 
                                                    <?php
                                                    echo $v['estimate_date'];
//                                                    echo date($format['format'], strtotime($v['estimate_date']) + $_COOKIE['currentOffset']); 
                                                    ?>
                                                </span>
                                            </div>
                                            <div class="col-md-3 col-xs-3">
                                                <span class='label border-left-primary label-striped mt-5 mr-5 text-bold span_name'> 
                                                    <?php echo $v['estimate_id'] ?>
                                                </span>
                                            </div>
                                            <div class="col-md-3 col-xs-3">
                                                <span class='label border-left-primary label-striped mt-5 mr-5 text-bold span_name'> 
                                                    <?php echo $v['cust_name'] ?>
                                                </span>
                                            </div>
                                            <div class="col-md-2 col-xs-1">
                                                <span><a href="<?php echo base_url() . 'invoices/view/' . base64_encode($v['id']) ?>" class="label bg-blue btn_view" id="<?php echo $v['id'] ?>">View</a></span>
                                                <?php if($invoice_edit == 1) { ?>
                                                    <?php if ($v['is_deducted'] == 0 && $v['is_sent'] == 0) { ?>
                                                    <span><a href="<?php echo base_url() . 'invoices/edit/' . base64_encode($v['id']) ?>" class="label bg-blue btn_edit">Edit</a></span>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class='col-md-6 hide' id="div_tool_list"></div>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
</div>
<div id="fakeLoader" class="loading"></div>

<!-- View modal -->
<div id="dash_view_modal1" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">Item Details</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar" id="dash_view_body1" style="height: 500px;overflow: hidden;overflow-y: scroll;"></div>
        </div>
    </div>
</div>
<script type="text/javascript" src="assets/js/custom_pages/front/dashboard.js?version='<?php echo time();?>'"></script>
<script type="text/javascript">
    var api_url = site_url + 'dashboard/get_vin_vehical_details';
</script>
<script type="text/javascript" src="assets/js/custom_pages/common_script.js?version='<?php echo time();?>'"></script>
<?php
if (isset($_GET) && !empty($_GET['year'])) {
    $get_year_id = base64_decode($_GET['year']);
    ?>
    <script type="text/javascript">
        var make_id = "<?= base64_decode($_GET['make']) ?>";
        var model_id = "<?= base64_decode($_GET['model']) ?>";
        var get_year_id = "<?= $get_year_id ?>";

        var data = {
            make_id: make_id,
            model_id: model_id
        };

        $.ajax({
            url: site_url + 'dashboard/get_transponder_item_years',
            dataType: "json",
            type: "POST",
            data: data,
            success: function (response) {
                $('#txt_year_name').html(response);
                $('#txt_year_name').select2({containerCssClass: 'select-sm'});
                $('#txt_year_name').val(get_year_id).trigger('change');
            }
        });
    </script>
    <?php
}
?>
<style>
    .error{
        color: red;
    }
    .dataTables_length{ float:left; }
    .tool_div .panel-title{
        max-height: 20px;
        font-size: unset;
    }
    .tool_div .panel-body{
        max-height: 200px!important;
        min-height: 150px!important;
        overflow: auto;
    }
    .label {white-space: initial;}
    .found_left_table{padding: 0px 5px 0px 10px;}
    .found_right_table{padding: 0px 10px 0px 5px;}
    .found_data table tr td {
        padding: 9px 20px;
    }
    .span_name{
        font-size: 11px;
        padding: 10px !important;
        line-height: 15px;
        border-left-color: #2196F3;
        /*        border-right-color: #2196F3;*/
        border-left-width: 4px;
        /*        border-right-width: 4px;*/
        border-radius: 4px !important;
        width: 100%;
        text-align: left;
    }
    /*.span_name span{padding: 0 3px;}*/
    .btn_view ,.btn_edit{
        margin-top: 11px;
        font-size: inherit;
        border-radius: 4px;
        padding: 4px 10px 3px 10px;
    }
    @media (max-width:1200px){
        .found_left_table .table tr:last-child td , .found_left_table .table{border-bottom: none;}
        .found_left_table , .found_right_table{padding:0px 10px 0px 10px;}
        .found_right_table .table {border-top: none;}
        .found_right_table .table tr:first-child td {border-top-color: #ddd;}
    }

    @media screen and (max-width:1199px){
        .span_name{
            font-size: 10px;
            padding: 5px !important;
        }
        
        .div_recent_search .panel-body {
            padding: 10px 30px 20px 15px;
        }
    }

    @media screen and (max-width:575px){
        .span_name{
            font-size: 9px;
            padding: 3px !important;
        }
        .div_recent_search .btn_view{
            padding: 2px 5px 2px 5px;
            font-size: 11px;
        }
    }

    @media(min-width:1025px) and (max-width:1539px){
        .heading-elements{display:none; height:auto; margin:0; top:100%; padding: 7px; right:0; border-radius: 4px; background-color: #5cbfff; white-space:nowrap;}
        .heading-elements-toggle{ display:block;}
        .heading-elements.visible-elements {display: block;}
        .ni_recent-search-list > .col-md-4,
        .ni_recent-search-list .div_estimate_invoice > .col-md-4,
        .ni_recent-search-list .div_estimate_invoice > .col-md-4 div#div_recent_estimate_search,
        .ni_recent-search-list .div_estimate_invoice > .col-md-4 div#div_recent_invoice_search{
            width: 410px;
        }
    }
    @media (max-width:1024px){
        .tool_div .panel-body {min-height: 60px!important;}
    }
    @media (max-width:768px){
        .found_left_table{padding: 0px 10px;}
        .found_right_table{padding: 0px 10px;}
        /*.div_recent_search{display: none}*/
    }
</style>