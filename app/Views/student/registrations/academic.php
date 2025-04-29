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
        padding: 30px;
        background-color: #fff;
    }

    .upload-section {
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 20px;
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

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Application No.</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->ncet_application_no ?>" readonly>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->name ?>" readonly>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Category</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->category ?>" readonly>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Mobile No.</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->phone ?>" readonly>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Stream</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->course ?>" readonly>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">NCET 2024 Application No.</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->ncet_application_no ?>" readonly>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Gender</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" value="<?= $details->gender ?>" readonly>
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
        </div>
    </div>
    <!-- </div> -->
    <!-- PART 2: Preference Form -->
    <!-- <div class="container main-container"> -->
    <hr />
    <div class="mt-4 section-title">
        <p>Please fill disciplinary major subject choice<br>On preference order</p>
    </div>
    <form method="post" action="<?php echo base_url(); ?>update-academic-profile" enctype="multipart/form-data" id="academic-form">
        <input type="hidden" name="id" value="<?php echo $details->id; ?>" />
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Course</label>
                    <div class="col-sm-8">
                        <select class="form-select" name="course" id="course">
                            <option selected disabled>--Select Course--</option>
                            <option value="B.Sc. B.Ed" <?php echo $details->course == "B.Sc. B.Ed" ? 'selected' : ''; ?>>B.Sc. B.Ed.</option>
                            <option value="B.A. B.Ed" <?php echo $details->course == "B.A. B.Ed" ? 'selected' : ''; ?>>B.A. B.Ed.</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
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
                </div>
            </div>
        </div>
        <!-- </form> -->
        <!-- </div> -->
        <!-- Part 3: Details of Sr. Secondary or Equivalent Exam -->
        <!-- <div class="container main-container"> -->
        <hr />
        <div class="mt-4 section-title">
            <p>Details of Sr. Secondary or Equivalent Exam</p>
        </div>
        <!-- <form> -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Discipline</label>
                    <div class="col-sm-8">
                        <select class="form-select" name="discipline" required>
                            <option selected disabled>--Select Stream--</option>
                            <option value="Science" <?php echo $details->discipline === 'Science' ? 'selected' : ''; ?>>Science</option>
                            <option value="Commerce" <?php echo $details->discipline === 'Commerce' ? 'selected' : ''; ?>>Commerce</option>
                            <option value="Art" <?php echo $details->discipline === 'Art' ? 'selected' : ''; ?>>Art</option>
                            <option value="Other" <?php echo $details->discipline === 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Year Of Passing</label>
                    <div class="col-sm-8">
                        <select class="form-select" name="year_of_passing" required>
                            <option selected disabled>--Select Year--</option>
                            <option value="2022" <?php echo $details->year_of_passing === '2022' ? 'selected' : ''; ?>>2022</option>
                            <option value="2023" <?php echo $details->year_of_passing === '2023' ? 'selected' : ''; ?>>2023</option>
                            <option value="2024" <?php echo $details->year_of_passing === '2024' ? 'selected' : ''; ?>>2024</option>
                        </select>
                    </div>
                </div>
                <label class="form-label fw-bold mt-4">Score</label>
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Maximum Marks</th>
                                <th>Obtained Marks</th>
                                <th>Percent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="number" class="form-control max-marks" id="max-marks" name="sr_sec_max_marks" value="<?php echo !empty($details->sr_sec_max_marks) ? $details->sr_sec_max_marks : ''; ?>" placeholder="Max" oninput="calculatePercent(this)" required></td>
                                <td><input type="number" class="form-control obtained-marks" id="obtained-marks" name="sr_sec_obtain_marks" value="<?php echo !empty($details->sr_sec_obtain_marks) ? $details->sr_sec_obtain_marks : ''; ?>" placeholder="Obtained" oninput="calculatePercent(this)" required></td>
                                <td class="d-flex"><input type="text" class="form-control percent" name="sr_sec_percentage" value="<?php echo !empty($details->sr_sec_percentage) ? $details->sr_sec_percentage : ''; ?>" placeholder="%" readonly>%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- </form> -->
        <!-- </div> -->
        <!-- Part 4: Details of NCET 2024 Exam -->
        <!-- <div class="container main-container"> -->
        <hr />
        <div class="mt-4 section-title">
            <p>Details of NCET 2024 Exam</p>
        </div>
        <!-- <form> -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">NCET 2024 Roll No</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" value="<?php echo !empty($details->ncet_roll_no) ? $details->ncet_roll_no : ''; ?>" name="ncet_roll_no" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">ITEP Course</label>
                    <div class="col-sm-8">
                        <select class="form-select" name="itep_courses" required>
                            <option selected disabled>--Select Course--</option>
                            <option value="B.Sc. B.Ed." <?php echo $details->itep_courses === 'B.Sc. B.Ed.' ? 'selected' : ''; ?>>B.Sc. B.Ed.</option>
                            <option value="B.A. B.Ed." <?php echo $details->itep_courses === 'B.A. B.Ed.' ? 'selected' : ''; ?>>B.A. B.Ed.</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Score</label>
                    <div class="col-sm-12">
                        <table class="table table-bordered text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Subject</th>
                                    <th>Total Maximum Marks</th>
                                    <th>Total Marks Obtained</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 0; $i < 7; $i++) {
                                ?>
                                    <tr>
                                        <td>
                                            <input type="hidden" value="<?php echo isset($ncet[$i]->id) ? $ncet[$i]->id : ''; ?>" name="ids[]" />
                                            <input type="number" class="form-control codes" name="code[]" id="code<?= $i; ?>" data-row="<?= $i; ?>" value="<?php echo isset($ncet[$i]->codes) ? $ncet[$i]->codes : ''; ?>" required>
                                        </td>
                                        <td><input type="text" class="form-control" name="subject[]" id="subject<?= $i; ?>" data-row="<?= $i; ?>" value="<?php echo isset($ncet[$i]->subjects) ? $ncet[$i]->subjects : ''; ?>" required readonly></td>
                                        <td><input type="number" class="form-control max_marks" name="max_marks[]" id="max_marks<?= $i; ?>" data-row="<?= $i; ?>" value="<?php echo isset($ncet[$i]->total_maximum_marks) ? $ncet[$i]->total_maximum_marks : ''; ?>" required></td>
                                        <td><input type="number" class="form-control obtain_marks" name="obtain_marks[]" id="obtain_marks<?= $i; ?>" data-row="<?= $i; ?>" value="<?php echo isset($ncet[$i]->total_marks_obtain) ? $ncet[$i]->total_marks_obtain : ''; ?>" required></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                <tr>
                                    <td colspan="2">Total Marks</td>
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
        <div class="mt-4 main-box">
            <h3 class="mb-4">Attachments</h3>
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
                            <img src="<?= isset($details->photo) && !empty($details->photo) ? base_url($details->photo) : base_url('/assets/img/no-image.png'); ?>" alt="Preview" class="preview me-3" id="previewPhoto">
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="photo" name="photo" style="width: auto;" onchange="previewImage(event, 'previewPhoto')" required>
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
                            <img src="<?= isset($details->signature) && !empty($details->signature) ? base_url($details->signature) : base_url('/assets/img/no-image.png'); ?>" alt="Preview" class="preview me-3" id="previewSignature">
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="signature" name="signature" style="width: auto;" onchange="previewImage(event, 'previewSignature')" required>
                        </div>
                    </div>
                </div>
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
                            <img src="<?= isset($details->certificate_10) && !empty($details->certificate_10) ? base_url($details->certificate_10) : base_url('/assets/img/no-image.png'); ?>" alt="Preview" class="preview me-3" id="previewCertificate_10">
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="certificate_10" name="certificate_10" style="width: auto;" onchange="previewImage(event, 'previewCertificate_10')" required>
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
                            <img src="<?= isset($details->certificate_12) && !empty($details->certificate_12) ? base_url($details->certificate_12) : base_url('/assets/img/no-image.png'); ?>" alt="Preview" class="preview me-3" id="previewCertificate_12">
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="certificate_12" name="certificate_12" style="width: auto;" onchange="previewImage(event, 'previewCertificate_12')" required>
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
                            <img src="<?= isset($details->ncet_score_card) && !empty($details->ncet_score_card) ? base_url($details->ncet_score_card) : base_url('/assets/img/no-image.png'); ?>" alt="Preview" class="preview me-3" id="preview_ncet_score">
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="ncet_score_card" name="ncet_score_card" style="width: auto;" onchange="previewImage(event, 'preview_ncet_score')" required>
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
                            <img src="<?= isset($details->caste_certificate) && !empty($details->caste_certificate) ? base_url($details->caste_certificate) : base_url('/assets/img/no-image.png'); ?>" alt="Preview" class="preview me-3" id="preview_caste_certificate">
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="caste_certificate" name="caste_certificate" style="width: auto;" onchange="previewImage(event, 'preview_caste_certificate')" required>
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
                            <img src="<?= isset($details->pwbd) && !empty($details->pwbd) ? base_url($details->pwbd) : base_url('/assets/img/no-image.png'); ?>" alt="Preview" class="preview me-3" id="preview_pwbd">
                        </div>
                        <div class="d-flex align-items-center flex-wrap mt-3">
                            <input type="file" class="form-control form-control-sm me-2" id="pwbd" name="pwbd" style="width: auto;" onchange="previewImage(event, 'preview_pwbd')" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 justify-content-center mt-3">
            <button type="submit" class="btn btn-sm btn-outline-success" id="save_draft" value="Save as Draft" name="save_as_draft">Save as Draft</button>
            <button type="submit" class="btn btn-sm btn-success" id="final_save" name="final_save">Save</button>
            <button type="button" class="btn btn-sm btn-danger">Cancel</button>
        </div>
    </form>
</div>

<script>
    function calculatePercent(element) {
        const row = element.closest('tr');
        const max = row.querySelector('.max-marks').value;
        const obtained = row.querySelector('.obtained-marks').value;
        const percentField = row.querySelector('.percent');

        if (max && obtained && max > 0) {
            const percent = ((obtained / max) * 100).toFixed(2);
            percentField.value = percent;
        } else {
            percentField.value = '';
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
    let preference_1 = '<?php echo $details->preference_1; ?>';
    let preference_2 = '<?php echo $details->preference_2; ?>';
    let preference_3 = '<?php echo $details->preference_3; ?>';
    let preference_4 = '<?php echo $details->preference_4; ?>';
    let preference_5 = '<?php echo $details->preference_5; ?>';
    const preferences = [preference_1, preference_2, preference_3, preference_4, preference_5];

    let photo = '<?php echo $details->photo; ?>';
    let signature = '<?php echo $details->signature; ?>';
    let certificate_10 = '<?php echo $details->certificate_10; ?>';
    let certificate_12 = '<?php echo $details->certificate_12; ?>';
    let ncet_score_card = '<?php echo $details->ncet_score_card; ?>';
    let caste_certificate = '<?php echo $details->caste_certificate; ?>';
    let pwbd = '<?php echo $details->pwbd; ?>';

    let status = '<?php echo $details->status; ?>';

    const attachment = {
        photo,
        signature,
        certificate_10,
        certificate_12,
        ncet_score_card,
        caste_certificate,
        pwbd
    };

    $(document).ready(function() {
        $('#course').trigger('change');
        $('.obtain_marks').trigger('blur');
        $('.max_marks').trigger('blur');

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
            sr_sec_obtain_marks: {
                required: true,
                number: true,
                lessThan: '#max-marks'
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
                console.log(key);
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
            $("#academic-form").submit();  // validate and submit
        });
    });

    $("#course").change(function(e) {
        try {
            $('.preference-select').html('');
            let course = e.target.value;
            let bscSubject = ['Physics', 'Chemistry', 'Mathematics', 'Zoology', 'Botany'];
            let baSubject = ['Geography', 'History', 'Hindi', 'English', 'Urdu'];
            let subjects = [];

            if (course == 'B.A. B.Ed') {
                // baSubject.map((value, idx) => {
                //     preferenceOptions += `<option value="${value}">${value}</option>`;
                // });
                subjects = baSubject;
            } else if (course == 'B.Sc. B.Ed') {
                // bscSubject.map((value, idx) => {
                //     preferenceOptions += `<option value="${value}">${value}</option>`;
                // });
                subjects = bscSubject;
            }

            $('.preference-select').each(function(idx) {
                let select = $(this);
                select.html(`<option value="" selected>--Select--</option>`);
                subjects.forEach(subject => {
                    let isSelected = preferences[idx] === subject ? 'selected' : '';
                    select.append(`<option value="${subject}" ${isSelected}>${subject}</option>`);
                });
            });

        } catch (err) {
            console.log(err);
        }

        // $('.preference-select').append(preferenceOptions);
    });

    bindPreferenceOption();


    function bindPreferenceOption() {
        const selects = document.querySelectorAll(".preference-select");

        function updateOptions() {
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

        selects.forEach(select => {
            select.removeEventListener("change", updateOptions);
            select.addEventListener("change", updateOptions);
        });

        updateOptions();
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
    })


    $('.codes').on('blur', (e) => {
        let element = e.target;
        let row = $(element).attr('data-row');
        let code = e.target.value.trim();
        let codes = [];
        let duplicate = false;

        $(".codes").each(function() {
            let val = $(this).val().trim();
            console.log('Out: ', code);
            if (val !== "") {
                console.log('Inner : ', code);
                if (codes.includes(val)) {
                    console.log('Inner Inner : ', code);
                    duplicate = true;
                } else {
                    codes.push(val);
                }
            }
            console.log('codes: ', codes);
        });

        if (duplicate) {
            e.preventDefault();
            $('#code' + row).addClass("error");
            alert("Each subject Code must be unique!");
            $(element).val('');
        } else {
            $('#code' + row).removeClass("error");
            if (code != '') {
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url('fetch-subject'); ?>/" + code,
                    dataType: "json",
                    contentType: "application/json",
                    cache: false,
                }).done(function(data) {
                    console.log("Complated", data);
                    console.log("StatusCode", data.status);
                    console.log("Result", data.result);

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
                            $(`#subject${row}`).val(result[0].subject);
                        }
                    }
                }).fail(function(data) {
                    console.log("failure", data);
                    //need to update actual error message
                    toastr.error('Something is wrong', 'Error');
                });
            }
        }
    });
</script>