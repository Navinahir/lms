<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/wysihtml5.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/toolbar.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/parsers.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/locales/bootstrap-wysihtml5.ua-UA.js"></script>
<script src="//cdn.ckeditor.com/4.5.9/standard/ckeditor.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.9/adapters/jquery.js"></script>

<?php
if (isset($dataArr)) {
    $form_action = site_url('admin/content/edit/' . base64_encode($dataArr['id']));
} else {
    $form_action = site_url('admin/content/add');
}
?>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('admin/content'); ?>" >Content</a></li>
            <li class="active"><?= (!empty($dataArr)) ? 'Edit' : 'Add' ?></li>
        </ul>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="<?php echo $form_action; ?>" class="form-horizontal" id="add_content_form" enctype="multipart/form-data" >
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Add Module Question Description</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row mt-20">
                            <div class="col-md-12">
                                <div class="form-group form-group-material has-feedback">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label required">Module Name :</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="txt_module_name" 
                                                   placeholder="Module Name" 
                                                   id="txt_module_name"
                                                   value="<?= (!empty($dataArr) && $dataArr['module_name']) ? $dataArr['module_name'] : '' ?>" />
                                            <?php echo '<label id="txt_module_name_error2" class="validation-error-label" for="txt_module_name">' . form_error('txt_module_name') . '</label>'; ?>
                                            <span class="validation-error-label" id="txt_module_name"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-group-material has-feedback">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label required">Module Description:</label>
                                            <!-- Add in class if remove ckeditor wysihtml5 wysihtml5-default -->
                                            <textarea class="form-control" placeholder="Module Description" name="module_description" id='module_description'><?= (!empty($dataArr) && $dataArr['module_description']) ? $dataArr['module_description'] : '' ?></textarea>
                                            <?php echo '<label id="module_description_error2" class="validation-error-label" for="module_description">' . form_error('module_description') . '</label>'; ?>
                                            <span class="validation-error-label" id="module_description"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 pl-0 pr-0">
                                <button type="submit" class="btn bg-teal custom_save_button">Save</button>
                                <button type="button" class="btn btn-default custom_cancel_button" onclick="if (history.length > 2) {
                                            window.history.back()
                                        } else {
                                            window.location.href = 'admin/content';
                                        }">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php $this->load->view('Templates/footer.php'); ?>
    </div>
    <script type="text/javascript" src="assets/js/custom_pages/content.js"></script>
    <style>
        .modal-open{ padding-right:3px !important; }
        .uploader .filename{max-width:170px;}
        @media(min-width:1024px) and (max-width:1300px){
            .media.no-margin-top{ position:relative;}
            .media-left#image_preview_div{max-width:50px; position: absolute; left: -50px;}
            .media-left#image_preview_div img{width:40px !important;height:auto !important;}
            .uploader .filename{max-width:140px;}
        }
        @media(max-width:320px){
            div#image_preview_div {padding-right: 5px;}
            div#image_preview_div  img {width: 40px !important;height: 40px !important;}
        }

        .nicehide {
            resize: none;
            display: block !important;
            width: 0;
            height: 0;
            margin: 0 0 0 -15px;
            float: left;
            border: none;
        }
    </style>
    <script type="text/javascript">
        $('#module_description').ckeditor();
    </script>