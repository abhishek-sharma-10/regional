<?php //var_dump($details);?>
<style>
    .main-box {
        /* max-width: 1200px; */
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
        font-size: 14px;
    }

    .attachments img{
        width: 80%;
        height: 80%;
        object-fit: cover;
    }

    .details label{
        font-weight: 700;
        width: 25%;
    }

    .details > tbody > tr > td:nth-child(1){
        width: 80%;
        line-height: 1.2;
    }

    .details > tbody > tr > td:nth-child(2){
        width: 20%;
    }

    .details > tbody > tr > td > p{
        margin: 0px;
    }

    .table, .table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td{
        border: 1px solid #000;
    }

    .attachments img{
        width: 100%;
        height: 150px;
        object-fit: contain;
        object-position: center;
    }
</style>

<?php
    if(isset($status) && $status === 'filled'){
?>

    <div class="container main-box text-center shadow-remove">
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-outline-primary mb-3 print-btn" onclick="openPrintPage()">🖨️ Print Form</button>
        </div>

        <div class="row align-items-start flex-row justify-content-center">
            <div style="width: 10%;">
                <img src="/assets/img/logo1.png" alt="Logo" class="logo mb-2">
            </div>
            <div style="width:fit-content;">
        <div class="header-title">Regional Institute of Education</div>
        <div class="subtitle">Pushkar Road, Ajmer (Raj.)</div>
        <h3 class="mt-2">Academic Details</h3>
                <h4><?= $details->course ?></h4>
        <div class="final-status">(Final)</div>

            </div>
        </div>
        <div class="row table-responsive mt-3">
            <table class="table table-bordered details">
                <tr>
                    <td rowspan="2">
                        <p><label>Name</label> <?= $details->name ?></p>
                        </br>
                        <p><label>Mother's Name</label> <?= $details->mother_name ?></p>
                        </br>
                        <p><label>Father's Name</label> <?= $details->father_name ?></p>
                        </br>
                        <p><label>Email</label> <?= $details->email ?></p>
                        </br>
                        <p><label>Mobile No.</label> <?= $details->phone ?></p>
                        </br>
                        <p><label>Aadhar No.</label> <?= $details->aadhar_no ?></p>
                        </br>
                        <p><label>Category</label> <?= $details->category ?></p>
                        </br>
                        <p><label>Physical Disability</label> <?= $details->physical_disable == 1 ? 'Yes' : 'No'; ?></p>
                        </br>
                        <p><label>Gender</label> <?= $details->gender ?></p>
                    </td>
                    <td class="text-center"><p>Reg. No.: </br><strong><?= $details->registration_no ?></strong></p></td>
                </tr>
                <tr>
                    <td>
                        <div class="text-center"><a href="<?= isset($details->photo) && !empty($details->photo) ? base_url($details->photo) : base_url('/public/assets/img/no-image.webp'); ?>" target="_blank"><img src="<?= isset($details->photo) && !empty($details->photo) ? base_url($details->photo) : base_url('/public/assets/img/no-image.webp'); ?>" class="img-fluid mb-2 app_photo" alt="Photo" style="width:60%;height: 150px;object-fit: cover;object-position: center;"></a></div>
                    </td>
                    </tr>
                <tr>
                    <td style="padding: 0px;">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center" style="margin-bottom:0px;">
                                <thead>
                                    <tr>
                                        <th colspan="7">Academic Details</th>
                                    </tr>
                    <tr>
                                        <th></th>
                                        <th>Board</th>
                                        <th>Stream</th>
                                        <th>Year of Passing</th>
                                        <th>Maximum Marks</th>
                                        <th>Obtained Marks</th>
                                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                                        <td>10th (Secondary)</td>
                        <td><?= $details->board_10th; ?><?php echo isset($details->board_10th_other) && !empty($details->board_10th_other) ? ' ('.$details->board_10th_other.')' : '';?></td>
                                        <td>-</td>
                        <td><?= $details->year_of_passing_10th; ?></td>
                        <td><?= $details->max_marks_10th ?></td>
                        <td><?= $details->obtain_marks_10th ?></td>
                        <td><?= $details->percentage_10th ?></td>
                    </tr>
                    <tr>
                                        <td>12th (Secondary)</td>
                                        <td><?= $details->board_12th; ?><?php echo isset($details->board_12th_other) && !empty($details->board_12th_other) ? ' ('.$details->board_12th_other.')' : '';?></td>
                        <td><?= $details->stream; ?></td>
                        <td><?= $details->year_of_passing_12th; ?></td>
                        <td><?= $details->max_marks_12th ?></td>
                        <td><?= $details->obtain_marks_12th ?></td>
                        <td><?= $details->percentage_12th ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

                    </td>
                    <td>
                        <div class="text-center">
                            <a href="<?= isset($details->signature) && !empty($details->signature) ? base_url($details->signature) : base_url('/public/assets/img/no-image.webp'); ?>" target="_blank"><img src="<?= isset($details->signature) && !empty($details->signature) ? base_url($details->signature) : base_url('/public/assets/img/no-image.webp'); ?>" class="img-fluid mb-2" alt="Signature" style="width: 75%;height: 90px;object-fit: contain;object-position: center;"></a>
                            <p style="margin-bottom: 0px;">Sign. of Candidate</p>
                        </div>
                    </td>
                </tr>
                <!-- <tr>
                    <td rowspan="2"><p class="text-center" style="height: 50px;">Sign. of Candidate</p></td>
                </tr> -->
            </table>
            </div>
        <div class="row table-responsive">
            <table class="table table-bordered" style="text-align:center">
                <thead>
                    <tr>
                        <th colspan="7" class="text-center">Details of NCET <?=date('Y');?> Exam</th>
                    </tr>
                    <tr>
                        <td colspan="3"><strong>NCET <?=date('Y');?> Roll No:</strong> <?= $details->ncet_application_no ?></td>
                        <td colspan="2"><strong>Course:</strong> <?= $details->course ?></td>
                    </tr>
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
                                <tr><td colspan="5">Please enter NCET Scores.</td></tr>
                        <?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        <div class="row table-responsive">
            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th colspan="2">Preference for Major Discipline in ITEP Course</th>
                    </tr>
                    <tr>
                        <th>Course</th>
                        <th>Preferences</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($preferences as $course => $subjects) {
                            $count = 0;
                            foreach($subjects as $subject){
                    ?>
                        <tr>
                            <?php if($count == 0){ ?><th rowspan="<?php echo count($subjects);?>"><?= $course ?></th> <?php } ?>
                            <td><?=$subject;?></td>
                        </tr>
                    <?php
                        $count++;
                        }}
                    ?>
                </tbody>
            </table>
        </div>
        <div class="row table-responsive">
            <table class="table table-bordered text-center attachments">
                <thead class="table-light">
                    <tr>
                        <th colspan="7">Attachments</th>
                    </tr>
                    <tr>
                        <th>10<sup>th</sup> Marksheet</th>
                        <th>12<sup>th</sup> Marksheet</th>
                        <th>NCET Score Card</th>
                        <th>NCET Application Form</th>
                        <?php if($details->category !== 'GEN'){ ?><th>Caste Certificate<br>(SC/ST/OBC-CL/OBC-NCL/EWS)</th><?php } ?>
                        <?php if($details->physical_disable != '0'){ ?><th>Physical Disability</th><?php } ?>
                        <th>Payment Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div>
                                <?php if(isset($details->certificate_10) && !empty($details->certificate_10) && str_ends_with($details->certificate_10,'.pdf')){ ?>
                                    <a href="<?=base_url($details->certificate_10);?>" target="_blank">Uploaded PDF</a>
                                <?php }else{ ?>
                                    <a href="<?= isset($details->certificate_10) && !empty($details->certificate_10) ? base_url($details->certificate_10) : base_url('/public/assets/img/no-image.webp'); ?>" target="_blank"><img src="<?= isset($details->certificate_10) && !empty($details->certificate_10) ? base_url($details->certificate_10) : base_url('/public/assets/img/no-image.webp'); ?>" class="img-fluid mb-2" alt="10th Marksheet"></a>
                                <?php } ?>
                </div>
                        </td>
                        <td>
                            <div>
                                <?php if(isset($details->certificate_12) && !empty($details->certificate_12) && str_ends_with($details->certificate_12,'.pdf')){ ?>
                                    <a href="<?=base_url($details->certificate_12);?>" target="_blank">Uploaded PDF</a>
                                <?php }else{ ?>
                                    <a href="<?= isset($details->certificate_12) && !empty($details->certificate_12) ? base_url($details->certificate_12) : base_url('/public/assets/img/no-image.webp'); ?>" target="_blank"><img src="<?= isset($details->certificate_12) && !empty($details->certificate_12) ? base_url($details->certificate_12) : base_url('/public/assets/img/no-image.webp'); ?>" class="img-fluid mb-2" alt="12th Marksheet"></a>
                                <?php } ?>
                </div>
                        </td>
                        <td>
                            <div>
                                <?php if(isset($details->ncet_score_card) && !empty($details->ncet_score_card) && str_ends_with($details->ncet_score_card,'.pdf')){ ?>
                                    <a href="<?=base_url($details->ncet_score_card);?>" target="_blank">Uploaded PDF</a>
                                <?php }else{ ?>
                                    <a href="<?= isset($details->ncet_score_card) && !empty($details->ncet_score_card) ? base_url($details->ncet_score_card) : base_url('/assets/img/no-image.webp'); ?>" target="_blank"><img src="<?= isset($details->ncet_score_card) && !empty($details->ncet_score_card) ? base_url($details->ncet_score_card) : base_url('/assets/img/no-image.webp'); ?>" class="img-fluid mb-2" alt="Ncet Score Card"></a>
                                <?php } ?>
                            </div>
                        </td>
                        <td>
                            <div>
                                <?php if(isset($details->ncet_application_form) && !empty($details->ncet_application_form) && str_ends_with($details->ncet_application_form,'.pdf')){ ?>
                                    <a href="<?=base_url($details->ncet_application_form);?>" target="_blank">Uploaded PDF</a>
                                <?php }else{ ?>
                                    <a href="<?= isset($details->ncet_application_form) && !empty($details->ncet_application_form) ? base_url($details->ncet_application_form) : base_url('/public/assets/img/no-image.webp'); ?>" target="_blank"><img src="<?= isset($details->ncet_application_form) && !empty($details->ncet_application_form) ? base_url($details->ncet_application_form) : base_url('/public/assets/img/no-image.webp'); ?>" class="img-fluid mb-2" alt="Ncet Application Form"></a>
                                <?php } ?>
                </div>
                        </td>
                        <?php if($details->category !== 'GEN'){ ?><td>
                            <div>
                                <?php if(isset($details->caste_certificate) && !empty($details->caste_certificate) && str_ends_with($details->caste_certificate,'.pdf')){ ?>
                                    <a href="<?=base_url($details->caste_certificate);?>" target="_blank">Uploaded PDF</a>
                                <?php }else{ ?>
                                    <a href="<?= isset($details->caste_certificate) && !empty($details->caste_certificate) ? base_url($details->caste_certificate) : base_url('/public/assets/img/no-image.webp'); ?>" target="_blank"><img src="<?= isset($details->caste_certificate) && !empty($details->caste_certificate) ? base_url($details->caste_certificate) : base_url('/public/assets/img/no-image.webp'); ?>" class="img-fluid mb-2" alt="Caste Certificate"></a>
                                <?php } ?>
                            </div>
                        </td><?php } ?>
                        <?php if($details->physical_disable != '0'){ ?><td>
                            <div>
                                <?php if(isset($details->pwbd) && !empty($details->pwbd) && str_ends_with($details->pwbd,'.pdf')){ ?>
                                    <a href="<?=base_url($details->pwbd);?>" target="_blank">Uploaded PDF</a>
                                <?php }else{ ?>
                                    <a href="<?= isset($details->pwbd) && !empty($details->pwbd) ? base_url($details->pwbd) : base_url('/assets/img/no-image.webp'); ?>" target="_blank"><img src="<?= isset($details->pwbd) && !empty($details->pwbd) ? base_url($details->pwbd) : base_url('/assets/img/no-image.webp'); ?>" class="img-fluid mb-2" alt="PwBD"></a>
                                <?php } ?>
                            </div>
                        </td><?php } ?>
                        <td>
                            <div>
                                <?php if(isset($details->payment_receipt) && !empty($details->payment_receipt) && str_ends_with($details->payment_receipt,'.pdf')){ ?>
                                    <a href="<?=base_url($details->payment_receipt);?>" target="_blank">Uploaded PDF</a>
                                <?php }else{ ?>
                                    <a href="<?= isset($details->payment_receipt) && !empty($details->payment_receipt) ? base_url($details->payment_receipt) : base_url('/assets/img/no-image.webp'); ?>" target="_blank"><img src="<?= isset($details->payment_receipt) && !empty($details->payment_receipt) ? base_url($details->payment_receipt) : base_url('/assets/img/no-image.webp'); ?>" class="img-fluid mb-2" alt="Payment Receipt"></a>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
                </div>
        <div class="row">
            <div class="offset-sm-9 col-sm-3 text-center">
                <img src="<?= isset($details->signature) && !empty($details->signature) ? base_url($details->signature) : base_url('/assets/img/no-image.webp'); ?>" class="img-fluid mb-2" alt="Signature" style="width: 70%;height: 80px;object-fit: cover;object-position: center;"></a>
                <p style="margin-bottom: 0px;">Signature of Candidate</p>
            </div>
        </div>
    </div>
<?php
    } elseif(isset($status) && $status === 'not-filled') {
?>
    <div class="container main-box text-center shadow-remove mt-4">
        <h2 style="color: #999999; font-weight: 500;">Please fill and save the Academic Form to print Academic Details.</h2>
    </div>
<?php
    }
?>

<script>
    function openPrintPage() {
        document.querySelector('#topbar').classList.add('d-none');
        document.querySelector('#header').classList.add('d-none');
        document.querySelector('footer').classList.add('d-none');
        document.querySelector('.print-btn').style = "display: none";
        document.querySelector('.main-box').classList.remove('container');
        document.querySelector('.main-box').style = "padding: 15px; margin-top: 15px;box-shadow:none;";
        document.querySelector('.table th, .table td').style = "font-size:12px";
        document.querySelector('.app_photo').style = "width: 70%; height: 100px;";
        
        window.onafterprint = (event) => {
            document.querySelector('#topbar').classList.remove('d-none');
            document.querySelector('#header').classList.remove('d-none');
            document.querySelector('footer').classList.remove('d-none');
            document.querySelector('.print-btn').style = "display: block";
            document.querySelector('.main-box').classList.add('container');
            document.querySelector('.main-box').style = "padding: 30px; margin-top: 30px";
            document.querySelector('.table th, .table td').style = "font-size:14px";
            document.querySelector('.app_photo').style = "width: 60%; height: 150px;";
        }
        window.print();
    }
</script>