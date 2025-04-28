<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RIE | <?php echo $pageTitle; ?></title>
  <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/starinfotechcollegelogo.png" />

  <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/css/animate.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
  <script src="<?php echo base_url(); ?>assets/js/jquery-2.1.1.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/jquery-ui.min.css">

  <script src="<?php echo base_url(); ?>assets/js/plugins/jquery-ui/jquery-ui.min.js"></script>

</head>

<style>
  body {
    background-color: #fff;
  }

  .main-div {
    width: 50%;
    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    margin-bottom: 2rem;
    margin-top: 2rem;
  }

  .login-container {
    background-color: #2a437a;
    padding: 30px;
    border-radius: 10px;
    max-width: 400px;
    margin: 60px auto;
    color: white;
  }

  .form-control::placeholder {
    color: #6c757d;
  }

  .login-btn {
    background-color: orange;
    border: none;
    width: 100%;
  }

  .login-btn:hover {
    background-color: darkorange;
  }

  .login-footer {
    font-size: 16px;
    font-weight: 900;
  }

  .login-footer a {
    color: red;
    text-decoration: none;
  }
</style>

<body>
  <div class="container main-div" style="box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;">
    <div class="login-container text-center">
      <form id="loginForm" method="post" action="<?php echo base_url(); ?>login">
        <div class="mb-3 text-start">
          <label for="userId" class="form-label"><strong>User Id:</strong></label>
          <input type="text" class="form-control" id="userId" placeholder="Enter your Email" name="email">
        </div>
        <div class="mb-3 text-start">
          <label for="password" class="form-label"><strong>Password:</strong></label>
          <input type="password" class="form-control" id="password" placeholder="Password" name="password">
        </div>
        <?php
        if (isset($invalid) && $invalid == true) {
        ?>
          <font color="#FF0000" face="Verdana">* Either username or password mismatch or your system is not valid to use this software.</font>
        <?php
        }
        ?>
        <button type="submit" class="btn login-btn text-white mt-2">Login</button>
      </form>
    </div>

    <div class="text-center mt-3 login-footer">
      <p>Forget Password ? <a href="<?php echo base_url('forget-password'); ?>">Click here</a></p>
      <p>If not Registered yet <a href="<?php echo base_url('registrations'); ?>">Click here</a></p>
    </div>
  </div>
</body>
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/dataTables.responsive.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/dataTables.tableTools.min.js"></script>
<script>
  $(document).ready(function() {
    $("#loginForm").validate();
  });
</script>

</html>