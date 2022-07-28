<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Product</li>
            <li class="active">Year</li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <?php $this->load->view('alert_view'); ?>
        <div class="col-md-4" id="year_form_row">
            <form method="post" class="form-validate-jquery" id="add_year_form" name="add_year_form">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Manage Year</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row mt-20">
                            <div class="col-md-12">
                                <div class="form-group form-group-material has-feedback">
                                    <label>Name <font color="red">*</font></label>
                                    <input type="text" class="form-control" name="txt_year_name" id="txt_year_name" required="required" placeholder="Enter Name" maxlength="4">
                                    <input type="hidden" name="txt_year_id" id="txt_year_id">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn bg-teal custom_save_button">Save</button>
                                <button type="button" class="btn btn-default custom_cancel_button" onclick="cancel_click()">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-8">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">List of Year</h5>
                </div>
                <table class="table datatable-basic">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Year</th>
                            <th>Last Edited</th>
                            <th>Action</th>
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
    remoteURL = site_url + "admin/product/checkUnique_Year_Name";
    <?php if (isset($dataArr)) { ?>
        var year_id = '<?php echo $dataArr['id'] ?>';
        remoteURL = site_url + "admin/product/checkUnique_Year_Name/" + year_id;
    <?php } ?>
</script>
<script type="text/javascript" src="assets/js/custom_pages/year.js?version='<?php echo time();?>'"></script>