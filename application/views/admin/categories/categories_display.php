<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/wysihtml5.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/toolbar.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/parsers.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/locales/bootstrap-wysihtml5.ua-UA.js"></script>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Categories data</li>
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
                            <th>No</th>
                            <th>Name</th>
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
<div id="transponder_view_modal" class="modal fade" >
    <div class="modal-dialog modal-lg" style="width: 1070px;">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">Details</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar" id="transponder_view_body" style="overflow: hidden;"></div>
        </div>
    </div>
</div>
<script>
    var user_type = '<?php echo checkLogin('R'); ?>';
    var uri_segment = '<?php echo $this->uri->segment(4); ?>';
    var remoteURL = '';
    var remoteURL_model = '';
    if(uri_segment == 'add')
    {
        remoteURL = site_url + "admin/product/checkUnique_Company_Name";
    }
</script>
<script type="text/javascript" src="assets/js/custom_pages/categories.js?version='<?php echo time();?>'"></script>
<style>
    .dataTables_length{ float:left; }
    .modal-open{ padding-right:3px !important; }
</style>
