<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Users</li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>
            <div class="panel panel-flat">
                <table class="table datatable-basic-cancel-subscription">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th style="width:5%">Status</th>
                            <th>Full Name</th>
                            <th>Business Name</th>
                            <th>Email ID</th>
                            <th>Contact No</th>
                            <th>Package</th>
                            <th>Unsubscribe Date</th>
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
<div id="requested_user_details_modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">User Details</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar" id="requested_user_details_body" style="max-height: 500px;overflow: hidden;overflow-y: scroll;">
                
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="assets/js/custom_pages/users_request.js?version='<?php echo time();?>'"></script>
<style>
    .dataTables_length{ float:left; }
</style>