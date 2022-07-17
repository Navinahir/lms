<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/wysihtml5.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/toolbar.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/parsers.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/locales/bootstrap-wysihtml5.ua-UA.js"></script>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">List Terms And Privacy Policies</li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>
            <div class="panel panel-flat">
                <div class="row">
                    <div class="col-sm-12 col-md-8 col-lg-5">
                        <div class="col-sm-12">
                            <div class="row select2-plus-dropdown">
                                <div class="status_filter_div col-md-10 col-xs-10 select2-dropdown-custom">
                                    <select data-placeholder="-Select Type-" class="select select-size-sm" id="filter_status">
                                        <option value=''>Select Type</option>
                                        <option value="Terms">Terms And Conditions</option>
                                        <option value="Privacy">Privacy Policies</option>
                                    </select>
                                </div>
                                <div class="status_filter_div col-md-2 col-sm-2 col-xs-2 select2-dropdown-plus pl-0">
                                    <button class="btn btn-primary btn-sm" style="padding: 7px 12px;" onclick="return window.location.reload();"><i class="fa fa-sync-alt fa-sm"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="status_filter_div col-md-4 col-lg-7">
                        <div class="col-sm-12">
                            <div class="col-sm-12 pr-2">
                                <div class="text-right">
                                    <a href="<?= site_url() ?>/admin/terms/privacy/add" class="btn bg-teal-400 btn-labeled custom_add_button"><b><i class="icon-plus-circle2"></i></b> Add Terms & Privacy</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
                <table class="table datatable-basic">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th>Page Name</th>
                            <th>Page Type</th>
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

    <!-- View modal -->
    <div id="content_view_modal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-teal-400 custom_modal_header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title text-center">Page Details</h6>
                </div>
                <div class="modal-body panel-body custom_scrollbar" id="content_view_body" style="overflow: hidden;overflow-y: scroll;">
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var remoteURL = '';
    var selectedDate = '';
</script>
<script type="text/javascript" src="assets/js/custom_pages/terms_privacy.js?version='<?php echo time();?>'"></script>
<style>
    .dataTables_length{ float:left; }
    .status_filter_div {
        margin-top: 10px;
    }
</style>