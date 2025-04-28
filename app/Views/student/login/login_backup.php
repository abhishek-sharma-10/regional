<?php  
    //view('template/header',$pageTitle);
    // session_destroy();
?>

<body class="gray-bg">
    <div class="loginColumns animated fadeInDown">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <h2 class="font-bold">Welcome to Online Education Mangement System</h2>
                <br/>
                <br/>                
                <p>Perfectly designed and precisely prepared to manage college Data.</p>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="ibox-content">
                    <form id="loginForm" method="post" action="<?php echo base_url(); ?>login">
                        <div class="form-group">
                            <input type="text" name="username" placeholder="Registration Number" class="form-control required" />
                        </div>
                        <div class="form-group">
                            <input type="password" name="passcode" placeholder="Password" class="form-control required" />
                        </div>
                        <?php
                        if(isset($invalid) && $invalid == true){
						?>						
							<font color="#FF0000" face="Verdana">* Either username or password mismatch or your system is not valid to use this software.</font>
						<?php
						}
						?>
                        <button  type="submit" class="btn btn-primary block full-width m-b">Login</button>
                        <!--User Name: <input type="text" value="" name="username" /><br/>
                        Password: <input type="text" value="" name="passcode" /><br/>
                        <input type="submit" value="Login" />-->
                    </form>
                    <p class="m-t" style="text-align:center">
                        <a href="<?php echo base_url();?>forget-password"><small>Forget Password </small></a>
                    </p>
                    <p class="m-t" style="text-align:center">
                        <small>Education Management System </small>
                    </p>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                Copyright @ Ibirds Services 
            </div>
            <div class="col-md-6 text-right">
               <small>© 2015-2016</small>
            </div>
        </div>
    </div>
</body>    

<?php    //$this->load->view('template/footer.php');  ?>

<script>
    $(document).ready(function(){
        $("#preloadercustom").hide();
        $(".myspin").hide();
        $("#loginForm").validate();
    });
</script>