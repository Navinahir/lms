<script type="text/javascript" src="assets/js/plugins/forms/tags/tagsinput.min.js"></script>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('vendor/home'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Products</li>
        </ul>
    </div>
</div>

<?php
$add = 0;
$edit = 0;
$delete = 0;

if (checkVendorLogin('R') != 5) {
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
    <?php
} else {
    $add = $edit = $delete = 1;
}
?>

<script>
    add = '<?php echo (isset($add) && $add == 1) ? $add : 0; ?>';
    edit = '<?php echo (isset($edit) && $edit == 1) ? $edit : 0; ?>';
    dlt = '<?php echo (isset($delete) && $delete == 1) ? $delete : 0; ?>';
</script>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>
            <div class="panel panel-flat">
                <table class="table datatable-responsive-control-right">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th>Item Parts</th>
                            <th>Item Description</th>
                            <th>Department</th>
                            <th style="width:18%">Action</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
</div>
<!-- View modal -->
<div id="items_view_modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">Items Details</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar" id="items_view_body" style="height: 500px;overflow: hidden;overflow-y: scroll;"></div>
        </div>
    </div>
</div>
<script type="text/javascript" src="assets/js/custom_pages/vendor/items.js?version='<?php echo time(); ?>'"></script>
<style>
    .dataTables_length{ float:left; }
    .modal-open{ padding-right:3px !important; }
</style>