<?php
    // var_dump($details);
?>

<style>
    body {
        background-color: #f8f9fa;
    }

    .main-container {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        border-radius: 8px;
        background-color: #fff;
        margin-bottom: 70px;
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

    .upload-status {
        font-weight: bold;
        color: gray;
    }

    img.preview {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
</style>

<!-- PART 1: Applicant Details -->
<div class="container mt-5 main-container">
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
                    <input type="text" class="form-control" value="<?= $details->physical_disable ?>" readonly>
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
    <form method="post" action="<?php echo base_url(); ?>update-academic-profile" enctype="multipart/form-data">
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
                        <select class="form-select preference-select" name="preference_1">
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Preference 2nd</label>
                    <div class="col-sm-8">
                        <select class="form-select preference-select" name="preference_2">
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Preference 3rd</label>
                    <div class="col-sm-8">
                        <select class="form-select preference-select" name="preference_3">
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Preference 4th</label>
                    <div class="col-sm-8">
                        <select class="form-select preference-select" name="preference_4">
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Preference 5th</label>
                    <div class="col-sm-8">
                        <select class="form-select preference-select" name="preference_5">
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
                        <select class="form-select" name="discipline">
                            <option selected disabled>--Select Stream--</option>
                            <option value="Science">Science</option>
                            <option value="Commerce">Commerce</option>
                            <option value="Art">Art</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Year Of Passing</label>
                    <div class="col-sm-8">
                        <select class="form-select" name="year_of_passing">
                            <option selected disabled>--Select Year--</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
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
                                <td><input type="number" class="form-control max-marks" name="sr_sec_max_marks" placeholder="Max" oninput="calculatePercent(this)"></td>
                                <td><input type="number" class="form-control obtained-marks" name="sr_sec_obtain_marks" placeholder="Obtained" oninput="calculatePercent(this)"></td>
                                <td class="d-inline-flex"><input type="text" class="form-control percent" name="sr_sec_percentage" placeholder="%" readonly>%</td>
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
                        <input type="text" class="form-control" value="" name="ncet_roll_no">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">ITEP Course</label>
                    <div class="col-sm-8">
                        <select class="form-select" name="itep_courses">
                            <option selected disabled>--Select Course--</option>
                            <option value="B.Sc. B.Ed.">B.Sc. B.Ed.</option>
                            <option value="B.A. B.Ed.">B.A. B.Ed.</option>
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
                                <!-- Generate 10 rows -->
                                <script>
                                    for (let i = 0; i < 7; i++) {
                                        document.write(`
                                            <tr>
                                                <td><input type="number" class="form-control codes" name="code[]" id="code${i+1}" data-row="${i+1}"></td>
                                                <td><input type="text" class="form-control" name="subject[]" id="subject${i+1}" data-row="${i+1}"></td>
                                                <td><input type="number" class="form-control max_marks" name="max_marks[]" id="max_marks${i+1}" data-row="${i+1}"></td>
                                                <td><input type="number" class="form-control obtain_marks" name="obtain_marks[]" id="obtain_marks${i+1}" data-row="${i+1}"></td>
                                            </tr>
                                        `);
                                    }
                                </script>
                                <tr>
                                    <td colspan="2">Total Marks</td>
                                    <td><input type="number" class="form-control" id="total_max_marks" name="total_max_marks"></td>
                                    <td><input type="number" class="form-control" id="total_obtain_marks" name="total_obtain_marks"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->
        <!-- Part 5 Image and Preview Section -->
        <!-- <div class="container mt-5"> -->
        <hr />
        <div class="mt-4 main-box">
            <h3 class="mb-4">Attachments</h3>

            <!-- Upload Section Template -->
            <div class="upload-section">
                <h6 class="text-success">Photo</h6>
                <p class="mb-1"><strong>Max Size:</strong> 3MB</p>
                <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG</p>
                <div class="d-flex align-items-center">
                    <div class="me-3 text-muted upload-status" id="uploadStatus">Pending</div>
                    <img src="#" alt="Preview" class="preview me-3" id="previewPhoto">
                    <input type="file" class="form-control form-control-sm me-2" name="photo" style="width: auto;" onchange="previewImage(event, 'previewPhoto')">
                    <!-- <button class="btn btn-sm btn-primary">Upload</button> -->
                </div>
            </div>
            <div class="upload-section">
                <h5 class="mb-4">Signature</h5>
                <h6 class="text-success">Photo</h6>
                <p class="mb-1"><strong>Max Size:</strong> 3MB</p>
                <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG</p>
                <div class="d-flex align-items-center">
                    <div class="me-3 text-muted upload-status" id="uploadStatus">Pending</div>
                    <img src="#" alt="Preview" class="preview me-3" id="previewPhoto">
                    <input type="file" class="form-control form-control-sm me-2" name="signature" style="width: auto;" onchange="previewImage(event, 'previewPhoto')">
                    <!-- <button class="btn btn-sm btn-primary">Upload</button> -->
                </div>
            </div>
            <div class="upload-section">
                <h5 class="mb-4">10th Certificate</h5>
                <h6 class="text-success">Photo</h6>
                <p class="mb-1"><strong>Max Size:</strong> 3MB</p>
                <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG</p>
                <div class="d-flex align-items-center">
                    <div class="me-3 text-muted upload-status" id="uploadStatus">Pending</div>
                    <img src="#" alt="Preview" class="preview me-3" id="previewPhoto">
                    <input type="file" class="form-control form-control-sm me-2" name="certificate_10" style="width: auto;" onchange="previewImage(event, 'previewPhoto')">
                    <!-- <button class="btn btn-sm btn-primary">Upload</button> -->
                </div>
            </div>
            <div class="upload-section">
                <h5 class="mb-4">12th Marksheet</h5>
                <h6 class="text-success">Photo</h6>
                <p class="mb-1"><strong>Max Size:</strong> 3MB</p>
                <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG</p>
                <div class="d-flex align-items-center">
                    <div class="me-3 text-muted upload-status" id="uploadStatus">Pending</div>
                    <img src="#" alt="Preview" class="preview me-3" id="previewPhoto">
                    <input type="file" class="form-control form-control-sm me-2" name="certificate_12" style="width: auto;" onchange="previewImage(event, 'previewPhoto')">
                    <!-- <button class="btn btn-sm btn-primary">Upload</button> -->
                </div>
            </div>
            <div class="upload-section">
                <h5 class="mb-4">NCET Score Card</h5>
                <h6 class="text-success">Photo</h6>
                <p class="mb-1"><strong>Max Size:</strong> 3MB</p>
                <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG</p>
                <div class="d-flex align-items-center">
                    <div class="me-3 text-muted upload-status" id="uploadStatus">Pending</div>
                    <img src="#" alt="Preview" class="preview me-3" id="previewPhoto">
                    <input type="file" class="form-control form-control-sm me-2" name="ncet_score_card" style="width: auto;" onchange="previewImage(event, 'previewPhoto')">
                    <!-- <button class="btn btn-sm btn-primary">Upload</button> -->
                </div>
            </div>
            <div class="upload-section">
                <h5 class="mb-4">Caste Certificate</h5>
                <h6 class="text-success">Photo</h6>
                <p class="mb-1"><strong>Max Size:</strong> 3MB</p>
                <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG</p>
                <div class="d-flex align-items-center">
                    <div class="me-3 text-muted upload-status" id="uploadStatus">Pending</div>
                    <img src="#" alt="Preview" class="preview me-3" id="previewPhoto">
                    <input type="file" class="form-control form-control-sm me-2" name="caste_certificate" style="width: auto;" onchange="previewImage(event, 'previewPhoto')">
                    <!-- <button class="btn btn-sm btn-primary">Upload</button> -->
                </div>
            </div>
            <div class="upload-section">
                <h5 class="mb-4">PwBD</h5>
                <h6 class="text-success">Photo</h6>
                <p class="mb-1"><strong>Max Size:</strong> 3MB</p>
                <p class="mb-3"><strong>File Type:</strong> JPG, JPEG, PNG</p>
                <div class="d-flex align-items-center">
                    <div class="me-3 text-muted upload-status" id="uploadStatus">Pending</div>
                    <img src="#" alt="Preview" class="preview me-3" id="previewPhoto">
                    <input type="file" class="form-control form-control-sm me-2" name="pwbd" style="width: auto;" onchange="previewImage(event, 'previewPhoto')">
                    <!-- <button class="btn btn-sm btn-primary">Upload</button> -->
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 justify-content-center mt-3">
            <button type="submit" class="btn btn-sm btn-outline-success">Save as Draft</button>
            <button type="submit" class="btn btn-sm btn-success">Save</button>
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

    $(document).ready(function() {
        $('#course').trigger('change');
    });

    $("#course").change((e) => {
        try{
            $('.preference-select').html('');
            let course = e.target.value;
            let bscSubject = ['Physics', 'Chemistry', 'Mathematics', 'Zoology', 'Botany'];
            let baSubject = ['Geography', 'History', 'Hindi', 'English', 'Urdu'];
            let subjects = [];
    
            let preferenceOptions = `<option selected>--Select--</option>`;
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
    
            $('.preference-select').each(function(idx){
                let select = $(this);
                select.html(preferenceOptions);
                subjects.forEach(subject => {
                    let isSelected = preferences[idx] === subject ? 'selected' : '';
                    select.append(`<option value="${subject}" ${isSelected}>${subject}</option>`);
                });
            });

        }catch(err){
            console.log(err);
        }

        // $('.preference-select').append(preferenceOptions);
    });

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
        select.addEventListener("change", updateOptions);
    });
</script>

<script>
    $('.max_marks').on('blur', (e) => {
        let total_marks = 0;
        $('.max_marks').each(function() {
            console.log($(this).val(), typeof $(this).val());
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

    let codes = [];
    let duplicate = false;
    $('.codes').on('blur', (e) => {
        let element = e.target;
        let row = $(element).attr('data-row');
        let code = e.target.value;

        $(".codes").each(function() {
            let val = $(this).val().trim();
            if (val !== "") {
                if (codes.includes(val)) {
                    $('#code'+row).addClass("error");
                    duplicate = true;
                } else {
                    codes.push(val);
                    $('#code'+row).removeClass("error");
                }
            }
        });

        if (duplicate) {
            e.preventDefault();
            alert("Each subject Code must be unique!");
        }else{
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