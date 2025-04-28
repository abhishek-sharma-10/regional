<div class="row" id="resetBox" style="<?php echo $validToken ?'display: block':'display: none';?>">
	<div class="col-sm-12">
		<br />
		<div class="ibox float-e-margins">
            <div class="ibox-title">
            	<h5>Reset Password</h5>
                <div class="ibox-tools">
                </div>
            </div>

            <div class="ibox-content">
            	<form name="reset_form" id="reset_form" method="post" class="form-horizontal">
            		<div class="form-group">
            			<div class="col-md-6 col-md-offset-3">
            				<label class="control-label">New Password </label>
                            <input type="password" name="newpswd" class="form-control requiredfield" id="newpswd" data-rule-required="true"/>
            			</div>
            		</div>

            		<div class="form-group">
            			<div class="col-md-6 col-md-offset-3">
            				<label class="control-label">Confirm Password </label>
                            <input type="password" name="confirmpswd" class="form-control requiredfield" id="confirmpassword" data-rule-equalto="#newpswd" data-msg-equalto="Invalid Confirm Password !" data-rule-required="true"/>
                        </div>
            		</div>


            		<div class="clearfix"></div>
            		<div class="form-group">
            			<div  class="col-md-6 col-md-offset-3" align="center">
            				<button class="btn btn-primary">Submit</button>
							<a href="<?php echo base_url();?>HomeController"><button type="button" class="btn btn-danger">Cancel</button></a>
            			</div>
            		</div>
            	</form> 
        	</div>
        </div>
    </div>
</div>
<div class="row" id="confirmBox" style="<?php echo $confirmBox ?'display: block':'display: none';?>">
	<div class="col-sm-12">
		<br />
		<div class="ibox float-e-margins">
            <div class="ibox-title">
            	<h5>Confirmation</h5>
                <div class="ibox-tools">
                </div>
            </div>

            <div class="ibox-content">
            	<h4>Your password has been successfully changed.</h4>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#preloadercustom").hide();
        $(".myspin").hide();
        
        if("<?php echo !isset($_REQUEST['confirmBox'])?>"){
    	    $.validator.addMethod("validatePassword", function(value, element) {
              return this.optional( element ) || /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*()_+=-`~\\\]\[{}|';:/.,?><]{8,}$/.test( value );
            }, 'Password must have<ul><li>Atleast 8 characters.</li><li>Atleast 1 small case character.</li><li>Atleast 1 upper case character.</li><li>Atleast 1 digit.</li><ul>');
    	    $.validator.addMethod("notValidAdmin", function(value, element) {
              return this.optional( element ) || !(value.toLowerCase().includes("admin") || value.toLowerCase().includes("ibirds") );
            }, 'Password Can not Contain word admin and ibirds');
    		$('#reset_form').validate({
    		    rules: {
                    newpswd: {
                        validatePassword: true,
                        required: true,
                        notValidAdmin: true
                    }
                }
    		});
    	}
    });
</script>