<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.all.min.js"></script> -->
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li> <input type="hidden" id="user_id" value="">
            <li class="active">/ Users</li>
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
                            <th>Full Name</th>
                            <th>User Name</th>
                            <th>Email ID</th>
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

<!-- View modal -->
<div id="otp_verify_view_modal" class="modal fade" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">OTP Verification For Delete User Account</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar" id="otp_verify_view_body" style="overflow: hidden;">
                <form id="otp_verify_view_modal_form" method="post">
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">OTP Verification:</label>
                        <input type="hidden" id="delete_user_id" name="delete_user_id" />
                        <input type="text" class="form-control" id="otp_verification" name="otp_verification" required placeholder="Enter OTP"/>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="fakeLoader" class="loading"></div>
<script type="text/javascript" src="assets/js/custom_pages/users.js?version='<?php echo time();?>'"></script>
<style>
    .dataTables_length{ float:left; }
    label#otp_verification-error {
        color: #1ca6ff;
        font-size: 12px;
    }
</style>
