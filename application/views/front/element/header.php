<div class="top_bar">
    <div class="container">
        <ul>
            <li><a href="javascript:void(0);" title="Youtube"><span class="social_icon"><i class="fab fa-youtube"></i></span></a></li>
            <li><a href="javascript:void(0);" title="Facebook"><span class="social_icon"><i class="fab fa-facebook-f"></i></span></a></li>
            <li><a href="javascript:void(0);" title="Linkedin"><span class="social_icon"><i class="fab fa-linkedin-in"></i></span></a></li>
            <li><a href="javascript:void(0);" title="Twitter"><span class="social_icon"><i class="fab fa-twitter"></i></span></a></li>
            <li><span class="contact_us">Have Any Questions? <a href="tel:+1-888-558-5397">Call Us Now</a></span></li>
        </ul>
    </div>
</div>
<nav class="navbar navbar-default header_navigation" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo site_url('/'); ?>"><img src="assets/images/logo.png"/></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <?php
            $class = $this->router->fetch_class();
            $action = $this->router->fetch_method();
            ?>
            <ul class="nav navbar-nav navbar-right">
                <li class="<?php echo ($class == 'home' && $action == 'index') ? 'active' : '' ?>"><a href="<?php echo site_url('/'); ?>">Home</a></li>
                <li class="<?php echo ($class == 'home' && $action == 'features') ? 'active' : '' ?>"><a href="<?php echo site_url('features'); ?>">Features</a></li>
                <li class="<?php echo ($class == 'home' && $action == 'about_us') ? 'active' : '' ?> about_us_collapse "><a href="<?php echo site_url('about_us'); ?>">About Us</a></li>
                <li class="<?php echo ($class == 'home' && $action == 'about_us#contact_us') ? 'active' : '' ?> contact_us_collapse"><a href="<?php echo site_url('about_us#contact_us'); ?>">Contact Us</a></li>
                <li class="<?php echo ($class == 'home' && $action == 'packages') ? 'active' : '' ?>"><a href="<?php echo site_url('packages'); ?>">Packages</a></li>
                <?php
                if (isset($user_data) && !empty($user_data)):
                    ?>
                    <li class="<?php echo ($class == 'dashboard' && $action == 'index') ? 'active' : '' ?>"><a href = "<?php echo site_url('dashboard'); ?>">Dashboard</a></li>
                    <?php
                else:
                    ?>
                    <li class="<?php echo ($class == 'home' && $action == 'register') ? 'active' : '' ?>"><a href="<?php echo site_url('register'); ?>">Sign up</a></li>
                    <li class="<?php echo ($class == 'home' && $action == 'login') ? 'active' : '' ?>"><a href="<?php echo site_url('login'); ?>">Log in</a></li>
                <?php
                endif;
                ?>
                <li><a class="contact_number" href="tel:+1-888-558-5397"> <span><i class="fas fa-phone"></i></span>1-888-558-5397</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div>
</nav>

<style>
    body {overflow-x: hidden;}
    @media (max-width: 1024px){
        .header_navigation .navbar-nav li .contact_number {font-size: 16px;}
        .header_navigation .navbar-brand {width: 116px;padding: 15px 0 10px 15px;}
    }
    @media (max-width: 768px){
        body .header_navigation .navbar-nav > li > a {font-size: 13.4px;padding: 9px 18px  !important;}
        .header_navigation .navbar-brand{width: 90px;padding: 10px 0 8px 15px;}
    }
    @media (max-width: 767px){
        .navbar-collapse {left: 20px;}

    }
</style>
<script type="text/javascript">
    $(document).on('click','.contact_us_collapse',function(){
        $('.about_us_collapse').removeClass('active');
    });
</script>