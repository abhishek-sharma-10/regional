<?php
    // var_dump($general, $obc_ncl, $sc, $st, $ews);exit;
?>

<div class="row m-t">
    <?php
        if(!isset($_SESSION['subject'])){
    ?>
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Filter</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <form method="POST" class="form-horizontal" id="filter">
                    <div class="row" style="display: flex; align-items: flex-end;">
                        <div class="col-sm-3">
                            <label class="form-label">Counelling:</label>
                            <!-- <input type="date" class="form-control" name="start_date"> -->
                            <select name="counsellingId" id="counsellingId" class="form-control">
                                <option value="">Select Counselling</option>
                                <?php
                                    if(count($counselling_list) > 0){
                                        foreach ($counselling_list as $key => $value) {
                                ?>
                                            <option value="<?php echo $value->id;?>" <?php echo $counsellingId == $value->id ? 'selected' : '';?>><?php echo $value->name;?></option>
                                <?php
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    
                        <div class="col-sm-3">
                            <label class="form-label">Subject:</label>
                            <!-- <input type="date" class="form-control" name="start_date"> -->
                            <select name="subject" id="subject" class="form-control">
                                <option value="">Select Subject</option>
                                <option value="Physics" <?php echo $subject == 'Physics' ? 'selected' : '';?>>Physics</option>
                                <option value="Chemistry" <?php echo $subject == 'Chemistry' ? 'selected' : '';?>>Chemistry</option>
                                <option value="Mathematics" <?php echo $subject == 'Mathematics' ? 'selected' : '';?>>Mathematics</option>
                                <option value="Zoology" <?php echo $subject == 'Zoology' ? 'selected' : '';?>>Zoology</option>
                                <option value="Botany" <?php echo $subject == 'Botany' ? 'selected' : '';?>>Botany</option>
                                <option value="History" <?php echo $subject == 'History' ? 'selected' : '';?>>History</option>
                                <option value="Geography" <?php echo $subject == 'Geography' ? 'selected' : '';?>>Geography</option>
                                <option value="English Language and Literature" <?php echo $subject == 'English Language and Literature' ? 'selected' : '';?>>English Language and Literature</option>
                                <option value="Hindi Language and Literature" <?php echo $subject == 'Hindi Language and Literature' ? 'selected' : '';?>>Hindi Language and Literature</option>
                                <option value="Urdu" <?php echo $subject == 'Urdu' ? 'selected' : '';?>>Urdu</option>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <button class="btn btn-primary m-n" id="show-list" type="submit">Show</button>
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-success m-n" id="send-mail" type="button">Send Mail</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
        }
    ?>
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>General Student List</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover general-counselling" style="font-size: 14px !important;">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Category</th>
                                <th>Physical Disable</th>
                                <th>Preference</th>
                                <th>Percentile</th>
                                <th>B.Sc. Preference 1</th>
                                <th>B.Sc. Preference 2</th>
                                <th>B.Sc. Preference 3</th>
                                <th>B.Sc. Preference 4</th>
                                <th>B.A. Preference 1</th>
                                <th>B.A. Preference 2</th>
                                <th>B.A. Preference 3</th>
                                <th>B.A. Preference 4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($general) && !empty($general) && count($general) > 0){
                                foreach ($general as $key => $value) {
                            ?>
                                <tr>
                                    <td><?=($key+1);?></td>
                                    <td><?=$value['name'];?></td>
                                    <td><?=$value['course'];?></td>
                                    <td><?=$value['category'];?></td>
                                    <td><?=$value['student_counselling_physical_disable'];?></td>
                                    <td><?=$value['student_counselling_subject']?></td>
                                    <td><?=$value['ncet_average_percentile']?></td>
                                    <td><?=$value['bsc_preference_1']?></td>
                                    <td><?=$value['bsc_preference_2']?></td>
                                    <td><?=$value['bsc_preference_3']?></td>
                                    <td><?=$value['bsc_preference_4']?></td>
                                    <td><?=$value['ba_preference_1']?></td>
                                    <td><?=$value['ba_preference_2']?></td>
                                    <td><?=$value['ba_preference_3']?></td>
                                    <td><?=$value['ba_preference_4']?></td>
                                </tr>
                            <?php
                                }}
                                else{
                                    echo "<td colspan='15' style='text-align:center;'>No Record For General Category</td>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>OBC-NCL Student List</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover obc-ncl-counselling" style="font-size: 14px !important;">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Category</th>
                                <th>Physical Disable</th>
                                <th>Preference</th>
                                <th>Percentile</th>
                                <th>B.Sc. Preference 1</th>
                                <th>B.Sc. Preference 2</th>
                                <th>B.Sc. Preference 3</th>
                                <th>B.Sc. Preference 4</th>
                                <th>B.A. Preference 1</th>
                                <th>B.A. Preference 2</th>
                                <th>B.A. Preference 3</th>
                                <th>B.A. Preference 4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($obc_ncl) && !empty($obc_ncl) && count($obc_ncl) > 0){
                                foreach ($obc_ncl as $key => $value) {
                            ?>
                                <tr>
                                    <td><?=($key+1);?></td>
                                    <td><?=$value['name'];?></td>
                                    <td><?=$value['course'];?></td>
                                    <td><?=$value['category'];?></td>
                                    <td><?=$value['student_counselling_physical_disable'];?></td>
                                    <td><?=$value['student_counselling_subject']?></td>
                                    <td><?=$value['ncet_average_percentile']?></td>
                                    <td><?=$value['bsc_preference_1']?></td>
                                    <td><?=$value['bsc_preference_2']?></td>
                                    <td><?=$value['bsc_preference_3']?></td>
                                    <td><?=$value['bsc_preference_4']?></td>
                                    <td><?=$value['ba_preference_1']?></td>
                                    <td><?=$value['ba_preference_2']?></td>
                                    <td><?=$value['ba_preference_3']?></td>
                                    <td><?=$value['ba_preference_4']?></td>
                                </tr>
                            <?php
                                }}
                                else{
                                    echo "<td colspan='15' style='text-align:center;'>No Record For OBC-(NCL) Category</td>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>SC Student List</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover sc-counselling" style="font-size: 14px !important;">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Category</th>
                                <th>Physical Disable</th>
                                <th>Preference</th>
                                <th>Percentile</th>
                                <th>B.Sc. Preference 1</th>
                                <th>B.Sc. Preference 2</th>
                                <th>B.Sc. Preference 3</th>
                                <th>B.Sc. Preference 4</th>
                                <th>B.A. Preference 1</th>
                                <th>B.A. Preference 2</th>
                                <th>B.A. Preference 3</th>
                                <th>B.A. Preference 4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($sc) && !empty($sc) && count($sc) > 0){
                                foreach ($sc as $key => $value) {
                            ?>
                                <tr>
                                    <td><?=($key+1);?></td>
                                    <td><?=$value['name'];?></td>
                                    <td><?=$value['course'];?></td>
                                    <td><?=$value['category'];?></td>
                                    <td><?=$value['student_counselling_physical_disable'];?></td>
                                    <td><?=$value['student_counselling_subject']?></td>
                                    <td><?=$value['ncet_average_percentile']?></td>
                                    <td><?=$value['bsc_preference_1']?></td>
                                    <td><?=$value['bsc_preference_2']?></td>
                                    <td><?=$value['bsc_preference_3']?></td>
                                    <td><?=$value['bsc_preference_4']?></td>
                                    <td><?=$value['ba_preference_1']?></td>
                                    <td><?=$value['ba_preference_2']?></td>
                                    <td><?=$value['ba_preference_3']?></td>
                                    <td><?=$value['ba_preference_4']?></td>
                                </tr>
                            <?php
                                }}
                                else{
                                    echo "<td colspan='15' style='text-align:center;'>No Record For SC Category</td>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>ST Student List</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover st-counselling" style="font-size: 14px !important;">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Category</th>
                                <th>Physical Disable</th>
                                <th>Preference</th>
                                <th>Percentile</th>
                                <th>B.Sc. Preference 1</th>
                                <th>B.Sc. Preference 2</th>
                                <th>B.Sc. Preference 3</th>
                                <th>B.Sc. Preference 4</th>
                                <th>B.A. Preference 1</th>
                                <th>B.A. Preference 2</th>
                                <th>B.A. Preference 3</th>
                                <th>B.A. Preference 4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($st) && !empty($st) && count($st) > 0){
                                foreach ($st as $key => $value) {
                            ?>
                                <tr>
                                    <td><?=($key+1);?></td>
                                    <td><?=$value['name'];?></td>
                                    <td><?=$value['course'];?></td>
                                    <td><?=$value['category'];?></td>
                                    <td><?=$value['student_counselling_physical_disable'];?></td>
                                    <td><?=$value['student_counselling_subject']?></td>
                                    <td><?=$value['ncet_average_percentile']?></td>
                                    <td><?=$value['bsc_preference_1']?></td>
                                    <td><?=$value['bsc_preference_2']?></td>
                                    <td><?=$value['bsc_preference_3']?></td>
                                    <td><?=$value['bsc_preference_4']?></td>
                                    <td><?=$value['ba_preference_1']?></td>
                                    <td><?=$value['ba_preference_2']?></td>
                                    <td><?=$value['ba_preference_3']?></td>
                                    <td><?=$value['ba_preference_4']?></td>
                                </tr>
                            <?php
                                }}
                                else{
                                    echo "<td colspan='15' style='text-align:center;'>No Record For ST Category</td>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>EWS Student List</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover ews-counselling" style="font-size: 14px !important;">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Category</th>
                                <th>Physical Disable</th>
                                <th>Preference</th>
                                <th>Percentile</th>
                                <th>B.Sc. Preference 1</th>
                                <th>B.Sc. Preference 2</th>
                                <th>B.Sc. Preference 3</th>
                                <th>B.Sc. Preference 4</th>
                                <th>B.A. Preference 1</th>
                                <th>B.A. Preference 2</th>
                                <th>B.A. Preference 3</th>
                                <th>B.A. Preference 4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($ews) && !empty($ews) && count($ews) > 0){
                                foreach ($ews as $key => $value) {
                            ?>
                                <tr>
                                    <td><?=($key+1);?></td>
                                    <td><?=$value['name'];?></td>
                                    <td><?=$value['course'];?></td>
                                    <td><?=$value['category'];?></td>
                                    <td><?=$value['student_counselling_physical_disable'];?></td>
                                    <td><?=$value['student_counselling_subject']?></td>
                                    <td><?=$value['ncet_average_percentile']?></td>
                                    <td><?=$value['bsc_preference_1']?></td>
                                    <td><?=$value['bsc_preference_2']?></td>
                                    <td><?=$value['bsc_preference_3']?></td>
                                    <td><?=$value['bsc_preference_4']?></td>
                                    <td><?=$value['ba_preference_1']?></td>
                                    <td><?=$value['ba_preference_2']?></td>
                                    <td><?=$value['ba_preference_3']?></td>
                                    <td><?=$value['ba_preference_4']?></td>
                                </tr>
                            <?php
                                }}
                                else{
                                    echo "<td colspan='15' style='text-align:center;'>No Record For EWS Category</td>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            $("#preloadercustom").hide();
            $(".myspin").hide();


            $("#send-mail").click(function(e) {

                let counsellingId = $('#counsellingId').find(":selected").val();
                let subject = $('#subject').find(":selected").val();
                
                let data = {
                    counsellingId: counsellingId,
                    subject: subject
                }

                console.log(data);
                // e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url('admin/subject/send-subject-mail') ?>",
                    data: data,
                    dataType: 'json',
                    beforeSend: function() {
                        $("#send-mail").prop('disabled', true);
                        $("#preloadercustom").show();
                        $(".myspin").show();
                    }, 
                    success: function(result) {
                        $("#send-mail").prop('disabled', false);
                        console.log(result);
                        if($.isEmptyObject(result.error_message)) {
                            // $(".result").html(result.success_message);
                            toastr.success(result.success_message);
                            // window.location.reload();
                        } else {
                            // $(".sub-result").html(result.error_message);
                            toastr.warning(result.error_message);
                        }
                        $("#form-upload")[0].reset();
                        $("#preloadercustom").hide();
                        $(".myspin").hide();
                    }
                });
            });
        });
    </script>