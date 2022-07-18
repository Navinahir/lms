<script src="assets/ckeditor/ckeditor.js"></script>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Subscriptions</li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <?php $this->load->view('alert_view'); ?>
        <div class="col-md-6" id="year_form_row">
            <form method="post" class="form-validate-jquery" id="add_package_form" name="add_package_form">
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Manage Subscriptions</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row mt-20">
                            <div class="col-md-12">
                                <div class="form-group form-group-material has-feedback">
                                    <label>Name <font color="red">*</font></label>
                                    <input type="text" class="form-control" name="txt_name" id="txt_name" required="required" placeholder="Enter Name">
                                    <input type="hidden" class="form-control" name="txt_package_id" id="txt_package_id">
                                </div>
                                <div class="form-group form-group-material has-feedback">
                                    <label>Value <font color="red">*</font></label>
                                    <input type="number" class="form-control" name="txt_price" id="txt_price" required="required" placeholder="Enter Value" value="1" min="1">
                                </div>
                                <!-- <div class="form-group form-group-material has-feedback">
                                    <label>Months <font color="red">*</font></label>
                                    <select class="select select-size-sm" data-placeholder="Select a Months..." id="txt_months" name="txt_months">
                                        <option value="1">1 Month</option>
                                        <option value="3">3 Months</option>
                                        <option value="6">6 Months</option>
                                        <option value="12">12 Months</option>
                                    </select>
                                </div>-->
                                 <div class="form-group form-group-material has-feedback">
                                    <label>QuickBooks Status<font color="red">*</font></label>
                                    <select class="select select-size-sm" data-placeholder="QuickBooks Status" id="quickbook_status" name="quickbook_status">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                <div class="form-group form-group-material has-feedback">
                                    <label>No. Of User's Licences Allowed <font color="red">*</font></label>
                                    <input type="number" class="form-control" name="txt_users" id="txt_users" required="required" placeholder="Enter mo. of Users Licences" value="1" min="1">
                                </div>
                                <div class="form-group form-group-material has-feedback">
                                    <label>No. Of Locations Allowed <font color="red">*</font></label>
                                    <input type="number" class="form-control" name="txt_locations" id="txt_locations" required="required" placeholder="Enter No. of Location" value="1" min="1">
                                </div>
                                <div class="form-group form-group-material has-feedback">
                                    <label>Description <font color="red">*</font></label>
                                    <textarea class="form-control" name="txt_description" id="txt_description" placeholder="Enter Description"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn bg-teal custom_save_button">Save</button>
                                <!-- <button type="button" class="btn btn-default custom_cancel_button" onclick="cancel_click()">Cancel</button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">List of Subscriptions</h5>
                </div>
                <table class="table datatable-basic">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Subscription Name</th>
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
    remoteURL = site_url + "admin/product/checkUnique_Package_Name";
<?php if (isset($dataArr)) { ?>
        var package_id = '<?php echo $dataArr['id'] ?>';
        remoteURL = site_url + "admin/product/checkUnique_Package_Name/" + package_id;
<?php } ?>
</script>
<script type="text/javascript" src="assets/js/custom_pages/package.js?version='<?php echo time();?>'"></script>