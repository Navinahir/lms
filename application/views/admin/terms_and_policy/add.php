<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/wysihtml5.min.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/toolbar.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/parsers.js"></script>
<script type="text/javascript" src="assets/js/plugins/editors/wysihtml5/locales/bootstrap-wysihtml5.ua-UA.js"></script>
<?php
if (isset($dataArr)) {
    $form_action = site_url('admin/terms/privacy/edit/' . base64_encode($dataArr['id']));
} else {
    $form_action = site_url('admin/terms/privacy/add');
}
?>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('admin/terms/privacy'); ?>" >Terms Condition And Privacy Policies</a></li>
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
                                            <label class="control-label required">Page Title :</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="page_name" 
                                                   placeholder="Page Name" 
                                                   id="page_name"
                                                   value="<?= (!empty($dataArr) && $dataArr['page_title']) ? $dataArr['page_title'] : '' ?>" />
                                                   <?php echo '<label id="page_name_error2" class="validation-error-label" for="page_name">' . form_error('page_name') . '</label>'; ?>
                                            <span class="validation-error-label" id="page_name"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-group-material has-feedback">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label required">Page Description:</label>
                                            <textarea class="wysihtml5 wysihtml5-default form-control" placeholder="Page Description" name="page_description" id='page_description'><?= (!empty($dataArr) && $dataArr['page_content']) ? $dataArr['page_content'] : '' ?></textarea>
                                            <?php echo '<label id="page_description_error2" class="validation-error-label" for="page_description">' . form_error('page_description') . '</label>'; ?>
                                            <span class="validation-error-label" id="page_description"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-group-material has-feedback">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label required">Page Type:</label>
                                            <select class="form-control" name="page_type" id="page_type">
                                                <option value="Terms" <?= (!empty($dataArr) && $dataArr['page_type'] == "Terms") ? 'selected' : '' ?>>Terms And Conditions</option>
                                                <option value="Privacy" <?= (!empty($dataArr) && $dataArr['page_type'] == "Privacy") ? 'selected' : '' ?>>Privacy Policies</option>
                                            </select>
                                            <?php echo '<label id="page_type_error2" class="validation-error-label" for="page_type">' . form_error('page_type') . '</label>'; ?>
                                            <span class="validation-error-label" id="page_type"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-group-material">
                                    <div class="form-check">
                                        <label class="control-label form-check-label" for="exampleCheck1">Is Introduction Page?: </label>
                                        <?php if (!empty($dataArr) && $dataArr['is_default'] == 1) { ?>
                                            <input type="checkbox" class="form-check-input" name="is_default" value="1" checked id="is_default">
                                        <?php } else { ?>
                                            <input type="checkbox" class="form-check-input" name="is_default" value="0" id="is_default">
                                        <?php } ?>
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
    <script type="text/javascript" src="assets/js/custom_pages/terms_privacy.js"></script>
    <style>
        .wysihtml5-toolbar > li .dropdown-toggle{
            position: relative;
        }
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