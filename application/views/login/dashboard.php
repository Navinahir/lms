<script type="text/javascript" src="assets/js/fakeLoader.js"></script>
<script type="text/javascript" src="assets/js/fakeLoader.min.js"></script>
<link href="assets/css/fakeloader.css" rel="stylesheet" type="text/css"/>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ul>
    </div>
</div>

<div id="fakeLoader" class="loading"></div>

<!-- View modal -->
<div id="dash_view_modal1" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-teal-400 custom_modal_header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title text-center">Item Details</h6>
            </div>
            <div class="modal-body panel-body custom_scrollbar" id="dash_view_body1" style="height: 500px;overflow: hidden;overflow-y: scroll;"></div>
        </div>
    </div>
</div>
