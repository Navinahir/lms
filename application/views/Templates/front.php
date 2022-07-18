<!DOCTYPE html>
<html class="no-js" lang="en">
    <head>
        <base href="<?php echo base_url(); ?>">
        <meta content="charset=utf-8">
        <title><?php echo $title; ?></title>
        <link rel="icon" href="assets/images/logo.ico" type="image/x-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <link href="assets/css/bootstrap.css?version='<?php echo time();?>'" rel="stylesheet"/>
        <link href="assets/css/fontawesome-all.css?version='<?php echo time();?>'" rel="stylesheet"/>
        <link href="assets/css/custom.css?version='<?php echo time();?>'" rel="stylesheet"/>
        <link href="assets/css/developer.css?version='<?php echo time();?>'" rel="stylesheet"/>

        <script type="text/javascript" src="assets/js/core/libraries/jquery.min.js?version='<?php echo time();?>'"></script>
        <script src="assets/js/bootstrap.js?version='<?php echo time();?>'" ></script>
        <script type="text/javascript" src="assets/js/plugins/forms/validation/validate.min.js?version='<?php echo time();?>'"></script>
        <style>
            i.icon-checkmark-circle {
                color: green;
            }
        </style>
        <script type="text/javascript">
            var currentDate = new Date();
            var currentOffset = (currentDate.getTimezoneOffset() * -1) * 60;
            document.cookie = "currentOffset=" + currentOffset;
        </script>
        <noscript>
        <META HTTP-EQUIV="Refresh" CONTENT="0;URL=js_disabled">
        </noscript>
    </head>
    <body>
        <?php echo $body; ?>
        <script>
            $(document).ready(function () {
                $('.carousel').carousel({
                    interval: false
                })
            })
        </script>
    </body>
</html>