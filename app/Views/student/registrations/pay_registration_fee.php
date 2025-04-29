<?php
  $status = isset($details->status) && ($details->status == "Request" || $details->status == "Save as Draft");
  var_dump($status);
?>

<style>
  .shadow-box {
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);
    border-radius: 10px;
    padding: 20px;
    background-color: #fff;
    max-width: 1000px;
  }

  .preview-image {
    max-height: 150px;
    /* margin-top: 10px; */
    border-radius: 5px;
    border: 1px solid #ddd;
  }

  .file-status {
    font-weight: bold;
  }

  .file-status.pending {
    color: #ffc107;
    /* warning */
  }

  .file-status.selected {
    color: #198754;
    /* success */
  }
</style>
<div class="container mt-4 mb-5">

  <!-- START | ERROR MESSAGE -->
  <?php if (session()->getFlashdata('err_msg')): ?>
    <div class="col-lg-12">
      <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">�</button>
        <?= session()->getFlashdata('err_msg') ?>
      </div>
    </div>
  <?php endif; ?>
  <!-- END | ERROR MESSAGE -->

  <div class="shadow-box mx-auto">
    <h4 class="text-center mb-4">Payment</h4>
    <div class="row g-3">
      <!-- Personal and academic info fields -->
      <div class="col-md-6"><label class="form-label">Application No.</label><input type="text" class="form-control" value="<?= $details->ncet_application_no ?>" readonly></div>
      <div class="col-md-6"><label class="form-label">NCET 2024 Reg. No.</label><input type="text" class="form-control" value="<?= $details->ncet_application_no ?>" readonly></div>
      <div class="col-md-6"><label class="form-label">Name</label><input type="text" class="form-control" value="<?= $details->name ?>" readonly></div>
      <div class="col-md-6"><label class="form-label">Gender</label><input type="text" class="form-control" value="<?= $details->gender ?>" readonly></div>
      <div class="col-md-6"><label class="form-label">Category</label><input type="text" class="form-control" value="<?= $details->category ?>" readonly></div>
      <div class="col-md-6"><label class="form-label">PwBD</label><input type="text" class="form-control" value="<?= $details->physical_disable == 1 ? 'Yes' : 'No'; ?>" readonly></div>
      <div class="col-md-6"><label class="form-label">Mobile No.</label><input type="text" class="form-control" value="<?= $details->phone ?>" readonly></div>
      <div class="col-md-6"><label class="form-label">Aadhaar No.</label><input type="text" class="form-control" value="<?= $details->aadhar_no ?>" readonly></div>
      <div class="col-md-12"><label class="form-label">Stream</label><input type="text" class="form-control" value="Not Set Yet" readonly></div>
    </div>

    <?php
      if($status){
    ?>
      <div class="text-danger mt-3 fw-bold text-center">
        You have not saved your Academic Details yet
      </div>
    <?php  
      }
    ?>

    <div class="mt-4">
      <p>To complete your registration Pay Counselling Fees Rs. 500/- using following info and confirm DU/No./Receipt Number submitting to us</p>
      <ul class="list-unstyled">
        <li><strong>Ac Name:</strong> Principal, RIE, Ajmer</li>
        <li><strong>Bank Name:</strong> SBI, RIE, Ajmer</li>
        <li><strong>Ac Number:</strong> 10200505552</li>
        <li><strong>IFSC:</strong> SBIN0015095</li>
      </ul>
    </div>

    <form action="<?php echo base_url(); ?>pay-registration-fee" method="post" enctype="multipart/form-data" id="payment-form">
      <!-- Upload section with preview and status -->
      <div class="mt-4">
        <div class="row">
          <input type="hidden" value="<?php echo $details->id; ?>" name="id">
          <div class="col-md-5 mb-3">
            <label class="form-label">Enter Receipt Number received from Payment Portal</label>
            <input type="text" class="form-control" name="receipt_no" required <?php echo $status ? 'disabled': '';?>>
          </div>
        </div>

        <label class="form-label">Attach Screenshot / Receipt Copy of Payment</label>
        <div class="row">
          <div class="col-md-4">
            <input type="file" class="form-control file-input" accept="image/*" name="payment_receipt" required <?php echo $status ? 'disabled': '';?>>
            <p class="mt-2 mb-0">Status: <span class="file-status pending">Pending</span></p>
          </div>
          <div class="col-md-4">
            <img id="preview" class="preview-image d-none" src="" alt="Preview">
          </div>
        </div>
      </div>

      <div class="text-center mt-4">
        <button type="submit" class="btn btn-warning px-5" <?php echo $status ? 'disabled': '';?>>Submit</button>
      </div>
    </form>
  </div>
</div>

<!-- Script for file preview and status change -->
<script>
  const fileInput = document.querySelector('.file-input');
  const previewImg = document.getElementById('preview');
  const fileStatus = document.querySelector('.file-status');

  fileInput.addEventListener('change', function() {
    if (this.files && this.files[0]) {
      const file = this.files[0];

      // Show preview
      previewImg.src = URL.createObjectURL(file);
      previewImg.classList.remove('d-none');

      // Update status
      fileStatus.textContent = 'Selected';
      fileStatus.classList.remove('pending');
      fileStatus.classList.add('selected');
    } else {
      previewImg.classList.add('d-none');
      fileStatus.textContent = 'Pending';
      fileStatus.classList.remove('selected');
      fileStatus.classList.add('pending');
    }
  });

  $(document).ready(function() {
    $.validator.addMethod('filesize', function(value, element, param) {
      return this.optional(element) || (element.files[0].size <= param * 1000000)
    }, 'Image size must be less than {0} MB');

    let rules = {
      receipt_no: {
        required: true,
        // number: true,
        minlength: 7,
        maxlength: 7,
      },
      payment_receipt: {
        required: true,
        extension: "jpg|jpeg|png",
        filesize: 3
      }
    };

    let messages = {
      receipt_no: {
        minlength: "Receipt No. should be of 7 Digits only.",
        maxlength: "Receipt No. should be of 7 Digits only."
      },
      payment_receipt: {
        required: "Please upload an image.",
        extension: "Please upload a file with a valid extension (jpg, jpeg, png)."
      }
    }

    $("#payment-form").validate({
      rules,
      messages
    });
  });
</script>