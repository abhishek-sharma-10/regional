<style>
  .main-card {
    max-width: 600px;
    margin: 30px auto 60px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    padding: 30px;
    background-color: white;
    border-radius: 10px;
  }

  .header-logo {
    width: 60px;
    height: auto;
  }

  .header-title {
        font-size: 24px;
        font-weight: 600;
    }

    .subtitle {
        font-size: 16px;
    }
</style>

<div class="main-card text-center shadow-remove">
  <div class="d-flex justify-content-end mb-3">
    <button type="button" class="btn btn-outline-primary print-btn" onclick="openPrintPage()">🖨️ Print Receipt</button>
  </div>
  <!-- <img src="<?php //echo base_url(); ?>assets/img/NIC_logo1.jpg" alt="Logo" class="header-logo mb-3"> -->
  <div class="header-title">Regional Institute of Education</div>
  <p class="subtitle">Pushkar Road, Ajmer (Raj.)</p>
  <h4 class="mt-4 mb-2">Acknowledgement of Admission Payment</h4>
  <hr>
  <div class="text-start mb-3">
    <div class="p-4">
      <div class="row mb-3">
        <div class="col-sm-6 fw-bold">RIEA Registration Number:</div>
        <div class="col-sm-6"><?= $details->registration_no;?></div>
      </div>
      <div class="row mb-3">
        <div class="col-sm-6 fw-bold">NCET <?=date('Y');?> Application Number:</div>
        <div class="col-sm-6"><?= $details->ncet_application_no;?></div>
      </div>
      <div class="row mb-3">
        <div class="col-sm-6 fw-bold">Name:</div>
        <div class="col-sm-6"><?= $details->name;?></div>
      </div>
      <div class="row mb-3">
        <div class="col-sm-6 fw-bold">Mobile Number:</div>
        <div class="col-sm-6"><?= $details->phone;?></div>
      </div>
      <div class="row">
        <div class="col-sm-6 fw-bold">Payment Date:</div>
        <div class="col-sm-6"><?= date('d-m-Y', strtotime($details->academic_payment_date));?></div>
      </div>
    </div>
  </div>

  <hr>
  <p><strong>Entered Receipt/DU Number of Payment Portal:</strong> <?= $details->academic_receipt_no;?></p>
</div>
<script>
  function openPrintPage() {
    document.querySelector('#header').classList.add('d-none');
    document.querySelector('#topbar').classList.add('d-none');
    document.querySelector('footer').classList.add('d-none');
    document.querySelector('.print-btn').style = "display: none";
    document.querySelector('.main-card').style = "border:1px solid #ccc; box-shadow: 0 0 0 rgba(0, 0, 0, 0)";
    
    window.onafterprint = (event) => {
      document.querySelector('#header').classList.remove('d-none');
      document.querySelector('#topbar').classList.remove('d-none');
      document.querySelector('footer').classList.remove('d-none');
      document.querySelector('.print-btn').style = "display: block";
      document.querySelector('.main-card').style = "border:none; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15)";
    }
    window.print();
  }
</script>