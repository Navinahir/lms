<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Inventory Locations</li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>
<?php
if (checkUserLogin('R') != 4) {
    $controller = $this->router->fetch_class();
    if (!empty(MY_Controller::$access_method) && array_key_exists('view', MY_Controller::$access_method[$controller])) {
        $view = 1;
    }
    ?>
    <script>
        view = '<?php echo (isset($view) && $view == 1) ? $view : 0; ?>';
    </script>
<?php } ?>

<div class="content">
    <div class="row">
        <?php $this->load->view('alert_view'); ?>
        <div class="col-md-12">
            <div class="panel panel-flat">
                <table class="table datatable-basic">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th>Location Name</th>
                            <th>Description</th>
                            <th>Status</th>
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
    remoteURL = site_url + "locations/checkUniqueName";
</script>
<script type="text/javascript" src="assets/js/custom_pages/front/inventory_locations.js?version='<?php echo time();?>'"></script>
<style>
    .dataTables_length{ float:left; }
</style>