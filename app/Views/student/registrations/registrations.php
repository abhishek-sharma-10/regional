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

  .submit-btn {
    background-color: #203a72;
    color: #ffffff;
    padding-left: 40px;
    padding-right: 40px;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
    height: 40px;
    border-radius: 34px;
  }

  .submit-btn:hover{
    color: #fff;
  }

  .required-icon{
    color: red;
  }
</style>


<div class="container">
  <div class="form-container">
    <h2 class="form-title">NCET 2024 Application Form</h2>
    <form method="post" id="registration-form">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Application No <span class="required-icon">*</span></label>
          <input type="text" class="form-control" placeholder="Enter your application number" name="ncet_application_no" id="ncet_application_no" required>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Name <span class="required-icon">*</span></label>
          <input type="text" class="form-control" placeholder="Full name" name="name" required>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Gender <span class="required-icon">*</span></label>
          <select class="form-select" name="gender" required>
            <option selected disabled>Select gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Trans">Trans</option>
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Date of Birth <span class="required-icon">*</span></label>
          <input type="date" class="form-control" name="dob" required>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Father's Name <span class="required-icon">*</span></label>
          <input type="text" class="form-control" placeholder="Father's name" name="father_name" required>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Mother's Name <span class="required-icon">*</span></label>
          <input type="text" class="form-control" placeholder="Mother's name" name="mother_name" required>
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Address <span class="required-icon">*</span></label>
        <textarea class="form-control" rows="2" placeholder="Address" name="address" required></textarea>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">City <span class="required-icon">*</span></label>
          <input type="text" class="form-control" name="city" required>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">District <span class="required-icon">*</span></label>
          <input type="text" class="form-control" name="district" required>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">State <span class="required-icon">*</span></label>
          <input type="text" class="form-control" name="state" required>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Mobile No. <span class="required-icon">*</span></label>
          <input type="tel" class="form-control" placeholder="10-digit mobile number" name="phone" required>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Aadhar No. <span class="required-icon">*</span></label>
          <input type="text" class="form-control" placeholder="12-digit Aadhar number" name="aadhar_no" required>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Category <span class="required-icon">*</span></label>
          <select class="form-select" name="category" required>
            <option selected disabled>Select category</option>
            <option value="GEN">General</option>
            <option value="SC">SC</option>
            <option value="ST">ST</option>
            <option value="OBC-NCL">OBC-NCL</option>
            <option value="EWS">EWS</option>
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Physical Disability <span class="required-icon">*</span></label>
          <select class="form-select" name="physical_disable" required>
            <option selected disabled>Select option</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Email <span class="required-icon">*</span></label>
          <input type="email" class="form-control" placeholder="name@example.com" name="email" required>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Course <span class="required-icon">*</span></label>
          <select class="form-select" name="course" required>
            <option selected disabled>Select course</option>
            <option value="B.Sc. B.Ed">B.Sc. B.Ed.</option>
            <option value="B.A. B.Ed">B.A. B.Ed.</option>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Password <span class="required-icon">*</span></label>
          <input type="password" class="form-control" placeholder="Create password" name="password" id="password" autocomplete="new-password" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Confirm Password <span class="required-icon">*</span></label>
          <input type="password" class="form-control" placeholder="Confirm password" name="confirm_password" id="confirm_password" required>
        </div>
      </div>
      <div class="row">
        <div class="mt-3 d-grid justify-content-center">
          <button type="submit" class="btn form-submit-button submit-btn" name="submit">Submit Application</button>
        </div>
      </div>
    </form>
  </div>
</div>
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

    let rules = {
      name:{
        charactersOnly: true
      },
      father_name:{
        charactersOnly: true
      },
      mother_name:{
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
      },
      confirm_password: {
        required: true,
        equalTo: "#password"
      }
    };

    let messages = {
      confirm_password: {
        equalTo: "Pawword is not matched."
      },
      phone: {
        minlength: "Mobile No. should be of 10 Digits only.",
        maxlength: "Mobile No. should be of 10 Digits only."
      },
      aadhar_no: {
        minlength: "Aadhar No. should be of 12 Digits only.",
        maxlength: "Aadhar No. should be of 12 Digits only."
      }
    }

    $("#registration-form").validate({
      rules,
      messages
    });
  });
</script>