<?php
if ($this->input->get('redirect')) {
    $action = site_url('admin/login') . '?redirect=' . $this->input->get('redirect');
} else {
    $action = site_url('admin/login');
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-6 animated hidden-xs" style="padding-top:100px">
            <h1 class="site-name">
                <span>A</span>lways
                <span>R</span>eliable 
                <span>K</span>eys
            </h1>
            <p>We have been working very hard to create a complete and comprehensive application for Automotive Security Professionals. To learn more please contact us at <a href="mailto:contact@arksecurity.com" target="_blank">contact@arksecurity.com</a></p>
        </div>
        <div class="col-md-6">
            <h1 class="site-name visible-xs">
                <span>A</span>lways
                <span>R</span>eliable 
                <span>K</span>eys
            </h1>
            <form method="post" action="<?php echo $action; ?>" id="User_Login_Form">
                <div class="panel panel-body login-form animated">
                    <div class="text-center">
                        <h1 style="color:#fff;padding: 15px 0;">Fill in the form below to get instant access now!</h1>
                    </div>
                    <?php $this->load->view('alert_view'); ?>
                    <div class="form-group has-feedback has-feedback-left">
                        <input type="text" class="form-control input-lg" placeholder="Username / Email" name="txt_username" id="txt_username" value="<?php if(isset($_COOKIE["admin_username"])) { echo $_COOKIE["admin_username"]; } ?>">
                        <div class="form-control-feedback">
                            <i class="icon-user text-muted"></i>
                        </div>
                    </div>

                    <div class="form-group has-feedback has-feedback-left">
                        <input type="password" class="form-control input-lg" placeholder="Password" name="txt_password" id="txt_password" value="<?php if(isset($_COOKIE["admin_password"])) { echo $_COOKIE["admin_password"]; } ?>">
                        <div class="form-control-feedback">
                            <i class="icon-lock2 text-muted"></i>
                        </div>
                    </div>

                    <div class="form-group login-options">
                        <div class="row">
                            <div class="col-sm-6">
                                <label class="checkbox-inline">
                                    <input type="checkbox" class="styled checkbox_status" name="remember" value="" <?php if(isset($_COOKIE["remember_me"]) == 1 && $_COOKIE["remember_me"] != "") { ?> checked <?php } ?> />
                                    Remember Me
                                </label>
                            </div>

                            <div class="col-sm-6 text-right">
                                <a href="<?php echo site_url('forgot_password?redirect_on=admin_login'); ?>">Forgot password?</a>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn bg-blue btn-block btn_login">Login <i class="icon-arrow-right14 position-right"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    @media (max-width:767px){
        .login-form h1 {font-size: 16px;}
    }
</style>
<script type="text/javascript">
    $(document).ready(function(){

        $(document).on('change','.checkbox_status',function(){
            checkCookie();
        });

        checkCookie();

        function checkCookie() {
            txt_admin_username = $("#txt_username").val();
            txt_admin_password = $("#txt_password").val();

            if($(".checkbox_status").prop('checked') == true && txt_admin_username != "" && txt_admin_password != ""){
                remember_me = 1;
                admin_username = txt_admin_username;
                admin_password = txt_admin_password;
            } else {
                remember_me = '';
                admin_username = '';
                admin_password = '';
            }
            setCookie("remember_me", remember_me, 30);
            setCookie("admin_username", admin_username, 30);
            setCookie("admin_password", admin_password, 30);
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