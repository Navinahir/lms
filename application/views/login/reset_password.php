<form action="" method="post" id="reset_password_form">
	<div class="panel panel-body login-form">
		<div class="text-center">
			<div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
			<h5 class="content-group"><small class="display-block">Please enter the e-mail address and you will receive an email to reset your password to the email address you entered.</small></h5>
		</div>
		<?php $this->load->view('alert_view'); ?>
		<div class="form-group has-feedback">
			<input type="text" class="form-control" name="txt_password" id="txt_password" placeholder="Password">
			<div class="form-control-feedback">
				<i class="icon-key text-muted"></i>
			</div>
		</div>
        <div class="form-group has-feedback">
            <input type="text" class="form-control" name="txt_c_password" id="txt_c_password" placeholder="Confirm Password">
            <div class="form-control-feedback">
                <i class="icon-key text-muted"></i>
            </div>
        </div>

		<button type="submit" class="btn bg-blue btn-block btn_reset_pwd">Set Password <i class="icon-arrow-right14 position-right"></i></button>
		<a class="btn btn-default btn-block" href="<?php echo base_url(); ?>"> <i class="icon-arrow-left13 position-left"></i> Back to login</a>
	</div>
</form>
<script>
	var validator = $("#reset_password_form").validate({
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
        txt_password: { required: true, minlength: 8 }
        txt_c_password: { required: true, equalTo: '#txt_password' }
    },
    messages: {
        txt_password: {
            required: 'Please enter password!',
            minlength: 'Password must be atleast 8 characters long!'
        },
        txt_c_password: {
            required: 'Please confirm password!',
            equalTo: 'Password does not match!'
        },
    },
    submitHandler: function(form){
        form.submit();
        $('.btn_reset_pwd').prop('disabled', true);
    },
   	invalidHandler: function() {
        $('.btn_reset_pwd').prop('disabled', false);
   	}
});
</script>