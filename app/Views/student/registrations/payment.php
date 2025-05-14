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
    <button type="button" class="btn btn-outline-primary print-btn" onclick="openPrintPage()">🖨️ Print Form</button>
  </div>
  <!-- <img src="<?php //echo base_url(); ?>assets/img/NIC_logo1.jpg" alt="Logo" class="header-logo mb-3"> -->
  <div class="header-title">Regional Institute of Education</div>
  <p class="subtitle">Pushkar Road, Ajmer (Raj.)</p>
  <h4 class="mt-4 mb-2">Acknowledgement of Payment Entry Information</h4>
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
      <div class="row">
        <div class="col-sm-6 fw-bold">Mobile Number:</div>
        <div class="col-sm-6"><?= $details->phone;?></div>
      </div>
    </div>
  </div>

  <hr>
  <p><strong>Entered Receipt/DU Number of Payment Portal:</strong> <?= $details->receipt_no;?></p>
</div>
<script>
  function openPrintPage() {
    document.querySelector('#topbar').classList.add('d-none');
    document.querySelector('footer').classList.add('d-none');
    document.querySelector('.print-btn').style = "display: none";
    document.querySelector('.shadow-remove').style = "box-shadow: 0 0 0 rgba(0, 0, 0, 0)";

    window.onafterprint = (event) => {
      document.querySelector('#topbar').classList.remove('d-none');
      document.querySelector('footer').classList.remove('d-none');
      document.querySelector('.print-btn').style = "display: block";
      document.querySelector('.shadow-remove').style = "display: block";
    }
    window.print();
  }
</script>