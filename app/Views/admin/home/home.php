<?php
// $this->load->view('template/header.php',$pageTitle);

// $pendingAssgnArr = isset($pendingAssignment) && is_array($pendingAssignment) && count($pendingAssignment) > 0 ? $pendingAssignment : null;
// $studentDetail = isset($studentData) ? $studentData : null;
// $contentdetail = isset($studentLastTwoLectures) && is_array($studentLastTwoLectures) && count($studentLastTwoLectures) > 0 ? $studentLastTwoLectures : array();

// $exams = isset($scheduleExams) && is_array($scheduleExams) && count($scheduleExams) > 0 ? $scheduleExams : array();

// $feedbackResponse = isset($feedbackResponse) && is_array($feedbackResponse) && count($feedbackResponse) > 0 ? $feedbackResponse : null;
// $feeDepositeDetail = isset($feeDepositeDetail) && is_array($feeDepositeDetail) && count($feeDepositeDetail) > 0 ? $feeDepositeDetail : null;

?>
<!-- <div class="row">
    <div class="col-md-12">
        <br />
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Student Detail</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="container">
                    <?php if (isset($studentDetail)) { ?>
                        <form name="student_detail" id="student_detail" method="post" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-2" align="right">Registration Number :</label>
                                <div class="col-md-3"><?php echo (isset($studentDetail->reg_no) && !empty($studentDetail->reg_no)) ? ucwords($studentDetail->reg_no) : '' ?></div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2" align="right">Name :</label>
                                <div class="col-md-3"><?php echo (isset($studentDetail->name) && !empty($studentDetail->name)) ? ucwords($studentDetail->name) : '' ?></div>
                                <div class="col-md-1"></div>
                                <label class="col-md-2" align="right">Father Name :</label>
                                <div class="col-md-3"><?php echo (isset($studentDetail->father_name) && !empty($studentDetail->father_name)) ? ucwords($studentDetail->father_name) : '' ?></div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2" align="right">Date of Birth :</label>
                                <div class="col-md-3"><?php echo (isset($studentDetail->dob) && !empty($studentDetail->dob)) && $studentDetail->dob != '0000-00-00' ? date('d-m-Y', strtotime($studentDetail->dob)) : '' ?></div>
                                <div class="col-md-1"></div>
                                <label class="col-md-2" align="right">Gender :</label>
                                <div class="col-md-3"><?php echo (isset($studentDetail->gender) && !empty($studentDetail->gender)) ? ucwords($studentDetail->gender) : '' ?></div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2" align="right">Email :</label>
                                <div class="col-md-3"><?php echo (isset($studentDetail->email) && !empty($studentDetail->email)) ? $studentDetail->email : '' ?></div>
                                <div class="col-md-1"></div>
                                <label class="col-md-2" align="right">Contact Number :</label>
                                <div class="col-md-3"><?php echo (isset($studentDetail->contact_no) && !empty($studentDetail->contact_no)) ? $studentDetail->contact_no : '' ?></div>
                            </div>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div> -->
<!-- <div class="row">
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <a class="collapse-link" style="float:right">
                    <i class="fa fa-chevron-up" style="color:#c4c4c4"></i>
                </a>
                <h5>Last 2 Days Lectures</h5>
            </div>
            <div class="ibox-content" style="padding-top:10px">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Batch</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Topic</th>
                            <th>Content</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // if (count($contentdetail) > 0) {
                        //     $lectureDetailData = array_values($contentdetail);
                        //     foreach ($lectureDetailData as $parentValue) {
                        //         if (is_array($parentValue) && count($parentValue) > 0) {
                        //             foreach ($parentValue as $childValue) {
                        ?>
                                        <tr>
                                            <td><?php //echo $childValue['batch-name']; ?></td>
                                            <td><?php //echo $childValue['subject-name']; ?></td>
                                            <td><?php //echo $childValue['delivered-date']; ?></td>
                                            <td><?php //echo $childValue['master-data']; ?></td>
                                            <td><?php //echo $childValue['delivered-data']; ?></td>
                                        </tr>
                        <?php
                        //             }
                        //         }
                        //     }
                        // }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> -->
<!-- <div class="row">
    <div class="col-md-6">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <a class="collapse-link" style="float:right">
                    <i class="fa fa-chevron-up" style="color:#c4c4c4"></i>
                </a>
                <h5>Pending Assignments</h5>
            </div>
            <div class="ibox-content" style="padding-top:10px">
                <?php //if (is_array($pendingAssgnArr) && count($pendingAssgnArr) > 0) {
                    //foreach ($pendingAssgnArr as $fileKey => $fileValue) {

                ?>
                        <h4><?php //echo ++$fileKey . ". <a href='" . base_url() . "HomeController/downloadFile?file=" . $fileValue . "' title='Download Assignment File'>" . $fileValue . "</a>" ?></a></h4>
                <?php
                //     }
                // }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <a class="collapse-link" style="float:right">
                    <i class="fa fa-chevron-up" style="color:#c4c4c4"></i>
                </a>
                <h5>Fee Deposited Detail</h5>
            </div>
            <div class="ibox-content" style="padding-top:10px">

                <?php //if (is_array($feeDepositeDetail) && count($feeDepositeDetail) > 0) {
                    # var_dump($feeDepositeDetail);
                ?>
                    <div class="row">
                        <div class="col-md-2">
                            <h4>Receipt No.</h4>
                        </div>
                        <div class="col-md-3">
                            <h4>Amount</h4>
                        </div>
                        <div class="col-md-3">
                            <h4>Date</h4>
                        </div>
                        <div class="col-md-4">
                            <h4>Course</h4>
                        </div>
                    </div>
                    <?php
                    //foreach ($feeDepositeDetail as $key => $value) {
                        #var_dump($value);
                    ?>
                        <div class="row">
                            <div class="col-md-2">
                                <?//= $value->id ?>
                            </div>
                            <div class="col-md-3">
                                <?//= $value->amount ?>
                            </div>
                            <div class="col-md-3">
                                <?//= date('d-m-Y', strtotime($value->date)) ?>
                            </div>
                            <div class="col-md-4">
                                <?//= ucfirst($value->course_name) ?>
                            </div>
                        </div>
                        <hr />
                    <?php
                    // }
                    // echo "<div align='right' style='padding-top:10px'><a href='FeesController' class='btn btn-primary'>Deposite Fees</a></div>";
                // } else { ?>
                    <div class="col-md-12">
                        <h4>You have not deposited any fees.</h4>
                    </div>
                    <hr />
                <?php
                // }
                ?>
            </div>
        </div>
    </div>
</div> -->

<!-- start -->
<!-- <div class="row">
    <div class="col-md-6" style="margin-bottom:20px;">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <a class="collapse-link" style="float:right">
                    <i class="fa fa-chevron-up" style="color:#c4c4c4"></i>
                </a>
                <h5>Upcoming Exam Schedule</h5>
            </div>
            <div class="ibox-content" style="padding-top:10px">
                <?php
                // $count = 1;

                // if (count($exams) > 0) {

                //     foreach ($exams as $key => $value) {
                //         if (isset($value['name']) && isset($value['exam_date']) && isset($value['start_time']) && isset($value['end_time'])) {

                //             $stime = date("h:i A", strtotime($value['start_time']));
                //             $etime = date("h:i A", strtotime($value['end_time']));
                //             $date = date("d-m-Y", strtotime($value['exam_date']));
                ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <h4><?//= $value['name'] ?></h4>
                                </div>
                                <div>
                                    <h4><?//= $value['practical'] == "no" ? "Theory" : "Practical" ?></h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5><?//= $date ?></h5>
                                </div>
                                <div>

                                    <h5><?//= $stime ?> To <?//= $etime ?></h5>
                                </div>
                            </div>
                            <hr />
                <?php
                //         }
                //         if ($count >= 2) {
                //             echo "<div align='right' style='padding-top:10px'><a href='Exam' class='btn btn-primary'>See more...</a></div>";
                //             break;
                //         }
                //         $count++;
                //     }
                // }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-6" style="margin-bottom:20px;">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <a class="collapse-link" style="float:right">
                    <i class="fa fa-chevron-up" style="color:#c4c4c4"></i>
                </a>
                <h5>Today, Feedback Response</h5>
            </div>
            <div class="ibox-content" style="padding-top:10px">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Response</th>
                                    <th>Created Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // if (!empty($feedbackResponse)) {
                                //     $count = 1;
                                //     foreach ($feedbackResponse as $key => $value) {
                                //         if ($count <= 2) {
                                ?>
                                            <tr>
                                                <td><?php //echo $value['description'] ?></td>
                                                <td><?php //echo $value['response'] ?></td>
                                                <td><?php //echo date('d-m-Y', strtotime($value['created_date'])) ?></td>
                                            </tr>
                                    <?php
                                //         } else {
                                //             echo "<tr><td colspan= 3 align='right' style='padding-top:10px'><a href='Feedback' class='btn btn-primary'>See more...</a></td></tr>";
                                //             break;
                                //         }
                                //         $count++;
                                //     }
                                // } else {
                                    ?>
                                    <tr>
                                        <td>No Response...</td>
                                    </tr>
                                <?php
                                // }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
<!-- end -->

<?php   // $this->load->view('template/footer.php');  
?>

<script>
    $(document).ready(function() {
        $("#preloadercustom").hide();
        $(".myspin").hide();
    });
</script>