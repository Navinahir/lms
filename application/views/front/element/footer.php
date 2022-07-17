<!--Footer-->
<div class="footer_section">
    <div class="container">
        <div class="row">
            <div class="col-sm-3 col-md-3 col-lg-4">
                <div class="about_us_section">
                    <h3 class="footer_label">About us</h3>
                    <p>Always Reliable Keys began in 2016 as a basic in-house system, but have grown since to be a widely used and trusted application for automotive locksmiths all across the U.S.</p>
                    <a href="<?= site_url('about_us') ?>">READ MORE</a>
                </div>                  
            </div>
            <div class="col-sm-3 col-md-2">
                <div class="links_section">
                    <h3 class="footer_label">LINKS</h3>
                    <ul>
                        <li><a href="<?php echo site_url('/'); ?>"><span></span>Home</a></li>
                        <li><a href="<?php echo site_url('features'); ?>"><span></span>Features</a></li>
                        <li><a href="<?php echo site_url('about_us'); ?>"><span></span>About Us</a></li>
                        <li><a href="<?php echo site_url('about_us#contact_us'); ?>"><span></span>Contact Us</a></li>
                        <li><a href="<?php echo site_url('packages'); ?>"><span></span>Packages</a></li>
                        <li><a href="<?php echo site_url('privacy_policies/Privacy'); ?>"><span></span>Privacy Policies</a></li>
                        <li><a href="<?php echo site_url('terms_of_services/Terms'); ?>"><span></span>Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-3 col-md-4 col-lg-3">
                <div class="get_in_touch_section">
                    <h3 class="footer_label">Get In Touch</h3>
                    <form method="post" action="<?php echo site_url('subscribe'); ?>" id="subscribevalidation">
                        <div class="form-group">                                
                            <input type="email" class="form-control custom_form subscribe_form" id="email" placeholder="Email" name="email" autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-primary submit_get_btn">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="col-sm-3 col-md-3">
                <div class="contact_section">
                    <h3 class="footer_label">Contact Us</h3>
                    <ul>
                        <li><a target="_blank" href="https://goo.gl/maps/ZUwWJJNs45QeEXHv5"><span><i class="fas fa-map-marker-alt"></i></span>47 - East Main Street, <br>Rexburg, Idaho - 83440.</a></li>
                        <li><a href="tel:+1-888-558-5397"><span><i class="fas fa-phone"></i></span>1-888-558-5397</a></li>
                        <!--<li><a><span><img src="assets/images/fax.png"/></span>208-356-4471</a></li>-->
                        <li><a href="mailto:contact@reliablekeys.com"><span><i class="fas fa-envelope"></i></span>contact@reliablekeys.com</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <p class="text-center text-white family-Roboto">© <?php echo date('Y'); ?> Always Reliable Keys </p>
            </div>
        </div>
    </div>
</div>
<!-- Validation -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<style type="text/css"> .error { color: red; font-weight: 100; } </style>
<script>
    $("#subscribevalidation").validate({
      rules: {
        email: 
            {
                required: true, 
                email: true, 
                stricemailonly: true,
            },
        },
        messages: {
            email: "Please enter valid email.",
       }
    });
    
    // Strick email only
    jQuery.validator.addMethod("stricemailonly", function(value, element) {
      return this.optional(element) || /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i.test(value);
    }, "Invalid Email Format.");

</script>