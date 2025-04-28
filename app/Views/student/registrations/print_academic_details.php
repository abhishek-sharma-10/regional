<?php //var_dump($details);?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>NCET Academic Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f2f2f2;
    }
    .main-box {
      max-width: 1200px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .logo {
      width: 60px;
      height: auto;
    }
    .header-title {
      font-size: 20px;
      font-weight: 500;
    }
    .subtitle {
      font-size: 18px;
    }
    .final-status {
      color: red;
      font-weight: bold;
    }
    .table th, .table td {
      vertical-align: middle;
    }
  </style>
</head>
<body>

    <div class="main-box text-center">

        <!-- Header -->
        <img src="your-logo.png" alt="Logo" class="logo mb-2">
        <div class="header-title">Regional Institute of Education</div>
        <div class="subtitle">Pushkar Road, Ajmer (Raj.)</div>
        <h5 class="mt-2">Academic Details</h5>
        <div class="final-status">(Final)</div>

        <!-- Profile Info -->
        <div class="mt-4">
        <h6 class="mb-1"><?=$details->name?></h6>
        <p class="text-muted">Application No.: <strong><?=$details->ncet_application_no?></strong></p>
        </div>

        <!-- Applicant Details Table -->
        <div class="table-responsive mb-4">
        <table class="table table-bordered text-center">
            <thead class="table-light">
            <tr>
                <th>NCET 2024 Application No.</th>
                <th>Stream</th>
                <th>Category</th>
                <th>PwBD</th>
                <th>Mobile No.</th>
                <th>Aadhar No.</th>
                <th>Gender</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?=$details->ncet_application_no?></td>
                <td><?=$details->course?></td>
                <td><?=$details->category?></td>
                <td><?=$details->physical_disable?></td>
                <td><?=$details->phone?></td>
                <td><?=$details->aadhar_no?></td>
                <td><?=$details->gender?></td>
            </tr>
            </tbody>
        </table>
        </div>

        <!-- Preference Table -->
        <h6 class="text-center mb-4">Disciplinary Major Subject Choice on Preference Order</h6>
        <div class="table-responsive mb-4">
        <table class="table table-bordered text-center">
            <thead class="table-light">
            <tr>
                <th>Course</th>
                <th>Preference 1st</th>
                <th>Preference 2nd</th>
                <th>Preference 3rd</th>
                <th>Preference 4th</th>
                <th>Preference 5th</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?=$details->course?></td>
                <td><?=$details->preference_1?></td>
                <td><?=$details->preference_2?></td>
                <td><?=$details->preference_3?></td>
                <td><?=$details->preference_4?></td>
                <td><?=$details->preference_5?></td>
            </tr>
            </tbody>
        </table>
        </div>

        <!-- Exam Details -->
        <h6 class="text-center mb-4">Details of Sr. Secondary or Equivalent Exam</h6>
        <div class="table-responsive mb-3">
        <table class="table table-bordered text-center">
            <thead class="table-light">
            <tr>
                <th>Stream</th>
                <th>Year of Passing</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?=$details->course?></td>
                <td><?=$details->year_of_passing?></td>
            </tr>
            </tbody>
        </table>
        </div>

        <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead class="table-light">
            <tr>
                <th>Maximum Marks</th>
                <th>Obtained Marks</th>
                <th>Percent</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?=$details->sr_sec_max_marks?></td>
                <td><?=$details->sr_sec_obtain_marks?></td>
                <td><?=$details->sr_sec_percentage?></td>
            </tr>
            </tbody>
        </table>
        </div>

        <div>
            <!-- NCET 2024 Exam Details -->
            <h5 class="text-center mb-4">Details of NCET 2024 Exam</h5>
            <div class="row text-center mb-3">
                <div class="col-md-4"><strong>Appeared in Year:</strong> <?=$details->year_of_passing?></div>
                <div class="col-md-4"><strong>NCET 2024 Roll No:</strong> <?=$details->ncet_roll_no?></div>
                <div class="col-md-4"><strong>Course:</strong> <?=$details->course?></div>
            </div>
            <!-- Subject Marks Table -->
            <div class="table-responsive mb-5">
                <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                    <th>Code</th>
                    <th>Subject</th>
                    <th>Total Maximum Marks</th>
                    <th>Total Obtained Marks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>102</td><td>Hindi</td><td>300</td><td>120</td></tr>
                    <tr><td>103</td><td>Assamese</td><td>300</td><td>120</td></tr>
                    <tr><td>104</td><td>Bengali</td><td>300</td><td>120</td></tr>
                    <tr><td>105</td><td>Gujarati</td><td>300</td><td>120</td></tr>
                    <tr><td>108</td><td>Kannada</td><td>300</td><td>120</td></tr>
                    <tr><td>107</td><td>Malayalam</td><td>300</td><td>120</td></tr>
                    <tr><td>301</td><td>ACCOUNTCY/BOOK KEEPING</td><td>300</td><td>120</td></tr>
                    <tr class="fw-bold">
                    <td colspan="2">Total</td>
                    <td>2100</td>
                    <td>840</td>
                    </tr>
                </tbody>
                </table>
            </div>
            <!-- Attachments Section -->
            <h6 class="mb-3">Attachments</h6>
            <div class="row text-center mb-5">
                <div class="col-md-2 col-6 mb-3">
                    <img src="10th.png" class="img-fluid mb-2" alt="10th Certificate">
                    <div>10th Certificate</div>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <img src="12th.png" class="img-fluid mb-2" alt="12th Marksheet">
                    <div>12th Marksheet</div>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <img src="scorecard.png" class="img-fluid mb-2" alt="Score Card">
                    <div>NCET 2024 Score Card</div>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <img src="caste.png" class="img-fluid mb-2" alt="Caste Certificate">
                    <div>Caste Certificate<br>(SC/ST/OBC-NCL/EWS)</div>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <img src="pwbd.png" class="img-fluid mb-2" alt="PwBD">
                    <div>PwBD</div>
                </div>
            </div>

            <!-- Disclaimer -->
            <div class="mt-3">
                <strong>Disclaimer :</strong>
                <p class="small">
                    I hereby declare that information furnished above is true and correct in every respect and in case any information is found incorrect even partially the candidature shall be liable to be rejected.
                </p>
            </div>
        </div>
    </div>

</body>
</html>
