<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('inventory_locations'); ?>"><i class="icon-location4"></i> Inventory Locations</a></li>
            <li class="active">View</li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12" id="">
            <?php if (isset($locations) && !empty($locations)): ?>
                <div class="panel panel-flat">
                    <div class="panel-heading" style="background-color: #26A69A;border-color: #26A69A; color: #fff;padding: 10px;font-weight:bold">
                        <h3 class="panel-title"><?php echo $locations['name'] ?></h3>
                    </div>
                    <div class="panel-body">
                        <div class="media mt-20">
                            <div class="media-body">
                                <span class="text-semibold">Description : </span><?php echo $locations['description'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="panel panel-flat mt-20">
                <div class="panel-heading" style="background-color: #26A69A;border-color: #26A69A; color: #fff;padding: 10px;font-weight:bold">
                    <h3 class="panel-title">Locations Inventory Details</h3>
                </div>
                <table class="table datatable-responsive-control-right">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th>Global Part</th>
                            <th>Parts</th>
                            <th>Vendor</th>
                            <th>Quantity</th>
                            <th>Modified Date</th>
                            <th></th>
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
    var location_id = '<?php echo (isset($locations) && !empty($locations)) ? $locations['id'] : null ?>';
</script>
<script type="text/javascript" src="assets/js/custom_pages/front/inventory_locations.js?version='<?php echo time();?>'"></script>
<style>
    .dataTables_length{ float:left; }
</style>