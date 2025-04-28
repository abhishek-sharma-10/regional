<body class="gray-bg">
    <div class="loginColumns animated fadeInDown">
		<div class="row">
			<div class="col-sm-12">
				<br />
				<div class="ibox float-e-margins">
		            <div class="ibox-title">
		            	<h5>Forgot Password Form</h5>
		                <div class="ibox-tools">
		                </div>
		            </div>

		            <div class="ibox-content">
		            	<form name="forget_form" id="forget_form" enctype="multipart/form-data" method="post" class="form-horizontal" action="<?php echo base_url(); ?>admin/forget-password">
		            		<div class="form-group">
		            			<div class="col-md-6 col-md-offset-3">
		            				<label class="control-label">Enter Username</label>
		            				<input type="text" name="username" id="username" class="form-control requiredfield" placeholder="Username" data-rule-required="true"/>
		            			</div>
		            		</div>
		            		<div class="clearfix"></div>
		            		<div class="form-group">
		            			<div  class="col-md-6 col-md-offset-3" align="center">
		            				<button class="btn btn-primary" type="submit">Submit</button>
									<button class="btn btn-danger" type="button" onclick="onCancelButton();">Cancel</button>
		            			</div>
		            		</div>
		            	</form> 
		            </div>
		        </div>
		    </div>
		</div>
	</div>
</body>

<script type="text/javascript">
    $(document).ready(function() {
    	//console.log('---');
    	$("#preloadercustom").hide();
        $(".myspin").hide();
        $('#forget_form').validate();   
    });
    
    function onCancelButton()
	{
        window.location.href = "<?php echo base_url();?>";
	}
</script>