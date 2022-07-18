<form action="" method="post" id="reset_password_form">
    <div class="forget_password_page reset_page login_bg">
        <div>
            <div>
                <div>
                    <div class="login_container">
                        <div class="login_left_panel">
                            <a href="<?php echo site_url('/') ?>"><h3>Always Reliable Keys</h3></a>
                        </div>
                        <div class="login_right_panel">
                            <?php $this->load->view('alert_view'); ?>

                            <div class="reset_header register-success-header">
                                <h3>Thank you for registering at <br><span class="theme-text-color theme-text-type">Always Reliable Keys</span>.<br> We are excited you choose to partner with us.</h3>
                            </div>

                            <div class="register-success-content-format">
                                <p>We are doing everything we can to give you the most innovative and easy to use resource for Automotive Locksmiths everywhere! Your new account will need to be approved by one of our team members. This should happen very soon.</p>
                                <p>Please watch your inbox for a confirmation email with your login credentials to the site.</p>
                                <p>Thanks and we are excited to work with you!</p>
                            </div>

                            <div class="register-success-footer">
                                <p>ARK Team,</br> <a href="mailto:support@reliablekeys.com"><u>support@reliablekeys.com</u></a></p>
                            </div>

                            <div class="login_footer text-center">
                                <a href="<?= site_url() ?>" class="btn btn-primary">
                                    <i class="fa fa-home"></i>&nbsp;&nbsp;Back to home
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>