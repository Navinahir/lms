<script type="text/javascript" src="assets/js/fakeLoader.js"></script>
<script type="text/javascript" src="assets/js/fakeLoader.min.js"></script>
<link href="assets/css/fakeloader.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/vendor/home'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <form method="post" class="form-validate-jquery" id="search_transponder_form" name="search_transponder_form">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="required">Make</label>
                                                <select data-placeholder="Select a Company..." class="select select-size-sm" id="txt_make_name" name="txt_make_name">
                                                    <option></option>
                                                    <?php foreach ($companyArr as $k => $v) { ?>
                                                        <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group has-feedback select_form_group">
                                                <label class="required">Model</label>
                                                <select data-placeholder="Select a Model..." class="select select-size-sm" id="txt_model_name" name="txt_model_name">
                                                    <option></option>
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h6 class="panel-title">Search Part:</h6>
                    </div>
                    <div class="panel-body">
                        <div class="mt-10">
                            <div class="form-group form-inline mb-0">
                                <form method="post" class="form-group w-100" id="searchPartDetailsForm">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mb-md-4">
                                            <div class="form-group w-100">
                                                <input type="text" class="form-control w-100" name="txt_part_no" placeholder="Part No" />
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mb-md-4">
                                            <div class="form-group w-100">
                                                <input type="text" class="form-control w-100" name="txt_part_description" placeholder="Part Description" />
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mt-2rem">
                                            <div class="form-group">
                                                <button type="submit" class="btn bg-teal custom_save_button">Search</button>
                                                <button type="reset" class="btn btn-default custom_cancel_button">Reset</button> 
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-flat">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="txt_vin_no" placeholder="VIN: (For Ex. 1N6AA07C68N321943)" />
                                    <span class="error hide" id="txt_vin_no_error"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-0">
                                    <button type="button" id="search-vin-for-make-model" class="btn bg-teal">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 hide" id="div_list_of_parts">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h6 class="panel-title">List Of Parts</h6>
                    </div>
                    <div class="panel-body">
                        <div class="div_part_list row">
                            <div class="col-sm-12 pl-0 pr-0">
                            </div>
                        </div>
                    </div>
                </div>
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
        </div>
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
<script type="text/javascript" src="assets/js/custom_pages/vendor/dashboard.js?version='<?php echo time();?>'"></script>
<script type="text/javascript">
    var api_url = site_url + 'vendor/home/get_vin_vehical_details';
</script>
<script type="text/javascript" src="assets/js/custom_pages/common_script.js?version='<?php echo time();?>'"></script>

<style>
    .dataTables_length{ float:left; }
    .tool_div .panel-title{
        max-height: 20px;
        font-size: unset;
    }
    .tool_div .panel-body{
        max-height: 100px;
        min-height: 100px;
        overflow: auto;
    }
    .error{
        color: red;
    }
</style>