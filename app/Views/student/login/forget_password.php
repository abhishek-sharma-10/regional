<style>
	.main-div {
		width: 50%;
		border-radius: 10px;
		box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
		margin-bottom: 2rem;
		margin-top: 2rem;
		padding: 15px;
	}

	.login-container {
		background-color: #2a437a;
		padding: 30px;
		border-radius: 10px;
		max-width: 400px;
		margin: 50px auto;
		color: white;
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

<div class="container main-div">
	<div class="login-container text-center">
		<form name="forget_form" id="forget_form" enctype="multipart/form-data" method="post" class="form-horizontal" action="<?php echo base_url(); ?>forget-password">
			<div class="mb-3 text-start">
				<label class="form-label">Enter Registration Email</label>
				<input type="text" name="email" id="email" class="form-control requiredfield" placeholder="Registration Email" data-rule-required="true" />
			</div>
			<div class="form-group">
				<div class="col-md-12" align="center">
					<button class="btn primary-btn text-white" type="submit">Submit</button>
					<button class="btn btn-danger" type="button" onclick="onCancelButton();">Cancel</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		//console.log('---');
		$("#preloadercustom").hide();
		$(".myspin").hide();
		$('#forget_form').validate();
	});

	function onCancelButton() {
		window.location.href = "<?php echo base_url('/'); ?>";
	}
</script>