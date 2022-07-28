<link href="<?= site_url('assets/blogs/css/font-awesome.min.css') ?>" rel="stylesheet"><!--font-awesome css-->

<link rel="stylesheet" type="text/css" href="<?= site_url('assets/blogs/css/style.css') ?>" />
<link rel="stylesheet" type="text/css" href="<?= site_url('assets/blogs/css/responsive.css') ?>"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" />
<script src="//cdn.jsdelivr.net/npm/afterglowplayer@1.x"></script>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('admin/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('admin/blogs'); ?>" >Blogs</a></li>
            <li class="active">View</li>
        </ul>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title"><?= $blogArr['blog_title'] ?></h5>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="blog-content-wrap">
                                <div class="blog-content-title">
                                    <h2><i class="fa fa-picture-o" aria-hidden="true"></i> Images</h2>
                                </div>
                                <div class="row">
                                    <?php
                                    $image_flage = FALSE;
                                    if (!empty($blogDataArr)) {
                                        foreach ($blogDataArr as $image_value) {
                                            if ($image_value['blog_content_type'] == 'Image') {
                                                $file_name = './uploads/blogs/' . $blogArr['blog_no'] . '/' . $image_value['file'];
                                                $filename = './uploads/blogs/' . $blogArr['blog_no'] . '/' . $image_value['file'];
                                                if (file_exists($file_name)) {
                                                    $image_flage = TRUE;
                                                    ?>
                                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" id="blog-content-div-<?= $image_value['id'] ?>">
                                                        <div class="doc-wrap">
                                                            <div class="thumbnail">
                                                                <a href="<?= site_url($filename) ?>" class="open-image">
                                                                    <img src="<?= site_url($filename) ?>" alt="<?= $image_value['alt'] ?>">
                                                                </a>
                                                            </div>

                                                            <div class="title">
                                                                <h2><?= $image_value['alt'] ?></h2>
                                                            </div>
                                                            <button type="button" data-fileurl="<?= $filename ?>" class="close remove-files" data-id="<?= $image_value['id'] ?>">
                                                                <span aria-hidden="true">
                                                                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                                                                </span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                        }
                                    }

                                    if ($image_flage == FALSE) {
                                        ?>
                                        <div class="col-md-12">
                                            <p>Image not found.</p>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="blog-content-wrap">
                                <div class="blog-content-title">
                                    <h2><i class="fa fa-file-video-o" aria-hidden="true"></i> Videos</h2>
                                </div>
                                <div class="row">
                                    <?php
                                    $video_flag = false;
                                    if (!empty($blogDataArr)) {
                                        foreach ($blogDataArr as $video_value) {
                                            if ($video_value['blog_content_type'] == 'Video') {
                                                $file_name = './uploads/blogs/' . $blogArr['blog_no'] . '/' . $video_value['file'];
                                                $filename = 'uploads/blogs/' . $blogArr['blog_no'] . '/' . $video_value['file'];
                                                if (file_exists($file_name)) {
                                                    $video_flag = TRUE;
                                                    ?>
                                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" id="blog-content-div-<?= $video_value['id'] ?>">
                                                        <div class="doc-wrap">
                                                            <div class="thumbnail">
                                                                <?php $video_url = site_url('uploads/blogs/' . $blogArr['blog_no'] . '/' . $video_value['file']); ?>
                                                                <video class="afterglow" id="myvideo" width="1280" height="720">
                                                                    <source type="video/mp4" src="<?= $video_url ?>" />
                                                                </video>
                                                            </div>
                                                            <div class="title">
                                                                <h2><?= $video_value['alt'] ?></h2>
                                                            </div>
                                                            <button type="button" class="close remove-files" data-fileurl="<?= $filename ?>" data-id="<?= $video_value['id'] ?>">
                                                                <span aria-hidden="true">
                                                                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                                                                </span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                        }
                                    }
                                    if ($video_flag == FALSE) {
                                        ?>
                                        <div class="col-md-12">
                                            <p>Video not found.</p>
                                        </div>
                                    <?php }
                                    ?>
                                </div>
                            </div>

                            <div class="blog-content-wrap">
                                <div class="blog-content-title">
                                    <h2><i class="fa fa-file-text-o" aria-hidden="true"></i> Documents</h2>
                                </div>
                                <div class="row">
                                    <?php
                                    $document_flag = false;
                                    if (!empty($blogDataArr)) {
                                        foreach ($blogDataArr as $document_value) {
                                            if ($document_value['blog_content_type'] == 'Document') {
                                                $file_name = './uploads/blogs/' . $blogArr['blog_no'] . '/' . $document_value['file'];
                                                $filename = 'uploads/blogs/' . $blogArr['blog_no'] . '/' . $document_value['file'];
                                                if (file_exists($file_name)) {
                                                    $document_flag = true;
                                                    ?>
                                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" id="blog-content-div-<?= $document_value['id'] ?>">
                                                        <div class="doc-wrap">
                                                            <div class="thumbnail">
                                                                <img src="<?= site_url('assets/blogs/img/docs-preview.png') ?>" alt="image">
                                                            </div>
                                                            <div class="title">
                                                                <a href="<?= site_url($filename) ?>" download><h2><?= $document_value['alt'] ?>&nbsp;&nbsp;<i class="fa fa-download"></i></h2></a>
                                                            </div>
                                                            <button type="button" class="close remove-files" data-fileurl="<?= $filename ?>" data-id="<?= $document_value['id'] ?>">
                                                                <span aria-hidden="true">
                                                                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                                                                </span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                        }
                                    }

                                    if ($document_flag == FALSE) {
                                        ?>
                                        <div class="col-md-12">
                                            <p>Document not found.</p>
                                        </div>
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .doc-wrap {
        position: relative;
        background-color: #f9f9f9;
        box-shadow: 0px 1px 7px #cacaca;
    }
    .doc-wrap .thumbnail > img, .thumbnail a > img {
        width: 100%;
        height: auto;
        max-height: 150px;
        object-fit: cover;
        object-position: center;
    }
    .doc-wrap .thumbnail{
        border-radius: 0;
        border: none;
        margin-bottom: 0px;
    }
    .doc-wrap .title{
        padding: 15px;
        min-height: 57px;
    }
    .doc-wrap .title h2{    
        margin: 0;
        font-size: 18px;
        text-transform: capitalize;
        color: #595959;
        overflow: hidden;
        text-overflow: ellipsis;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
    }
    .doc-wrap .close{
        position: absolute;
        top: -8px;
        right: -8px;
    }
    .blog-content-title h2{
        font-size: 28px;
        margin-top: 10px;
    }
    .blog-content-title h2 .fa{
        color: #6bc4ed;
    }
    .blog-content-title {
        margin-bottom: 5px;
        display: inline-block;
    }
    .doc-wrap:hover .title h2 {
        color: #03a9f4;
    }
    .blog-content-wrap {
        margin: 15px 0;
    }
    @media(max-width: 767px){	
        .doc-wrap {
            margin-bottom: 30px;
        }
        @media(min-width:544px) and (max-width:767px){
            .col-xs-12{width: 50%;}
        }
    }
    @media(max-width: 768px){	
        .doc-wrap {
            margin-bottom: 15px;
        }

       /* .doc-wrap .thumbnail > img, .thumbnail a > img {
            width: 456px;
            height: 349px;
        }*/
        @media(min-width:544px) and (max-width:767px){
            .col-xs-12{width: 50%;}
        }
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>
<script type="text/javascript">
    $('.open-image').click(function (e) {
        e.preventDefault();
        $(this).ekkoLightbox();
    });
    $(document).on('click', '[data-toggle="lightbox"]', function (event) {
        event.preventDefault();
        return $(this).ekkoLightbox({
            wrapping: false
        });
    });

    $(document).ready(function () {
        $(".remove-files").click(function (e) {
            var blog_content_id = $(this).attr('data-id');
            var file_url = $(this).attr('data-fileurl');

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#FF7043",
                confirmButtonText: "Delete",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    var url = site_url + 'admin/blogs/remove_blog_media_content_files';
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            blog_content_id: blog_content_id,
                            file_url: file_url
                        },
                        success: function (data) {
                            if (data == 1) {
                                $("#blog-content-div-" + blog_content_id).remove();
                                swal("Deleted!", "Your imaginary file has been deleted.", "success");
                            } else {
                                swal("Error", "Something went wrong!", "error");
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            swal("Error", "Something went wrong!", "error");
                        }
                    });
                } else {
                    swal("Cancelled", "Your imaginary file is safe :)", "error");
                }
            });
            return false;
        });
    });
</script>