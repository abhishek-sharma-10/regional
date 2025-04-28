<?php
?>

<html>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>RIE, AJMER</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?php echo base_url(); ?>public/assets/img/favicon.png" rel="icon">
  <!-- <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">
    -->
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?php echo base_url(); ?>public/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>public/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>public/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>public/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <link href="<?php echo base_url(); ?>public/assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">
  <!-- <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet"> -->
  <!--  <link href="assets/vendor/aos/aos.css" rel="stylesheet"> -->

        <link href="<?php echo base_url();?>public/assets/font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href="<?php echo base_url();?>public/assets/css/animate.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/assets/css/jquery-ui.min.css">


  <!-- Template Main CSS File -->
  <link href="<?php echo base_url(); ?>public/assets/css/style.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>public/assets/css/student_style.css" rel="stylesheet">
  <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
  <script src="<?php echo base_url(); ?>public/assets/js/jquery-2.1.1.js"></script>
</head>

<body>

  <!-- ======= Top Bar ======= -->
  <section id="topbar" class="d-flex align-items-center">
    <div class="container d-flex justify-content-center justify-content-md-between">
      <div class="w-100 d-flex">
        <div>
          <img src="<?php echo base_url(); ?>public/assets/img/logo1.png" class="img-fluid logo" />
        </div>
        <div>
          <h3 class="pt-3 pb-12 blue-color fw-bolder">REGIONAL INSTITUTE OF EDUCATION, AJMER</h3>
          <h5 class="blue-color pb-2">A Constituent Unit of NCERT, New Delhi <span class="ps-2 orange-color">NAAC Graded A+ Institute</span></h5>
        </div>
      </div>
      <div class="d-flex justify-content-end topbar-imgs">
        <img src="<?php echo base_url(); ?>public/assets/img/naac-logo.png" class="img-fluid" />
        <img src="<?php echo base_url(); ?>public/assets/img/AKAMLogo1.png" class="img-fluid" />
        <img src="<?php echo base_url(); ?>public/assets/img/NIC_logo1.jpg" class="img-fluid" />
        <a href="<?php echo base_url(). (array_key_exists('student', $_SESSION) && isset($_SESSION['student'])) ? '/logout' : ''; ?>"><button class="btn login-btn mt-4 ms-5"><?php echo (array_key_exists('student', $_SESSION) && isset($_SESSION['student'])) ? 'Logout' : 'Login'; ?></button></a>
        <a href="<?php echo base_url('registrations') ?>"><button class="btn login-btn mt-4 ms-5">Register</button></a>
      </div>
    </div>
  </section>