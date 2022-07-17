<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Vendor Users</li>
        </ul>
    </div>
</div>
<?php
if (checkVendorLogin('R') == 5) {
    $controller = $this->router->fetch_class();
    $add = 1;

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
        var add = '<?php echo (isset($add) && $add == 1) ? $add : 0; ?>';
        var edit = '<?php echo (isset($edit) && $edit == 1) ? $edit : 0; ?>';
        var dlt = '<?php echo (isset($delete) && $delete == 1) ? $delete : 0; ?>';
    </script>
<?php } ?>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>
            <div class="panel panel-flat">
                <table class="table datatable-basic">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th>User Details</th>
                            <th>User Name</th>
                            <th>User's Type</th>
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
<script type="text/javascript" src="assets/js/custom_pages/vendor/users.js"></script>
<script>
        var user_id = '<?php echo checkVendorLogin('I') ?>';
</script>
<style>
    .dataTables_length{ float:left; }
</style>