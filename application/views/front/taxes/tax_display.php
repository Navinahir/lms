<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Setting</li>
            <li class="active">Tax Rates</li>
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

        <?php $this->load->view('alert_view'); ?>
        <div class="col-md-4" id="tax_form_row">
            <form method="post" class="form-validate-jquery" id="add_tax_form" name="add_tax_form" action='<?php echo site_url('taxes/add') ?>'>
                <div class="panel panel-flat add">
                    <div class="panel-heading">
                        <h5 class="panel-title">Manage Tax Rate</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-group-material has-feedback">
                                    <label>Tax Name <font color="red">*</font></label>
                                    <input type="text" class="form-control" name="txt_tax_name" id="txt_tax_name" required="required" placeholder="Enter Tax Name">
                                    <input type="hidden" name="txt_tax_id" id="txt_tax_id">
                                </div>
                                <div class="form-group form-group-material has-feedback">
                                    <label>Rate(%) <font color="red">*</font></label>
                                    <input type="number" class="form-control" name="txt_rate" id="txt_rate" required="required" placeholder="Enter Rate(%)" min='0' value='0'>
                                    <span class="tax_alert" style="color: red; display: none;">Maximum tax limit is 25%.</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn bg-blue custom_save_button">Save</button>
                                <button type="button" class="btn btn-default custom_cancel_button" onclick="cancel_click()">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-8">           
            <div class="panel panel-flat">
                <table class="table datatable-basic">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th>Tax Name</th>
                            <th>Rate(%)</th>
                            <th>Last Edited</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
</div>
<script>
    remoteURL = site_url + "taxes/checkUniqueName";
</script>
<script type="text/javascript" src="assets/js/custom_pages/front/taxes.js?version='<?php echo time();?>'"></script>
<style>
    .dataTables_length{ float:left; }
</style>