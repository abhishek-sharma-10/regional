<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>RIE | <?php echo $pageTitle; ?></title>
        <link rel="shortcut icon" href="<?php echo base_url();?>public/assets/img/favicon.png" />

        <link href="<?php echo base_url();?>public/assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url();?>public/assets/font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href="<?php echo base_url();?>public/assets/css/animate.css" rel="stylesheet">
        <script src="<?php echo base_url();?>public/assets/js/jquery-2.1.1.js"></script>
        <!-- <script src="<?php echo base_url();?>public/assets/js/jquery-3.7.1.js"></script> -->
        <script src="<?php echo base_url();?>public/assets/js/jquery.validate.js"></script>
        <script src="<?php echo base_url();?>public/assets/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/assets/css/jquery-ui.min.css">
        
        <!--Date picker css-->
        <link href="<?php echo base_url();?>public/assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
        <!-- Data Tables CSS-->
        <link href="<?php echo base_url();?>public/assets/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url();?>public/assets/css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">
        <!-- <link href="<?php echo base_url();?>public/assets/css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet"> -->
        <link href="<?php echo base_url();?>public/assets/css/plugins/dataTables/buttons.dataTables.css" rel="stylesheet">
        
        <link href="<?php echo base_url(); ?>public/assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">        
        
        <link href="<?php echo base_url();?>public/assets/css/style.css" rel="stylesheet">
    </head>
    <body>
        <i class="fa fa-spinner fa-spin myspin" ></i>
            <div id="preloadercustom">
        </div>
        <div class="pace-div1"></div>
        <div id="wrapper">

        <?php
        
            // if(isset($_SESSION) && isset($_SESSION['student'])){
            //     view("template/navbar.php");
            // }
        ?>