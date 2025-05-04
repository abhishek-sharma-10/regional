<?php
// var_dump($details);
// var_dump($ncet);
?>

<style>
    .main-container {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        border-radius: 8px;
        background-color: #fff;
        margin-bottom: 40px;
    }

    .section-title {
        text-align: center;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .main-box {
        /* box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); */
        border-radius: 12px;
        padding: 15px 30px;
        background-color: #fff;
    }

    .upload-section {
        /* border-bottom: 1px solid #e0e0e0; */
        /* padding-bottom: 20px; */
        margin-bottom: 20px;
    }

    .upload-section>.row {
        border-right: 1px solid #e0e0e0;
        padding: 5 5 0;
    }

    .upload-section:nth-of-type(3n)>.row {
        border-right: none;
    }

    .upload-status {
        font-weight: bold;
        color: gray;
    }

    img.preview {
        width: 75px;
        height: 75px;
        object-fit: cover;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .error {
        font-size: 13px;
    }
</style>

<!-- PART 1: Applicant Details -->
<div class="container mt-5 main-container">

    <!-- START | ERROR MESSAGE -->
    <?php if (session()->getFlashdata('err_msg')): ?>
        <div class="col-lg-12">
            <div class="alert alert-danger alert-dismissable">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <?= session()->getFlashdata('err_msg') ?>
            </div>
        </div>
    <?php endif; ?>
    <!-- END | ERROR MESSAGE -->

    <div class="my-3 section-title">
        <h2>Candidate Details</h2>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">NCET <?=date('Y');?> Application No.</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->ncet_application_no ?>" readonly>
                </div>
            </div>
            
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Category</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->category ?>" readonly>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Gender</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->gender ?>" readonly>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Mobile No.</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->phone ?>" readonly>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->name ?>" readonly>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">PwBD</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->physical_disable == 1 ? 'Yes' : 'No'; ?>" readonly>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Aadhar No</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->aadhar_no ?>" readonly>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Stream</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->course ?>" readonly>
                </div>
            </div>
        </div>
    </div>
    <!-- </div> -->
    
    <hr />
    <form method="post" action="<?php echo base_url(); ?>update-academic-profile" enctype="multipart/form-data" id="academic-form">
        <input type="hidden" name="id" value="<?php echo $details->id; ?>" />
        
        <div class="mt-4 section-title">
            <h2>Academic Details</h2>
        </div>
        <!-- 10th -->
        <div class="row flex-column align-content-center">
            <div class="col-md-6"><h3 class="mb-3">10<sup>th</sup> (Secondary)</h3></div>
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Board</label>
                    <div class="col-sm-8">
                        <select class="form-select" name="board_10th" id="board_10th" data-input="board-10-any-other" required>
                            <option value="">Select Board</option>
                            <option value="CBSE" <?php echo $details->board_10th == 'CBSE' ? 'selected' : '';?>>CBSE</option>
                            <option value="State Board" <?php echo $details->board_10th == 'State Board' ? 'selected' : '';?>>State Board</option>
                            <option value="Any Other" <?php echo $details->board_10th == 'Any Other' ? 'selected' : '';?>>Any Other</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row" id="board-10-any-other">
                    <label class="col-sm-4 col-form-label">Other Board Name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="board_10th_other" value="<?php $details->board_10th_other; ?>" <?php isset($details->board_10th_other) && !empty($details->board_10th_other) ? "style='display:block';": "style='display:none';"?>/>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Year Of Passing</label>
                    <div class="col-sm-8">
                        <select class="form-select" name="year_of_passing_10th" required>
                            <option value="" selected disabled>Select Year</option>
                        <?php foreach ($year_of_passing as $year) {
                        ?>
                            <option value="<?= $year; ?>" <?php echo $details->year_of_passing_10th == $year ? 'selected' : ''; ?>><?= $year; ?></option>
                        <?php
                        } ?>
                        </select>
                    </div>
                </div>
                <label class="form-label fw-bold mt-4">10<sup>th</sup> Scores</label>
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Maximum Marks</th>
                                <th>Obtained Marks</th>
                                <th>Percent(%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="number" class="form-control max-marks" id="max-marks-10" name="max_marks_10th" value="<?php echo !empty($details->max_marks_10th) ? $details->max_marks_10th : ''; ?>" placeholder="Max" oninput="calculatePercent('#max-marks-10', '#obtained-marks-10', '#percentage-10')" required></td>
                                <td><input type="number" class="form-control obtained-marks" id="obtained-marks-10" name="obtain_marks_10th" value="<?php echo !empty($details->obtain_marks_10th) ? $details->obtain_marks_10th : ''; ?>" placeholder="Obtained" oninput="calculatePercent('#max-marks-10', '#obtained-marks-10', '#percentage-10')" required></td>
                                <td class="d-flex"><input type="text" class="form-control percent" id="percentage-10" name="percentage_10th" value="<?php echo !empty($details->percentage_10th) ? $details->percentage_10th : ''; ?>" placeholder="%" readonly></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 12th -->
        <div class="row flex-column align-content-center mt-3">
            <div class="col-md-6"><h3 class="mb-3">12<sup>th</sup> (Senior Secondary)</h3></div>
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Stream</label>
                    <div class="col-sm-8">
                        <select class="form-select" name="stream" required>
                            <option value="" selected disabled>Select Stream</option>
                            <option value="Science" <?php echo $details->stream === 'Science' ? 'selected' : ''; ?>>Science</option>
                            <option value="Commerce" <?php echo $details->stream === 'Commerce' ? 'selected' : ''; ?>>Commerce</option>
                            <option value="Art" <?php echo $details->stream === 'Art' ? 'selected' : ''; ?>>Art</option>
                            <option value="Other" <?php echo $details->stream === 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Board</label>
                    <div class="col-sm-8">
                        <select class="form-select" name="board_12th" id="board_12th" data-input="board-12-any-other" required>
                            <option value="">Select Board</option>
                            <option value="CBSE" <?php echo $details->board_10th == 'CBSE' ? 'selected' : '';?>>CBSE</option>
                            <option value="State Board" <?php echo $details->board_10th == 'State Board' ? 'selected' : '';?>>State Board</option>
                            <option value="Any Other" <?php echo $details->board_10th == 'Any Other' ? 'selected' : '';?>>Any Other</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row" id="board-12-any-other">
                    <label class="col-sm-4 col-form-label">Other Board Name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="board-12-any-other" name="board_12th_other"  value="<?php $details->board_12th_other; ?>" <?php isset($details->board_12th_other) && !empty($details->board_12th_other) ? "style='display:block';": "style='display:none';"?>/>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Year Of Passing</label>
                    <div class="col-sm-8">
                        <select class="form-select" name="year_of_passing_12th" required>
                            <option value="" selected disabled>Select Year</option>
                        <?php foreach ($year_of_passing as $year) {
                        ?>
                            <option value="<?= $year; ?>" <?php echo $details->year_of_passing_12th == $year ? 'selected' : ''; ?>><?= $year; ?></option>
                        <?php
                        } ?>
                        </select>
                    </div>
                </div>
                <label class="form-label fw-bold mt-4">12<sup>th</sup> Score</label>
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Maximum Marks</th>
                                <th>Obtained Marks</th>
                                <th>Percent(%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="number" class="form-control max-marks" id="max-marks-12" name="max_marks_12th" value="<?php echo !empty($details->max_marks_12th) ? $details->max_marks_12th : ''; ?>" placeholder="Max" oninput="calculatePercent('#max-marks-12', '#obtained-marks-12', '#percentage-12')" required></td>
                                <td><input type="number" class="form-control obtained-marks" id="obtained-marks-12" name="obtain_marks_12th" value="<?php echo !empty($details->obtain_marks_12th) ? $details->obtain_marks_12th : ''; ?>" placeholder="Obtained" oninput="calculatePercent('#max-marks-12', '#obtained-marks-12', '#percentage-12')" required></td>
                                <td class="d-flex"><input type="text" class="form-control percent" id="percentage-12" name="percentage_12th" value="<?php echo !empty($details->percentage_12th) ? $details->percentage_12th : ''; ?>" placeholder="%" readonly></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <hr />
        
        <div class="mt-4 section-title">
            <p>Details of NCET <?=date('Y');?> Exam</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="mb-3 row">
                    <label class="col-sm-4 offset-sm-1 col-form-label">NCET <?=date('Y');?> Roll No</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" value="<?php echo !empty($details->ncet_application_no) ? $details->ncet_application_no : ''; ?>" name="ncet_roll_no" readonly required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 offset-sm-1 col-form-label">ITEP Course</label>
                    <div class="col-sm-6">
                        <select class="form-select" name="itep_courses" required>
                            <option selected disabled>--Select Course--</option>
                            <option value="ITEP - B.Sc. B.Ed." <?php echo $details->itep_courses === 'ITEP - B.Sc. B.Ed.' ? 'selected' : ''; ?>>ITEP - B.Sc. B.Ed.</option>
                            <option value="ITEP - B.A. B.Ed." <?php echo $details->itep_courses === 'ITEP - B.A. B.Ed.' ? 'selected' : ''; ?>>ITEP - B.A. B.Ed.</option>
                            <option value="ITEP - Both" <?php echo $details->itep_courses === 'ITEP - Both' ? 'selected' : ''; ?>>ITEP - Both</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 form-label fw-bold">Score</label>
                    <div class="col-sm-12">
                        <table class="table table-bordered text-center">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 12%; vertical-align: middle"></th>
                                    <th style="width: 12%; vertical-align: middle">Domain</th>
                                    <th style="width: 40%; vertical-align: middle">Subject</th>
                                    <th style="width: 12%; vertical-align: middle">Maximum Score</th>
                                    <th style="width: 12%; vertical-align: middle">Score Obtained</th>
                                    <th style="width: 12%; vertical-align: middle">Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach($sectionArray as $key => $sections){
                                for ($k = 0; $k < $sections; $k++) {
                                ?>
                                    <tr>
                                        <?php if($k == 0){?><th style="vertical-align: middle; white-space: nowrap;" rowspan="<?=$sections ?>"><?= $key ?></th><?php } ?>
                                        <td>
                                            <input type="hidden" value="<?php echo isset($ncet[$i]->id) ? $ncet[$i]->id : ''; ?>" name="ids[]" />
                                            <input type="number" class="form-control codes" name="code[]" id="code<?= $i; ?>" data-row="<?= $i; ?>" data-section="<?= $key; ?>" value="<?php echo isset($ncet[$i]->codes) ? $ncet[$i]->codes : ''; ?>" required>
                                        </td>
                                        <td><input type="text" class="form-control subjects" name="subject[]" id="subject<?= $i; ?>" data-row="<?= $i; ?>" value="<?php echo isset($ncet[$i]->subjects) ? $ncet[$i]->subjects : ''; ?>" required readonly></td>
                                        <td><input type="number" class="form-control max_marks" name="max_marks[]" id="max_marks<?= $i; ?>" data-row="<?= $i; ?>" value="<?php echo isset($ncet[$i]->total_maximum_marks) ? $ncet[$i]->total_maximum_marks : ''; ?>" required readonly></td>
                                        <td><input type="number" class="form-control obtain_marks" name="obtain_marks[]" id="obtain_marks<?= $i; ?>" data-row="<?= $i; ?>" value="<?php echo isset($ncet[$i]->total_marks_obtain) ? $ncet[$i]->total_marks_obtain : ''; ?>" oninput="calculatePercent('#max_marks<?= $i; ?>', '#obtain_marks<?= $i; ?>', '#percentage<?= $i; ?>')" required></td>
                                        <td><input type="number" class="form-control percentage" name="percentage[]" id="percentage<?= $i; ?>" data-row="<?= $i; ?>" value="<?php echo isset($ncet[$i]->percentage) ? $ncet[$i]->percentage : ''; ?>" readonly required></td>
                                    </tr>
                                <?php
                                $i++;
                                }}
                                ?>
                                <tr>
                                    <td colspan="3" style="vertical-align: middle; font-weight: 700;">Total Marks</td>
                                    <td><input type="number" class="form-control" id="total_max_marks"></td>
                                    <td><input type="number" class="form-control" id="total_obtain_marks"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <hr />

        <div class="mt-4 section-title">
            <p>Preference for Major Discipline in ITEP Course</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Course</label>
                    <div class="col-sm-8">
                        <select class="form-select" name="course" id="course">
                            <option selected disabled>--Select Course--</option>
                            <option value="ITEP - B.Sc. B.Ed." <?php echo $details->itep_courses === 'ITEP - B.Sc. B.Ed.' ? 'selected' : ''; ?>>ITEP - B.Sc. B.Ed.</option>
                            <option value="ITEP - B.A. B.Ed." <?php echo $details->itep_courses === 'ITEP - B.A. B.Ed.' ? 'selected' : ''; ?>>ITEP - B.A. B.Ed.</option>
                            <option value="ITEP - Both" <?php echo $details->itep_courses === 'ITEP - Both' ? 'selected' : ''; ?>>ITEP - Both</option>
                        </select>
                    </div>
                </div>
                <div class="bscPreferences"></div>
                <div class="baPreferences"></div>
                <!-- <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Preference 1st</label>
                    <div class="col-sm-8">
                        <select class="form-select preference-select" name="preference_1" required>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Preference 2nd</label>
                    <div class="col-sm-8">
                        <select class="form-select preference-select" name="preference_2" required>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Preference 3rd</label>
                    <div class="col-sm-8">
                        <select class="form-select preference-select" name="preference_3" required>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Preference 4th</label>
                    <div class="col-sm-8">
                        <select class="form-select preference-select" name="preference_4" required>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Preference 5th</label>
                    <div class="col-sm-8">
                        <select class="form-select preference-select" name="preference_5" required>
                        </select>
                    </div>
                </div> -->
            </div>
        </div>
        <hr />
        
        <div class="mt-4 main-box">
            <h3 class="mb-4">Uploads</h3>
            <div class="row">
                <div class="col-md-4 upload-section">
                    <div class="row">
                        <h4 class="mb-4">Photo</h4>
                        <div class="col-md-8">
                            <p class="mb-1"><strong>Max Size:</strong> 3MB</p>
                            <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <!-- <div class="me-3 text-muted upload-status" id="uploadStatus"><? //= isset($details->photo) && !empty($details->photo) ? 'Uploaded' : 'Pending'
                                                                                                ?></div> -->
                            <img src="<?= isset($details->photo) && !empty($details->photo) ? base_url($details->photo) : base_url('/public/assets/img/no-image.png'); ?>" alt="Preview" class="preview me-3" id="previewPhoto">
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="photo" name="photo" style="width: auto;" onchange="previewImage(event, 'previewPhoto')" accept=".jpg,.jpeg,.png" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 upload-section">
                    <div class="row">
                        <h4 class="mb-4">Signature</h4>
                        <div class="col-md-8">
                            <p class="mb-1"><strong>Max Size:</strong> 3MB</p>
                            <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <!-- <div class="me-3 text-muted upload-status" id="uploadStatus"><? //= isset($details->signature) && !empty($details->signature) ? 'Uploaded' : 'Pending'
                                                                                                ?></div> -->
                            <img src="<?= isset($details->signature) && !empty($details->signature) ? base_url($details->signature) : base_url('/public/assets/img/no-image.png'); ?>" alt="Preview" class="preview me-3" id="previewSignature">
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="signature" name="signature" style="width: auto;" onchange="previewImage(event, 'previewSignature')" accept=".jpg,.jpeg,.png" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-box">
            <h3 class="mb-4">Attach Documents</h3>
            <div class="row">
                <div class="col-md-4 upload-section">
                    <div class="row">
                        <h4 class="mb-4">10th Marksheet</h4>
                        <div class="col-md-8">
                            <p class="mb-1"><strong>Max Size:</strong> 3MB</p>
                            <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <!-- <div class="me-3 text-muted upload-status" id="uploadStatus"><? //= isset($details->certificate_10) && !empty($details->certificate_10) ? 'Uploaded' : 'Pending'
                                                                                                ?></div> -->
                            <img src="<?= isset($details->certificate_10) && !empty($details->certificate_10) ? base_url($details->certificate_10) : base_url('/public/assets/img/no-image.png'); ?>" alt="Preview" class="preview me-3" id="previewCertificate_10">
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="certificate_10" name="certificate_10" style="width: auto;" onchange="previewImage(event, 'previewCertificate_10')" accept=".jpg,.jpeg,.png" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 upload-section">
                    <div class="row">
                        <h4 class="mb-4">12th Marksheet</h4>
                        <div class="col-md-8">
                            <p class="mb-1"><strong>Max Size:</strong> 3MB</p>
                            <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <!-- <div class="me-3 text-muted upload-status" id="uploadStatus"><? //= isset($details->certificate_12) && !empty($details->certificate_12) ? 'Uploaded' : 'Pending'
                                                                                                ?></div> -->
                            <img src="<?= isset($details->certificate_12) && !empty($details->certificate_12) ? base_url($details->certificate_12) : base_url('/public/assets/img/no-image.png'); ?>" alt="Preview" class="preview me-3" id="previewCertificate_12">
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="certificate_12" name="certificate_12" style="width: auto;" onchange="previewImage(event, 'previewCertificate_12')" accept=".jpg,.jpeg,.png" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 upload-section">
                    <div class="row">
                        <h4 class="mb-4">NCET Score Card</h4>
                        <div class="col-md-8">
                            <p class="mb-1"><strong>Max Size:</strong> 3MB</p>
                            <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <!-- <div class="me-3 text-muted upload-status" id="uploadStatus"><? //= isset($details->ncet_score_card) && !empty($details->ncet_score_card) ? 'Uploaded' : 'Pending'
                                                                                                ?></div> -->
                            <img src="<?= isset($details->ncet_score_card) && !empty($details->ncet_score_card) ? base_url($details->ncet_score_card) : base_url('/public/assets/img/no-image.png'); ?>" alt="Preview" class="preview me-3" id="preview_ncet_score">
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="ncet_score_card" name="ncet_score_card" style="width: auto;" onchange="previewImage(event, 'preview_ncet_score')" accept=".jpg,.jpeg,.png" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 upload-section">
                    <div class="row">
                        <h4 class="mb-4">Caste Certificate</h4>
                        <div class="col-md-8">
                            <p class="mb-1"><strong>Max Size:</strong> 3MB</p>
                            <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <!-- <div class="me-3 text-muted upload-status" id="uploadStatus"><? //= isset($details->caste_certificate) && !empty($details->caste_certificate) ? 'Uploaded' : 'Pending'
                                                                                                ?></div> -->
                            <img src="<?= isset($details->caste_certificate) && !empty($details->caste_certificate) ? base_url($details->caste_certificate) : base_url('/public/assets/img/no-image.png'); ?>" alt="Preview" class="preview me-3" id="preview_caste_certificate">
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="caste_certificate" name="caste_certificate" style="width: auto;" onchange="previewImage(event, 'preview_caste_certificate')" accept=".jpg,.jpeg,.png" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 upload-section">
                    <div class="row">
                        <h4 class="mb-4">PwBD</h4>
                        <div class="col-md-8">
                            <p class="mb-1"><strong>Max Size:</strong> 3MB</p>
                            <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <!-- <div class="me-3 text-muted upload-status" id="uploadStatus"><? //= isset($details->pwbd) && !empty($details->pwbd) ? 'Uploaded' : 'Pending'
                                                                                                ?></div> -->
                            <img src="<?= isset($details->pwbd) && !empty($details->pwbd) ? base_url($details->pwbd) : base_url('/public/assets/img/no-image.png'); ?>" alt="Preview" class="preview me-3" id="preview_pwbd">
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="pwbd" name="pwbd" style="width: auto;" onchange="previewImage(event, 'preview_pwbd')"  accept=".jpg,.jpeg,.png" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 justify-content-center mt-3">
            <button type="submit" class="btn btn-sm btn-outline-success" id="save_draft" value="Save as Draft" name="save_as_draft">Save as Draft</button>
            <button type="submit" class="btn btn-sm primary-btn text-white" id="final_save" name="final_save">Save</button>
            <button type="button" class="btn btn-sm btn-danger">Cancel</button>
        </div>
    </form>
</div>

<script>
    function calculatePercent(max_element, obtain_element, percentage_element) {
        // const row = element.closest('tr');
        const max = $(max_element).val();
        const obtained = $(obtain_element).val();
        const percentField = $(percentage_element);

        if (max && obtained && max > 0) {
            const percent = ((obtained / max) * 100).toFixed(2);
            percentField.val(percent);
        } else {
            percentField.val('');
        }
    }
</script>
<!-- Image Preview Script -->
<script>
    function previewImage(event, previewId) {
        const file = event.target.files[0];
        const reader = new FileReader();

        // If a file is selected
        if (file) {
            reader.onload = function() {
                document.getElementById(previewId).src = reader.result;
            };
            reader.readAsDataURL(file);

            // Change status text to "Selected"
            document.getElementById('uploadStatus').textContent = "Selected";
        } else {
            // No file selected or cleared – fallback to "Pending"
            document.getElementById('uploadStatus').textContent = "Pending";
        }
    }
</script>
<!-- preference selection script-->
<script>
    let bsc_preference_1 = '<?php echo $details->bsc_preference_1; ?>';
    let bsc_preference_2 = '<?php echo $details->bsc_preference_2; ?>';
    let bsc_preference_3 = '<?php echo $details->bsc_preference_3; ?>';
    let bsc_preference_4 = '<?php echo $details->bsc_preference_4; ?>';
    let ba_preference_1 = '<?php echo $details->ba_preference_1; ?>';
    let ba_preference_2 = '<?php echo $details->ba_preference_2; ?>';
    let ba_preference_3 = '<?php echo $details->ba_preference_3; ?>';
    const bsc_preferences = [bsc_preference_1, bsc_preference_2, bsc_preference_3, bsc_preference_4];
    const ba_preferences = [ba_preference_1, ba_preference_2, ba_preference_3];

    let photo = '<?php echo $details->photo; ?>';
    let signature = '<?php echo $details->signature; ?>';
    let certificate_10 = '<?php echo $details->certificate_10; ?>';
    let certificate_12 = '<?php echo $details->certificate_12; ?>';
    let ncet_score_card = '<?php echo $details->ncet_score_card; ?>';
    let caste_certificate = '<?php echo $details->caste_certificate; ?>';
    let pwbd = '<?php echo $details->pwbd; ?>';

    let status = '<?php echo $details->status; ?>';
    var category =  '<?php echo $details->category; ?>';
    var physical_disable =  '<?php echo $details->physical_disable; ?>';

    const attachment = {
        photo,
        signature,
        certificate_10,
        certificate_12,
        ncet_score_card,
        caste_certificate,
        pwbd
    };

    var bscSubject = [];
    var baSubject = [];

    $(document).ready(function() {
        $('.baPreferences').hide();
        $('.bscPreferences').hide();

        $('#course').trigger('change');
        $('.obtain_marks').trigger('blur');
        $('.max_marks').trigger('blur');
        $('.codes').trigger('blur');
        
        $(`#board-10-any-other`).hide();
        $(`#board-12-any-other`).hide();
        $('#board_10th, #board_12th').change(function(){
            let id = $(this).data('input');
            let value = $(this).val();

            if(value === 'Any Other'){
                $(`#${id}`).show();
                $(`#${id} input`).attr('required', true);
            } else{
                $(`#${id}`).hide();
                $(`#${id} input`).removeAttr('required');
            }
        });

        $.validator.addMethod("lessThan", function(value, element, param) {
            return this.optional(element) || parseInt(value) <= parseInt($(param).val());
        }, "Obtained marks cannot be greater than maximum marks.");

        $.validator.addMethod('filesize', function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param * 1000000)
        }, 'Image size must be less than {0} MB');

        $.validator.addMethod("obtainCheck", function(value, element) {
            let row = $(element).attr('data-row'); // get the same row number
            let maxMarks = $(`#max_marks${row}`).val();
            if (maxMarks === "") return true; // skip if max marks not filled yet
            return parseFloat(value) <= parseFloat(maxMarks);
        }, "Obtained marks cannot be greater than maximum marks.");

        var rules = {
            obtain_marks_10th: {
                required: true,
                number: true,
                lessThan: '#max-marks-10'
            },
            obtain_marks_12th: {
                required: true,
                number: true,
                lessThan: '#max-marks-12'
            },
            "code[]": {
                required: true,
                number: true,
                minlength: 1
            },
            "subject[]": {
                required: true,
                minlength: 2
            },
            "max_marks[]": {
                required: true,
                number: true,
                min: 1
            },
            "obtain_marks[]": {
                required: true,
                number: true,
                min: 0,
                obtainCheck: true
            }
        };

        var messages = {
            "code[]": {
                required: "Please enter subject code",
                number: "Code must be a number",
                minlength: "Code must be at least 1 digit"
            },
            "subject[]": {
                required: "Please enter subject name",
                minlength: "Subject must be at least 2 characters"
            },
            "max_marks[]": {
                required: "Please enter maximum marks",
                number: "Must be a valid number",
                min: "Marks must be at least 1"
            },
            "obtain_marks[]": {
                required: "Please enter obtained marks",
                number: "Must be a valid number",
                min: "Marks cannot be negative"
            }
        };

        if (status === "Request") {
            for (key in attachment) {
                $(`input[name=${key}]`).attr('required', true);
                rules[key] = {
                    required: true,
                    extension: "jpg|jpeg|png",
                    filesize: 3
                };
                messages[key] = {
                    required: "Please upload an image.",
                    extension: "Please upload a file with a valid extension (jpg, jpeg, png)."
                }
            }
        } else if (status === "Save as Draft") {
            for (key in attachment) {
                if (attachment[key] != '') {
                    $(`input[name=${key}]`).attr('required', false);
                } else {
                    $(`input[name=${key}]`).attr('required', true);
                    rules[key] = {
                        required: true,
                        extension: "jpg|jpeg|png",
                        filesize: 3
                    };
                    messages[key] = {
                        required: "Please upload an image.",
                        extension: "Please upload a file with a valid extension (jpg, jpeg, png)."
                    };
                }
            }
        }
        
        const validator = $("#academic-form").validate({
            rules,
            messages
        });

        $('#save_draft').click(function (e) {
            e.preventDefault();
            // Remove validation rules
            $('input, select', '#academic-form').each(function () {
                if ($(this).attr("name")) { // skip elements without a name
                    try {
                        $(this).rules('remove');
                    } catch (e) {
                        // Ignore elements that aren't part of the validator
                    }
                }
            });

            // Clear validation errors
            validator.resetForm();
            $('.error').removeClass('error');

            // Submit form without validation
            $('#academic-form').off('submit').submit();
        });

        $('#final_save').click(function () {
            if (category === "GEN") {
                $('#caste_certificate').removeAttr('required').rules('remove');
            } else {
                $('#caste_certificate').attr('required', true);
            }
            
            if(physical_disable == 0){
                $('#pwbd').removeAttr('required').rules('remove');
            }else{
                $('#pwbd').attr('required', true);
            }
            $("#academic-form").submit();  // validate and submit
        });
    });

    $("#course").change(function (e) {
        try {
            let course = e.target.value;
            let subjects = [];

            if (course == 'ITEP - B.A. B.Ed.') {
                subjects = baSubject;
                $('.baPreferences').show();
                $('.bscPreferences').hide();
            } else if (course == 'ITEP - B.Sc. B.Ed.') {
                subjects = bscSubject;
                $('.baPreferences').hide();
                $('.bscPreferences').show();
            } else {
                $('.baPreferences').hide();
                $('.bscPreferences').hide();
            }

            bindPreferenceOption();
        } catch (err) {
            console.log(err);
        }
    });

    function bindPreferenceOption() {
        const bsc_selects = document.querySelectorAll(".bsc-preference-select");
        const ba_selects = document.querySelectorAll(".ba-preference-select");

        function updateOptions(selects) {
            const selectedValues = Array.from(selects)
                .map(select => select.value)
                .filter(val => val !== "--Select--" && val !== "");

            selects.forEach(select => {
                const currentValue = select.value;

                Array.from(select.options).forEach(option => {
                    if (option.disabled && option.value !== "--Select--") {
                        option.disabled = false; // reset disabled state
                    }

                    if (option.value !== currentValue && selectedValues.includes(option.value)) {
                        option.disabled = true;
                    }
                });
            });
        }

        bsc_selects.forEach(select => {
            select.removeEventListener("change", updateOptions);
            select.addEventListener("change", () => updateOptions(bsc_selects));
        });
        ba_selects.forEach(select => {
            select.removeEventListener("change", updateOptions);
            select.addEventListener("change", () => updateOptions(ba_selects));
        });

        updateOptions(ba_selects);
        updateOptions(bsc_selects);
    }
</script>

<script>
    $('.max_marks').on('blur', (e) => {
        let total_marks = 0;
        $('.max_marks').each(function() {
            total_marks += parseInt($(this).val() || 0);
        });
        $('#total_max_marks').val(total_marks);
    });

    $('.obtain_marks').on('blur', (e) => {
        let total_obtain_marks = 0;
        $('.obtain_marks').each(function() {
            total_obtain_marks += parseInt($(this).val() || 0);
        });
        $('#total_obtain_marks').val(total_obtain_marks);
    });

    const courses = {
        "B.Sc. B.Ed.": [],
        "B.A. B.Ed.": [],
    };
    $('.codes').on('blur', (e) => {
        let element = e.target;
        let row = $(element).attr('data-row');
        let code = e.target.value.trim();
        let section = $(element).attr('data-section');
        let codes = [];
        let duplicate = false;

        $(".codes").each(function() {
            let val = $(this).val().trim();
            if (val !== "") {
                if (codes.includes(val)) {
                    duplicate = true;
                } else {
                    codes.push(val);
                }
            }
        });

        if (duplicate) {
            e.preventDefault();
            $('#code' + row).addClass("error");
            alert("Each subject Code must be unique!");
            $(element).val('');
        } else {
            $('#code' + row).removeClass("error");
            if (code != '') {
                $('.loader-wrapper').show();
                $.ajax({
                    type: "GET",
                    url: `<?php echo base_url('fetch-subject'); ?>/${code}?section=${section}`,
                    dataType: "json",
                    contentType: "application/json",
                    cache: false,
                }).done(function(data) {
                    // console.log("Complated", data);
                    // console.log("StatusCode", data.status);
                    // console.log("Result", data.result);

                    let result = data.result;

                    if (data.status == 200) {
                        if (data.result.length == 0) {
                            let idx = codes.indexOf(code);
                            if (idx > -1) {
                                codes.splice(idx, 1);
                                $(element).val('');
                            }
                            toastr.warning('Please enter correct subject code.');
                        } else {
                            let selectedSubject = result[0].subject;
                            let oldSubject = $(`#subject${row}`).val();
                            console.log('oldSubject ', oldSubject);
                            $(`#subject${row}`).val(selectedSubject);
                            $(`#max_marks${row}`).val(result[0].max_score);
                            $('.max_marks').trigger('blur');
                            if(section == 'Section 2'){

                                for (let key in courses) {
                                    courses[key] = courses[key].filter(subject => subject !== oldSubject);
                                }
                                console.log('adL ', courses);
                                bscSubject = bscSubject.filter(subject => subject !== oldSubject);
                                baSubject = baSubject.filter(subject => subject !== oldSubject);
                                if(result[0].course === 'B.Sc. B.Ed.'){
                                    if(selectedSubject == 'Biology/Biological Studies'){
                                        courses[result[0].course].push('Zoology');
                                        courses[result[0].course].push('Botany');
                                        bscSubject.push('Zoology');
                                        bscSubject.push('Botany');
                                    }else{
                                        bscSubject.push(selectedSubject);
                                        courses[result[0].course].push(selectedSubject);
                                    }
                                }else if(result[0].course === 'B.A. B.Ed.'){
                                    baSubject.push(selectedSubject);
                                    courses[result[0].course].push(selectedSubject);
                                }

                                createPreferences();
                            }
                        }
                        // console.log(courses);
                    }
                    $('.loader-wrapper').hide();
                }).fail(function(data) {
                    console.log("failure", data);
                    toastr.error('Something is wrong', 'Error');
                    $('.loader-wrapper').hide();
                });
            }
        }
    });
    // Create preferences only once and append to containers
    let preferencesCreated = false;

    function createPreferences() {
        if (preferencesCreated) return;
        // preferencesCreated = true;
        
        $('.bscPreferences').html('');
        $('.baPreferences').html('');

        bscCount = 1;
        baCount = 1;
        bscPreferences = '';
        baPreferences = '';

        for (const key in courses) {
            for (const element of courses[key]) {
                console.log('element: ', element);
                if(key == 'B.Sc. B.Ed.'){
                    bscPreferences += `<div class="mb-3 row">
                                            <label class="col-sm-4 col-form-label">Preference ${bscCount}st</label>
                                            <div class="col-sm-8">
                                                <select class="form-select bsc-preference-select" name="bsc_preference_${bscCount}" required></select>
                                            </div>
                                        </div>`;
                    bscCount++;
                }else if(key == 'B.A. B.Ed.'){
                    baPreferences += `<div class="mb-3 row">
                                            <label class="col-sm-4 col-form-label">Preference ${baCount}st</label>
                                            <div class="col-sm-8">
                                                <select class="form-select ba-preference-select" name="ba_preference_${baCount}" required></select>
                                            </div>
                                        </div>`;
                    baCount++;
                }
            }
        }
        
        $('.bscPreferences').append(bscPreferences);
        $('.baPreferences').append(baPreferences);

        $('.ba-preference-select').each(function(idx) {
            let select = $(this);
            $(this).html(`<option value="" selected>--Select--</option>`);
            baSubject.forEach(subject => {
                let isSelected = ba_preferences[idx] === subject ? 'selected' : '';
                $(this).append(`<option value="${subject}" ${isSelected}>${subject}</option>`);
            });
        });

        $('.bsc-preference-select').each(function(idx) {
            let select = $(this);
            $(this).html(`<option value="" selected>--Select--</option>`);
            bscSubject.forEach(subject => {
                let isSelected = bsc_preferences[idx] === subject ? 'selected' : '';
                $(this).append(`<option value="${subject}" ${isSelected}>${subject}</option>`);
            });
        });
        
    }
</script>