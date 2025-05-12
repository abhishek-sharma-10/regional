<?php
if (isset($confirmBox) && !empty($confirmBox)) {
	$confirmBox = $confirmBox;
} else {
	$confirmBox = false;
}
?>

<style>
	.main-div {
		width: 50%;
		border-radius: 10px;
		box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
		margin-bottom: 2rem;
		margin-top: 2rem;
		padding: 15px;
	}

	.forget-container {
		background-color: #2a437a;
		border-radius: 10px;
		max-width: 400px;
		margin: 40px auto;
		color: white;
	}

	.forget-msg-container {
		background-color: #2a437a;
		border-radius: 10px;
		max-width: 550px;
		margin: 40px auto;
		color: white;
	}
	
	/* .forget-container label, .forget-container h4 {
		color: white;
	} */

	.forget-container>div:first-child, .forget-msg-container>div:first-child {
		padding: 15px 30px 0px;
	}

	.forget-container>div:nth-child(3), .forget-msg-container>div:nth-child(3) {
		padding: 10px 30px 30px;
	}

	.form-control::placeholder {
		color: #6c757d;
	}

	.login-footer {
		font-size: 14px;
		font-weight: 600;
	}

	.login-footer p {
		margin-bottom: 5px;
	}

	.login-footer a {
		color: #ff0800;
		text-decoration: none;
	}
</style>

<div class="container main-div" id="resetBox" style="<?php echo $validToken ? 'display: block' : 'display: none'; ?>">
	<div class="forget-container">
		<div>
			<h4>Reset Password</h4>
		</div>
		<hr />
		<div>
			<form name="reset_form" id="reset_form" method="post" class="form-horizontal">
				<input type="hidden" name="userid" id="userid" value="<?php echo $userId ?>" />
				<div class="form-group mb-3">
					<label class="control-label">New Password </label>
					<input type="password" name="newpassword" class="form-control requiredfield" id="newpassword" data-rule-required="true" />
				</div>

				<div class="form-group mb-3">
					<label class="control-label">Confirm Password </label>
					<input type="password" name="confirmpassword" class="form-control requiredfield" id="confirmpassword" data-rule-equalto="#newpassword" data-msg-equalto="Invalid Confirm Password !" data-rule-required="true" />
				</div>

				<div class="form-group text-center">
					<button class="btn primary-btn text-white">Submit</button>
					<button class="btn btn-danger" type="reset">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="container main-div" id="confirmBox" style="<?php echo $confirmBox ? 'display: block' : 'display: none'; ?>">
	<div class="forget-msg-container">
		<div>
			<h4>Confirmation</h4>
		</div>
		<hr />
		<div>
			<h4>Your password has been successfully changed.</h4>
			<a href="<?php echo base_url('/'); ?>" class="btn primary-btn text-white mt-3" style="float: right;">Login</a><br /><br />
		</div>
	</div>
</div>

<div class="container main-div" id="warningBox" style="<?php echo !($validToken) && !isset($confirmBox) ? 'display: block' : 'display: none'; ?>">
	<div class="forget-msg-container">
		<div>
			<h4>Warning</h4>
		</div>
		<hr />
		<div>
			<h4>Your reset password link is expired. Please request for new password.</h4>
			<a href="<?php echo base_url('/'); ?>" class="btn primary-btn text-white mt-3" style="float: right;">Login</a><br /><br />
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {

		$("#preloadercustom").hide();
		$(".myspin").hide();
		$("#loginForm").validate();

		if ("<?php echo !isset($_REQUEST['confirmBox']) ?>") {
			$.validator.addMethod("validatePassword", function(value, element) {
				return this.optional(element) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*()_+=-`~\\\]\[{}|';:/.,?><]{8,}$/.test(value);
			}, 'Password must have<ul><li>Atleast 8 characters.</li><li>Atleast 1 small case character.</li><li>Atleast 1 upper case character.</li><li>Atleast 1 digit.</li><ul>');
			$.validator.addMethod("notValidAdmin", function(value, element) {
				return this.optional(element) || !(value.toLowerCase().includes("admin") || value.toLowerCase().includes("rie"));
			}, 'Password Can not Contain word admin and rie');
			$('#reset_form').validate({
				rules: {
					newpassword: {
						validatePassword: true,
						required: true,
						notValidAdmin: true
					}
				}
			});
		}
	});
</script>