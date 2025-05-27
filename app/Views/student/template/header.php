<html>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>RIEA | <?= $pageTitle;?></title>
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
<style>
    body {
      background-color: #f8f9fa;
    }

    .loader-wrapper {
      background: #d3d3d382;
      position: fixed;
      top: 0;
      bottom: 0;
      right: 0;
      left: 0;
      display: grid;
      place-items: center;
    }
    .loader {
      width: 50px;
      aspect-ratio: 1;
      border-radius: 50%;
      background: 
        radial-gradient(farthest-side,#ffa516 94%,#0000) top/8px 8px no-repeat,
        conic-gradient(#0000 30%,#ffa516);
      -webkit-mask: radial-gradient(farthest-side,#0000 calc(100% - 8px),#000 0);
      animation: spin 1s infinite linear;
    }
    @keyframes spin{ 
      100%{transform: rotate(1turn)}
    }
  </style>
</head>

<body>
<div class="loader-wrapper" style="display: none;">
  <div class="loader"></div>
</div>
  <!-- ======= Top Bar ======= -->
  <section id="topbar" class="d-flex align-items-center">
    <div class="container d-flex justify-content-center justify-content-md-between">
      <div class="col-6 d-flex">
        <div>
          <img src="<?php echo base_url(); ?>public/assets/img/logo1.png" class="img-fluid logo" />
        </div>
        <div>
          <h3 class="pt-2 pb-12 blue-color fw-bolder">REGIONAL INSTITUTE OF EDUCATION, AJMER</h3>
          <h4 class="blue-color">A Constituent Unit of NCERT, New Delhi</h4>
          <h5 class="blue-color">NAAC Accrediated A+ Grade Institute</h5>
        </div>
      </div>
      <div class="col-6 d-flex justify-content-end topbar-imgs">
        <img src="<?php echo base_url(); ?>public/assets/img/naac-logo.png" class="img-fluid" />
        <!-- <img src="<?php //echo base_url(); ?>public/assets/img/AKAMLogo1.png" class="img-fluid" /> -->
        <a href="<?= base_url('public/assets/ITEP-Admission-Brochure-2025.pdf') ?>" target="_blank" class="header-anchor mt-4 ms-4">Download Brochure</a>
        
        <a href="<?php echo base_url('instructions') ?>" class="header-anchor mt-4">Instructions to Apply</a>
        <a href="<?php echo base_url('contact-us') ?>" class="header-anchor mt-4">Contact Us</a>
        <?php if(!(array_key_exists('student', $_SESSION) && isset($_SESSION['student']))){ ?><a href="<?php echo base_url('register'); ?>" class=" mt-4 ms-2"><button class="btn secondary-btn blink-button">Register</button></a><?php } ?>
        <a href="<?php echo base_url(). (array_key_exists('student', $_SESSION) && isset($_SESSION['student'])) ? '/logout' : ''; ?>" class=" mt-4 ms-2"><button class="btn secondary-btn"><?php echo (array_key_exists('student', $_SESSION) && isset($_SESSION['student'])) ? 'Logout' : 'Login'; ?></button></a>
      </div>
    </div>
  </section>

  <!-- ======= Header ======= -->
  <?php if (isset($_SESSION['student'])){
    $payment_status = $details->status === 'Complete' ? true : false;
  ?>
  <header id="header" class="pt-1 pb-2">
    <div class="container-fluid d-flex align-items-center justify-content-center">
      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto <?php echo $active === 'academic' ? 'active' : ''; ?>" href="<?php echo base_url('academic');?>">Academic Details</a></li>
          <li><a class="nav-link scrollto <?php echo $active === 'pay-fees' ? 'active' : ''; ?>" href="<?php echo base_url('pay-registration-fee');?>">Pay Registration Fees</a></li>
          <?php if($payment_status) { ?><li><a class="nav-link scrollto <?php echo $active === 'print-academic' ? 'active' : ''; ?>" href="<?php echo base_url('print-academic-details'); ?>">Print Form</a></li><?php } ?>
        </ul>
        <!-- <i class="bi bi-list mobile-nav-toggle"></i> -->
      </nav><!-- .navbar -->
    </div>
  </header>
  <?php } ?>
  <!-- End Header -->