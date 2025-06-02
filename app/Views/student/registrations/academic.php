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
        margin-bottom: 60px;
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

    .required-icon {
        color: red;
    }
</style>

<!-- PART 1: Applicant Details -->
<div class="container mt-4 main-container">

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
                <label class="col-sm-4 col-form-label">RIEA Registration Number</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->registration_no ?>" readonly>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">NCET <?=date('Y');?> Application No.</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->ncet_application_no ?>" readonly>
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
        </div>

        <div class="col-md-6">
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Gender</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->gender ?>" readonly>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Category</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->category ?>" readonly>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Physical Disability</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->physical_disable; ?>" readonly>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Mobile No.</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->phone ?>" readonly>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Aadhar Number</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->aadhar_no ?>" readonly>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Course</label>
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
            <h2>Academic Qualifications</h2>
        </div>
        <!-- 10th -->
        <div class="row flex-column align-content-center">
            <div class="col-md-6"><h3 class="mb-3">10<sup>th</sup> (Secondary)</h3></div>
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Board <span class="required-icon">*</span></label>
                    <div class="col-sm-8">
                        <select class="form-select" name="board_10th" id="board_10th" data-input="board-10-any-other" required>
                            <option value="" selected>Select Board</option>
                            <option value="CBSE" <?php echo $details->board_10th == 'CBSE' ? 'selected' : '';?>>CBSE</option>
                            <option value="State Board" <?php echo $details->board_10th == 'State Board' ? 'selected' : '';?>>State Board</option>
                            <option value="Any Other Board" <?php echo $details->board_10th == 'Any Other Board' ? 'selected' : '';?>>Any Other Board</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row" id="board-10-any-other">
                    <label class="col-sm-4 col-form-label">Name of Board <span class="required-icon">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="board_10th_other" value="<?php echo $details->board_10th_other; ?>"/>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Year of Passing <span class="required-icon">*</span></label>
                    <div class="col-sm-8">
                        <select class="form-select" name="year_of_passing_10th" required>
                            <option value="" selected>Select Year</option>
                        <?php foreach ($years_for_10th as $year) {
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
                                <th>Maximum Marks <span class="required-icon">*</span></th>
                                <th>Obtained Marks <span class="required-icon">*</span></th>
                                <th>Percentage(%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="number" class="form-control max-marks" id="max-marks-10" name="max_marks_10th" value="<?php echo !empty($details->max_marks_10th) ? $details->max_marks_10th : ''; ?>" placeholder="Maximum Marks" oninput="calculatePercent('#max-marks-10', '#obtained-marks-10', '#percentage-10')" required></td>
                                <td><input type="number" class="form-control obtained-marks" id="obtained-marks-10" name="obtain_marks_10th" value="<?php echo !empty($details->obtain_marks_10th) ? $details->obtain_marks_10th : ''; ?>" placeholder="Obtained Marks" oninput="calculatePercent('#max-marks-10', '#obtained-marks-10', '#percentage-10')" data-max-id="max-marks-10" required></td>
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
                    <label class="col-sm-4 col-form-label">Stream <span class="required-icon">*</span></label>
                    <div class="col-sm-8">
                        <select class="form-select" name="stream" required>
                            <option value="" selected>Select Stream</option>
                            <option value="Science" <?php echo $details->stream === 'Science' ? 'selected' : ''; ?>>Science</option>
                            <option value="Commerce" <?php echo $details->stream === 'Commerce' ? 'selected' : ''; ?>>Commerce</option>
                            <option value="Art" <?php echo $details->stream === 'Art' ? 'selected' : ''; ?>>Art</option>
                            <option value="Other" <?php echo $details->stream === 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Board <span class="required-icon">*</span></label>
                    <div class="col-sm-8">
                        <select class="form-select" name="board_12th" id="board_12th" data-input="board-12-any-other" required>
                            <option value="" selected>Select Board</option>
                            <option value="CBSE" <?php echo $details->board_12th == 'CBSE' ? 'selected' : '';?>>CBSE</option>
                            <option value="State Board" <?php echo $details->board_12th == 'State Board' ? 'selected' : '';?>>State Board</option>
                            <option value="Any Other Board" <?php echo $details->board_12th == 'Any Other Board' ? 'selected' : '';?>>Any Other Board</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row" id="board-12-any-other">
                    <label class="col-sm-4 col-form-label">Name of Board <span class="required-icon">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="board-12-any-other" name="board_12th_other"  value="<?php echo $details->board_12th_other; ?>" <?php isset($details->board_12th_other) && !empty($details->board_12th_other) ? "style='display:block';": "style='display:none';"?>/>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Year of Passing <span class="required-icon">*</span></label>
                    <div class="col-sm-8">
                        <select class="form-select" name="year_of_passing_12th" required>
                            <option value="" selected>Select Year</option>
                        <?php foreach ($years_for_12th as $year) {
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
                                <th>Maximum Marks <span class="required-icon">*</span></th>
                                <th>Obtained Marks <span class="required-icon">*</span></th>
                                <th>Percentage(%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="number" class="form-control max-marks" id="max-marks-12" name="max_marks_12th" value="<?php echo !empty($details->max_marks_12th) ? $details->max_marks_12th : ''; ?>" placeholder="Maximum Marks" oninput="calculatePercent('#max-marks-12', '#obtained-marks-12', '#percentage-12')" required></td>
                                <td><input type="number" class="form-control obtained-marks" id="obtained-marks-12" name="obtain_marks_12th" value="<?php echo !empty($details->obtain_marks_12th) ? $details->obtain_marks_12th : ''; ?>" placeholder="Obtained Marks" oninput="calculatePercent('#max-marks-12', '#obtained-marks-12', '#percentage-12')" data-max-id="max-marks-12" required></td>
                                <td class="d-flex"><input type="text" class="form-control percent" id="percentage-12" name="percentage_12th" value="<?php echo !empty($details->percentage_12th) ? $details->percentage_12th : ''; ?>" placeholder="%" readonly></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <hr />
        
        <div class="mt-4 section-title">
            <h2>Details of NCET <?=date('Y');?> Exam</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="mb-3 row">
                    <label class="col-sm-4 offset-sm-1 col-form-label">NCET <?=date('Y');?> Application Number</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" value="<?php echo !empty($details->ncet_application_no) ? $details->ncet_application_no : ''; ?>" name="ncet_roll_no" readonly required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 offset-sm-1 col-form-label">ITEP Course</label>
                    <div class="col-sm-6">
                        <!-- <select class="form-select" name="itep_courses" required> -->
                        <select class="form-select" disabled>
                            <option selected disabled>--Select Course--</option>
                            <option value="ITEP - B.Sc. B.Ed." <?php echo $details->course === 'ITEP - B.Sc. B.Ed.' ? 'selected' : ''; ?>>ITEP - B.Sc. B.Ed.</option>
                            <option value="ITEP - B.A. B.Ed." <?php echo $details->course === 'ITEP - B.A. B.Ed.' ? 'selected' : ''; ?>>ITEP - B.A. B.Ed.</option>
                            <option value="ITEP - B.Sc. B.Ed. & B.A. B.Ed." <?php echo $details->course === 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.' ? 'selected' : ''; ?>>ITEP - B.Sc. B.Ed. & B.A. B.Ed.</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <h3 class="col-sm-3 form-label fw-bold mt-3">NCET <?=date('Y');?> Score</h3>
                    <div class="col-sm-12">
                        <h5><span class="required-icon">*</span> Fill the details of score obtained in Languages, Domain-Specific Subjects, and General Test in NCET <?=date('Y');?></h5>
                        <table class="table table-bordered text-center">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 12%; vertical-align: middle"></th>
                                    <th style="width: 12%; vertical-align: middle">Code <span class="required-icon">*</span></th>
                                    <th style="width: 40%; vertical-align: middle">Name</th>
                                    <th style="width: 12%; vertical-align: middle">Maximum Score</th>
                                    <th style="width: 12%; vertical-align: middle">Score Obtained <span class="required-icon">*</span></th>
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
                                            <input type="number" class="form-control codes" name="code[]" id="code<?= $i; ?>" data-row="<?= $i; ?>" data-section="<?= $key; ?>" value="<?php echo isset($ncet[$i]->codes) && $ncet[$i]->codes != 0 ? $ncet[$i]->codes : ''; ?>" required>
                                        </td>
                                        <td><input type="text" class="form-control subjects" name="subject[]" id="subject<?= $i; ?>" data-row="<?= $i; ?>" value="" readonly></td>
                                        <td><input type="number" class="form-control max_marks" name="max_marks[]" id="max_marks<?= $i; ?>" data-row="<?= $i; ?>" value="<?php echo isset($ncet[$i]->total_maximum_marks) && $ncet[$i]->total_maximum_marks != 0 ? $ncet[$i]->total_maximum_marks : ''; ?>" readonly></td>
                                        <td><input type="number" class="form-control obtain_marks" name="obtain_marks[]" id="obtain_marks<?= $i; ?>" data-row="<?= $i; ?>" value="<?php echo isset($ncet[$i]->total_marks_obtain) && $ncet[$i]->total_marks_obtain != 0 ? $ncet[$i]->total_marks_obtain : ''; ?>" oninput="calculatePercent('#max_marks<?= $i; ?>', '#obtain_marks<?= $i; ?>', '#percentage<?= $i; ?>')" data-max-id="max_marks<?= $i; ?>" required></td>
                                        <td><input type="number" class="form-control percentage" name="percentage[]" id="percentage<?= $i; ?>" data-row="<?= $i; ?>" value="<?php echo isset($ncet[$i]->percentage) && $ncet[$i]->percentage != 0 ? $ncet[$i]->percentage : ''; ?>" readonly></td>
                                    </tr>
                                <?php
                                $i++;
                                }}
                                ?>
                                <tr>
                                    <td colspan="3" style="vertical-align: middle; font-weight: 700;">Total Marks</td>
                                    <td><input type="number" class="form-control" id="total_max_marks" readonly></td>
                                    <td><input type="number" class="form-control" id="total_obtain_marks" readonly></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <hr />

        <div class="mt-4 section-title">
            <h2>Preference for Major Discipline in ITEP Course</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Course</label>
                    <div class="col-sm-8">
                        <select class="form-select" id="course">
                            <option selected disabled>--Select Course--</option>
                            <option value="ITEP - B.Sc. B.Ed." <?php echo $details->course === 'ITEP - B.Sc. B.Ed.' ? 'selected' : ''; ?> <?php echo ($details->course !== 'ITEP - B.Sc. B.Ed.' && $details->course !== 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') ? 'disabled' : ''; ?>>ITEP - B.Sc. B.Ed.</option>
                            <option value="ITEP - B.A. B.Ed." <?php echo $details->course === 'ITEP - B.A. B.Ed.' ? 'selected' : ''; ?> <?php echo ($details->course !== 'ITEP - B.A. B.Ed.' && $details->course !== 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') ? 'disabled' : ''; ?>>ITEP - B.A. B.Ed.</option>
                            <!-- <option value="ITEP - Both" <?php //echo $details->itep_courses === 'ITEP - Both' ? 'selected' : ''; ?>>ITEP - Both</option> -->
                        </select>
                    </div>
                </div>
                <div class="bscPreferences"></div>
                <div class="baPreferences"></div>
            </div>
        </div>
        <hr />
        
        <div class="mt-4 main-box">
            <h3 class="mb-4">Uploads</h3>
            <div class="row">
                <div class="col-md-4 upload-section">
                    <div class="row">
                        <h4 class="mb-4">Photo <span class="required-icon">*</span></h4>
                        <div class="col-md-8">
                            <p class="mb-1"><strong>Max Size:</strong> 200KB</p>
                            <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG, PDF</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <!-- <div class="me-3 text-muted upload-status" id="uploadStatus"><? //= isset($details->photo) && !empty($details->photo) ? 'Uploaded' : 'Pending'
                                                                                                ?></div> -->
                            <?php if(isset($details->photo) && !empty($details->photo) && str_ends_with($details->photo, '.pdf')){ ?>
                                <a href="<?=base_url($details->photo);?>" target="_blank">Uploaded PDF</a>
                            <?php }else{ ?>
                                <img src="<?= isset($details->photo) && !empty($details->photo) ? base_url($details->photo) : base_url('/public/assets/img/no-image.webp'); ?>" alt="Preview" class="preview me-3" id="previewPhoto">
                            <?php } ?>
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="photo" name="photo" style="width: auto;" data-preview="previewPhoto" accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 upload-section">
                    <div class="row">
                        <h4 class="mb-4">Signature <span class="required-icon">*</span></h4>
                        <div class="col-md-8">
                            <p class="mb-1"><strong>Max Size:</strong> 200KB</p>
                            <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG, PDF</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <!-- <div class="me-3 text-muted upload-status" id="uploadStatus"><? //= isset($details->signature) && !empty($details->signature) ? 'Uploaded' : 'Pending'
                                                                                                ?></div> -->
                            <?php if(isset($details->signature) && !empty($details->signature) && str_ends_with($details->signature, '.pdf')){ ?>
                                <a href="<?=base_url($details->signature);?>" target="_blank">Uploaded PDF</a>
                            <?php }else{ ?>
                                <img src="<?= isset($details->signature) && !empty($details->signature) ? base_url($details->signature) : base_url('/public/assets/img/no-image.webp'); ?>" alt="Preview" class="preview me-3" id="previewSignature">
                            <?php } ?>
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="signature" name="signature" style="width: auto;" data-preview="previewSignature" accept=".jpg,.jpeg,.png,.pdf" required>
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
                        <h4 class="mb-4">10th Marksheet <span class="required-icon">*</span></h4>
                        <div class="col-md-8">
                            <p class="mb-1"><strong>Max Size:</strong> 1MB</p>
                            <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG, PDF</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <!-- <div class="me-3 text-muted upload-status" id="uploadStatus"><? //= isset($details->certificate_10) && !empty($details->certificate_10) ? 'Uploaded' : 'Pending'
                                                                                                ?></div> -->
                            <?php if(isset($details->certificate_10) && !empty($details->certificate_10) && str_ends_with($details->certificate_10,'.pdf')){ ?>
                                <a href="<?=base_url($details->certificate_10);?>" target="_blank">Uploaded PDF</a>
                            <?php }else{ ?>
                                <img src="<?= isset($details->certificate_10) && !empty($details->certificate_10) ? base_url($details->certificate_10) : base_url('/public/assets/img/no-image.webp'); ?>" alt="Preview" class="preview me-3" id="previewCertificate_10">
                            <?php } ?>
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="certificate_10" name="certificate_10" style="width: auto;"  data-preview="previewCertificate_10" accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 upload-section">
                    <div class="row">
                        <h4 class="mb-4">12th Marksheet <span class="required-icon">*</span></h4>
                        <div class="col-md-8">
                            <p class="mb-1"><strong>Max Size:</strong> 1MB</p>
                            <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG, PDF</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <!-- <div class="me-3 text-muted upload-status" id="uploadStatus"><? //= isset($details->certificate_12) && !empty($details->certificate_12) ? 'Uploaded' : 'Pending'
                                                                                                ?></div> -->
                            <?php if(isset($details->certificate_12) && !empty($details->certificate_12) && str_ends_with($details->certificate_12,'.pdf')){ ?>
                                <a href="<?=base_url($details->certificate_12);?>" target="_blank">Uploaded PDF</a>
                            <?php }else{ ?>
                                <img src="<?= isset($details->certificate_12) && !empty($details->certificate_12) ? base_url($details->certificate_12) : base_url('/public/assets/img/no-image.webp'); ?>" alt="Preview" class="preview me-3" id="previewCertificate_12">
                            <?php } ?>
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="certificate_12" name="certificate_12" style="width: auto;"  data-preview="previewCertificate_12" accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 upload-section">
                    <div class="row">
                        <h4 class="mb-4">NCET Score Card <span class="required-icon">*</span></h4>
                        <div class="col-md-8">
                            <p class="mb-1"><strong>Max Size:</strong> 1MB</p>
                            <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG, PDF</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <!-- <div class="me-3 text-muted upload-status" id="uploadStatus"><? //= isset($details->ncet_score_card) && !empty($details->ncet_score_card) ? 'Uploaded' : 'Pending'
                                                                                                ?></div> -->
                            <?php if(isset($details->ncet_score_card) && !empty($details->ncet_score_card) && str_ends_with($details->ncet_score_card,'.pdf')){ ?>
                                <a href="<?=base_url($details->ncet_score_card);?>" target="_blank">Uploaded PDF</a>
                            <?php }else{ ?>
                                <img src="<?= isset($details->ncet_score_card) && !empty($details->ncet_score_card) ? base_url($details->ncet_score_card) : base_url('/public/assets/img/no-image.webp'); ?>" alt="Preview" class="preview me-3" id="preview_ncet_score">
                            <?php } ?>
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="ncet_score_card" name="ncet_score_card" style="width: auto;"  data-preview="preview_ncet_score" accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 upload-section">
                    <div class="row">
                        <h4 class="mb-4">NCET Application Form <span class="required-icon">*</span></h4>
                        <div class="col-md-8">
                            <p class="mb-1"><strong>Max Size:</strong> 1MB</p>
                            <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG, PDF</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <?php if(isset($details->ncet_application_form) && !empty($details->ncet_application_form) && str_ends_with($details->ncet_application_form,'.pdf')){ ?>
                                <a href="<?=base_url($details->ncet_application_form);?>" target="_blank">Uploaded PDF</a>
                            <?php }else{ ?>
                                <img src="<?= isset($details->ncet_application_form) && !empty($details->ncet_application_form) ? base_url($details->ncet_application_form) : base_url('/public/assets/img/no-image.webp'); ?>" alt="Preview" class="preview me-3" id="preview_application_form">
                            <?php } ?>
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="ncet_application_form" name="ncet_application_form" style="width: auto;"  data-preview="preview_application_form" accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 upload-section" style="<?php echo $details->category !== 'GEN' ? 'display:block' : 'display:none'; ?>">
                    <div class="row">
                        <h4 class="mb-4">Caste Certificate <span class="required-icon">*</span></h4>
                        <div class="col-md-8">
                            <p class="mb-1"><strong>Max Size:</strong> 1MB</p>
                            <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG, PDF</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <!-- <div class="me-3 text-muted upload-status" id="uploadStatus"><? //= isset($details->caste_certificate) && !empty($details->caste_certificate) ? 'Uploaded' : 'Pending'
                                                                                                ?></div> -->
                            <?php if(isset($details->caste_certificate) && !empty($details->caste_certificate) && str_ends_with($details->caste_certificate,'.pdf')){ ?>
                                <a href="<?=base_url($details->caste_certificate);?>" target="_blank">Uploaded PDF</a>
                            <?php }else{ ?>
                                <img src="<?= isset($details->caste_certificate) && !empty($details->caste_certificate) ? base_url($details->caste_certificate) : base_url('/public/assets/img/no-image.webp'); ?>" alt="Preview" class="preview me-3" id="preview_caste_certificate">
                            <?php } ?>
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="caste_certificate" name="caste_certificate" style="width: auto;"  data-preview="preview_caste_certificate" accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 upload-section" style="<?php echo $details->physical_disable != 'No' ? 'display:block' : 'display:none'; ?>">
                    <div class="row">
                        <h4 class="mb-4">Physical Disability <span class="required-icon">*</span></h4>
                        <div class="col-md-8">
                            <p class="mb-1"><strong>Max Size:</strong> 1MB</p>
                            <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG, PDF</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <!-- <div class="me-3 text-muted upload-status" id="uploadStatus"><? //= isset($details->pwbd) && !empty($details->pwbd) ? 'Uploaded' : 'Pending'
                                                                                                ?></div> -->
                            <?php if(isset($details->pwbd) && !empty($details->pwbd) && str_ends_with($details->pwbd,'.pdf')){ ?>
                                <a href="<?=base_url($details->pwbd);?>" target="_blank">Uploaded PDF</a>
                            <?php }else{ ?>
                                <img src="<?= isset($details->pwbd) && !empty($details->pwbd) ? base_url($details->pwbd) : base_url('/public/assets/img/no-image.webp'); ?>" alt="Preview" class="preview me-3" id="preview_pwbd">
                            <?php } ?>
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="pwbd" name="pwbd" style="width: auto;" data-preview="preview_pwbd" accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 justify-content-center mt-3">
            <input type="hidden" name="button_value" id="button_value" value="">
            <button type="button" class="btn btn-sm btn-outline-success" id="save_draft" value="Save as Draft" name="save_as_draft">Save as Draft</button>
            <button type="button" class="btn btn-sm primary-btn text-white" id="final_save" name="final_save">Save</button>
            <?php if($details->status == 'Request'){?>
            <button type="button" class="btn btn-sm btn-danger" id="cancel-btn">Cancel</button>
            <?php } ?>
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
            // document.getElementById('uploadStatus').textContent = "Selected";
        } else {
            // No file selected or cleared – fallback to "Pending"
            // document.getElementById('uploadStatus').textContent = "Pending";
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
    let ba_preference_4 = '<?php echo $details->ba_preference_4; ?>';
    const bsc_preferences = [bsc_preference_1, bsc_preference_2, bsc_preference_3, bsc_preference_4];
    const ba_preferences = [ba_preference_1, ba_preference_2, ba_preference_3, ba_preference_4];

    let photo = '<?php echo $details->photo; ?>';
    let signature = '<?php echo $details->signature; ?>';
    let certificate_10 = '<?php echo $details->certificate_10; ?>';
    let certificate_12 = '<?php echo $details->certificate_12; ?>';
    let ncet_score_card = '<?php echo $details->ncet_score_card; ?>';
    let ncet_application_form = '<?php echo $details->ncet_application_form; ?>';
    let caste_certificate = '<?php echo $details->caste_certificate; ?>';
    let pwbd = '<?php echo $details->pwbd; ?>';

    let status = '<?php echo $details->status; ?>';
    var category =  '<?php echo $details->category; ?>';
    var physical_disable =  '<?php echo $details->physical_disable; ?>';
    var selectedITEPCourse = '<?php echo $details->course; ?>';

    const attachment = {
        photo,
        signature,
        certificate_10,
        certificate_12,
        ncet_score_card,
        ncet_application_form,
        caste_certificate,
        pwbd
    };

    var bscSubject = [];
    var baSubject = [];
    var baSubjectCodes = [101, 102, 113];
    const courses = {
        "B.Sc. B.Ed.": [],
        "B.A. B.Ed.": [],
    };

    $(document).ready(function() {
        $('.baPreferences').hide();
        $('.bscPreferences').hide();

        $('#course').trigger('change');
        $('.obtain_marks').trigger('blur');
        $('.max_marks').trigger('blur');
        $('.codes').trigger('blur');
        
        $(`#board-10-any-other`).hide();
        $(`#board-12-any-other`).hide();
        
        selectBoard($('#board_10th').prop('outerHTML'));
        selectBoard($('#board_12th').prop('outerHTML'));
        $('#board_10th,#board_12th').change(function(){
            selectBoard(this);
        });

        if (status === "Request") {
            for (key in attachment) {
                if(key === 'caste_certificate'){
                    if (category === "GEN") {
                        $(`input[name=${key}]`).removeAttr('required');
                    } else {
                        $(`input[name=${key}]`).attr('required', true);
                    }
                }else if(key === 'pwbd'){
                    if(physical_disable == 0){
                        $(`input[name=${key}]`).removeAttr('required');
                    }else{
                        $(`input[name=${key}]`).attr('required', true);
                    }
                }else{
                    $(`input[name=${key}]`).attr('required', true);
                }
            }
        } else {
            for (key in attachment) {
                if (attachment[key] != '') {
                    $(`input[name=${key}]`).attr('required', false);
                } else {
                    // $(`input[name=${key}]`).attr('required', true);
                    if(key === 'caste_certificate'){
                        if (category === "GEN") {
                            $(`input[name=${key}]`).removeAttr('required');
                        } else {
                            $(`input[name=${key}]`).attr('required', true);
                        }
                    }else if(key === 'pwbd'){
                        if(physical_disable === 'No'){
                            $(`input[name=${key}]`).removeAttr('required');
                        }else{
                            $(`input[name=${key}]`).attr('required', true);
                        }
                    }else{
                        $(`input[name=${key}]`).attr('required', true);
                    }
                }
            }
        };
        
        $('#cancel-btn').click(function(){
            let action = confirm("Are you sure you want to cancel?");
            console.log('action ', action);
            if(action){
                window.location.reload();
            }
        });

        // Function to validate file size and type
        function validateFile(fileInput, maxSize, allowedTypes) {
            const file = fileInput[0]?.files[0];
            if (file) {
                const fileSize = file.size / 1024; // size in KB
                const fileType = file.type;

                if (fileSize > maxSize) {
                    return false; // File size exceeds limit
                }
                if (!allowedTypes.includes(fileType)) {
                    return false; // File type not allowed
                }
            }
            return true; // Valid file
        }

        // Function to validate obtained marks
        function validateObtainedMarks(obtained_id, max_id) {
            let isValid = true;
            $('#'+obtained_id).each(function() {
                const obtained = parseFloat($(this).val());
                const max = parseFloat($(this).closest('tr').find('#'+max_id).val());
                // console.log(obtained, $(this).val(), max, $(this).closest('tr').find('#'+max_id).val());
                if (obtained > max) {
                    $(this).next().remove('label');
                    $(this).addClass('is-invalid');
                    $(this).after('<label class="error">Obtained marks cannot be greater than maximum marks</label>');
                    isValid = false;
                } else if(obtained < 0){
                    $(this).next().remove('label');
                    $(this).addClass('is-invalid');
                    $(this).after('<label class="error">Obtained marks cannot be less then 0</label>');
                    isValid = false;
                } else {
                    if($(this).next().text() === 'Obtained marks cannot be greater than maximum marks' || $(this).next().text() === 'Obtained marks cannot be less then 0'){
                        $(this).next().remove('label');
                        $(this).removeClass('is-invalid');
                    }
                }
            });
            return isValid;
        }

        // Real-time validation for required fields and obtained marks
        $('input[required], select[required]').on('input change', function() {
            // console.log($(this));
            if ($(this).val() === '') {
                // console.log('Enter');
                $(this).next().remove('label');
                $(this).after('<label class="error">This field is required</label>');
                $(this).addClass('is-invalid');
            } else {
                if($(this).next().text() === 'This field is required'){
                    $(this).next().remove('label');
                    $(this).removeClass('is-invalid');
                }
            }
        });

        $('.obtained-marks, .obtain_marks').on('input', function() {
            let obtained_id = $(this).attr('id');
            let max_id = $(this).attr('data-max-id');
            // console.log(obtained_id, max_id);
            validateObtainedMarks(obtained_id, max_id);
        });

        // Real-time validation for file inputs
        $('#photo, #signature, #certificate_10, #certificate_12, #ncet_score_card, #ncet_application_form, #caste_certificate, #pwbd').on('change', function(e) {
            let maxSize = $(this).is('#photo, #signature') ? 200 : 1024;
            let allowedTypes = ['image/jpg', 'image/jpeg', 'image/png', 'application/pdf'];
            let preview = $(this).attr('data-preview');
            
            console.log(preview);

            if (!validateFile($(this), maxSize, allowedTypes)) {
                $(this).next().remove('label');
                $(this).after('<label class="error">Invalid file. Please check the file type and size.</label>');
                $(this).addClass('is-invalid');
                // alert('Invalid file. Please check the file type and size.');
            } else {
                if($(this).next().text() === 'Invalid file. Please check the file type and size.' || $(this).next().text() === 'This field is required'){
                    // console.log('84098');
                    $(this).next().remove('label');
                    $(this).removeClass('is-invalid');
                }
                previewImage(e, preview);
            }
        });

        // Save as Draft button click event
        $('#save_draft').click(function() {
            let isValid = true;
            let validObtainCount = 0;
            let validFileCount = 0;
            
            // Validate file inputs for all documents
            const fileInputs = [
                { input: $('#photo'), maxSize: 200, types: ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'] },
                { input: $('#signature'), maxSize: 200, types: ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'] },
                { input: $('#certificate_10'), maxSize: 1024, types: ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'] },
                { input: $('#certificate_12'), maxSize: 1024, types: ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'] },
                { input: $('#ncet_score_card'), maxSize: 1024, types: ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'] },
                { input: $('#ncet_application_form'), maxSize: 1024, types: ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'] },
                { input: $('#caste_certificate'), maxSize: 1024, types: ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'] },
                { input: $('#pwbd'), maxSize: 1024, types: ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'] },
            ];

            fileInputs.forEach(function(fileInput) {
                if (!validateFile(fileInput.input, fileInput.maxSize, fileInput.types)) {
                    // alert('One or more files are invalid. Please check the file types and sizes.');
                    isValid = false;
                    validFileCount++;
                }
            });

            // Validate obtained marks against maximum marks
            $('.obtained-marks, .obtain_marks').each(function() {
                let obtained_id = $(this).attr('id');
                let max_id = $(this).attr('data-max-id');
                // console.log(obtained_id, max_id);
                if(!validateObtainedMarks(obtained_id, max_id)){
                    validObtainCount++;
                }
            });

            // If all validations pass, you can proceed with saving as draft
            if (validFileCount === 0 && validObtainCount === 0) {
                $('#button_value').val('Save as Draft');
                $('#academic-form').submit();
            }
        });

        // Save button click event
        $('#final_save').click(function() {
            let validRequiredCount = 0;
            let validObtainCount = 0;
            let validFileCount = 0;
            // let isRequiredValid = true;
            // let isObtainValid = true;

            $('.error').remove();

            // Validate required fields and marks
            $('input[required], select[required]').each(function() {
                if ($(this).val() === '') {
                    alert('Please fill all required fields.');
                    console.log(this);
                    // isRequiredValid = false;
                    $(this).after('<label class="error">This field is required</label>');
                    $(this).addClass('is-invalid');
                    validRequiredCount++;
                    // return false; // Break out of the loop
                }
            });

            // Validate obtained marks against maximum marks
            $('.obtained-marks, .obtain_marks').each(function() {
                let obtained_id = $(this).attr('id');
                let max_id = $(this).attr('data-max-id');
                console.log(obtained_id, max_id);
                if(!validateObtainedMarks(obtained_id, max_id)){
                    validObtainCount++;
                }
            });

            // Validate file inputs for all documents
            const fileInputs = [
                { input: $('#photo'), maxSize: 200, types: ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'] },
                { input: $('#signature'), maxSize: 200, types: ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'] },
                { input: $('#certificate_10'), maxSize: 1024, types: ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'] },
                { input: $('#certificate_12'), maxSize: 1024, types: ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'] },
                { input: $('#ncet_score_card'), maxSize: 1024, types: ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'] },
                { input: $('#ncet_application_form'), maxSize: 1024, types: ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'] },
                { input: $('#caste_certificate'), maxSize: 1024, types: ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'] },
                { input: $('#pwbd'), maxSize: 1024, types: ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'] },
            ];

            fileInputs.forEach(function(fileInput) {
                if (!validateFile(fileInput.input, fileInput.maxSize, fileInput.types)) {
                    // alert('One or more files are invalid. Please check the file types and sizes.');
                    isValid = false;
                    validFileCount++;
                }
            });

            console.log(validFileCount, validObtainCount, validRequiredCount, validFileCount === 0 && validObtainCount === 0 && validRequiredCount === 0);
            // If all validations pass, you can proceed with saving
            if (validFileCount === 0 && validObtainCount === 0 && validRequiredCount === 0) {
                // Submit the form or perform the final save action
                $('#button_value').val('Final Save');
                $('#academic-form').submit();
            }
        });
    });

    $("#course").change(function (e) {
        try {
            let course = e.target.value;
            let subjects = [];

            if (course == 'ITEP - B.A. B.Ed.') {
                subjects = baSubject;
                $('.baPreferences').show();
                // $('.bscPreferences').hide();
            } else if (course == 'ITEP - B.Sc. B.Ed.') {
                subjects = bscSubject;
                // $('.baPreferences').hide();
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

    function selectBoard(element){
        let id = $(element).data('input');
        let value = $(element).val();
        // console.log(id, value);
        if(value === 'Any Other Board' || value === 'State Board'){
            $(`#${id}`).show();
            $(`#${id} input`).attr('required', true);
        } else{
            $(`#${id}`).hide();
            $(`#${id} input`).removeAttr('required');
        }
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
            if(element.value !== '0')
                alert("Each subject Code must be unique!");

            $(`#code${row}`).val('');
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
                        $(element).next().remove('label');
                        if (data.result.length == 0) {
                            let idx = codes.indexOf(code);
                            if (idx > -1) {
                                codes.splice(idx, 1);
                                $(`#code${row}`).val('');
                            }
                            // toastr.warning('Please enter correct subject code.');
                            $(element).after('<label class="error">Please enter correct subject code</label>');
                        } else {
                            let selectedSubject = result[0].subject;
                            let oldSubject = $(`#subject${row}`).val();
                            // console.log('oldSubject ', oldSubject);
                            $(`#subject${row}`).val(selectedSubject);
                            $(`#max_marks${row}`).val(result[0].max_score);
                            $('.max_marks').trigger('blur');
                            if(section == 'Section 2'){
                                for (let key in courses) {
                                    if(oldSubject === "Biology/Biological Studies"){
                                        courses[key] = courses[key].filter(subject => subject !== "Zoology" && subject !== "Botany");
                                    }else{
                                        courses[key] = courses[key].filter(subject => subject !== oldSubject);
                                    }
                                }
                                // console.log('adL ', courses);
                                if(oldSubject === "Biology/Biological Studies"){
                                    bscSubject = bscSubject.filter(subject => subject !== "Zoology" && subject !== "Botany");
                                }else{
                                    bscSubject = bscSubject.filter(subject => subject !== oldSubject);
                                }
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
                            
                            if(section == 'Section 1' && (selectedITEPCourse === 'ITEP - B.A. B.Ed.' || selectedITEPCourse === 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.') && baSubjectCodes.includes(parseInt(code))){
                                for (let key in courses) {
                                    courses[key] = courses[key].filter(subject => subject !== oldSubject);
                                }
                                baSubject = baSubject.filter(subject => subject !== oldSubject);
                                baSubject.push(selectedSubject);
                                courses[result[0].course].push(selectedSubject);
                                createPreferences();
                            }else{
                                for (let key in courses) {
                                    courses[key] = courses[key].filter(subject => subject !== oldSubject);
                                }
                                baSubject = baSubject.filter(subject => subject !== oldSubject);
                                createPreferences();
                            }
                        }
                        // console.log(courses);
                    }
                    $('.loader-wrapper').hide();
                }).fail(function(data) {
                    // console.log("failure", data);
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
                // console.log('element: ', element);
                if(key == 'B.Sc. B.Ed.'){
                    bscPreferences += `<div class="mb-3 row">
                                            <label class="col-sm-4 col-form-label">Preference ${bscCount}st <span class="required-icon">*</span></label>
                                            <div class="col-sm-8">
                                                <select class="form-select bsc-preference-select" name="bsc_preference_${bscCount}" required></select>
                                            </div>
                                        </div>`;
                    bscCount++;
                }else if(key == 'B.A. B.Ed.'){
                    baPreferences += `<div class="mb-3 row">
                                            <label class="col-sm-4 col-form-label">Preference ${baCount}st <span class="required-icon">*</span></label>
                                            <div class="col-sm-8">
                                                <select class="form-select ba-preference-select" name="ba_preference_${baCount}" required></select>
                                            </div>
                                        </div>`;
                    baCount++;
                }
            }
        }
        
        if(selectedITEPCourse === 'ITEP - B.Sc. B.Ed.'){
            $('.bscPreferences').append(bscPreferences);
        } else if(selectedITEPCourse === 'ITEP - B.A. B.Ed.'){
            $('.baPreferences').append(baPreferences);
        } else if(selectedITEPCourse === 'ITEP - B.Sc. B.Ed. & B.A. B.Ed.'){
            $('.bscPreferences').append(bscPreferences);
            $('.baPreferences').append(baPreferences);
        }

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
        
        bindPreferenceOption();
    }
</script>