<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active"><?php echo $title; ?></li>
        </ul>
        <?php $this->load->view('search_view'); ?>
    </div>
</div>
<div class="content">
    <div class="row">
        <?php $this->load->view('alert_view'); ?>
        <div class="col-md-12">
            <div class="panel panel-flat">
                <table class="table datatable-basic">
                    <thead>
                        <tr>
                            <th width="1%">#</th>
                            <th width="9%">Date</th>
                            <th width="10%">Type</th>
                            <th width="15%">User</th>
                            <th width="20%">Part</th>
                            <th width="45%">Description</th>
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
<script type="text/javascript" src="assets/js/custom_pages/front/invoice_inventory_history.js?version='<?php echo time();?>'"></script>
<style>
    .dataTables_length{ float:left; }
</style>