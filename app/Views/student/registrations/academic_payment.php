<?php
$status = isset($details->status) && ($details->status == "Request" || $details->status == "Save as Draft");
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
    border-radius: 5px;
    border: 1px solid #ddd;
  }

  .file-status {
    font-weight: bold;
  }

  .file-status.pending {
    color: #ffc107;
  }

  .file-status.selected {
    color: #198754;
  }

  @media screen and (min-width: 786px) {
    .bank-details {
      border-right: 1px solid #e0e0e0;
    }
  }

  /* .error {
    color: red;
    font-size: 0.875em;
    margin-top: 5px;
  } */
</style>

<div class="container mt-4 mb-5">
  <?php if (session()->getFlashdata('err_msg')): ?>
    <div class="col-lg-12">
      <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <?= session()->getFlashdata('err_msg') ?>
      </div>
    </div>
  <?php endif; ?>

  <div class="shadow-box mx-auto">
    <h4 class="text-center mb-4">Admission Payment</h4>
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label">RIEA Registration Number</label><input type="text" class="form-control" value="<?= $details->registration_no ?>" readonly></div>
      <div class="col-md-6"><label class="form-label">NCET <?=date('Y');?> Application Number</label><input type="text" class="form-control" value="<?= $details->ncet_application_no ?>" readonly></div>
      <div class="col-md-6"><label class="form-label">Name</label><input type="text" class="form-control" value="<?= $details->name ?>" readonly></div>
      <div class="col-md-6"><label class="form-label">Gender</label><input type="text" class="form-control" value="<?= $details->gender ?>" readonly></div>
      <div class="col-md-6"><label class="form-label">Category</label><input type="text" class="form-control" value="<?= $details->category ?>" readonly></div>
      <div class="col-md-6"><label class="form-label">Physical Disability</label><input type="text" class="form-control" value="<?= $details->physical_disable ?>" readonly></div>
      <div class="col-md-6"><label class="form-label">Mobile Number</label><input type="text" class="form-control" value="<?= $details->phone ?>" readonly></div>
      <div class="col-md-6"><label class="form-label">Aadhaar Number</label><input type="text" class="form-control" value="<?= $details->aadhar_no ?>" readonly></div>
      <div class="col-md-6"><label class="form-label">Course</label><input type="text" class="form-control" value="<?= $details->course ?>" readonly></div>
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
      <p class="fw-bold">Please pay Institute fees as given below:</p>

      <div class="row mb-4">
        <div class="col-md-8">
          <div class="table-responsive">
            <table class="table table-striped table-bordered" style="font-size: 14px !important;">
              <thead>
                <tr>
                  <th>S.No.</th>
                  <th>Category</th>
                  <th>Fees (Without Hostel)</th>
                  <th>Fees (With Hostel)</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>GENERAL/ OBC/ EWS</td>
                  <td>Rs. 7,450/-</td>
                  <td>Rs. 29,650/-</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>SC/ ST/ PH</td>
                  <td>Rs. 4,950/-</td>
                  <td>Rs. 27,150/-</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-4 bank-details">
          <h3 class="mb-3">Bank Details</h3>
          <ul class="list-unstyled">
            <li><strong>Ac Name:</strong> Principal, RIE, Ajmer - CEE</li>
            <li><strong>Bank Name:</strong> SBI, RIE, Ajmer</li>
            <li><strong>Ac Number:</strong> 33687215909</li>
            <li><strong>IFSC:</strong> SBIN0015309</li>
          </ul>
        </div>
        <div class="col-sm-6">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-3">Scan & Pay</h3>
              <ul class="list-unstyled">
                <li><strong>Ac Name:</strong> Principal, RIE, Ajmer - CEE</li>
                <li><strong>Bank Name:</strong> SBI, RIE, Ajmer</li>
                <li><strong>UPI ID:</strong> rieajmer@sbi</li>
              </ul>
            </div>
            <div class="col-sm-6">
              <img src="<?php echo base_url('public/assets/sbi-qr-code.png');?>" alt="QR-Code" style="width: 170px;">
            </div>
          </div>
        </div>
      </div>
    </div>
    <form action="<?= base_url(); ?>pay-academic-fee" method="post" enctype="multipart/form-data" id="payment-form" novalidate>
      <input type="hidden" value="<?= $details->student_counselling_id; ?>" name="id">
      <input type="hidden" value="<?= $details->id; ?>" name="r_id">
      
      <?php //if($details->course == 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') { ?>
        <div class="row mt-4">
          <div class="col-md-5 mb-3">
            <label class="form-label">Select Course</label>
            <select name="course" id="course" class="form-select" required>
              <option value="" disabled>Select Course</option>
              <option value="ITEP - B.Sc. B.Ed." <?php echo ($details->course == 'ITEP - B.Sc. B.Ed.' ? 'selected' : ($details->course == 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.' ? '' : 'disabled'));?>>B.Sc. B.Ed.</option>
              <option value="ITEP - B.A. B.Ed." <?php echo ($details->course == 'ITEP - B.A. B.Ed.' ? 'selected' : ($details->course == 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.' ? '' : 'disabled'));?>>B.A. B.Ed.</option>
            </select>
            <label id="courseError" class="error"></label>
          </div>
        </div>
      <?php //} ?>

      <div class="row mt-4">
        <div class="col-md-5 mb-3">
          <label class="form-label">Enter Receipt Number received from Payment Portal</label>
          <input type="text" class="form-control" id="receipt_no" name="academic_receipt_no" <?php echo $status ? 'disabled': '';?>>
          <label id="receiptError" class="error"></label>
        </div>
      </div>

      <label class="form-label">Attach Screenshot / Receipt Copy of Payment</label>
      <div class="row">
        <div class="col-md-5">
          <input type="file" class="form-control file-input" id="fileInput" accept=".jpg,.jpeg,.png,.pdf" name="academic_payment_receipt" <?php echo $status ? 'disabled': '';?>>
          <label id="fileError" class="error"></label>
          <p class="mt-2 mb-0">Status: <span class="file-status pending">Pending</span></p>
        </div>
        <div class="col-md-4">
          <img id="preview" class="preview-image d-none" src="" alt="Preview">
        </div>
      </div>

      <div class="text-center mt-4">
        <button type="submit" class="btn btn-warning px-5" <?php echo $status ? 'disabled': '';?>>Submit</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const receiptInput = document.getElementById("receipt_no");
    const fileInput = document.getElementById("fileInput");
    const receiptError = document.getElementById("receiptError");
    const fileError = document.getElementById("fileError");
    const previewImg = document.getElementById("preview");
    const fileStatus = document.querySelector(".file-status");

    function validateReceipt() {
      const value = receiptInput.value.trim();
      const regex = /^[a-zA-Z0-9]+$/;
      if (!value) {
        receiptError.textContent = "Receipt number is required.";
        return false;
      } else if (!regex.test(value)) {
        receiptError.textContent = "Only letters and numbers allowed.";
        return false;
      } else {
        receiptError.textContent = "";
        return true;
      }
    }

    function validateFile() {
      const file = fileInput.files[0];
      if (!file) {
        fileError.textContent = "Please select a file.";
        return false;
      }

      const allowedExtensions = ["jpg", "jpeg", "png", "pdf"];
      const fileExtension = file.name.split(".").pop().toLowerCase();

      if (!allowedExtensions.includes(fileExtension)) {
        fileError.textContent = "Only JPG, JPEG, PNG, and PDF are allowed.";
        return false;
      }

      if (fileExtension === "pdf" && file.size > 1 * 1024 * 1024) {
        fileError.textContent = "PDF file must be 1MB or less.";
        return false;
      }

      fileError.textContent = "";
      return true;
    }

    fileInput.addEventListener("change", function () {
      const file = fileInput.files[0];
      if (file && validateFile()) {
        previewImg.src = URL.createObjectURL(file);
        previewImg.classList.remove("d-none");

        fileStatus.textContent = "Selected";
        fileStatus.classList.remove("pending");
        fileStatus.classList.add("selected");
      } else {
        previewImg.classList.add("d-none");
        fileStatus.textContent = "Pending";
        fileStatus.classList.remove("selected");
        fileStatus.classList.add("pending");
      }
    });

    receiptInput.addEventListener("input", validateReceipt);

    document.getElementById("payment-form").addEventListener("submit", function (e) {
      const isReceiptValid = validateReceipt();
      const isFileValid = validateFile();

      if (!isReceiptValid || !isFileValid) {
        e.preventDefault();
      }
    });
  });
</script>
