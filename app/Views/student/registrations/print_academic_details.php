<?php //var_dump($details);?>
<style>
    .main-box {
        max-width: 1200px;
        margin: 30px auto 60px;
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
        font-size: 24px;
        font-weight: 600;
    }

    .subtitle {
        font-size: 16px;
    }

    .final-status {
        color: red;
        font-weight: bold;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .attachments img{
        width: 80%;
        height: 80%;
        object-fit: cover;
    }
</style>
<div class="main-box text-center shadow-remove">
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-outline-primary print-btn" onclick="openPrintPage()">🖨️ Print Form</button>
    </div>

    <!-- Header -->
    <img src="/public/assets/img/NIC_logo1.jpg" alt="Logo" class="logo mb-2">
    <div class="header-title">Regional Institute of Education</div>
    <div class="subtitle">Pushkar Road, Ajmer (Raj.)</div>
    <h3 class="mt-2">Academic Details</h3>
    <div class="final-status">(Final)</div>

    <!-- Profile Info -->
    <div class="mt-4">
        <h4 class="mb-1"><?= $details->name ?></h4>
        <p class="text-muted">Application No.: <strong><?= $details->ncet_application_no ?></strong></p>
    </div>

    <!-- Applicant Details Table -->
    <div class="table-responsive mb-4">
        <table class="table table-bordered text-center">
            <thead class="table-light">
                <tr>
                    <th>NCET <?=date('Y');?> Application No.</th>
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
                    <td><?= $details->ncet_application_no ?></td>
                    <td><?= $details->course ?></td>
                    <td><?= $details->category ?></td>
                    <td><?= $details->physical_disable ?></td>
                    <td><?= $details->phone ?></td>
                    <td><?= $details->aadhar_no ?></td>
                    <td><?= $details->gender ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Exam Details -->
    <h3 class="text-center mb-4">Academic Details</h3>
    <div class="table-responsive mb-2">
        <table class="table table-bordered text-center">
            <thead class="table-light">
                <tr>
                    <th>Stream</th>
                    <th>Year of Passing</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $details->board_10th; ?><?php echo isset($details->board_10th_other) && !empty($details->board_10th_other) ? ' ('.$details->board_10th_other.')' : '';?></td>
                    <td><?= $details->year_of_passing_10th; ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="table-responsive mb-4">
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
                    <td><?= $details->max_marks_10th ?></td>
                    <td><?= $details->obtain_marks_10th ?></td>
                    <td><?= $details->percentage_10th ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="table-responsive mb-2">
        <table class="table table-bordered text-center">
            <thead class="table-light">
                <tr>
                    <th>Board</th>
                    <th>Stream</th>
                    <th>Year of Passing</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $details->board_12th; ?></td>
                    <td><?= $details->stream; ?></td>
                    <td><?= $details->year_of_passing_12th; ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="table-responsive mb-4">
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
                    <td><?= $details->max_marks_12th ?></td>
                    <td><?= $details->obtain_marks_12th ?></td>
                    <td><?= $details->percentage_12th ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div>
        <!-- NCET 2024 Exam Details -->
        <h3 class="text-center mb-4">Details of NCET <?=date('Y');?> Exam</h3>
        <div class="row text-center mb-3">
            <div class="col-md-4"><strong>NCET <?=date('Y');?> Roll No:</strong> <?= $details->ncet_application_no ?></div>
            <div class="col-md-4"><strong>Course:</strong> <?= $details->course ?></div>
        </div>
        <!-- Subject Marks Table -->
        <div class="table-responsive mb-5">
            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th>Domain</th>
                        <th>Subject</th>
                        <th>Maximum Marks</th>
                        <th>Score Obtained</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if(isset($ncet) && !empty($ncet)){
                            $total_max = 0;
                            $total_obtain = 0;
                            foreach($ncet as $data){
                                $total_max += $data->total_maximum_marks;
                                $total_obtain += $data->total_marks_obtain;
                    ?>
                                <tr>
                                    <td><?= $data->codes;?></td>
                                    <td><?= $data->subjects;?></td>
                                    <td><?= $data->total_maximum_marks;?></td>
                                    <td><?= $data->total_marks_obtain;?></td>
                                    <td><?= $data->percentage;?></td>
                                </tr>
                    <?php
                            }
                    ?>
                            <tr class="fw-bold">
                                <td colspan="2">Total</td>
                                <td><?= $total_max; ?></td>
                                <td><?= $total_obtain;?></td>
                            </tr>
                    <?php
                        }else{
                    ?>
                            <tr colspan="5">Please enter NCET Scores.</tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Preference Table -->
    <h3 class="text-center mb-4">Preference for Major Discipline in ITEP Course</h3>
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
                    <td><?= $details->course ?></td>
                    <td><?= $details->bsc_preference_1; ?></td>
                    <td><?= $details->bsc_preference_2; ?></td>
                    <td><?= $details->bsc_preference_3; ?></td>
                    <td><?= $details->bsc_preference_4; ?></td>
                    <td><?= $details->ba_preference_1; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        <!-- Attachments Section -->
        <h3 class="mb-3">Attachments</h3>
        <div class="row text-center attachments">
            <div class="col-md-2 col-6 mb-3">
                <a href="<?= isset($details->photo) && !empty($details->photo) ? base_url($details->photo) : base_url('/assets/img/no-image.png'); ?>" target="_blank"><img src="<?= isset($details->photo) && !empty($details->photo) ? base_url($details->photo) : base_url('/assets/img/no-image.png'); ?>" class="img-fluid mb-2" alt="Photo"></a>
                <label>Photo</label>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="<?= isset($details->signature) && !empty($details->signature) ? base_url($details->signature) : base_url('/assets/img/no-image.png'); ?>" target="_blank"><img src="<?= isset($details->signature) && !empty($details->signature) ? base_url($details->signature) : base_url('/assets/img/no-image.png'); ?>" class="img-fluid mb-2" alt="Signature"></a>
                <label>Signature</label>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="<?= isset($details->certificate_10) && !empty($details->certificate_10) ? base_url($details->certificate_10) : base_url('/assets/img/no-image.png'); ?>" target="_blank"><img src="<?= isset($details->certificate_10) && !empty($details->certificate_10) ? base_url($details->certificate_10) : base_url('/assets/img/no-image.png'); ?>" class="img-fluid mb-2" alt="10th Marksheet"></a>
                <label>10th Marksheet</label>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="<?= isset($details->certificate_12) && !empty($details->certificate_12) ? base_url($details->certificate_12) : base_url('/assets/img/no-image.png'); ?>" target="_blank"><img src="<?= isset($details->certificate_12) && !empty($details->certificate_12) ? base_url($details->certificate_12) : base_url('/assets/img/no-image.png'); ?>" class="img-fluid mb-2" alt="12th Marksheet"></a>
                <label>12th Marksheet</label>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="<?= isset($details->ncet_score_card) && !empty($details->ncet_score_card) ? base_url($details->ncet_score_card) : base_url('/assets/img/no-image.png'); ?>" target="_blank"><img src="<?= isset($details->ncet_score_card) && !empty($details->ncet_score_card) ? base_url($details->ncet_score_card) : base_url('/assets/img/no-image.png'); ?>" class="img-fluid mb-2" alt="Ncet Score Card"></a>
                <label>NCET Score Card</label>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="<?= isset($details->caste_certificate) && !empty($details->caste_certificate) ? base_url($details->caste_certificate) : base_url('/assets/img/no-image.png'); ?>" target="_blank"><img src="<?= isset($details->caste_certificate) && !empty($details->caste_certificate) ? base_url($details->caste_certificate) : base_url('/assets/img/no-image.png'); ?>" class="img-fluid mb-2" alt="Caste Certificate"></a>
                <label>Caste Certificate<br>(SC/ST/OBC-NCL/EWS)</label>
            </div>
            <div class="col-md-2 col-6 mb-3">
                <a href="<?= isset($details->pwbd) && !empty($details->pwbd) ? base_url($details->pwbd) : base_url('/assets/img/no-image.png'); ?>" target="_blank"><img src="<?= isset($details->pwbd) && !empty($details->pwbd) ? base_url($details->pwbd) : base_url('/assets/img/no-image.png'); ?>" class="img-fluid mb-2" alt="PwBD"></a>
                <label>PwBD</label>
            </div>
        </div>

        <!-- Disclaimer -->
        <!-- <div class="mt-3">
            <strong>Disclaimer :</strong>
            <p class="small">
                I hereby declare that information furnished above is true and correct in every respect and in case any information is found incorrect even partially the candidature shall be liable to be rejected.
            </p>
        </div> -->
    </div>
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