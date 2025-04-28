
    <style>
        body {
            background-color: #fff;
        }
    
        .main-div{
            width: 50%;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            margin-bottom: 2rem;
            margin-top: 2rem;
            padding: 30px 15px 15px;
        }
        .login-container {
          background-color: #2a437a;
          padding: 30px;
          border-radius: 10px;
          max-width: 400px;
          margin: 0px auto;
          color: white;
        }
        .form-control::placeholder {
          color: #6c757d;
        }
        .form-login-btn {
          background-color: orange;
          border: none;
          width: 100%;
        }
        .form-login-btn:hover {
         background-color: darkorange;
        }
        .login-footer {
          font-size:14px;
          font-weight: 600;
        }
        .login-footer p {
            margin-bottom: 5px;
        }
        .login-footer a {
          color: red;
          text-decoration: none;
          font-weight: 900;
        }
    </style>

    <div class="container main-div">
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
            if(isset($invalid) && $invalid == true){
        ?>						
            <font color="#FF0000" face="Verdana">* Either username or password mismatch or your system is not valid to use this software.</font>
        <?php
            }
        ?>
        <button type="submit" class="btn form-login-btn text-white mt-2">Login</button>
      </form>
    </div>

    <div class="text-center mt-3 login-footer">
      <p>Forget Password ? <a href="#">Click here</a></p>
      <p>If not Registered yet <a href="<?php echo base_url('registrations'); ?>">Click here</a></p>
    </div>
  </div>

    <!--<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>-->
    <!--<script src="<?php echo base_url();?>assets/js/plugins/dataTables/jquery.dataTables.js"></script>-->
    <!--<script src="<?php echo base_url();?>assets/js/plugins/dataTables/dataTables.bootstrap.js"></script>-->
    <!--<script src="<?php echo base_url();?>assets/js/plugins/dataTables/dataTables.responsive.js"></script>-->
    <!--<script src="<?php echo base_url();?>assets/js/plugins/dataTables/dataTables.tableTools.min.js"></script>-->
    <script>
        // $(document).ready(function(){
        //     $("#loginForm").validate();
        // });
    </script>