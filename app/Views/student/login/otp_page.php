
<style>
    .otperror{
        color: #f00;
    }
</style>
<body class="gray-bg">
    <div class="loginColumns animated fadeInDown">
        <div class="row">
            <div class="col-md-3">
                
            </div>
            <div class="col-md-6">
                <div class="ibox-content">
	                    <div class="form-group">
	                    	<label>Enter OTP:</label>
	                        <input type="text" name="otp_value" id="otp" placeholder="OTP" class="form-control" required="true" autocomplete="off" />
	                    </div>	                    
                        <a href="javascript:void(0)" onclick="checkLoginOtp()" class="btn btn-primary block full-width m-b">Login</a>
	                    <p><span class="otperror"></span></p>
	                    <p><label>OTP is :</label><?php echo $_SESSION['OTP']?></p> 
	                    <p><label>NOTE:</label> An OTP has been sent on your registered email. Please check your email.</p>
                    
                    <p class="m-t" style="text-align:center">
                        <small>Education Management System </small>
                    </p>
                </div>
            </div>
        </div>
        <hr/>
    </div>
</body>
<script>

	var otpCount = 0;

	function checkLoginOtp(){
		var otpVal = $("#otp").val();
        // console.log(otpVal.length);

		if (otpCount < 5) {

            $.ajax({
                type: "GET",
                url: "<?php echo base_url();?>admin/otp-process", 
                data: {'otp' : otpVal},
                dataType: "json",  
                cache:false,
                success: 
                function(data){
                    console.log(data);
                    if(data.status == 'success'){
                        window.location.href = "<?php echo base_url();?>admin/home";
                    }
                    else{
                        $(".otperror").text(data.message);
                        $("#otp").addClass('has-error');
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    $('#loader').hide();
                }
            });

			// if(otpVal && otp == otpVal){
			// 	window.location.href = "<?php echo base_url();?>/home";
            // }else{
            //     if(otpVal.length == 0){
            //         $(".otperror").text('Please Enter a otp. *');
            //     }else{
            //         $(".otperror").text('Please Enter a valid otp. *');
            //         $("#otp").addClass('has-error');
            //     }
            // }
            otpCount++;
        }
		else{
			window.location.href = "<?php echo base_url();?>";
		}
	}

	$(document).ready(function(){

        $("#preloadercustom").hide();
        $(".myspin").hide();

		$("#otp").on('keyup', function (e) {
		    if (e.keyCode == 13) {
		        checkLoginOtp();
		    }
		});
	});
	
</script>