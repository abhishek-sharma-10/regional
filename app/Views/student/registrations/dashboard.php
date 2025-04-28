
  <style>
    body {
        background-color: #f8f9fa;
    }
    .box-shadow {
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
      padding: 2rem;
      border-radius: 0.5rem;
      background-color: white;
    }
    .info-box {
      background-color: #007f00;
      color: white;
      padding: 0.75rem 1rem;
      margin-bottom: 0.5rem;
    }
    .info-label {
      font-weight: bold;
      display: inline-block;
      width: 180px;
    }
    .action-btn {
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      font-weight: bold;
      padding: 1.5rem 1rem;
      color: white;
    }
  </style>
  <div class="container my-5">
    <div class="box-shadow">
      <h5 class="mb-4">Course Name: <strong>Integrated Teacher Education Program (ITEP)</strong></h5>

      <!-- Candidate Info -->
      <div class="row">
        <div class="col-lg-6">
          <div class="info-box"><span class="info-label">NCET Application No:</span> <?= $details->ncet_application_no;?></div>
          <div class="info-box"><span class="info-label">Name:</span> <?= $details->name;?></div>
          <div class="info-box"><span class="info-label">Father's Name:</span> <?= $details->father_name;?></div>
          <div class="info-box"><span class="info-label">Mother's Name:</span> <?= $details->mother_name?></div>
          <div class="info-box"><span class="info-label">DoB:</span> <?= $details->dob;?></div>
          <div class="info-box"><span class="info-label">Application Status:</span> Request</div>
          <div class="info-box"><span class="info-label">Payment Details:</span> Not Entered</div>
        </div>

        <!-- Action Buttons -->
        <div class="col-lg-6">
          <div class="row h-100">
            <div class="col-6 mb-3">
            <a href="/academic/<?=$details->id?>"><button class="w-100 action-box bg-warning text-dark shadow border-0" style="height:183px">
                <b>Update Candidate<br>Academic Details</b>
            </button></a>

            </div>
            <div class="col-6 mb-3">
            <a href="/pay-registration-fee/<?=$details->id?>"><button class="w-100 action-box bg-primary text-dark shadow border-0" style="height:183px">
                <b>Pay Registration<br>Fees</b>
            </button></a>
             
            </div>
            <div class="col-6 mb-3">
            <a href="/print-academic-details/<?=$details->id?>"><button class="w-100 action-box bg-info text-dark shadow border-0" style="height:183px">
                <b>Print Candidate<br>Academic Details</b>
            </button></a>
              
            </div>
            <div class="col-6 mb-3">
            <a href="/payment/<?=$details->id?>"><button class="w-100 action-box bg-secondary text-dark shadow border-0" style="height:183px">
                <b>Print Payment<br>Info</b>
            </button></a>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer Note -->
      <div class="mt-4 text-muted small">
        It is recommended that before apply please read the instruction carefully first 
        <a href="#" class="text-decoration-underline">Click here</a>
      </div>
    </div>
  </div>