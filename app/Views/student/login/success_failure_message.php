
<body class="gray-bg">
	<div class="loginColumns animated fadeInDown">
		<div class="row">
			<div class="col-sm-12">
				<br />
				<div class="ibox float-e-margins">
		            <div class="ibox-title">
		            	<h5>Information</h5>
		                <div class="ibox-tools">
		                </div>
		            </div>
		            <div class="ibox-content">		                
		            	<?php
						    $msg = isset($msg)? $msg : '';
						?>
						<h3><?= $msg ?></h3>
						<a href="<?php echo base_url();?>" class="btn btn-primary" style="float: right;" >Login</a><br/><br/>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
</body>    

<script>
    $(document).ready(function(){
        $("#preloadercustom").hide();
        $(".myspin").hide();
    });
</script>