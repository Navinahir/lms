<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo base_url(); ?>">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $title; ?></title>
        <?php
        $class = $this->router->fetch_class();
        $action = $this->router->fetch_method();
        ?>
        <!-- Global stylesheets -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
        <link href="assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
        <link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css">
        <link href="assets/css/core.css" rel="stylesheet" type="text/css">
        <link href="assets/css/components.css" rel="stylesheet" type="text/css">
        <link href="assets/css/colors.css" rel="stylesheet" type="text/css">
        <link href="assets/css/custom_pav.css" rel="stylesheet" type="text/css">
        <link href="assets/css/vendor_dashboard.css" rel="stylesheet" type="text/css">
        <!-- /global stylesheets -->
        <link rel="icon" href="assets/images/favicon.png" type="image/png">
        <script type="text/javascript">
            var site_url = "<?php echo site_url() ?>";
            var base_url = "<?php echo base_url() ?>";
            var domain = "<?php echo MY_DOMAIN_NAME ?>";
            var currentDate = new Date();
            var currentOffset = (currentDate.getTimezoneOffset() * -1) * 60;
            document.cookie = "currentOffset=" + currentOffset;
            var remoteURL = '';
            var role = '<?php echo checkVendorLogin('R') ?>';
            var add = '<?php echo (checkVendorLogin('R') == 5) ? 1 : 0 ?>';
            var edit = '<?php echo (checkVendorLogin('R') == 5) ? 1 : 0 ?>';
            var dlt = '<?php echo (checkVendorLogin('R') == 5) ? 1 : 0 ?>';
            var view = '<?php echo (checkVendorLogin('R') == 5) ? 1 : 0 ?>';
        </script>

        <!-- Core JS files -->
        <script type="text/javascript" src="assets/js/plugins/loaders/pace.min.js"></script>
        <script type="text/javascript" src="assets/js/core/libraries/jquery.min.js"></script>
        <script type="text/javascript" src="assets/js/core/libraries/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/loaders/blockui.min.js"></script>
        <!-- /core JS files -->

        <!-- Theme JS files -->
        <script type="text/javascript" src="assets/js/plugins/visualization/d3/d3.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/styling/switchery.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/styling/switch.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
        <script type="text/javascript" src="assets/js/plugins/ui/moment/moment.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/pickers/daterangepicker.js"></script>
        <script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/responsive.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
        <script type="text/javascript" src="assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/validation/validate.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/forms/inputs/touchspin.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/notifications/sweet_alert.min.js"></script>
        <script type="text/javascript" src="assets/js/plugins/media/fancybox.min.js"></script>
        <!-- <script type="text/javascript" src="assets/js/pages/gallery_library.js"></script> -->

        <script type="text/javascript" src="assets/js/core/app.js"></script>

        <script type="text/javascript" src="assets/js/pages/form_select2.js"></script>
        <script type="text/javascript" src="assets/js/pages/invoice_grid.js"></script>
        <script type="text/javascript" src="assets/js/pages/form_layouts.js"></script>
        <script type="text/javascript" src="assets/js/pages/form_multiselect.js"></script>
        <script>
            $(function () {
                Pace.on('start', function () {
                    $('#custom_loading').show();
                });
                Pace.on('done', function () {
                    $('#custom_loading').hide();
                });
                // Lightbox
                $('[data-popup="lightbox"]').fancybox({
                    padding: 3
                });
            })
        </script>
        <script>
            $('document').ready(function () {
                $('.flashmsg').fadeOut(6000);
            });
        </script>
        <!-- <script type="text/javascript" src="assets/js/pages/dashboard.js"></script> -->
        <!-- /theme JS files -->
        <noscript>
        <META HTTP-EQUIV="Refresh" CONTENT="0;URL=js_disabled">
        </noscript>
    </head>
    <body class="<?php echo (!empty($_COOKIE['sidemenuopen']) && $_COOKIE['sidemenuopen'] == 'sideclose') ? 'sidebar-xs' : '' ?>">
        <!-- Main navbar -->
        <div class="navbar navbar-inverse custom_navbar vendor_custom_navbar">
            <div class="navbar-header">
                <a class="navbar-brand" href="<?php echo site_url('/'); ?>" style="letter-spacing: 3px;font-weight: 500;font-size: 16px;line-height:2">
                    <?php // echo 'Always Reliable Keys'; ?>
                    <img src="assets/images/logo_white.png" alt="">
                </a>

                <ul class="nav navbar-nav visible-xs-block">
                    <li><a class="sidebar-mobile-main-toggle"><img src="assets/images/hamburger.png" alt=""></a></li>
                </ul>
            </div>

            <div class="navbar-collapse collapse" id="navbar-mobile">
                <ul class="nav navbar-nav">
                    <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><img src="assets/images/hamburger.png" alt=""></a></li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown dropdown-user">
                        <a class="dropdown-toggle" data-toggle="dropdown">
                            <?php if (checkVendorLogin('P') != '') {
                                ?>
                                <img src="uploads/profile/<?php echo checkVendorLogin('P') ?>" alt="" class="img-circle img-sm">
                                <?php
                            } else {
                                ?>
                                <img src="assets/images/user_image.png" alt="">
                                <?php
                            }
                            ?>
                            <span><?php echo strtoupper(checkVendorLogin('U')); ?></span>
                            <i class="caret"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="<?php echo site_url('/vendor/change_password'); ?>"><i class="icon-lock"></i> Change Password</a></li>
                            <li><a href="<?php echo site_url('/vendor/logout'); ?>"><i class="icon-switch2"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /main navbar -->

        <!-- Page container -->
        <div class="page-container custom_content">
            <div class="page-content">
                <div class="sidebar sidebar-main">
                    <div class="sidebar-content">
                        <div class="sidebar-user">
                            <div class="category-content">
                                <div class="media">
                                    <a href="#" class="media-left profile_img"><?php if (checkVendorLogin('P') != '') {
                                ?>
                                            <img src="uploads/profile/<?php echo checkVendorLogin('P') ?>" alt="" class="img-circle img-sm">
                                            <?php
                                        } else {
                                            ?>
                                            <img src="assets/images/user_image.png" alt="" class="img-circle img-sm">
                                            <?php
                                        }
                                        ?></a>
                                    <div class="media-body user_profile">
                                        <span class="media-heading text-semibold"><?php echo strtoupper(checkVendorLogin('U')); ?></span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="sidebar-category sidebar-category-visible">
                            <div class="category-content no-padding">
                                <ul class="navigation navigation-main navigation-accordion">
                                    <li class="<?php echo ($class == 'home') ? 'active' : '' ?>"><a href="<?php echo site_url('vendor/home'); ?>"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
                                    <?php if (checkVendorLogin('R') == 5) { ?>
                                        <li class="<?php echo ($class == 'vendors' || $class == 'roles') ? 'active' : ''; ?>">
                                            <a href="#" class="has-ul"><i class="icon-users4"></i><span>Users & Roles</span></a>
                                            <ul class="hidden-ul" style="<?php echo ($class == 'vendors' || $class == 'roles') ? 'display:block' : ''; ?>">
                                                <li class="<?php echo ($class == 'roles' && ($action == 'display_roles' || $action == 'add_edit_roles')) ? 'active' : ''; ?>"><a href="<?php echo site_url('/vendor/roles'); ?>"><i class="icon-user-tie"></i><span>Roles & Permission</span></a></li>
                                                <li class="<?php echo ($class == 'vendors' && ($action == 'index' || $action == 'add_edit_users' )) ? 'active' : ''; ?>"><a href="<?php echo site_url('/vendor/users'); ?>"><i class="icon-users"></i><span>Users</span></a></li>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    if ((in_array('products', MY_Controller::$access_controller) && array_key_exists('list', MY_Controller::$access_method['products'])) || checkVendorLogin('R') == 5) {
                                        ?>
                                        <li class="<?php echo ($class == 'products') ? 'active' : ''; ?>"><a href="<?php echo site_url('vendor/products'); ?>"><i class=" icon-archive"></i> <span>Products</span></a></li>
                                        <?php
                                    }

                                    if ((in_array('history', MY_Controller::$access_controller) && array_key_exists('list', MY_Controller::$access_method['history'])) || checkVendorLogin('R') == 5) {
                                        ?>
                                        <li class="<?php echo ($class == 'history') ? 'active' : ''; ?>"><a href="<?php echo site_url('vendor/history'); ?>"><i class="icon-magazine"></i> <span>History</span></a></li>
                                        <?php
                                    }
                                    ?>
                                    <li class="<?php echo ($class == 'reports') ? 'active' : ''; ?>">
                                        <a href="#" class="has-ul"><i class="icon-file-stats"></i><span>Reports</span></a>
                                        <ul class="hidden-ul" style="<?php echo ($class == 'reports') ? 'display:block' : ''; ?>">
                                            <?php if ((in_array('reports', MY_Controller::$access_controller) && array_key_exists('list', MY_Controller::$access_method['reports'])) || checkVendorLogin('R') == 5) {
                                                ?>
                                                <li class="<?php echo ($class == 'reports') ? 'active' : ''; ?>"><a href="<?php echo site_url('vendor/reports'); ?>"><i class="icon-stats-bars"></i> <span>Report</span></a></li>
                                            <?php } if (checkVendorLogin('R') == 5) {
                                                ?>
                                                <li class="<?php echo ($class == 'reports' && ($action == 'parts_compatability')) ? 'active' : ''; ?>">
                                                    <a href="<?php echo site_url('/vendor/reports/parts-compatability'); ?>"><i class="icon-file-text2"></i><span>Part Compatibility Count</span></a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-wrapper">
                    <div id="custom_loading" class="hide">
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
                    <?php echo $body; ?>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            
            // Manage collapse 
            var sidemenucookie = getCookie('sidemenuopen');

            if(sidemenucookie === 'sideclose'){
                $(document.body).addClass('sidebar-xs');    
            } else {
                $(document.body).removeClass('sidebar-xs');
            }

            $('.sidebar-main-toggle').click(function(event) {
                var check = $("body").hasClass('sidebar-xs');

                var check_val = (check === true) ? 'sideopen' : 'sideclose';

                setTimeout(function(){setCookie('sidemenuopen', check_val,1); }, 500); 
            });

            function setCookie(cname, cvalue, exdays) {
                var d = new Date();
                d.setTime(d.getTime() + (exdays*24*60*60*1000));
                var expires = "expires="+ d.toUTCString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            }

            function getCookie(cname) {
                var name = cname + "=";
                var decodedCookie = decodeURIComponent(document.cookie);
                var ca = decodedCookie.split(';');
                for(var i = 0; i <ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            }
        </script>
        <!--/Page container-->