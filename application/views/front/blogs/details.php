<link href="<?= site_url('assets/blogs/css/font-awesome.min.css') ?>" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?= site_url('assets/blogs/css/style.css') ?>" />
<link rel="stylesheet" type="text/css" href="<?= site_url('assets/blogs/css/responsive.css') ?>"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" />
<style type="text/css">
    .doc-wrap {position: relative;background-color: #f9f9f9;box-shadow: 0px 1px 7px #cacaca;}
    .doc-wrap .thumbnail{border-radius: 0;border: none;margin-bottom: 0px;}
    .doc-wrap .thumbnail video{ height: 184px;width: 100%;}
    .doc-wrap .title{padding: 15px;}
    .doc-wrap .title h2{margin: 0;font-size: 18px;text-transform: capitalize;color: #595959;}
    .doc-wrap .close{position: absolute;top: -8px;right: -8px;}
    .doc-wrap .close:hover {}
    .blog-content-title h2{font-size: 28px;}
    .blog-content-title h2 .fa{color: #6bc4ed;}
    .blog-content-title {margin-bottom: 5px;display: inline-block;}
    .doc-wrap:hover .title h2 {color: #03a9f4;}
    .blog-content-wrap {margin: 30px 0;}
    .blog-detail-wrap .blog-detail .title {padding: 0 0 10px 0;}
    .blog-detail-wrap .blog-detail {padding: 15px;}
    .blog-detail-wrap .blog-date {text-align: right;margin-bottom: 15px;font-size: 12px;margin-top: -10px;color: grey;}
    .blog-detail-wrap a {font-weight: 600;}
    .blog-detail-wrap {margin-bottom: 30px;}
    .blog-detail-wrap .w3-tag {background-color: #2b3c48;color: #fff;display: inline-block;padding-left: 8px;padding-right: 8px;text-align: center;margin-bottom: 5px;}
    .blog-detail-wrap .blog-tag {margin-bottom: 10px;}

    /*************18-2*************/
    .blog-inner-wrap {background-color: white;padding-bottom: 50px;}
    .blog-inner-wrap .thumbnail {height: 350px;overflow: hidden;padding: 0;border-radius: 0;border: none;}
    .blog-inner-wrap .thumbnail img{width: 100%;height: 190px;object-fit: contain; object-position: center;}
    .blog-inner-wrap .thumbnail img.banner-image{width: 100%;height: 350px;object-fit: cover;object-position: center;}
    .w3-tag {background-color: #2b3c48;color: #fff;display: inline-block;padding-left: 8px;padding-right: 8px;text-align: center;}
    .blog-inner-wrap .blog-detail .blog-tag {margin-bottom: 20px;}
    .blog-inner-wrap .blog-detail  p {font-size: 18px;line-height: 1.9;color: #5D6769;margin: 20px 0;}
    .blog-inner-wrap .blog-detail .blog-meta em{font-size: 18px;line-height: 1.9;color: #292b2c;text-align: center !important;display: inline-block;margin: 20px 0;}
    .blog-inner-wrap .blog-detail {margin: 0 70px;padding-bottom: 20px;border-bottom: 1px solid #03a9f4;}
    .blog-inner-wrap .blog-detail h2 {font-size: 34px;color: #03a9f4;}
    /*.blog-inner-wrap .blog-detail .blog-meta-video , .blog-inner-wrap .blog-meta-image{margin: 50px 160px;}*/
    .blog-inner-wrap .blog-detail .blog-meta-video video {width: 100%;}
    .blog-inner-wrap .blog-meta-image .thumbnail {height: inherit;padding: 4px;}
    .blog-inner-wrap .blog-meta-image .doc-wrap {margin-bottom: 30px;}
    .blog-inner-wrap .blog-detail  .blog-date{font-weight: 600;color: #b9b6b6;}
    
    .doc-wrap .image-title{padding: 15px;}
    .doc-wrap .image-title h2{margin: 0;font-size: 12px;text-transform: capitalize;color: #595959;}

    @media screen and (max-width:1199px){
        .blog-inner-wrap .blog-detail{
            margin:0px 30px;
        }
    }

    @media(max-width: 768px){
        .blog-inner-wrap .blog-detail .blog-meta-video, .blog-inner-wrap .blog-meta-image {margin: 50px 0;}
        .blog-inner-wrap{padding-bottom: 25px;}
    }

    @media(max-width: 767px){	
        .doc-wrap {margin-bottom: 30px;}
        .blog-inner-wrap .blog-detail {margin: 0 20px;padding-bottom: 0px;}
        .blog-inner-wrap .blog-detail h2 {font-size: 20px;}
        .blog-inner-wrap .blog-detail p {font-size: 15px;line-height: 1.5;}
        .blog-inner-wrap .blog-detail .blog-meta-video, .blog-inner-wrap .blog-meta-image {margin: 0px 0;}
        .blog-inner-wrap .blog-detail .blog-meta em {font-size: 16px;line-height: 1.5;margin: 10px 0;}
        .blog-inner-wrap .thumbnail {height: 350px;}
    }
    @media(min-width:544px) and (max-width:767px){
        .col-xs-12{width: 50%;}
    }
</style>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li><a href="<?php echo site_url('/blogs'); ?>">Blogs</a></li>
            <li class="active"><?= $blogArr['blog_title'] ?></li>
        </ul>
    </div>
</div>

<div class="content">
    <div class="blog-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <div class="blog-inner-wrap">
                    <?php
                    $default_image = site_url('assets/blogs/img/image-preview-blog-detail.png');

                    $filename = './uploads/blogs/' . $blogArr['blog_no'] . '/' . $blogImage['file'];
                    if (!empty($blogImage['file']) && file_exists($filename)) {
                        $default_image = site_url('uploads/blogs/' . $blogArr['blog_no'] . '/' . $blogImage['file']);
                    }
                    ?>

                    <div class="thumbnail">
                        <img class="banner-image" src="<?= $default_image ?>" alt="image">
                    </div>

                    <div class="blog-detail">

                        <div class="blog-date"><em><?= date('dS F Y', strtotime($blogArr['created_date'])) ?></em></div>

                        <div class="title">
                            <h2><?= $blogArr['blog_title'] ?></h2>
                        </div>

                        <div class="blog-tag">
                            <?php
                            $tagsArr = explode(',', $blogArr['tags']);

                            foreach ($tagsArr as $tag) {
                                ?>
                                <span class="w3-tag w3-blue"><?= $tag ?></span>
                            <?php } ?>
                        </div>

                        <div class="blog-meta">
                            <?= $blogArr['blog_content'] ?>
                        </div>

                        <h2>Media Contents</h2>
                        <hr>
                        <div class="blog-meta-image">
                            <div class="row">
                                <?php
                                if (!empty($blogDataArr)) {
                                    foreach ($blogDataArr as $image_value) {
                                        if ($image_value['blog_content_type'] == 'Image') {
                                            $file_name = './uploads/blogs/' . $blogArr['blog_no'] . '/' . $image_value['file'];
                                            $filename = 'uploads/blogs/' . $blogArr['blog_no'] . '/' . $image_value['file'];
                                            if (file_exists($file_name)) {
                                                ?>
                                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                                    <div class="doc-wrap ">
                                                        <div class="thumbnail">
                                                            <a href="<?= site_url($filename) ?>" class="open-image">
                                                                <img src="<?= site_url($filename) ?>" alt="<?= $image_value['alt'] ?>">
                                                            </a>
                                                        </div>
                                                        <div class="image-title">
                                                            <h2><?= $image_value['alt'] ?></h2>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <?php
                                            }
                                        }
                                    }

                                    foreach ($blogDataArr as $document_value) {
                                        if ($document_value['blog_content_type'] == 'Document') {
                                            $file_name = './uploads/blogs/' . $blogArr['blog_no'] . '/' . $document_value['file'];
                                            $filename = 'uploads/blogs/' . $blogArr['blog_no'] . '/' . $document_value['file'];
                                            if (file_exists($file_name)) {
                                                ?>
                                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                                    <div class="doc-wrap ">
                                                        <div class="thumbnail">
                                                            <a href="<?= site_url($filename) ?>" download>
                                                                <img src="<?= site_url('assets/blogs/img/docs-preview.png') ?>" alt="image">
                                                            </a>
                                                        </div>
                                                        <div class="image-title">
                                                            <h2><?= $document_value['alt'] ?></h2>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                    }

                                    foreach ($blogDataArr as $video_value) {
                                        if ($video_value['blog_content_type'] == 'Video') {
                                            $file_name = './uploads/blogs/' . $blogArr['blog_no'] . '/' . $video_value['file'];
                                            $filename = 'uploads/blogs/' . $blogArr['blog_no'] . '/' . $video_value['file'];
                                            if (file_exists($file_name)) {
                                                $video_url = site_url($filename);
                                                $file_ext = pathinfo($video_url, PATHINFO_EXTENSION);
                                                ?>
                                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                                    <div class="doc-wrap ">
                                                        <div class="thumbnail">
                                                            <video controls width='' height=''>
                                                                <source src='<?= $video_url ?>' type='video/<?= $file_ext ?>'>
                                                            </video>
                                                        </div>
                                                        <div class="image-title">
                                                            <h2><?= $video_value['alt'] ?></h2>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                    }
                                } else {
                                    ?>
                                    <div class="col-md-12">
                                        <h3>File not found.</h3>
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
    <?php $this->load->view('Templates/footer'); ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>

<script>
    $('.open-image').click(function (e) {
        e.preventDefault();
        $(this).ekkoLightbox();
    });
</script>