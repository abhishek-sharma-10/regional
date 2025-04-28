<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>RIE | <?php echo $pageTitle; ?></title>
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/img/starinfotechcollegelogo.png" />

        <link href="<?php echo base_url();?>public/assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url();?>public/assets/font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href="<?php echo base_url();?>public/assets/css/animate.css" rel="stylesheet">
        <link href="<?php echo base_url();?>public/assets/css/style.css" rel="stylesheet">
        <script src="<?php echo base_url();?>public/assets/js/jquery-2.1.1.js"></script>
        <script src="<?php echo base_url();?>public/assets/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/jquery-ui.min.css">

        <script src="<?php echo base_url();?>assets/js/plugins/jquery-ui/jquery-ui.min.js"></script>
    
    </head>
    
    <style>
        body{ 
            background-color: #fefefe;
        }
        
        .heading1{
            font-family: math;
            font-size: 38px;
            font-weight: 700;
            color: #4f4f4f;
        }

        .heading1::after{
            content: "";
            margin: 0px auto 6px;
            display: block;
            width: 315px;
            height: 4px;
            border-radius: 2px;

            background: linear-gradient(25deg, #F13F79, #FFC778); 
        }

        .heading2{
            text-transform: uppercase;
            font-size: 20px;
            font-weight: 600;
            letter-spacing: 5px;
        }

        .head-div{
            display: flex;
        }

        .loginColumns{
            margin: 0px;
        }

        .banner-img{
            background-image: url('/assets/img/college-bg.jpg');
            height: 100%;
            background-position: 30% 35%;
        }

        .logo{
            width: 15%;
        }

        .form{
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .form-1{
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0px 0px 30px;
        }

        .form-1>div{
            margin-left: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-content{
            width: 25%;
            margin-top: 20px;
        }

        input{
            line-height: 36px !important;
            height: 36px !important;
            border-radius: 5px !important;
        }

        input:focus{
            border-color: #de2b2bd9 !important;
            outline: 0 !important;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(233 102 102 / 60%) !important;
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(233 102 102 / 60%) !important;
        }

        .btn-primary, .btn-primary:hover{
            background-color: #de2b2bd9;
            border-color: #de2b2bd9;
        }
    </style>
    <body>
        <div class="row">
            <div class="col-md-12 form">
                <div class="form-1">
                    <!-- <img src='\assets\img\starinfotechcollegelogo.png' class="logo"> -->
                    <div>
                        <span class="heading1">Regional Institute of Education</span>
                        <span class="heading2">Admin Portal</span>
                    </div>
                </div>

                <div class="form-content">
                    <form id="loginForm" method="post" action="<?php echo base_url(); ?>admin/login">
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
                    </form>
                    <p class="m-t" style="text-align:center">
                        <a href="<?php echo base_url();?>admin/forget-password">Forget Password</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- <footer>
            <div class="footer">
                <div class="text-center">
                    <strong>Copyright</strong> &copy; <?php echo date('Y');?> Star Infotech College <strong>• Developed By</strong> iBirds Software Services Pvt. Ltd.
                </div>
            </div>
        </footer> -->

        <!-- <div class="loginColumns animated fadeInDown">
            <div class="row">
                <div class="col-md-6 col-sm-12 head-div">
                    <div>
                        <img src='\assets\img\starinfotechcollegelogo.png' width='20%'>
                    </div>
                    <div>
                        <span class="heading1">Star Infotech College</span>
                        <p>Student Portal</p>
                    </div>
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
                            <button  type="submit" class="btn btn-primary block full-width m-b">Login</button> -->
                            <!--User Name: <input type="text" value="" name="username" /><br/>
                            Password: <input type="text" value="" name="passcode" /><br/>
                            <input type="submit" value="Login" />-->
                        <!-- </form>
                        <p class="m-t" style="text-align:center">
                            <a href="<?php //echo base_url();?>forget-password"><small>Forget Password </small></a>
                        </p>
                        <p class="m-t" style="text-align:center">
                            <small>Education Management System </small>
                        </p>
                    </div>
                </div>
            </div>
        </div> -->
    </body>
    <script src="<?php echo base_url();?>public/assets/js/jquery.validate.js"></script>
    <script src="<?php echo base_url();?>public/assets/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url();?>public/assets/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="<?php echo base_url();?>public/assets/js/plugins/dataTables/dataTables.responsive.js"></script>
    <script src="<?php echo base_url();?>public/assets/js/plugins/dataTables/dataTables.tableTools.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#preloadercustom").hide();
            $(".myspin").hide();
            $("#loginForm").validate();
        });
    </script>
</html>