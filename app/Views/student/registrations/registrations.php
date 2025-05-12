<style>
  body {
    background-color: #f8f9fa;
  }

  .form-container {
    max-width: 80%;
    margin: 2rem auto 4rem;
    padding: 2rem;
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  .form-title {
    text-align: center;
    margin-bottom: 1.5rem;
  }

  .small-container {
    max-width: 60%;
    margin: 2rem auto 4rem;
    padding: 2rem;
    background-color: #fff;
    border-radius: 15px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  .submit-btn {
    background-color: #203a72;
    color: #ffffff;
    padding-left: 40px;
    padding-right: 40px;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
    height: 40px;
    border-radius: 34px;
  }

  .submit-btn:hover {
    color: #fff;
  }

  .required-icon {
    color: red;
  }

  .col-md-6 {
    position: relative;
  }

  #password {
    padding-right: 40px;
    /* height: 38px;
    box-sizing: border-box; */
  }

  #togglePassword {
    position: fixed;
    top: 28%;
    left: 47%;
    transform: translate(25%, 4%);
    cursor: pointer;
    color: #333;
    z-index: 10;
    pointer-events: all;
    font-size: 16px;
  }

  #password:invalid {
    box-shadow: none;
  }
</style>
<!-- First Section-->
<?php
if ($email_container) {
?>
  <div class="container">
    <div class="small-container">
      <h2 class="form-title">Registration for Admission in ITEP Courses - 2025</h2>
      <form method="post" id="registration-form">
        <div class="row">
          <div class="col-md-6 mb-3">
            <b><label class="form-label">Email</label></b>
            <input type="email" class="form-control" placeholder="Enter your Email" name="email" id="email" required>
            <input type="hidden" class="form-control" name="registrations-process" value="send-email">
          </div>
          <div class="col-md-4 mb-3">
            <button type="submit" class="btn form-submit-button secondary-btn" name="submit" style="margin-top:26px">Send Email</button>
          </div>
        </div>
        <?php
        if (isset($msg) && !empty($msg)) {
        ?>
          <div class="alert alert-<?=$msg['box'];?>" role="alert">
            <span><?php echo $msg['msg']; ?></span>
          </div>
        <?php
        }
        ?>
        <?php
        //if (isset($emailMsg) && !empty($emailMsg)) {
        ?>
          <!-- <div class="alert alert-warning" role="alert">
            <span><?php //echo $emailMsg; ?></span>
          </div> -->
        <?php
        //}
        ?>
      </form>
    </div>
  </div>
<?php
}
?>

<!-- Second Section -->
<?php
if ($otp_container) {
?>
  <div class="container">
    <div class="small-container">
      <h2 class="form-title">Registration for Admission in ITEP Courses - 2025</h2>
      <form method="post" id="registration-form">
        <div class="row">
          <div class="col-md-6 mb-3">
            <b><label class="form-label">Verify OTP</label></b>
            <input type="text" class="form-control" placeholder="Enter OTP" name="otp" id="otp" required>
            <input type="hidden" class="form-control" name="registrations-process" value="verify-otp">
            <input type="hidden" class="form-control" name="email" value="<?php echo $email; ?>">
            <!-- <span>Session: <?php //echo session()->get('otp'); ?></span> -->
          </div>
          <div class="col-md-4 mb-3">
            <button type="submit" class="btn form-submit-button secondary-btn" name="submit" style="margin-top:26px">Verify OTP</button>
          </div>
        </div>
        <?php
        if (isset($msg) && !empty($msg)) {
        ?>
          <div class="alert alert-<?=$msg['box'];?>" role="alert">
            <span><?php echo $msg['msg']; ?></span>
          </div>
        <?php
        }
        ?>
      </form>
    </div>
  </div>
<?php } ?>

<!-- Third Section -->
<?php
if ($register_container) {
?>
  <div class="container">
    <div class="form-container">
      <h2 class="form-title">Registration for Admission in ITEP Courses - <?= date('Y'); ?></h2>
      <form method="post" id="registration-form">
        <?php
        if (isset($msg) && !empty($msg)) {
        ?>
          <div class="alert alert-<?=$msg['box'];?>" role="alert">
            <span><?php echo $msg['msg']; ?></span>
          </div>
        <?php
        }
        ?>
        <div class="row">
          <div class="col-md-6 mb-3">
            <b><label class="form-label">Email</label></b>
            <input type="text" class="form-control" value="<?php echo $email; ?>" name="email" id="email" readonly>
          </div>
          <div class="col-md-6 mb-3">
            <b><label class="form-label">NCET <?= date('Y'); ?> Application Number <span class="required-icon">*</span></label></b>
            <input type="hidden" class="form-control" name="registrations-process" value="registration">
            <input type="text" class="form-control" placeholder="Enter Your Application Number" name="ncet_application_no" id="ncet_application_no" required>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <b><label class="form-label">Candidate Name <span class="required-icon">*</span></label></b>
            <input type="text" class="form-control" placeholder="Candidate Name" name="name" required>
          </div>

          <div class="col-md-6 mb-3">
            <b><label class="form-label">Gender <span class="required-icon">*</span></label></b>
            <select class="form-select" name="gender" required>
              <option selected disabled>Select Gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Trans">Trans</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <b><label class="form-label">Mother's Name <span class="required-icon">*</span></label></b>
            <input type="text" class="form-control" placeholder="Mother's Name" name="mother_name" required>
          </div>
          <div class="col-md-6 mb-3">
            <b><label class="form-label">Father's Name <span class="required-icon">*</span></label></b>
            <input type="text" class="form-control" placeholder="Father's Name" name="father_name" required>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <b><label class="form-label">Date of Birth <span class="required-icon">*</span></label></b>
            <input type="date" class="form-control" name="dob" required>
          </div>

          <div class="col-md-6 mb-3">
            <b><label class="form-label">Aadhar Number <span class="required-icon">*</span></label></b>
            <input type="text" class="form-control" placeholder="12-digit Aadhar Number" name="aadhar_no" required>
          </div>
        </div>
        
        <div class="mb-3">
          <b><label class="form-label">Address <span class="required-icon">*</span></label></b>
          <textarea class="form-control" rows="2" placeholder="Address" name="address" required></textarea>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <b><label class="form-label">City <span class="required-icon">*</span></label></b>
            <input type="text" class="form-control" name="city" required>
          </div>
          
          <div class="col-md-6 mb-3">
            <b><label class="form-label">District <span class="required-icon">*</span></label></b>
            <input type="text" class="form-control" name="district" required>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-6 mb-3">
            <b><label class="form-label">State <span class="required-icon">*</span></label></b>
            <select class="form-select" name="state" required>
              <option selected disabled>Select State</option>
              <option value="Andhra Pradesh">Andhra Pradesh</option>
              <option value="Arunachal Pradesh">Arunachal Pradesh</option>
              <option value="Assam">Assam</option>
              <option value="Bihar">Bihar</option>
              <option value="Chhattisgarh">Chhattisgarh</option>
              <option value="Gujarat">Gujarat</option>
              <option value="Haryana">Haryana</option>
              <option value="Himachal Pradesh">Himachal Pradesh</option>
              <option value="Jammu and Kashmir">Jammu and Kashmir</option>
              <option value="Goa">Goa</option>
              <option value="Jharkhand">Jharkhand</option>
              <option value="Karnataka">Karnataka</option>
              <option value="Kerala">Kerala</option>
              <option value="Madhya Pradesh">Madhya Pradesh</option>
              <option value="Maharashtra">Maharashtra</option>
              <option value="Manipur">Manipur</option>
              <option value="Meghalaya">Meghalaya</option>
              <option value="Mizoram">Mizoram</option>
              <option value="Nagaland">Nagaland</option>
              <option value="Odisha">Odisha</option>
              <option value="Punjab">Punjab</option>
              <option value="Rajasthan">Rajasthan</option>
              <option value="Sikkim">Sikkim</option>
              <option value="Tamil Nadu">Tamil Nadu</option>
              <option value="Telangana">Telangana</option>
              <option value="Tripura">Tripura</option>
              <option value="Uttarakhand">Uttarakhand</option>
              <option value="Uttar Pradesh">Uttar Pradesh</option>
              <option value="West Bengal">West Bengal</option>
              <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
              <option value="Chandigarh">Chandigarh</option>
              <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
              <option value="Daman and Diu">Daman and Diu</option>
              <option value="Delhi">Delhi</option>
              <option value="Lakshadweep">Lakshadweep</option>
              <option value="Puducherry">Puducherry</option>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <b><label class="form-label">Pincode <span class="required-icon">*</span></label></b>
            <input type="text" class="form-control" placeholder="Enter Pincode" name="pincode" required>
          </div>          
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <b><label class="form-label">Category <span class="required-icon">*</span></label></b>
            <select class="form-select" name="category" required>
              <option selected disabled>Select Category</option>
              <option value="GEN">General</option>
              <option value="SC">SC</option>
              <option value="ST">ST</option>
              <option value="OBC-CL">OBC (Creamy Layer)</option>
              <option value="OBC-NCL">OBC (Non-Creamy Layer)</option>
              <option value="EWS">EWS</option>
            </select>
          </div>

          <div class="col-md-6 mb-3">
            <b><label class="form-label">Physical Disability <span class="required-icon">*</span></label></b>
            <select class="form-select" name="physical_disable" required>
              <option selected disabled>Select Option</option>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <b><label class="form-label">Course <span class="required-icon">*</span></label></b>
            <select class="form-select" name="course" required>
              <option selected disabled>Select Course</option>
              <option value="ITEP - B.Sc. B.Ed.">ITEP - B.Sc. B.Ed.</option>
              <option value="ITEP - B.A. B.Ed.">ITEP - B.A. B.Ed.</option>
              <option value="ITEP - B.Sc. B.Ed. &  B.A. B.Ed.">ITEP - (B.Sc. B.Ed. &  B.A. B.Ed.)</option>
            </select>
          </div>
          <div class="col-md-6 mb-3">
              <b><label class="form-label">Mobile Number. <span class="required-icon">*</span></label></b>
              <input type="tel" class="form-control" placeholder="10-digit Mobile Number" name="phone" required>
          </div> 
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <b><label class="form-label">Password <span class="required-icon">*</span></label></b>
            <input type="password" class="form-control" placeholder="Create Password" name="password" id="password" autocomplete="new-password" required>
            <i class="bi bi-eye-slash" id="togglePassword"></i>
          </div>
          <div class="col-md-6 mb-3">
            <b><label class="form-label">Confirm Password <span class="required-icon">*</span></label></b>
            <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" id="confirm_password" required>
          </div>
        </div>
        <div class="row">
        <div class="col-md-6 mb-3">
          <ul class="password-requirements">
            <li>Must contain at least 8 characters</li>
            <li>Must contain at least one uppercase letter & one lowercase letter</li>
            <li>Must contain at least one number</li>
            <li>Must contain at least one special character</li>
            
          </ul>
        </div>
        </div>
        <div class="row">
          <div class="mt-3 d-grid justify-content-center">
            <button type="submit" class="btn form-submit-button submit-btn" name="submit">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
<?php } ?>
<script>
  $(document).ready(function() {
    $("#preloadercustom").hide();
    $(".myspin").hide();

    $('.form-submit-button').click(function() {
      if ($("#registration-form").valid()) {
        $("#spinner").removeClass("hidden")
      }
    });

    $.validator.addMethod("exists", function(value, element) {
      console.log($(element).val());
      var result = false;
      let applicationNo = $(element).val();

      if (applicationNo != '') {
        $.ajax({
          type: "GET",
          url: "<?php echo base_url('checkApplicationNo'); ?>/" + applicationNo,
          dataType: "json",
          async: false,
          contentType: "application/json",
          cache: false,
        }).done(function(data) {
          console.log("Complated", data);
          console.log("StatusCode", data.status);
          console.log("Result", data.result);
          if (data.status == 200) {
            if (data.result.length > 0) {
              result = false;
              $('.submit-btn').attr('disabled', true);
            } else {
              result = true;
              $('.submit-btn').attr('disabled', false);
            }
          }
        }).fail(function(data) {
          result = true;
          console.log("failure", data);
        });
      }

      return result;
      // if (maxMarks === "") return true; // skip if max marks not filled yet
      // return parseFloat(value) <= parseFloat(maxMarks);
    }, "Application is already filled with the entered NCET Application No.");

    $.validator.addMethod("charactersOnly", function(value, element) {
      return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
    }, "Please enter characters only.");

    $.validator.addMethod("regex", function(value, element, regexp) {
    if (regexp.constructor != RegExp)
        regexp = new RegExp(regexp);
    else if (regexp.global)
        regexp.lastIndex = 0;
    return this.optional(element) || regexp.test(value);
  }, "Please check your input format.");

    let rules = {
      name: {
        charactersOnly: true
      },
      father_name: {
        charactersOnly: true
      },
      mother_name: {
        charactersOnly: true
      },
      ncet_application_no: {
        exists: true,
      },
      aadhar_no: {
        required: true,
        number: true,
        minlength: 12,
        maxlength: 12
      },
      phone: {
        required: true,
        number: true,
        minlength: 10,
        maxlength: 10
      },
      email: {
        required: true,
        email: true
      },
      password: {
        required: true,
        regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/
      },
      confirm_password: {
        required: true,
        equalTo: "#password"
      },
      pincode: {
        required: true,
        minlength: 6,
        maxlength: 6,
        number: true,
      }
    };

    let messages = {
      confirm_password: {
        equalTo: "Password is not matched."
      },
      phone: {
        minlength: "Mobile No. should be of 10 Digits only.",
        maxlength: "Mobile No. should be of 10 Digits only."
      },
      aadhar_no: {
        minlength: "Aadhar No. should be of 12 Digits only.",
        maxlength: "Aadhar No. should be of 12 Digits only."
      },
      pincode: {
        // required: "Pincode is required.",
        minlength: "Pincode must be exactly 6 digits.",
        maxlength: "Pincode must be exactly 6 digits.",
        number: "Pincode must contain only numbers.",
      }
    };

    $("#registration-form").validate({
      rules,
      messages
    });
  });

  const togglePassword = document.querySelector('#togglePassword');
		const password = document.querySelector('#password');
		togglePassword.addEventListener('click', () => {
			// Toggle the type attribute using
			// getAttribure() method
			const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
			password.setAttribute('type', type);
			// Toggle the eye and bi-eye icon
			this.classList.toggle('bi-eye-slash');
            this.classList.toggle('bi-eye');
		});
</script>