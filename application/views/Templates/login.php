<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo base_url(); ?>">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $title; ?></title>

        <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
        <link href="assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
        <link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="assets/css/core.css" rel="stylesheet" type="text/css">
        <link href="assets/css/components.css" rel="stylesheet" type="text/css">
        <link href="assets/css/colors.css" rel="stylesheet" type="text/css">
        <link href="assets/css/custom_pav.css" rel="stylesheet" type="text/css">
        <!-- <link href="assets/css/icons/fontawesome/font-awesome.css" rel="stylesheet" type="text/css"> -->

        <script type="text/javascript" src="assets/js/plugins/loaders/pace.min.js"></script>
        <script type="text/javascript" src="assets/js/core/libraries/jquery.min.js"></script>
        <script type="text/javascript" src="assets/js/core/libraries/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/loaders/blockui.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/validation/validate.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
        <script type="text/javascript" src="assets/js/core/app.js"></script>
        <script type="text/javascript" src="assets/js/custom_pages/login.js"></script>
        <script>
            $(function () {
                Pace.on('start', function () {
                    $('#custom_loading').show();
                });
                Pace.on('done', function () {
                    $('#custom_loading').hide();
                });
            })
            // $(function(){
            // 	$('.login-cover').css({
            //      'background':'url(assets/images/header-bg.jpg) 50% 50% no-repeat',
            //      'background-size': 'cover',
            //      'background-attachment': 'fixed',
            //      '-webkit-background-size':'cover'
            //  });
            // });
        </script>
        <noscript>
        <META HTTP-EQUIV="Refresh" CONTENT="0;URL=js_disabled">
        </noscript>
        <link rel="icon" href="assets/images/favicon.png" type="image/png">
    </head>

    <body class="login-container login-cover">
        <div id="custom_loading" style="display:none;">
            <div id="loading-center">
                <svg version="1.1" id="L7" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
                <path fill="#009688" d="M31.6,3.5C5.9,13.6-6.6,42.7,3.5,68.4c10.1,25.7,39.2,38.3,64.9,28.1l-3.1-7.9c-21.3,8.4-45.4-2-53.8-23.3c-8.4-21.3,2-45.4,23.3-53.8L31.6,3.5z">
                <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="2s" from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                </path>
                <path fill="#26A69A" d="M42.3,39.6c5.7-4.3,13.9-3.1,18.1,2.7c4.3,5.7,3.1,13.9-2.7,18.1l4.1,5.5c8.8-6.5,10.6-19,4.1-27.7c-6.5-8.8-19-10.6-27.7-4.1L42.3,39.6z">
                <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="-360 50 50" repeatCount="indefinite" />
                </path>
                <path fill="#74afa9" d="M82,35.7C74.1,18,53.4,10.1,35.7,18S10.1,46.6,18,64.3l7.6-3.4c-6-13.5,0-29.3,13.5-35.3s29.3,0,35.3,13.5L82,35.7z">
                <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="2s" from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                </path>
                </svg>
            </div>
        </div>
        <div class="page-container">
            <div class="page-content">
                <div class="content-wrapper">
                    <div class="content pb-20">
                        <?php echo $body; ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<style>
    .login-cover{background: url(assets/images/header-bg2.jpg) no-repeat;background-size: cover;}
    body{
        font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
        position:relative;	
    }
    body.login-cover::before {
        background: rgba(0, 0, 0, 0.5) none repeat scroll 0 0;
        bottom: 0;
        content: "";
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
    }
    .site-name{ font-size:50px;color:#fff;text-align: center; }
    .site-name span{ color:#03a9f4; }
    p{
        font-size: 20px;
        color: #ccc;
        text-align: center;
    }
    .login-form{
        background: rgba(0, 0, 0, 0.35);
        border-color: rgba(0, 0, 0, 0.35);
        border-radius: 30px 0 0 0;
        width:450px !important;
    }
    .checker span{
        color: #ffffff;
        border: 2px solid #ffffff;
    }
    .checkbox-inline,
    .login-form a{
        color:#ffffff;
    }
    .login-form a:hover{ text-decoration: underline; }
    .btn_login,
    .btn_reset_pwd,
    .btn_forgot_pwd,
    .btn_cancel{ font-size:17px;font-weight:500; }
    input[type=text],input[type=password]{
        border:2px solid #03a9f400;
    }
    input[type=text]:hover,
    input[type=password]:hover{
        border:2px solid #03A9F4;
    }
    .animated {
        animation: fadein 2s;
        -moz-animation: fadein 2s; /* Firefox */
        -webkit-animation: fadein 2s; /* Safari and Chrome */
        -o-animation: fadein 2s; /* Opera */
    }
    @keyframes fadein {
        from {
            opacity:0;
        }
        to {
            opacity:1;
        }
    }
    @-moz-keyframes fadein { /* Firefox */
        from {
            opacity:0;
        }
        to {
            opacity:1;
        }
    }
    @-webkit-keyframes fadein { /* Safari and Chrome */
        from {
            opacity:0;
        }
        to {
            opacity:1;
        }
    }
    @-o-keyframes fadein { /* Opera */
        from {
            opacity:0;
        }
        to {
            opacity: 1;
        }
    }
    @media (max-width: 480px){ 
        .login-form{
            width:100% !important;
        }
        .site-name{ font-size:30px; }
        .login-cover{background-size:auto;}
    } 
</style>
