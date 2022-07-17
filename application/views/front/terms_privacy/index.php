<html>
    <head>
        <title><?= $title ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/png" href="<?= site_url() ?>/assets/images/favicon.png"/>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?= site_url() ?>/assets/css/terms_privacy.css">

<!--        <link href="<?= site_url() ?>assets/css/font-awesome/font-awesome.css" rel="stylesheet">
        <link href="<?= site_url() ?>assets/css/font-awesome/font-awesome.min.css" rel="stylesheet">-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    </head>

    <body data-spy="scroll" data-target=".navbar" data-offset="1000">
        <div class="intro">
            <a class="navbar-brand" href="<?= site_url('/') ?>">
                <img src="<?= site_url('/') ?>/assets/images/logo.png" class="terms_logo" />
            </a>
            <h3 class="terms-title"><?= $page_title ?></h3>
        </div>
        <div class="collapse-btn">
            <a href="javascript:void(0)" class="bar-btn">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </a>
        </div>

        <?php 
            $privacy_policy_action = "";
            $service_policy_action = "";
            if($this->uri->segment(1) == "privacy_policies") {   
                $privacy_policy_action = "active"; 
            }
            if($this->uri->segment(1) == "terms_of_services") {   
                $service_policy_action = "active"; 
            }
        ?>

        <ul class="nav nav-tabs">
            <!-- <li> <i class="fa fa-bars" aria-hidden="true"></i></li> -->
            <li class="<?php echo $privacy_policy_action; ?>" id="privacy-policy-menu"><a data-toggle="tab" href="#privacy-policy">Privacy Policy</a></li>
            <li class="<?php echo $service_policy_action; ?>"  id="tos-menu"><a data-toggle="tab" href="#tos">Terms of Service</a></li>
            <li class="pull-right"><a href="<?= site_url() ?>">Home</a></li>
        </ul>   

        <div class="policy-terms-wrap">
            <div class="tab-content">
                <div id="privacy-policy" class="tab-pane fade in <?php echo $privacy_policy_action; ?>">
                    <div class="">
                        <div class="policy-terms-left">
                            <nav id="nav" class="navbar" data-spy="affix" data-offset-top="197">
                                <div class="">
                                    <div class="navbar-header">
                                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>                        
                                        </button>
                                    </div>
                                    <div>
                                        <div class="navbar-nav-wrap" id="">
                                            <ul class="nav navbar-nav">
                                                <?php
                                                if (!empty($terms_data)) {
                                                    foreach ($terms_data as $term) {
                                                        ?>
                                                        <li><a href="#<?= $term['slug'] ?>"><?= $term['page_title'] ?></a></li>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </div>

                        <?php if (!empty($terms_data)) { ?> 
                            <div class="policy-terms-right"> 
                                <?php foreach ($terms_data as $term) { ?>
                                    <div id="<?= $term['slug'] ?>" class="container-fluid">
                                        <p class="tp_page_title"><?= $term['page_title'] ?></p>
                                        <div class="tp_page_content">
                                            <?= $term['page_content'] ?>
                                        </div>
                                    </div>
                                <?php } ?> 
                            </div> 
                            <?php
                        } else {
                            ?>
                            <div class="policy-terms-right height-100">
                                <div class="container-fluid">
                                    <div class="tp_page_content">
                                        <img src="<?= site_url() ?>/assets/images/empty-result_shot.png" class="no-data-image" >
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div id="tos" class="tab-pane fade in <?php echo $service_policy_action; ?>">
                    <div class="">
                        <div class="policy-terms-left">
                            <nav id="nav" class="navbar" data-spy="affix" data-offset-top="197">
                                <div class="">
                                    <div class="navbar-header">
                                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>                        
                                        </button>
                                    </div>
                                    <div>
                                        <div class="navbar-nav-wrap" id="">
                                            <ul class="nav navbar-nav">
                                                <?php
                                                if (!empty($privacy_data)) {
                                                    foreach ($privacy_data as $privacy) {
                                                        ?>
                                                        <li><a href="#<?= $privacy['slug'] ?>"><?= $privacy['page_title'] ?></a></li>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </div>

                        <?php if (!empty($privacy_data)) { ?>
                            <div class="policy-terms-right">
                                <?php foreach ($privacy_data as $privacy) {
                                    ?>
                                    <div id="<?= $privacy['slug'] ?>" class="container-fluid">
                                        <p class="tp_page_title"><?= $privacy['page_title'] ?></p>
                                        <div class="tp_page_content">
                                            <?= $privacy['page_content'] ?>
                                        </div>
                                    </div>
                                <?php } ?> 
                            </div> 
                            <?php
                        } else {
                            ?>
                            <div class="policy-terms-right height-100">
                                <div class="container-fluid">
                                    <div class="tp_page_content">
                                        <img src="<?= site_url() ?>/assets/images/empty-result_shot.png" class="no-data-image" >
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script>
            $("#nav ul li a[href^='#']").on('click', function (e) {
                // prevent default anchor click behavior
                e.preventDefault();

                // store hash
                var hash = this.hash;

                // animate
                $('html, body').animate({
                    scrollTop: $(hash).offset().top - 100
                }, 1000, function () {

                    // when done, add hash to url
                    // (default click behaviour)
                    window.location.hash = hash;
                });

            });

            $(document).ready(function(){
                $('.bar-btn').click(function(){
                   $(this).closest(".collapse-btn").next(".nav-tabs").toggleClass("open-menu");
                }); 

                // Close side menu on select tab
                $(document).on('click','#tos-menu,#privacy-policy-menu',function(){
                    $('.nav-tabs').removeClass('open-menu');
                });
            });        
        </script>
    </body>
</html>