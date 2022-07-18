<link href="<?= site_url('assets/blogs/css/font-awesome.min.css') ?>" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?= site_url('assets/blogs/css/style.css') ?>" />
<link rel="stylesheet" type="text/css" href="<?= site_url('assets/blogs/css/responsive.css') ?>"/>

<script type="text/javascript" src="assets/js/fakeLoader.js"></script>
<script type="text/javascript" src="assets/js/fakeLoader.min.js"></script>
<link href="assets/css/fakeloader.css" rel="stylesheet" type="text/css" />

<style type="text/css">
    .blog-meta h1{font-size: 20px;margin-top: 10px;word-break: break-all;overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 1;}
    .doc-wrap {position: relative;background-color: #f9f9f9;box-shadow: 0px 1px 7px #cacaca;}
    .doc-wrap .thumbnail{border-radius: 0;border: none;margin-bottom: 0px;}
    .doc-wrap .title{padding: 15px;}
    .doc-wrap .title h2{margin: 0;font-size: 18px;text-transform: capitalize;color: #595959;}
    .doc-wrap .close{position: absolute;top: -8px;right: -8px;}
    .doc-wrap .close:hover {}
    .doc-wrap .thumbnail > img, .thumbnail a > img {width: 100%;height: 200px;object-fit: contain;
    object-position: center;}
    .blog-meta {max-height: 45px;overflow: hidden;}
    .blog-content-title h2{font-size: 28px;}
    .blog-content-title h2 .fa{color: #6bc4ed;}
    .blog-content-title {margin-bottom: 5px;display: inline-block;}
    .doc-wrap:hover .title h2 {color: #03a9f4;}
    /*.blog-content-wrap {margin: 30px 0;}*/
    .blog-detail-wrap .blog-detail .title {padding: 0 0 10px 0;}
    .blog-detail-wrap .blog-detail {padding: 15px;}
    .blog-detail-wrap .blog-date {text-align: right;margin-bottom: 15px;font-size: 12px;margin-top: -10px;color: grey;}
    .blog-detail-wrap a {font-weight: 600;}
    .blog-detail-main{margin-bottom: 30px;}
    /*.blog-detail-wrap {margin-bottom: 30px;}*/
    .blog-detail-wrap .w3-tag {background-color: #2b3c48;color: #fff;display: inline-block;padding-left: 8px;padding-right: 8px;text-align: center;}
    .blog-detail-wrap .blog-tag {margin-bottom: 10px;}

    .blog-detail-main .blog-detail-wrap{
        height: 100%;
    }

    @media screen and (min-width:768px){
        .blog-detail-main .blog-detail-wrap .blog-detail .title h2{
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            max-height: 55px;
            height: 55px;
        }
    }

    @media(max-width: 767px){	
        /*.doc-wrap {
            margin-bottom: 30px;
        }*/
        /*@media(min-width:544px) and (max-width:767px){
            .col-xs-12{width: 50%;}
        }*/
    }
</style>

<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/dashboard'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li class="active">Blogs</li>
        </ul>
    </div>
</div>

<div class="content">
    <div class="panel panel-flat">
        <div class="panel-body">
            <div class="blog-content-wrap">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 blog-content-title pl-0 pr-0">
                        <div class="col-sm-4 col-md-5 text-left">
                            <h2><i class="fas fa-book-open" aria-hidden="true"></i> Blogs</h2>
                        </div>
                        <div class="col-sm-8 col-md-7 form-inline mt-20 text-right mb-3">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-8 col-sm-7 col-md-7 blog-search-main">
                                        <input type="text" class="form-control" name="blog_search" id="search_blog_option" placeholder="Search">
                                    </div>&nbsp;
                                    <div class="col-xs-4 col-sm-5 col-md-5 blog-searchbtn-main">
                                        <button type="button" id="blog_search_button" class="btn btn-primary blog-search-btn"><i class="fa fa-search"></i></button>&nbsp;
                                        <a href="<?= site_url('blogs') ?>" class="btn btn-default blog-refresh-btn" title="Refresh"><i class="fa fa-refresh"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row d-flex flex-wrap" id="blogs-list">
                    <?php
                    if (!empty($records)) {
                        foreach ($records as $value) {
                            ?>
                            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 blog-detail-main">
                                <div class="doc-wrap blog-detail-wrap">
                                    <div class="thumbnail">
                                        <?php
                                        $default_image = site_url('assets/blogs/img/image-preview-blog-detail.png');

                                        $filename = './uploads/blogs/' . $value['blog_no'] . '/' . $value['file'];
                                        if (!empty($value['file']) && file_exists($filename)) {
                                            $default_image = site_url('/uploads/blogs/' . $value['blog_no'] . '/' . $value['file']);
                                        }
                                        ?>
                                        <img src="<?= $default_image ?>" alt="image">
                                    </div>
                                    <div class="blog-detail">
                                        <div class="blog-date"><em><?= date('dS F Y', strtotime($value['created_date'])) ?></em></div>
                                        <div class="title">
                                            <a href="<?= site_url('blogs/' . base64_encode($value['id'])) ?>">
                                                <h2><?= $value['blog_title'] ?></h2>
                                            </a>
                                        </div>
                                        <!-- <div class="blog-tag">
                                            <?php
                                            $tagsArr = explode(',', $value['tags']);

                                            foreach ($tagsArr as $tag) {
                                                ?>
                                                <span class="w3-tag w3-blue mb-2"><?= $tag ?></span>
                                            <?php } ?>
                                        </div> -->
                                        <!-- <div class="blog-meta">
                                            <?= $value['blog_content'] ?>
                                        </div> -->
                                        <a class="read-more-anchor" href="<?= site_url('blogs/' . base64_encode($value['id'])) ?>">Read More... </a>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="col-md-12 text-center">
                            <div class="title">
                                <h3>Blog not found.</h3>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                
                <div class="row">
                    <div class="col-md-12 text-center hide" id="blog-not-found-text">
                        <div class="title">
                            <h3>Blog not found.</h3>
                        </div>
                    </div>
                </div>

                <?php if (!empty($records)) { ?>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                            <button type="button" class="btn btn-primary btn-lg" id="l-m-bl-gs" data-start="<?= $start ?>">
                                Load More...
                            </button>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div id="fakeLoader" class="loading"></div>
        </div>
    </div>
    <?php $this->load->view('Templates/footer'); ?>
</div>


<script type="text/javascript">
    var url = site_url + 'dashboard/get_blogs';

    $("#l-m-bl-gs").click(function () {
        var start = parseFloat($(this).attr('data-start')) + 10;
        var searched_text = $("input[name=blog_search]").val();

        $('#custom_loading').removeClass('hide');
        $('#custom_loading').css('display', 'block');

        var data = {
            start: start,
            searched_text: searched_text
        };

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'HTML',
            success: function (data) {
                if (data) {
                    $("#blogs-list").append(data);
                    $("#l-m-bl-gs").attr('data-start', start);
                } else {
                    $("#l-m-bl-gs").attr('disabled', true).html('No more blog found.');
                }
                $("#custom_loading").fadeOut(1000);
            }
        });
    });

    $("#blog_search_button").click(function () {
        var start = $("#l-m-bl-gs").attr('data-start');
        var searched_text = $("input[name=blog_search]").val();
        search_blog(start,searched_text);         
    });

    $(document).on('keyup','#search_blog_option',function(){
        var start = $("#l-m-bl-gs").attr('data-start');
        var searched_text = $("input[name=blog_search]").val();
        search_blog(start,searched_text);         
    });

    function search_blog(start,searched_text){
        // $('#custom_loading').removeClass('hide');
        // $('#custom_loading').css('display', 'block');
        var data = {
            start: start,
            searched_text: searched_text
        };

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'HTML',
            success: function (data) {
                if (data) {
                    $("#blog-not-found-text").addClass('hide');
                    $("#blogs-list").empty().append(data);
                    $("#l-m-bl-gs").attr('data-start', start);
                    $("#l-m-bl-gs").attr('disabled', false).html('Load More...');
                } else {
                    $("#blogs-list").empty();
                    $("#blog-not-found-text").removeClass('hide');
                    $("#l-m-bl-gs").attr('disabled', true).html('No more blog found.');
                }
                // $("#custom_loading").fadeOut(1000);
            }
        });
    }
</script>