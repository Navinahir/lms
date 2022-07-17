<link rel="stylesheet" href="<?= site_url('assets/css/bootstrap-tagsinput.css') ?>" />

<?php
if (isset($dataArr)) {
    $form_action = site_url('admin/blogs/edit/' . base64_encode($dataArr['id']));
} else {
    $form_action = site_url('admin/blogs/add');
}
?>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('admin/blogs'); ?>" >Blogs</a></li>
            <li class="active"><?= (!empty($dataArr)) ? 'Edit' : 'Add' ?></li>
        </ul>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="<?php echo $form_action; ?>" class="form-horizontal" id="add_content_form" enctype="multipart/form-data" data-parsley-validate="true" >
                <div class="panel panel-flat">
                    <div class="panel-heading">
                        <h5 class="panel-title">Add Blog</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row mt-20">
                            <div class="col-md-12 pl-0 pr-0">
                                <div class="form-group-material has-feedback">
                                    <div class="row">
                                        <div class="col-md-6 form-group ml-0 mr-0">
                                            <label class="control-label required">Title :</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="blog_title" 
                                                   placeholder="Blog Title" 
                                                   id="blog_title"
                                                   data-parsley-required
                                                   data-parsley-required-message="Blog title is required" 
                                                   value="<?= (!empty($dataArr) && $dataArr['blog_title']) ? $dataArr['blog_title'] : '' ?>" />
                                        </div>
                                        <div class="col-md-6 form-group ml-0 mr-0">
                                            <label class="control-label required">Tags :</label>
                                            <input type="text" 
                                                   name="blog_tags"
                                                   id="blog_tags"
                                                   data-role="tagsinput"
                                                   placeholder="Add Tags"
                                                   class="form-control" 
                                                   data-parsley-required
                                                   data-parsley-required-message="Blog tags is required"
                                                   value="<?= (!empty($dataArr) && $dataArr['tags']) ? $dataArr['tags'] : '' ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-group-material has-feedback">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label required">Description:</label>
                                            <textarea 
                                                class="form-control" 
                                                placeholder="Description" 
                                                name="blog_description"
                                                id="blog_description"
                                                required=""
                                                data-parsley-errors-container="#description-errors"
                                                data-parsley-required-message="Blog description is required"
                                                data-parsley-group="block1"><?= (!empty($dataArr) && $dataArr['blog_content']) ? $dataArr['blog_content'] : '' ?></textarea>

                                            <div style="margin-bottom: 7px;" id="description-errors">
                                                <span class="parsley-required"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php $required_input = (!empty($dataArr)) ? '' : 'data-parsley-required' ?>

                            <div class="multi-media-content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label required">Media Contents:</label>
                                    </div>
                                </div>
                                <div class='element' id='div_1'>
                                    <div class="col-md-12 pr-0">
                                        <div class="form-group-material has-feedback">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group mr-0">
                                                        <select id='select_content_type_1' <?= $required_input ?> name="content_types[]" class="form-control content-type-select" data-rowid="1">
                                                            <option value="Video">Video</option>
                                                            <option value="Image">Image</option>
                                                            <option value="Document">Document</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group mr-0">
                                                        <input type='text' class="form-control" data-parsley-maxlength='255' <?= $required_input ?> data-parsley-required-message="Title is required" placeholder='Title' name="titles[]" id='title_1' >
                                                    </div>
                                                </div>
                                                <div class="col-md-5 pl-0">
                                                    <div class="row select2-plus-dropdown form-group">
                                                        <div class="col-md-10 col-sm-10 col-xs-10 select2-dropdown-custom">
                                                            <input type='file' class="form-control" data-parsley-fileextension='mp4' data-parsley-max-file-size="100" <?= $required_input ?> id='file_1' data-parsley-required-message="File is required" name="content_files[]" >
                                                        </div>
                                                        <div class="col-md-2 col-sm-2 col-xs-2 select2-dropdown-plus pl-0">
                                                            <button type='button' class="btn btn-info btn-sm add" style="padding: 7px 12px;"><i class="icon-plus3"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group form-group-material">
                                    <div class="col-md-12 pl-0 pr-0">
                                        <div class="form-check">
                                            <?php
                                            $is_active_value = (!empty($dataArr) && $dataArr['is_active'] == 1) ? 1 : 0;
                                            $is_checked = (!empty($dataArr) && $dataArr['is_active'] == 1) ? 'checked' : '';
                                            ?>

                                            <label class="control-label form-check-label" for="exampleCheck1">Is Active? :  
                                                <input type="checkbox" class="form-check-input" name="is_active" <?= $is_checked ?> value="<?= $is_active_value ?>" id="is_active">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 pl-0 pr-0">
                                <button type="submit" id="submit-button" class="btn bg-teal custom_save_button">Save</button>
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
</div>

<script type="text/javascript">
    var max = '<?= MAX_MEDIA_CONTENTS_FIELDS_LIMIT ?>'; //maximum input boxes allowed
    var blog_id = '<?= (!empty($dataArr) && $dataArr['id']) ? $dataArr['id'] : '' ?>';
</script>

<script src="<?= site_url('assets/js/bootstrap-tagsinput.min.js') ?>"></script>
<script type="text/javascript" src="assets/js/custom_pages/blogs.js?version='<?php echo time();?>'"></script>
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

    input.parsley-error {
        border: 1px solid #ff2a2a;
    }

    ul.parsley-errors-list{
        list-style-type: none;
        padding-left: 0px;  
    }

    ul.parsley-errors-list li{
        color: #ff2a2a;
    }

    #description-errors span {
        color: #ff2a2a;
    }
</style>

<script src="//cdn.ckeditor.com/4.11.2/full/ckeditor.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.8.1/parsley.min.js"></script>
<script>
    $("#blog_tags").tagsinput('items');
    CKEDITOR.replace('blog_description');

    $("form").submit(function () {
        var messageLength = CKEDITOR.instances['blog_description'].getData().replace(/<[^>]*>/gi, '').length;
        if (!messageLength) {
            $("#description-errors span").html('Blog Description is required');
        } else {
            $("#description-errors span").empty();
        }
    });

    window.ParsleyValidator.addValidator('fileextension', function (value, requirement) {
        var tagslistarr = requirement.split(',');
        var fileExtension = value.split('.').pop();
        var arr = [];
        $.each(tagslistarr, function (i, val) {
            arr.push(val);
        });
        if (jQuery.inArray(fileExtension, arr) != '-1') {
            return true;
        } else {
            return false;
        }
    }, 32).addMessage('en', 'fileextension', 'The file formats should be %s');

    window.Parsley.addValidator('maxFileSize', {
        validateString: function (_value, maxSize, parsleyInstance) {
            if (!window.FormData) {
                return true;
            }

            var files = parsleyInstance.$element[0].files;
            var file_size = files[0].size / 1024;
            var max_size = maxSize * 1024;

            return files.length != 1 || file_size <= max_size;
        },
        requirementType: 'integer',
        messages: {en: 'This file should not be larger than %s Mb'}
    });

    $('form').parsley();

</script>