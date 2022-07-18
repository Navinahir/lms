<?php
if ($this->input->get('redirect')) {
    $action = site_url('/login') . '?redirect=' . $this->input->get('redirect');
} else {
    $action = site_url('/login');
}
?>
<link href="assets/css/components.css" rel="stylesheet" type="text/css">
<link href="assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
<div class="login_page login_bg">
    <div>
        <div>
            <div>
                <div class="login_container">
                    <div class="login_left_panel">
                        <div class="logo_main">
                            <img src="<?php echo site_url('/assets/images/logo-white.png'); ?>" class="img-fluid">
                        </div>
                        <h3>Always Reliable Keys</h3>
                        <p>We have been working very hard to create a complete and comprehensive application for Automotive Security Professionals. </p>
                        <a href="mailto:contact@reliablekeys.com"><i class="fas fa-envelope"></i>contact@reliablekeys.com</a>
                    </div>
                    <div class="login_right_panel">
                        <form method="post" action="<?php echo $action; ?>" id="User_Login_Form">
                            <div>
                                <h2><span>L</span>ogin</h2>
                                <span><a class="account_msg" href="<?php echo site_url('register'); ?>">Don't have an account?</a></span>
                            </div>
                            <?php $this->load->view('alert_view'); ?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group custom_form_container">
                                        <input type="text" class="form-control ark_form" placeholder="Email" name="txt_username" id="txt_username" value="<?php if(isset($_COOKIE["user_username"])) { echo $_COOKIE["user_username"]; } ?>">
                                        <?php echo '<label id="txt_username_error2" class="validation-error-label" for="txt_username">' . form_error('txt_username') . '</label>'; ?>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group custom_form_container">
                                        <input type="password" class="form-control ark_form forget-login-input" placeholder="Password" name="txt_password" id="txt_password" value="<?php if(isset($_COOKIE["user_password"])) { echo $_COOKIE["user_password"]; } ?>">
                                        <?php echo '<label id="txt_Password_error2" class="validation-error-label" for="txt_password">' . form_error('txt_password') . '</label>'; ?>
                                        <a href="<?php echo site_url('forgot_password'); ?>" class="forget_msg">Forgot Password?</a>
                                    </div>
                                </div>

                            </div>
                            <div class="checkbox">
                                <label class="custom_check">&nbsp;&nbsp;Remember Me
                                    <input type="checkbox" class="styled checkbox_status" name="remember" value=""  <?php if(isset($_COOKIE["user_remember_me"]) == 1 && $_COOKIE["user_remember_me"] != "") { ?> checked <?php } ?> />
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="login_footer">
                                <button class="Login_btn btn_login">Login</button>
                                <div class="login_home">
                                    <a href="<?php echo base_url('/'); ?>" class="back_login_url">
                                        Back to Home <i class="fas fa-long-arrow-alt-right"></i> 
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .validation-error-label {
        /*color: #33a9f4;*/
        font-weight: 400;
        font-size: 16.8px;
        font-family: 'Roboto Light';
    }
    .form_success_vert_icon.form-control-feedback{
       /* margin-right: 90px !important;*/
       right: -24px;
    }

    .form_success_vert_icon.form-control-feedback i.icon-checkmark-circle{
        color: #03a9f4;
    }

    .forget_msg{margin-top: 12px;}
    
    /* Sotp crome autocomplete affect */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
        transition: background-color 5000s ease-in-out 0s;
        -webkit-text-fill-color: #fff !important;
    }
    
    @media (max-width: 767px){
        .login_page .checkbox {margin-bottom: 20px;}
        .validation-error-label,
        .validation-error-label:before, .validation-valid-label:before{
            font-size: 14px;
        }
    }
</style>
<script type="text/javascript" src="assets/js/custom_pages/front/home.js?version='<?php echo time();?>'"></script>
<script type="text/javascript">
    $(document).ready(function(){

        $(document).on('change','.checkbox_status',function(){
            checkCookie();
        });

        checkCookie();

        function checkCookie() {
            txt_user_username = $("#txt_username").val();
            txt_user_password = $("#txt_password").val();

            if($(".checkbox_status").prop('checked') == true && txt_user_username != "" && txt_user_password != ""){
                user_remember_me = 1;
                user_username = txt_user_username;
                user_password = txt_user_password;
            } else {
                user_remember_me = '';
                user_username = '';
                user_password = '';
            }
            setCookie("user_remember_me", user_remember_me, 30);
            setCookie("user_username", user_username, 30);
            setCookie("user_password", user_password, 30);
        }           

        function setCookie(cname,cvalue,exdays) {
          var d = new Date();
          d.setTime(d.getTime() + (exdays*24*60*60*1000));
          var expires = "expires=" + d.toGMTString();
          document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
          var name = cname + "=";
          var decodedCookie = decodeURIComponent(document.cookie);
          var ca = decodedCookie.split(';');
          for(var i = 0; i < ca.length; i++) {
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

    });
</script>