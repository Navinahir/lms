<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ul>
    </div>
</div>
<div class="content">
    <div>
        <div class="row">
            <?php $this->load->view('alert_view'); ?>

            <div class="col-md-4">
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
                                                    <option></option>
                                                    <?php foreach ($yearArr as $k => $v) { ?>
                                                        <option value="<?php echo $v['id']; ?>"><?php echo $v['name']; ?></option>
                                                    <?php } ?>
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
                    <div class="col-md-12 hide" id="div_list_of_parts">
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h6 class="panel-title">List Of Parts</h6>
                                <div class="heading-elements">
                                    <span class="status-mark border-success position-left"></span>In Stock&nbsp;&nbsp;
                                    <span class="status-mark border-danger position-left"></span>Out Of Stock
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="div_part_list">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 hide" id="div_transponder_result">
                <div class="panel panel-flat">
                    <div class="panel-heading text-center" style="padding:10px 20px">
                        <h6 class="panel-title">Transponder Details</h6>
                    </div>
                    <div class="panel-body" style="padding:0px">
                        <div class="table-responsive custom_scrollbar" style="overflow-y: scroll; height: 526px">
                            <table class="table table-bordered table-striped" id="tbl_dashboard_trans">

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $this->load->view('Templates/footer'); ?>
</div>

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

<script type="text/javascript" src="assets/js/custom_pages/dashboard.js"></script>
<style>
    .dataTables_length{ float:left; }
</style>