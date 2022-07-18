<?php $this->load->view('front/element/header'); ?>
<!--banner section-->
<div id="banner_carousel" class="carousel slide banner_carousel" data-ride="carousel">
    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        <div class="item active">
            <img src="assets/images/banner.jpg" alt="...">
            <div class="carousel-caption">
                <div>
                    <h3>Welcome to Always Reliable Keys Because Being An Expert is the <span style="color:#03a9f4;">KEY</span></h3>
                    <h4>&nbsp;</h4>
                    <!--<h1>locksmith company that can takecare of all your locksmithing needs.</h1>-->
                    <div class="mt-10">
                        <button onclick="return window.location.href = '#about_section';" class="banner_read_more">Read More</button>
                        <button onClick="return window.location.href = 'about_us#contact_us';" class="banner_contact_us">Contact us</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <img src="assets/images/banner2.jpg" alt="...">
            <div class="carousel-caption">
            </div>
        </div>
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#banner_carousel" data-slide="prev">
        <span class="fas fa-angle-left"></span>
    </a>
    <a class="right carousel-control" href="#banner_carousel" data-slide="next">
        <span class="fas fa-angle-right"></span>
    </a>
</div>

<!--need service section-->
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="nedd_service_section">
                <div class="need_service_bg">
                    <img class="need_service_img" src="assets/images/need_service_bg.png"/>
                    <img class="cogs" src="assets/images/cogs.png"/>
                </div>
                <div class="need_service_content">
                    <h4>See The Application in Action</h4>
                    <span> Request a demonstration today and one of our team members will contact you shortly.</span>
                </div>
                <button onClick="return window.location.href = 'about_us#contact_us';" class="Request_service_btn">REQUEST A DEMO</button>
            </div>
        </div>
    </div>
</div>

<!--our feature section-->
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h2 class="section_header"><span>Our </span>Features</h2>					
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="feature_container">
                <a href="features">
                    <div class="feature_icon feature_1">
                    </div>
                    <div class="feature_content">
                        <h3>Dashboard Search</h3>
                        <p>Search vehicle information by year, make, and model.</p>
                    </div>
                </a>
                <a href="features" class="more_feature_content">
                </a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="feature_container">
                <a href="features">
                    <div class="feature_icon feature_2">
                    </div>
                    <div class="feature_content">
                        <h3>Users & Roles</h3>
                        <p>Manage employee and administrative access levels</p>
                    </div>
                </a>
                <a href="features" class="more_feature_content">
                </a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="feature_container">
                <a href="features">
                    <div class="feature_icon feature_3">
                    </div>
                    <div class="feature_content">
                        <h3>Inventory</h3>
                        <p>Track and manage inventory</p>
                    </div>
                </a>
                <a href="features" class="more_feature_content">
                </a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="feature_container">
                <a href="features">
                    <div class="feature_icon feature_4">
                    </div>
                    <div class="feature_content">
                        <h3>Estimates and invoices</h3>
                        <p>Build a custom price estimate and create invoices</p>
                    </div>
                </a>
                <a href="features" class="more_feature_content">
                </a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="feature_container">
                <a href="features">
                    <div class="feature_icon feature_5">
                    </div>
                    <div class="feature_content">
                        <h3>Reports</h3>
                        <p>View simple, personalized sales reports</p>
                    </div>
                </a>
                <a href="features" class="more_feature_content">
                </a>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="feature_container">
                <a href="features">
                    <div class="feature_icon feature_6">
                    </div>
                    <div class="feature_content">
                        <h3>Programming and Troubleshooting</h3>
                        <p>Explore and follow procedure guides for programming and troubleshooting</p>
                    </div>
                </a>
                <a href="features" class="more_feature_content">
                </a>
            </div>
        </div>
    </div>
</div>

<!--about section-->
<div class="about_section" id="about_section">
    <div class="container">
        <div class="about_left_section">
            <div>
                <h3 class="about_header">Our Purpose</h3>
                <p class="about_para">The purpose of ARK is to provide you, our fellow locksmiths, with reliable tools, resources, and information to give your business a competitive edge. In this dynamic and fast-paced automotive locksmithing market, we are here to give you a hand.</p>
                <p class="about_para">At ARK, we understand what it feels like to waste time searching through numerous books and websites, just to find a simple answer. Our software has all of that information consolidated so it is readily accessible and easily understandable. Just enter the year, make, and model of a vehicle. We’ll do the rest.</p>
                <ul>
                    <li><span><img src="assets/images/cogs_blue.png"/></span>Service</li>
                    <li><span><img src="assets/images/security.png"/></span>Security</li>
                    <li><span><img src="assets/images/trust.png"/></span>Trust</li>
                </ul>
                <button onClick="return window.location.href = 'about_us';" class="about_read_more">Read More</button>
            </div>

        </div>
    </div>
</div>

<!--our product section-->
<div class="container hide">
    <div class="row">
        <div class="col-sm-12">
            <h2 class="section_header"><span>Our </span>Products</h2>
            <div class="row">
                <div class="col-sm-offset-2 col-sm-8">
                    <h5 class="section_subheader">We make it a goal to always have the keys you need. With 1,200+ Keys in stock at all times it is a pretty good chance that we will have the one you need.</h5>
                </div>
            </div>						
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div id="product_carousel" class="carousel slide product_carousel" data-ride="carousel">
                <!--Wrapper for slides--> 
                <div class="carousel-inner">
                    <div class="item active">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="product_container">
                                    <div class="product_img">
                                        <img src="assets/images/product1.jpg"/>
                                    </div>
                                    <h4>Basic Single Sided Key
                                    </h4>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="product_container">
                                    <div class="product_img">
                                        <img src="assets/images/product2.jpg"/>
                                    </div>
                                    <h4>Transponder Key
                                    </h4>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="product_container">
                                    <div class="product_img">
                                        <img src="assets/images/product3.jpg"/>
                                    </div>
                                    <h4>High Security Automotive Key
                                    </h4>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="product_container">
                                    <div class="product_img">
                                        <img src="assets/images/product4.jpg"/>
                                    </div>
                                    <h4>Automotive Key
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="item ">
                        <div class="row">

                            <div class="col-sm-3">
                                <div class="product_container">
                                    <div class="product_img">
                                        <img src="assets/images/product1.jpg"/>
                                    </div>
                                    <h4>Basic Single Sided Key
                                    </h4>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="product_container">
                                    <div class="product_img">
                                        <img src="assets/images/product2.jpg"/>
                                    </div>
                                    <h4>Transponder Key
                                    </h4>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="product_container">
                                    <div class="product_img">
                                        <img src="assets/images/product3.jpg"/>
                                    </div>
                                    <h4>High Security Automotive Key
                                    </h4>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="product_container">
                                    <div class="product_img">
                                        <img src="assets/images/product4.jpg"/>
                                    </div>
                                    <h4>Automotive Key
                                    </h4>
                                </div>

                            </div>		
                        </div>
                    </div>
                </div>

                <!--Controls--> 
                <a class="left carousel-control" href="#product_carousel" data-slide="prev">
                    <span class="fas fa-angle-left"></span>
                </a>
                <a class="right carousel-control" href="#product_carousel" data-slide="next">
                    <span class="fas fa-angle-right"></span>
                </a>
            </div>
        </div>
    </div>
</div>

<!--sub section-->
<div class="sub_section hide">
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <div class="sub_section_container">
                    <img src="assets/images/subsection1.jpg"/>
                    <h4>Services</h4>
                    <p>We are a full service locksmith company that can take care of all your locksmithing needs, including:</p>
                    <ul>
                        <li>Car Lockout</li>
                        <li>Keys Locked inside the Car</li>
                        <li>Broken Car Key Extraction</li>
                        <li>Lost Car Keys</li>
                        <li>Car Key Cutting Services</li>
                    </ul>
                    <a class="sub_section_read">Read More</a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="sub_section_container">
                    <img src="assets/images/subsection3.jpg"/>
                    <h4>sECURITY</h4>
                    <p>We offer years of experience in the security business as well as top of the line products that will make you and your property feel protected.</p>
                    <p>Our commitment. We hold every interaction with every customer to the highest standard. We are extremely proud of our repeat business and utmost level of customer satisfaction.</p>
                    <a class="sub_section_read">Read More</a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="sub_section_container">
                    <img src="assets/images/subsection2.jpg"/>
                    <h4>TRUST</h4>
                    <p>Not are locksmiths are created equal. We are Bonded and licensed locksmiths who are worthy of your trust</p>
                    <p>we have gone through great lengths to become Licensed, Bonded, and Insured so that you know with confidence that you can trust us.</p>
                    <a class="sub_section_read">Read More</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('front/element/footer'); ?>

<style>
    .sub_section_container{min-height: 500px;}
    @media(min-width:990px) and (max-width:1024px){
        .feature_container{height: 330px;}
    }
    @media(max-width:1024px){
        .need_service_content h4{padding-top: 20px;}
    }
    @media(max-width:768px){
        .feature_container{height: inherit;}
        .header_navigation .navbar-brand {width: 90px;padding: 10px 0 8px 15px;}
    }
    @media(max-width:767px){
        .sub_section_container {min-height: inherit;}
        .navbar-header {float: left;width: 100%;margin: 0 !important;}
        .navbar-header .navbar-toggle{margin-right: 0;}
        .navbar-collapse{left: 20px;}
    }

</style>