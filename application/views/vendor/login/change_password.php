<div class="page-header page-header-default">
    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url('/vendor/home'); ?>"><i class="icon-home2 position-left"></i> Home</a></li>
            <li  class="active">Change Password</li>
        </ul>
    </div>
</div>
<div class="content" id="">
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="<?php echo site_url('vendor/change_password'); ?>" id="change_password">
                <div class="panel panel-flat">
                    <div class="panel-body change_password">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alpha-blue border-blue alert-styled-left alert-bordered">
                                    Your password must include:
                                    <ul>
                                        <li>A minimum 8 characters</li>
                                        <li>Have at least one number between [0-9]</li>
                                        <li>Have at least one alphabet character between [A-Z] or [a-z]</li>
                                        <li>Have at least one special character [! @ # $ % ^ & * ( ) + ?]</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-20">
                            <label class="control-label col-lg-2">Password</label>
                            <div class="col-lg-10">
                                <div class="progress progress-micro pwd_progress1 hide">
                                    <div class="progress-bar pwd_strength_bar1" style="width:0%;"></div>
                                </div>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group mt-20">
                            <label class="control-label col-lg-2">Confirm Password</label>
                            <div class="col-lg-10">
                                <div class="progress progress-micro pwd_progress2 hide">
                                    <div class="progress-bar pwd_strength_bar2 progress-bar-danger" style="width: 0%;"></div>
                                </div>
                                <input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Confirm Password">
                            </div>
                        </div>
                        <div class="row" style="padding-left: 9px;">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button type="submit" class="btn bg-blue btn-block custom_save_button" style="letter-spacing: 1px;text-transform: uppercase;font-weight: 500;">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php $this->load->view('Templates/footer.php'); ?>
</div>
<script type="text/javascript" src="assets/js/custom_pages/vendor/dashboard.js"></script>
<style>
       .form-group {overflow: hidden;}
</style>