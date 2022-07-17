<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Staff</li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>
            <div class="panel panel-flat">
                <table class="table datatable-basic">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th style="width:5%">Status</th>
                            <th>Role Type</th>
                            <th>Full Name</th>
                            <th>User Name</th>
                            <th>Email ID</th>
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

<!-- View modal -->
<div id="menu_cat_view_modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header custom_modal_header bg-teal-400">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">Menu Category Details</h6>
            </div>
            <div class="modal-body panel-body" id="menu_cat_view_body">
            </div>         
        </div>
    </div>
</div>
<script>
    var remoteURL = site_url + "admin/staff/checkUnique_Email";
    var remoteURL2 = site_url + "admin/staff/checkUnique_Username";
    var username_req = '';
    var uri_segment = '<?php echo $this->uri->segment('3'); ?>'
</script>
<script type="text/javascript" src="assets/js/custom_pages/staff.js?version='<?php echo time();?>'"></script>
<style>
    .dataTables_length{ float:left; }
</style>