<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Inventory</li>
            <li class="active">Vendors</li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>
            <div class="panel panel-flat">
                <table class="table datatable-responsive-control-right">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th>Status</th>
                            <th style="width:15%">Name</th>
                            <th style="width:15%">Email</th>
                            <th style="width:15%">Username</th>
                            <th style="width:15%">Contact Person / Number</th>
                            <th>Description</th>
                            <th style="width:15%">Action</th>
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
<div id="department_view_modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">Department Details</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar" id="department_view_body" style="height: 500px;overflow: hidden;overflow-y: scroll;"></div>
        </div>
    </div>
</div>
<script>
    var remoteURL = '';
    var phone_formate = '';
</script>
<script type="text/javascript" src="assets/js/custom_pages/vendor.js?version='<?php echo time();?>'"></script>
<style>
    .dataTables_length{ float:left; }
    .modal-open{ padding-right:3px !important; }
    .switch { display: inline-block; height: 29px; position: relative; width: 50px; }
    .switch input { display:none; }
    .slider { background-color: #ccc; bottom: 0; cursor: pointer; left: 0; position: absolute; right: 0; top: 0; transition: .4s; }
    .slider:before { background-color: white; bottom: 3.5px; content: ""; height: 23px; left: 3px; position: absolute; transition: .4s; width: 23px; }
    input:checked + .slider { background-color: #2196F3; }
    input:checked + .slider:before { transform: translateX(20px); }
    .slider.round { border-radius: 34px; }
    .slider.round:before { border-radius: 50%;}
    body { background-color: #f1f2f3; }
</style>