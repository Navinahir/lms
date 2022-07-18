<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/wysihtml5.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/toolbar.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/parsers.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/locales/bootstrap-wysihtml5.ua-UA.js"></script>
<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Modules Content</li>
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
                            <th>Module Name</th>
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
                    <h6 class="modal-title text-center">Module Content Details</h6>
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
<script type="text/javascript" src="assets/js/custom_pages/content.js"></script>
<style>
    .dataTables_length{ float:left; }
</style>