<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('vendor/home'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Reports</li>
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
                            <!--<th style="width:15%">Business</th>-->
                            <th style="width:30%">Global Parts</th>
                            <th>Parts</th>
                            <th>Department</th>
                            <!--<th style="width:25%">Availability</th>-->
                            <th style="width:5%">Action</th>
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
<script type="text/javascript" src="assets/js/custom_pages/vendor/reports.js"></script>
<style>
    .dataTables_length{ float:left; }
    .modal-open{ padding-right:3px !important; }
</style>