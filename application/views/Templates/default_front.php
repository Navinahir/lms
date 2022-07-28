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
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
        <link href="assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
        
        <!-- Global stylesheets -->
        <link href="assets/css/bootstrap.css?version='<?php echo time();?>'" rel="stylesheet" type="text/css">
        <link href="assets/css/core.css?version='<?php echo time();?>'" rel="stylesheet" type="text/css">
        <link href="assets/css/components.css?version='<?php echo time();?>'" rel="stylesheet" type="text/css">
        <link href="assets/css/colors.css?version='<?php echo time();?>'" rel="stylesheet" type="text/css">
        <link href="assets/css/custom_pav.css?version='<?php echo time();?>'" rel="stylesheet" type="text/css">
        <link href="assets/css/developer.css?version='<?php echo time();?>'" rel="stylesheet" type="text/css">
        <link href="assets/css/dashboard.css?version='<?php echo time();?>'" rel="stylesheet" type="text/css">
        <!-- /global stylesheets -->
        
        <link href="assets/css/select/bootstrap-select.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
        <!-- <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" /> -->
        
        <link rel="icon" href="assets/images/favicon.png" type="image/png">
        <script type="text/javascript">
            var site_url = "<?php echo site_url() ?>";
            var base_url = "<?php echo base_url() ?>";
            var remoteURL = '';
            var role = '<?php echo checkUserLogin('R') ?>';
            var add = '<?php echo (checkUserLogin('R') == 4) ? 1 : 0 ?>';
            var edit = '<?php echo (checkUserLogin('R') == 4) ? 1 : 0 ?>';
            var dlt = '<?php echo (checkUserLogin('R') == 4) ? 1 : 0 ?>';
            var view = '<?php echo (checkUserLogin('R') == 4) ? 1 : 0 ?>';
            var domain = "<?php echo MY_DOMAIN_NAME ?>";
            var currentDate = new Date();
            var currentOffset = (currentDate.getTimezoneOffset() * -1) * 60;
            document.cookie = "currentOffset=" + currentOffset;
        </script>
        <script src="<?php echo base_url('websocket/node_modules/socket.io-client/dist/socket.io.js');?>"></script>
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
            var authUrl = "<?= (!empty($_SESSION['authUrl'])) ? $_SESSION['authUrl'] : ""; ?>";
        </script>
        <script type="text/javascript" src="assets/js/custom_pages/front/quickbook_login.js"></script>
        <script type="text/javascript" src="assets/js/plugins/select/bootstrap-select.min.js"></script>
        <script>
            var hostname = '<?php echo $_SERVER['HTTP_HOST']; ?>';
            if(hostname == 'localhost' || hostname == 'clientapp.narola.online')
            {
                var socket = io.connect('http://' + window.location.hostname + ':9010',{secure:true,rejectUnauthorized : false });
            }
            else
            {
                var socket = io.connect('https://' + window.location.hostname + ':9001',{secure:true,rejectUnauthorized : false });
            }
            socket.on('new_notification', function(data) {
                view_load();
            }); 
            function view_load()
            {
                $.ajax({
                    url: site_url + 'message/view',
                    type: 'POST',
                    dataType: 'json',
                    async: false,
                    success: function(data)
                    {
                        $('.notifications_new').html(data.html_new_notification);
                        $('.notifications_estimate_invoice').html(data.html_estimate_invoice);
                        $('.notifications_closed').html(data.html_closed_notification);
                        $('.item_notifications_closed').html(data.item_html_closed_notification);
                        $('.notification_count').html(data.count);
                        $('.low_inventory_notification_count').html('( ' + data.low_inventory_notification_count + ' )');
                        $('.est_inv_notification_count').html('( ' + data.est_inv_notification_count + ' )');
                    }
                });
            }
            
            $(document).on('click', '.notification_item_view_btn', function () {
                $('#custom_loading').removeClass('hide');
                $('#custom_loading').css('display', 'block');
                $.ajax({
                    url: site_url + 'items/get_item_data_ajax_by_id',
                    type: "POST",
                    data: {id: this.id},
                    success: function (response) {
                        $('#custom_loading').removeClass('hide');
                        $('#custom_loading').css('display', 'none');
                        $('#notification_items_view_body').html(response);
                        $('#notification_items_view_modal').modal('show');
                    }
                });
            });

            // Close notification
            $(document).on('click','.close-notification', function(){
                $.ajax({
                    url: site_url + 'notification/remove_notification',
                    type: "POST",
                    data: {id: this.id},
                    success: function (response) {

                    }
                });
            });

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
            
            function check_device() {
                var rearCamera = 0;
                var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
                
                if (isMobile == true) {
                    var rearCamera = 1;
                }else{
                    var rearCamera = 0;
                }
                
                return rearCamera;
            }
        </script>
        <!-- <script type="text/javascript" src="assets/js/pages/dashboard.js"></script> -->
        <!-- /theme JS files -->
        <noscript>
        <META HTTP-EQUIV="Refresh" CONTENT="0;URL=js_disabled">
        </noscript>
    </head>
    <body class="<?php echo (!empty($_COOKIE['sidemenuopen']) && $_COOKIE['sidemenuopen']=='sideclose') ? 'sidebar-xs' : ''?>">
        
    <!-- View modal -->
    <div id="notification_items_view_modal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-teal-400 custom_modal_header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h6 class="modal-title text-center">Items Details</h6>
                </div>
                <div class="modal-body panel-body custom_scrollbar" id="notification_items_view_body" style="height: 500px;overflow: hidden;overflow-y: scroll;"></div>
            </div>
        </div>
    </div>
    <?php 
        // Manage sidemenu for the single user account
        $single_user_sidemenu = 1;
        $single_user = $this->db->query('select * from '.TBL_USERS.' where id=' . checkUserLogin('I'))->row_array();
        if($single_user['package_id'] != "" && $single_user['package_id'] != null)
        {
            $sidemenu = $this->db->query('select no_of_users from '.TBL_PACKAGES.' where id=' .$single_user['package_id'])->row_array();
            if($sidemenu['no_of_users'] != "" && $sidemenu['no_of_users'] != null && $sidemenu['no_of_users'] > 1)
            {
                $single_user_sidemenu = "";
            } else {
                $single_user_sidemenu = 1;
            }
        }
    ?>    
    <!-- Main navbar -->
        <div class="navbar navbar-inverse custom_navbar">
            <div class="navbar-header">
                <a class="navbar-brand" href="<?php echo site_url('/'); ?>" style="letter-spacing: 3px;font-weight: 500;font-size: 16px;line-height:2">
                    <?php // echo 'Always Reliable Keys';  ?>
                    <img src="assets/images/logo_white.png" alt="">
                </a>

                <ul class="nav navbar-nav visible-xs-block">
                    <li><a class="sidebar-mobile-main-toggle"><img src="assets/images/hamburger.png" alt=""></a></li>
                </ul>
            </div>

            <div class="navbar-collapse collapse" id="navbar-mobile">
                <ul class="nav navbar-nav">
                    <li>
                        <a class="sidebar-control sidebar-main-toggle hidden-xs">
                            <img src="assets/images/hamburger.png" alt="">
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right col-one">         
                    <li class="dropdown dropdown-user user_drop">
                        <a class="dropdown-toggle" data-toggle="dropdown">
                            <?php if (checkUserLogin('P') != '' && checkUserLogin('P') != null && !empty(checkUserLogin('P'))) { ?>
                                <img src="uploads/profile/<?php echo checkUserLogin('P') ?>" alt="" class="user-prfile-img img-circle img-sm">
                            <?php } else if (checkUserLogin('A') != '' && checkUserLogin('A') != null && !empty(checkUserLogin('A'))) { ?>
                                <img src="uploads/profile/<?php echo checkUserLogin('A') ?>" alt="" class="img-circle img-sm">
                            <?php } else { ?>
                                <img src="assets/images/user_image.png" alt="">
                            <?php } ?>
                            <span><?php echo strtoupper(checkUserLogin('U')); ?></span>
                            <i class="caret"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-right KBH">
<?php 
                        if($this->session->userdata('u_quickbook_status') == 1)
                        {
?>
                            <li>
                                <a onclick="oauth.loginPopup()">

                                    <img src="<?php echo base_url('assets/images/quickbook/quickbooks.png'); ?>" alt="">
                                    <!-- <i class="icon-home"></i> -->
<?php  
                                        if (isset($_SESSION['sessionAccessToken'])) 
                                        {
                                            echo "connected to Quickbook";
                                        }
                                        else 
                                        {
                                            echo "Quickbooks Login";
                                        }
?>                                    
                                </a>
                            </li>
<?php
                            if (isset($_SESSION['sessionAccessToken'])) 
                            {
?>
                            <li>
                                <a href="<?php echo site_url('quickbook') ?>"> 
                                <i class="icon-gear"></i>
<?php 
                                    echo "Quickbook Settings"; 
?>
                                </a>
                            </li>
<?php 
                            }
                        }
?>    

                            <li><a href="<?php echo site_url('/change_password'); ?>"><i class="icon-lock"></i> Change Password</a></li>
                            <li><a href="<?php echo site_url('/logout'); ?>"><i class="icon-switch2"></i> Logout</a></li>
                        </ul>

                    </li>
                </ul>
<?php
                if ((in_array('notification', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) { 
?>
                <ul class="nav navbar-nav navbar-right col-two border-bottom-0">
                    <li class="dropdown" id="push_notification">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-bell2"></i>
                            <span class="visible-xs-inline-block position-right">Messages</span>
                            <span class="badge bg-warning-400 notification_count">0</span>
                        </a>                        
                        <div class="dropdown-menu dropdown-content width-350 noti-ul">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div class="dropdown-content-heading">
                                        Low inventory notifications <span class="low_inventory_notification_count">(0)</span>
                                        <ul class="icons-list">
                                            <li><a href="#"><i class="icon-compose"></i></a></li>
                                        </ul>
                                    </div>
                                    <ul class="media-list dropdown-content-body notifications_new">
                                    </ul>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div class="dropdown-content-heading">
                                        Estimate & Invoice notifications <span class="est_inv_notification_count">(0)</span>
                                        <ul class="icons-list">
                                            <li><a href="#"><i class="icon-compose"></i></a></li>
                                        </ul>
                                    </div>
                                    <ul class="media-list dropdown-content-body notifications_estimate_invoice">
                                    </ul>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div class="dropdown-content-heading">
                                        Closed low inventory notifications Upto(50) 
                                        <ul class="icons-list">
                                            <li><a href="#"><i class="icon-compose"></i></a></li>
                                        </ul>
                                    </div>
                                     <ul class="media-list dropdown-content-body item_notifications_closed">
                                    </ul>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div class="dropdown-content-heading">
                                        Closed estimate & invoice notifications Upto(50) 
                                        <ul class="icons-list">
                                            <li><a href="#"><i class="icon-compose"></i></a></li>
                                        </ul>
                                    </div>
                                    <ul class="media-list dropdown-content-body notifications_closed">
                                    </ul>
                                </div>
                            </div>
                           
                        </div>
                    </li>
                </ul>
<?php 
                } 
?>
                
<?php
                if ((in_array('trash', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) { 
?>
                <ul class="nav navbar-nav navbar-right col-two border-bottom-0 trash-navbtn">
                    <li class="dropdown" id="">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-trash fa-2x"></i>
                        </a>                        
                        <ul class="dropdown-menu notify-drop content_dropdown">
                            <?php
                            if (((in_array('trash', MY_Controller::$access_controller)) && in_array('inventory_item_trash', MY_Controller::$access_method['trash'])) || checkUserLogin('R') == 4) {
                                ?>
                                <li>
                                    <a href="<?php echo base_url('Items/items_trash')?>">
                                        <div class="notify-img">
                                            <i class="far fa-trash-alt fa-2x"></i>
                                        </div>
                                        <div class="div_content_inner">
                                            <p class="time">Inventory Items</p>
                                        </div>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php
                            if (((in_array('trash', MY_Controller::$access_controller)) && in_array('customer_trash', MY_Controller::$access_method['trash'])) || checkUserLogin('R') == 4) {
                                ?>
                                <li>
                                    <a href="<?php echo base_url('Customers/customer_trash')?>">
                                        <div class="notify-img">
                                            <i class="far fa-trash-alt fa-2x"></i>
                                        </div>
                                        <div class="div_content_inner">
                                            <p class="time">Customers</p>
                                        </div>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php
                            if (((in_array('trash', MY_Controller::$access_controller)) && in_array('estimate_trash', MY_Controller::$access_method['trash'])) || checkUserLogin('R') == 4) {
                                ?>
                                <li>
                                    <a href="<?php echo base_url('Estimates/estimate_trash')?>">
                                        <div class="notify-img">
                                            <i class="far fa-trash-alt fa-2x"></i>
                                        </div>
                                        <div class="div_content_inner">
                                            <p class="time">Estimate</p>
                                        </div>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php
                            if (((in_array('trash', MY_Controller::$access_controller)) && in_array('invoice_trash', MY_Controller::$access_method['trash'])) || checkUserLogin('R') == 4) {
                                ?>
                                <li>
                                    <a href="<?php echo base_url('Invoices/invoice_trash')?>">
                                        <div class="notify-img">
                                            <i class="far fa-trash-alt fa-2x"></i>
                                        </div>
                                        <div class="div_content_inner">
                                            <p class="time">Invoice</p>
                                        </div>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                </ul>
<?php 
                } 
?>

                <ul class="nav navbar-nav navbar-right col-three">
                     <li>
<?php 
                            if($this->session->userdata('u_quickbook_status') == 1)
                            {
                                if(isset($_SESSION['sessionAccessToken']))
                                {
?>
                                    <img src="assets/images/quickbook/C2QB_green_btn_sm_default.png" alt="" class="green-logo quickbook-logo">
<?php 
                                }
                                else
                                {                                
?>
                                    <img src="assets/images/quickbook/C2QB_white_btn_sm_default.png" alt="" class="red-logo quickbook-logo">
<?php 
                                }
                            } 
?>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="#" title="Help" class="dropdown-toggle" id="content-dropdown-open" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="icon-question4 icon-spinner11 content-question-box"></i>
                        </a>
                        <ul class="dropdown-menu notify-drop content_dropdown dropdown_content_data"></ul>
                    </li>

                    <li>
                        <a href="<?= site_url('blogs') ?>" title="Blogs">
                            <!-- <i class="fas fa-book-open fa-2x <?php echo ($action == 'get_blogs' || $action == 'get_blog_details') ? 'active-blog-menu"' : '' ?>"></i> -->
                            <i class="fas fa-book-open fa-2x"></i>
                        </a>
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
                        <div class="sidebar-user mobile-username">
                            <div class="category-content">
                                <div class="media media_mobile">
                                    <a href="#" class="media-left profile_img">
                                        <?php if (checkUserLogin('P') != '' && checkUserLogin('P') != null && !empty(checkUserLogin('P'))) { ?>
                                            <img src="uploads/profile/<?php echo checkUserLogin('P') ?>" alt="" class="img-circle img-sm user-prfile-img">
                                        <?php } else if (checkUserLogin('A') != '' && checkUserLogin('A') != null && !empty(checkUserLogin('A'))) { ?>
                                            <img src="uploads/profile/<?php echo checkUserLogin('A') ?>" alt="" class="img-circle img-sm">
                                        <?php } else { ?>
                                            <img src="assets/images/user_image.png" alt="">
                                        <?php } ?>
                                    </a>
                                    <div class="media-body user_profile">
                                        <span class="media-heading text-semibold"><?php echo strtoupper(checkUserLogin('U')); ?></span>
                                    </div>
                                </div>
                                <ul class="dropdown-menu dropdown-menu-right dorp_cstm">
<?php 
                                    if($this->session->userdata('u_quickbook_status') == 1)
                                    {
?>
                                        <li>
                                            <a onclick="oauth.loginPopup()" >
                                            <i class="icon-home"></i>
<?php  
                                                if (isset($_SESSION['sessionAccessToken'])) 
                                                {
                                                    echo "Connected to Quickbooks";
                                                }
                                                else 
                                                {
                                                    echo "Quickbooks Login";
                                                }
?>                                    
                                            </a>
                                        </li>  
<?php 
                                    } 
?>  
                                    <li><a href="<?php echo site_url('/change_password'); ?>"><i class="icon-lock"></i> Change Password</a></li>
                                    <li><a href="<?php echo site_url('/logout'); ?>"><i class="icon-switch2"></i> Logout</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="sidebar-category sidebar-category-visible">
                            <div class="category-content no-padding">
                                <ul class="navigation navigation-main navigation-accordion">
                                    <li class="<?php echo ($class == 'dashboard') ? 'active' : '' ?>"><a href="<?php echo site_url('dashboard'); ?>"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
                                    <?php
                                
                                    if (isset($_SESSION['sessionAccessToken'])) 
                                    {
                                        if ((in_array('Dashboard_quickbook', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) {
                                            ?>
                                            <li class="<?php echo ($class == 'Dashboard_quickbook') ? 'active' : ''; ?>">
                                                <a href="#" class="has-ul"><img src="<?php echo base_url('assets/images/quickbook/quickbooks.png') ?>" alt=""><span>Quickbooks</span></a>
                                                <ul class="hidden-ul" style="<?php echo ($class == 'Dashboard_quickbook') ? 'display:block' : ''; ?>">
                                                    <?php if ((in_array('Dashboard_quickbook', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) { ?>
                                                        <li class="<?php echo ($class == 'Dashboard_quickbook' && ($action == 'view_estimate' || $action == 'estimate' )) ? 'active' : ''; ?>"><a href="<?php echo site_url('quickbook/estimate'); ?>"><i class="icon-file-text2"></i><span>Un - Sync Estimates</span></a></li>
                                                    <?php } 
                                                    if ((in_array('Dashboard_quickbook', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) { ?>
                                                        <li class="<?php echo ($class == 'Dashboard_quickbook' && ($action == 'view_invoice' || $action == 'invoice')) ? 'active' : ''; ?>"><a href="<?php echo site_url('quickbook/invoice'); ?>"><i class="icon-cash3"></i><span>Un - Sync Invoices</span></a></li>
                                                    <?php } ?>
                                                <?php if ((in_array('Dashboard_quickbook', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) { ?>
                                                        <li class="<?php echo ($class == 'Dashboard_quickbook' && ($action == 'view_unsync_item' || $action == 'item')) ? 'active' : ''; ?>"><a href="<?php echo site_url('quickbook/item'); ?>"><img src="<?php echo base_url('assets\images\icomoon\sell.png'); ?>" alt=""><span>Un - sync Items</span></a></li>
                                                    <?php } 
                                                    if ((in_array('Dashboard_quickbook', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) { ?>
                                                        <li class="<?php echo ($class == 'Dashboard_quickbook' && ($action == 'view_unsync_service' || $action == 'service')) ? 'active' : ''; ?>"><a href="<?php echo site_url('quickbook/service'); ?>"><img src="<?php echo base_url('assets\images\icomoon\services.png'); ?>" alt=""><span>Un - sync Services</span></a></li>
                                                    <?php }
                                                    if ((in_array('Dashboard_quickbook', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) { ?>
                                                        <li class="<?php echo ($class == 'Dashboard_quickbook' && ($action == 'view_unsync_customers' || $action == 'customer')) ? 'active' : ''; ?>"><a href="<?php echo site_url('quickbook/customer'); ?>"><i class="fa fa-user"></i><span>Un - sync Customers</span></a></li>
                                                    <?php }
                                                    if ((in_array('Dashboard_quickbook', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) { ?>
                                                        <li class="<?php echo ($class == 'Dashboard_quickbook' && $action == 'qb_customer') ? 'active' : ''; ?>"><a href="<?php echo site_url('quickbook/qb_customer'); ?>"><i class="fa fa-user"></i><span>QB Customers</span></a></li>
                                                    <?php }
                                                    if ((in_array('Dashboard_quickbook', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) { ?>
                                                        <li class="<?php echo ($class == 'Dashboard_quickbook' && ($action == 'view_item' || $action == 'items')) ? 'active' : ''; ?>"><a href="<?php echo site_url('quickbook/items'); ?>"><i class="icon-cash3"></i><span>Item Summary</span></a></li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                            <?php
                                        }
                                    }
                                   
                                    if ((in_array('inventory', MY_Controller::$access_controller) || in_array('items', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) {
                                        ?>
                                        <li class="<?php echo ($class == 'inventory' || $class == 'items') ? 'active' : ''; ?>">
                                            <a href="#" class="has-ul"><i class=" icon-archive"></i><span>Inventory</span></a>
                                            <ul class="hidden-ul" style="<?php echo ($class == 'inventory' || $class == 'items') ? 'display:block' : ''; ?>">
                                                <?php
                                                if ((in_array('items', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) {
                                                    ?>
                                                    <li class="<?php echo ($class == 'items' && ($action == 'display_items' || $action == 'edit_items' || $action == 'add_non_global')) ? 'active' : '' ?>"><a href="<?php echo site_url('items'); ?>"><i class="icon-books"></i> <span>Items</span></a></li>
                                                    <?php
                                                }
                                                if (((in_array('inventory', MY_Controller::$access_controller)) && in_array('transfer_inventory', MY_Controller::$access_method['inventory'])) || checkUserLogin('R') == 4) {
                                                    ?>
                                                    <?php if($single_user_sidemenu == "") { ?>
                                                        <li class="<?php echo ($class == 'inventory' && ($action == 'transfer_inventory')) ? 'active' : ''; ?>"><a href="<?php echo site_url('/move_inventory'); ?>"><i class=" icon-transmission"></i><span>Move Inventory</span></a></li> 
                                                    <?php } ?>
                                                    <?php
                                                }
                                                if (((in_array('inventory', MY_Controller::$access_controller)) && in_array('add_inventory', MY_Controller::$access_method['inventory'])) || checkUserLogin('R') == 4) {
                                                    ?>
                                                    <li class="<?php echo ($class == 'inventory' && ($action == 'add_inventory')) ? 'active' : ''; ?>"><a href="<?php echo site_url('/receive_inventory'); ?>"><i class="icon-googleplus5"></i><span>Receive Inventory</span></a></li> <?php
                                                }
                                                if (((in_array('inventory', MY_Controller::$access_controller)) && in_array('adjust_inventory', MY_Controller::$access_method['inventory'])) || checkUserLogin('R') == 4) {
                                                    ?>
                                                    <li class="<?php echo ($class == 'inventory' && ($action == 'adjust_inventory')) ? 'active' : ''; ?>"><a href="<?php echo site_url('/adjust_inventory'); ?>"><i class="icon-insert-template"></i><span>Adjust Inventory</span></a></li> <?php
                                                }
                                                if (((in_array('inventory', MY_Controller::$access_controller)) && in_array('inventory_locations', MY_Controller::$access_method['inventory'])) || checkUserLogin('R') == 4) {
                                                    ?>
                                                    <li class="<?php echo ($class == 'inventory' && ($action == 'inventory_locations')) ? 'active' : ''; ?>"><a href="<?php echo site_url('/inventory_locations'); ?>"><i class="icon-location4"></i><span>Inventory Locations</span></a></li> <?php
                                                }
                                                if (((in_array('inventory', MY_Controller::$access_controller)) && in_array('inventory_history', MY_Controller::$access_method['inventory'])) || checkUserLogin('R') == 4) {
                                                    ?>
                                                    <li class="<?php echo ($class == 'inventory' && ($action == 'inventory_history')) ? 'active' : ''; ?>"><a href="<?php echo site_url('/inventory_history'); ?>"><i class="icon-magazine"></i><span>Inventory History</span></a></li> <?php
                                                }
                                                if (((in_array('inventory', MY_Controller::$access_controller)) && in_array('invoice_inventory', MY_Controller::$access_method['inventory'])) || checkUserLogin('R') == 4) {
                                                    ?>
                                                    <li class="<?php echo ($class == 'inventory' && ($action == 'invoice_inventory')) ? 'active' : ''; ?>"><a href="<?php echo site_url('/inventory/invoice_inventory'); ?>"><i class="icon-book"></i><span>Invoice Inventory History</span></a></li> <?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                        <?php
                                    }

                                    if ((in_array('customers', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) {
                                        ?>
                                        <li class="<?php echo ($class == 'customers') ? 'active' : '' ?>"><a href="<?php echo site_url('customers'); ?>"><i class="icon-users"></i> <span>Customers</span></a></li>
                                        <?php
                                    }
                                    if ((in_array('estimates', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) {
                                        ?>
                                        <li class="<?php echo ($class == 'estimates') ? 'active' : '' ?>"><a href="<?php echo site_url('estimates'); ?>"><i class="icon-file-text2"></i> <span> Estimates</span></a></li>
                                        <?php
                                    }
                                    if ((in_array('invoices', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) {
                                        ?>
                                        <li class="<?php echo ($class == 'invoices') ? 'active' : '' ?>"><a href="<?php echo site_url('invoices'); ?>"><i class="icon-cash3"></i> <span> Invoices</span></a></li>
                                        <?php
                                    }
                                    if ((in_array('orders', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) {
                                        ?>
                                        <li class="<?php echo ($class == 'orders') ? 'active' : '' ?>"><a href="<?php echo site_url('orders'); ?>"><i class="icon-car"></i> <span> Orders</span></a></li>
                                        <?php
                                    }
                                    
                                    if ((in_array('reports', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) {
                                        ?>
                                        <li class="<?php echo ($class == 'reports') ? 'active' : ''; ?>">
                                            <a href="#" class="has-ul"><i class="icon-stats-bars"></i><span>Reports</span></a>
                                            <ul class="hidden-ul" style="<?php echo ($class == 'reports') ? 'display:block' : ''; ?>">
                                                <?php
                                                if (((in_array('reports', MY_Controller::$access_controller)) && in_array('custom_reports', MY_Controller::$access_method['reports'])) || checkUserLogin('R') == 4) {
                                                    ?>
                                                    <li class="<?php echo ($class == 'reports' && ($action == 'custom_reports')) ? 'active' : '' ?>"><a href="<?php echo site_url('reports/custom_reports'); ?>"><i class="icon-cash"></i> <span>Custom Reports</span></a></li>
                                                    <?php
                                                }
                                                if (((in_array('reports', MY_Controller::$access_controller)) && in_array('sales', MY_Controller::$access_method['reports'])) || checkUserLogin('R') == 4) {
                                                    ?>
                                                    <li class="<?php echo ($class == 'reports' && ($action == 'sales')) ? 'active' : '' ?>"><a href="<?php echo site_url('reports/sales'); ?>"><i class="icon-stats-growth"></i> <span>Sales</span></a></li>
                                                    <?php
                                                }
                                                if (((in_array('reports', MY_Controller::$access_controller)) && in_array('tax', MY_Controller::$access_method['reports'])) || checkUserLogin('R') == 4) {
                                                    ?>
                                                    <li class="<?php echo ($class == 'reports' && ($action == 'tax')) ? 'active' : '' ?>"><a href="<?php echo site_url('reports/tax'); ?>"><i class=" icon-statistics"></i> <span>Tax</span></a></li>
                                                    <?php
                                                }
                                                if (((in_array('reports', MY_Controller::$access_controller)) && in_array('sales_by_user', MY_Controller::$access_method['reports'])) || checkUserLogin('R') == 4) {
                                                    ?>
                                                    <?php if($single_user_sidemenu == "") { ?>
                                                        <li class="<?php echo ($class == 'reports' && ($action == 'sales_by_user')) ? 'active' : '' ?>"><a href="<?php echo site_url('reports/sales_by_user'); ?>"><i class=" icon-chart"></i> <span>Sales by user</span></a></li>
                                                    <?php } ?>
                                                    <?php
                                                }
                                                if (((in_array('reports', MY_Controller::$access_controller)) && in_array('popular_items', MY_Controller::$access_method['reports'])) || checkUserLogin('R') == 4) {
                                                    ?>
                                                    <li class="<?php echo ($class == 'reports' && ($action == 'popular_items')) ? 'active' : '' ?>"><a href="<?php echo site_url('reports/popular_items'); ?>"><i class=" icon-stats-bars2"></i> <span>Most Popular Items</span></a></li>
                                                    <?php
                                                }
                                                if (((in_array('reports', MY_Controller::$access_controller)) && in_array('sales_by_category', MY_Controller::$access_method['reports'])) || checkUserLogin('R') == 4) {
                                                    ?>
                                                    <li class="<?php echo ($class == 'reports' && ($action == 'sales_by_category')) ? 'active' : '' ?>"><a href="<?php echo site_url('reports/sales_by_category'); ?>"><i class="icon-stats-bars3"></i> <span>Sales by Category</span></a></li>
                                                    <?php
                                                }
                                                if (((in_array('reports', MY_Controller::$access_controller)) && in_array('labor_and_services', MY_Controller::$access_method['reports'])) || checkUserLogin('R') == 4) {
                                                    ?>
                                                    <li class="<?php echo ($class == 'reports' && ($action == 'labor_and_services')) ? 'active' : '' ?>"><a href="<?php echo site_url('reports/labor_and_services'); ?>"><i class="icon-pie-chart8"></i> <span>Labor and Services</span></a></li>
                                                    <?php
                                                }
                                                if (((in_array('reports', MY_Controller::$access_controller)) && in_array('low_inventory_items', MY_Controller::$access_method['reports'])) || checkUserLogin('R') == 4) {
                                                    ?>
                                                    <li class="<?php echo ($class == 'reports' && ($action == 'low_inventory_items')) ? 'active' : '' ?>"><a href="<?php echo site_url('reports/low_inventory_items'); ?>"><i class="icon-graph"></i> <span>Low Inventory Items</span></a></li>
                                                    <?php
                                                }
                                                if (((in_array('reports', MY_Controller::$access_controller)) && in_array('inventory_value', MY_Controller::$access_method['reports'])) || checkUserLogin('R') == 4) {
                                                    ?>
                                                    <li class="<?php echo ($class == 'reports' && ($action == 'inventory_value')) ? 'active' : '' ?>"><a href="<?php echo site_url('reports/inventory_value'); ?>"><i class="fa fa-arrow-up"></i> <span>Inventory Value</span></a></li>
                                                    <?php
                                                }
                                                if (((in_array('reports', MY_Controller::$access_controller)) && in_array('inventory_investment', MY_Controller::$access_method['reports'])) || checkUserLogin('R') == 4) {
                                                    ?>
                                                    <li class="<?php echo ($class == 'reports' && ($action == 'inventory_investment')) ? 'active' : '' ?>"><a href="<?php echo site_url('reports/inventory_investment'); ?>"><i class="fa fa-arrow-down"></i> <span>Inventory Investment</span></a></li>
                                                    <?php
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                    <?php }

                                    if (((in_array('company_profile', MY_Controller::$access_controller)) || (in_array('settings', MY_Controller::$access_controller)) || in_array('users', MY_Controller::$access_controller)) || (in_array('roles', MY_Controller::$access_controller)) || (in_array('services', MY_Controller::$access_controller)) || (in_array('taxes', MY_Controller::$access_controller)) || (in_array('locations', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) {
                                        ?>
                                        <li class="<?php echo ($class == 'company_profile' || $class == 'settings' || $class == 'users' || $class == 'roles' || $class == 'services' || $class == 'taxes' || $class == 'locations') ? 'active' : ''; ?>">
                                            <a href="#" class="has-ul"><i class="icon-cog52"></i><span>Settings</span></a>
                                            <ul class="hidden-ul" style="<?php echo ($class == 'Company_Profile' || $class == 'settings' || $class == 'users' || $class == 'roles' || $class == 'services' || $class == 'taxes' || $class == 'locations') ? 'display:block' : ''; ?>">

                                                <?php if ((in_array('company_profile', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) {
                                                    ?>
                                                    <li class="<?php echo ($class == 'Company_Profile' && $action == 'index') ? 'active' : '' ?>"><a href="<?php echo site_url('company_profile'); ?>"><i class="icon-profile"></i> <span>Company Profile</span></a></li>
                                                <?php } ?>

                                                <?php if ((in_array('settings', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) { ?>
                                                    <li class="<?php echo ($class == 'settings' && $action == 'index') ? 'active' : ''; ?>"><a href="<?php echo site_url('/settings'); ?>"><i class="icon-transmission"></i><span>Customization</span></a></li>
                                                <?php } ?>

                                                <?php if ((in_array('roles', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) { ?>
                                                    <?php if($single_user_sidemenu == "") { ?>
                                                        <li class="<?php echo ($class == 'roles' && ($action == 'display_roles' || $action == 'edit_roles' )) ? 'active' : ''; ?>"><a href="<?php echo site_url('/users/roles'); ?>"><i class="icon-user-tie"></i><span>Roles & Permission</span></a></li>
                                                    <?php } ?>
                                                <?php } ?>
                                               
                                                <?php if ((in_array('users', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) { ?>
                                                    <li class="<?php echo ($class == 'users' && ($action == 'display_users' || $action == 'edit_users')) ? 'active' : ''; ?>"><a href="<?php echo site_url('/users'); ?>"><i class="icon-users"></i><span>Users</span></a></li>
                                                <?php } ?>

                                                <?php if ((in_array('services', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) { ?>
                                                    <li class="<?php echo ($class == 'services') ? 'active' : '' ?>"><a href="<?php echo site_url('services'); ?>"><i class="icon-puzzle2"></i> <span> Labor and Services</span></a></li>
                                                <?php } ?>
                                                
                                                <?php if ((in_array('taxes', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) { ?>
                                                    <li class="<?php echo ($class == 'taxes' && ($action == 'display_taxes' || $action == 'edit_taxes' )) ? 'active' : ''; ?>"><a href="<?php echo site_url('/taxes'); ?>"><i class="icon-percent"></i><span>Tax Rates</span></a></li>
                                                <?php } ?>

                                                <?php if ((in_array('locations', MY_Controller::$access_controller)) || checkUserLogin('R') == 4) { ?>
                                                    <li class="<?php echo ($class == 'locations' && ($action == 'display_locations' || $action == 'edit_locations' || $action == 'location_view' )) ? 'active' : ''; ?>"><a href="<?php echo site_url('/locations'); ?>"><i class=" icon-location3"></i><span>Locations</span></a></li>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    ?>
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

            <!-- View modal -->
            <div id="content_view_modal" class="modal fade">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-teal-400 custom_modal_header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h6 class="modal-title text-center">Module Overview </h6>
                        </div>
                        <div class="modal-body panel-body custom_scrollbar" id="content_view_body" style="overflow: hidden;overflow-y: scroll;">
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <style>
            .breadcrumb {
                padding: 16px 0;
            }
        </style>
        <script>
            function notification(message, user,date, msg_count)
            {
                if(hostname == 'localhost' || hostname == 'clientapp.narola.online')
                {
                    var socket = io.connect('http://' + window.location.hostname + ':9010',{secure:true,rejectUnauthorized : false });
                }
                else
                {
                    var socket = io.connect('https://' + window.location.hostname + ':9001',{secure:true,rejectUnauthorized : false });
                }
                
                socket.emit('new_notification', {
                    message: message,
                    user: user,
                    date: date,
                    msgcount: msg_count
                });
            }
            $('document').ready(function () {
                // $('.flashmsg').fadeOut(6000);
                $(".media_mobile").click(function () {
                    $(".dorp_cstm").slideToggle();
                });
                view_load();
            });

            $(function () {
                $('[data-tooltip="tooltip"]').tooltip()

                $('#content-dropdown-open').click(function () {
                    $("#searched-module-content").val("");
                    load_content_bar();
                });
            });

            function load_content_bar(search_val = "") {
                // $('#custom_loading').removeClass('hide');
                // $('#custom_loading').css('display', 'block');

                $.ajax({
                    url: site_url + 'dashboard/get_content_data',
                    type: 'POST',
                    data: {
                        search_val: search_val
                    },
                    success: function (response) {
                        var content = '';
                        var default_content = '';

                        default_content += '<li class="serachbar ml-0">'
                                + '<div class="searchbox">'
                                + '<input class="form-control" id="searched-module-content" autocomplete="off">'
                                + '<button type="button" class="btn bg-blue custom_search_button" id="searched-module-content-button">'
                                + '<i class="icon-search4 text-size-base"></i>'
                                + '</button>'
                                + '</div>'
                                + '</li>';

                        var text = $("#searched-module-content").val();

                        response = JSON.parse(response);
                        if (response.length > 0) {
                            $.each(response, function (key, value) {
                                content += '<li class="ml-0"><a data-id="' + value.id + '" class="rIcon module_content_show">'
                                        + '<div class="notify-img"><img src="assets/images/info.png" alt=""></div>'
                                        + '<div class="div_content_inner">'
                                        + '<p class="time">' + value.module_name + '</p>'
                                        + '</div></a></li>';
                            });
                        } else {
                            content += '<li>'
                                    + '<div class="notify-img"><i class="icon-info4"></i></div>'
                                    + '<div class="div_content_inner">'
                                    + '<p class="time">Module Not Found</p>'
                                    + '</div></li>';
                        }

                        $(".dropdown_content_data").html(default_content + content);

                        if (text !== undefined) {
                            $("#searched-module-content").val(text);
                        }

                        // $('#custom_loading').removeClass('hide');
                        // $('#custom_loading').css('display', 'none');

                        // Set courser on the serarch box
                        $('#searched-module-content').focus();
                    }
                });
            }

            $(document).on('click', '#searched-module-content-button', function (e) {
                var searched_text = $('#searched-module-content').val();

                if (searched_text) {
                    load_content_bar(searched_text);
                } else {
                    load_content_bar();
                }
                e.stopPropagation();
            });

            $(document).on('keyup', '#searched-module-content', function (e) {
                var searched_text = $('#searched-module-content').val();

                if (searched_text) {
                    load_content_bar(searched_text);
                } else {
                    load_content_bar();
                }
                e.stopPropagation();
            });

            $(document).on('click', '.module_content_show', function (e) {
                var module_content_id = $(this).attr('data-id');

                $('#custom_loading').removeClass('hide');
                $('#custom_loading').css('display', 'block');

                $.ajax({
                    url: site_url + 'dashboard/get_content_data_ajax_by_id',
                    type: "POST",
                    data: {id: module_content_id},
                    success: function (response) {
                        $('#custom_loading').removeClass('hide');
                        $('#custom_loading').css('display', 'none');
                        $('#content_view_body').html(response);
                        $('#content_view_modal').modal('show');
                    }
                });

                $("#searched-module-content").val("");
//                e.stopPropagation();
            });

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
            $("#push_notification").click(function(e) {
                $.post(site_url + 'message/count', function(data) {
                    view_load();
                });
            });
        </script>
        <script>
            var session_set_status = '<?php if(isset($_SESSION['sessionAccessToken'])) { echo "yes"; }else {echo "no";} ?>';
        </script>
    </body>
</html>
<!--/Page container-->