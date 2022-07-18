<form action="" method="post" id="forgot_password_form">
    <div class="panel panel-body login-form">
        <div class="text-center">
            <div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
            <h5 class="content-group"><small class="display-block">Please enter the e-mail address and you will receive an email to reset your password to the email address you entered.</small></h5>
        </div>
        <?php $this->load->view('alert_view'); ?>
        <div class="form-group has-feedback">
            <input type="text" class="form-control" name="txt_email" id="txt_email" placeholder="Email">
            <div class="form-control-feedback">
                <i class="icon-mail5 text-muted"></i>
            </div>
        </div>

        <button type="submit" class="btn bg-blue btn-block btn_forgot_pwd">Reset password <i class="icon-arrow-right14 position-right"></i></button>
        <a class="btn btn-default btn-block" href="<?php echo base_url(); ?>"> <i class="icon-arrow-left13 position-left"></i> Back to login</a>
    </div>
</form>
<script>
	var validator = $("#forgot_password_form").validate({
    ignore: 'input[type=hidden], .select2-search__field, #txt_status', // ignore hidden fields
    errorClass: 'validation-error-label',
    successClass: 'validation-valid-label',
    highlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
    },
    unhighlight: function (element, errorClass) {
        $(element).removeClass(errorClass);
    },
    errorPlacement: function (error, element) {
        $(element).parent().find('.form_success_vert_icon').remove();
        if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container')) {
            if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                error.appendTo(element.parent().parent().parent().parent());
            } else {
                error.appendTo(element.parent().parent().parent().parent().parent());
            }
        } else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
            error.appendTo(element.parent().parent().parent());
        } else if (element.parents('div').hasClass('has-feedback') || element.hasClass('select2-hidden-accessible')) {
            error.appendTo(element.parent());
        } else if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
            error.appendTo(element.parent().parent());
        } else if (element.parent().hasClass('uploader') || element.parents().hasClass('input-group')) {
            error.appendTo(element.parent().parent());
        } else {
            error.insertAfter(element);
        }
    },
    validClass: "validation-valid-label",
    success: function (element) {
        $(element).parent().find('.form_success_vert_icon').remove();
        $(element).parent().append('<div class="form_success_vert_icon form-control-feedback"><i class="icon-checkmark-circle"></i></div>');
        $(element).remove();
    },
    rules: {
        txt_email: { required: true, email: true }
    },
    submitHandler: function(form){
        form.submit();
        $('.btn_forgot_pwd').prop('disabled', true);
    },
   	invalidHandler: function() {
        $('.btn_forgot_pwd').prop('disabled', false);
   	}
});
</script>
<style>
    .login-form a {color: #333 !important;}
</style>