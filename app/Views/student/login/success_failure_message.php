<style>
	.main-div {
		width: 50%;
		border-radius: 10px;
		box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
		margin-bottom: 2rem;
		margin-top: 2rem;
		padding: 15px;
	}

	.message-container {
		background-color: #2a437a;
		border-radius: 10px;
		max-width: 550px;
		margin: 40px auto;
		color: white;
	}

	.message-container div:first-child{
		padding: 15px 30px 0px;
	}

	.message-container div:nth-child(3){
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

<div class="container main-div">
	<div class="message-container">
		<div>
			<h4>Information</h4>
		</div>
		<hr/>
		<div class="text-start">
			<?php
				$msg = isset($msg) ? $msg : '';
			?>
			<h3><?= $msg ?></h3>
			<a href="<?php echo base_url('/'); ?>" class="btn primary-btn mt-3" style="float: right;">Login</a><br /><br />
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$("#preloadercustom").hide();
		$(".myspin").hide();
	});
</script>