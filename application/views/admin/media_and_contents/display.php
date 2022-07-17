<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">List Blogs</li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <?php $this->load->view('alert_view'); ?>
            <div class="panel panel-flat">
                <div class="status_filter_div col-md-12">
                    <div class="text-right">
                        <a href="<?= site_url('admin/blogs/add') ?>" class="btn bg-teal-400 btn-labeled custom_add_button"><b><i class="icon-plus-circle2"></i></b> Add Blogs</a>
                    </div>
                </div>
                <table class="table datatable-basic">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th>Blog Title</th>
                            <th>Status</th>
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
<script type="text/javascript" src="assets/js/custom_pages/blogs.js?version='<?php echo time();?>'"></script>
<style>
    .dataTables_length{ float:left; }
    .dataTables_length select{ 
        outline: 0;
        /* width: 67%; */
        height: 36px;
        padding: 7px 12px;
        padding-right: 36px;
        font-size: 13px;
        line-height: 1.5384616;
        color: #333333;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 3px;
    }
    .status_filter_div {
        margin-top: 10px;
    }

</style>